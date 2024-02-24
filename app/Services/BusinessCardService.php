<?php

namespace App\Services;

use App\Traits\ListTrait;
use App\Models\BusinessCard;
use App\Exceptions\PosException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BusinessCardService
{
    use ListTrait;

    public function index($params)
    {

        $this->query = BusinessCard::with(
            [
                'member'=> function($query) {
                    $query->select( 'id', 'member_number', 'name_kanji' );
                }
            ]
        );

        if( !empty($params['name']) ){
            $this->query->whereHas('member', function($query) use ($params)  {
                $query->where('name_kanji', 'LIKE', '%'.$params['name'].'%')
                    ->orWhere('name_furigana', 'LIKE', '%'.$params['name'].'%');
            })->orWhere('card_name_kanji', 'LIKE', '%'.$params['name'].'%')
            ->orWhere('card_name_roman', 'LIKE', '%'.$params['name'].'%');
        }

        if( !empty($params['member_number']) ){
            $this->query->whereRelation('member', 'member_number', 'LIKE', '%'.$params['member_number'].'%');
        }

        if( !empty($params['from']) ){
            $this->query->whereDate('created_at','>=', $params['from']);
        }

        if( !empty($params['to']) ){
            $this->query->whereDate('created_at','<=', $params['to']);
        }
        if( !empty($params['status']) && is_array($params['status'])){
            $this->query->whereIn('status', $params['status']);
        }

        $this->params = $params;


        return $this->query();
    }

    public function show($id)
    {
        $model = BusinessCard::with(
            [
                'member'=> function($query) {
                    $query->select( 'id', 'name_kanji');
                }
            ])->find($id);
        // 存在しなかったら404
        if (!$model) {
            throw new PosException('15', '001', 404);
        }
        $model->card_image =  Storage::url($model->card_image);
        $model->card_background_image =  Storage::url($model->card_background_image);

        return $model;
    }

    public function update($params, $id)
    {
        return DB::transaction(function () use ($params, $id) {
            //指定IDでbusiness-cardsを検索する
            $model = BusinessCard::find($id);
            // 存在しなかったら404
            if (!$model) {
                throw new PosException('15', '002', 404);
            }

            if (isset($params['card_image_file'])) {  // TODO:名刺プレビューはバックエンド側で作成
                $image = $params['card_image_file'];
                $path = $model->card_image;
                if (Storage::exists($path)) {
                    Storage::delete($path);
                }
                Storage::put($path, file_get_contents($image), 'private');
            }

            unset($params['member_id']);
            unset($params['card_image']);
            unset($params['card_background_image']);
            //指定相互リンクの情報を更新する
            $model->fill($params)->save();
            $model->card_image =  Storage::url($model->card_image);
            $model->card_background_image =  Storage::url($model->card_background_image);
            return $model;
        });
    }

    public function support($id)
    {
        //指定IDでbusiness-cardsを検索する
        $model = BusinessCard::with(
            [
                'member'=> function($query) {
                    $query->select( 'id', 'member_number', 'name_kanji' );
                }
            ])->find($id);
        // 存在しなかったら404
        if (!$model) {
            throw new PosException('15', '003', 404);
        }
        $model->status = BusinessCard::STATUS_SUPPORT;
        $model->save();
        return $model;
    }

    public function complete($id)
    {
        //指定IDでbusiness-cardsを検索する
        $model = BusinessCard::with(
            [
                'member'=> function($query) {
                    $query->select( 'id', 'member_number', 'name_kanji' );
                }
            ])->find($id);
        // 存在しなかったら404
        if (!$model) {
            throw new PosException('15', '004', 404);
        }
        $model->status = BusinessCard::STATUS_COMPLETE;
        $model->save();
        return $model;
    }

    public function download($id)
    {
        //指定IDでbusiness-cardsを検索する
        $model = BusinessCard::find($id);

        // 存在しなかったら404
        if (!$model) {
            throw new PosException('15', '005', 404);
        }
        $file_name = basename($model->card_image);
        $fileContent =  Storage::get($model->card_image);

        return response($fileContent, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="'.$file_name.'"',
        ]);
    }
}
