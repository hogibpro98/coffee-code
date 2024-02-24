<?php

namespace App\Services;

use App\Traits\ListTrait;
use App\Models\Link;
use App\Exceptions\PosException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LinkService
{
    use ListTrait;

    public function index($params)
    {
        $this->query = Link::query();
        $this->params = $params;
        $this->addBooleanParam('is_private');
        $this->addParams('title', true);
        return $this->query();
    }

    public function show($id)
    {
        //指定IDでlinksを検索する
        $model = Link::find($id);
        // 存在しなかったら404
        if (!$model) {
            throw new PosException('12', '001', 404);
        }
        if($model->image_path) {
            $model->image_path = Storage::temporaryUrl(
                $model->image_path, now()->addMinutes(5)
            );
        }
        return $model;
    }

    public function store($params)
    {
        return DB::transaction(function () use ($params) {
            //要求データでlinksに登録する
            $model = new Link();
            $model->fill($params)->save();
            if (isset($params['image'])) {
                $image = $params['image'];
                $params['image_name'] = $image->getClientOriginalName();
                $path = config('filesystems.disks.s3.bucket'). '/link/'.$model->id;
                $this->validatorImageName($params);
                //入力されている画像データをAWS s3に保存する
                Storage::put($path, file_get_contents($image), 'private');
                $params['image_path'] = $path;
                
                $model->fill($params)->save();
            }
            return $model;
        });
    }
    
    public function update($params, $id)
    {
        return DB::transaction(function () use ($params, $id) {
            //指定IDでlinksを検索する
            $model = Link::find($id);
            // 存在しなかったら404
            if (!$model) {
                throw new PosException('12', '002', 404);
            }

            if (isset($params['image'])) {
                $image = $params['image'];
                $params['image_name'] = $image->getClientOriginalName();
                $this->validatorImageName($params);
                $path = config('filesystems.disks.s3.bucket'). '/link/'.$model->id;
                //入力されている画像データをAWS s3に保存する
                Storage::delete($path);
                Storage::put($path, file_get_contents($image), 'private');
                $params['image_path'] = $path;
            }
            //指定相互リンクの情報を更新する
            $model->fill($params)->save();
            return $model;
        });
    }

    private function validatorImageName($params) {
        $validator = Validator::make($params, ['image_name' => 'string|max:255']);
        if ($validator->fails()) {
            $response['errors']  = $validator->errors()->toArray();
            throw new HttpResponseException(
                response()->json($response, 422)
            );
        }
    }
}
