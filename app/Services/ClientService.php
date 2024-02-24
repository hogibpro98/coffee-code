<?php

namespace App\Services;

use App\Traits\ListTrait;
use App\Models\Client;
use App\Exceptions\PosException;
use Illuminate\Support\Facades\DB;

class ClientService
{
    use ListTrait;

    public function index($params)
    {
        $this->query = Client::with('clientRepresentatives');
        //add params search
        if(!empty($params['client_name_fullwidth'])) {
            $this->query->where('client_name_fullwidth', 'LIKE', '%'. $params['client_name_fullwidth']. '%');
        }
        if (!empty($params['representative'])) {
            $this->query->whereRelation('clientRepresentatives', 'name',  $params['representative']);
        }
        $this->params = $params;
        if (!empty($params['industry_type_id'])) {
            $this->addParams('industry_type_id', false);
        }
        return $this->query();
    }

    public function show($id)
    {
        $model = Client::query()
            ->with([
                'industryType',
                'clientRepresentatives',
                'matters' => function($query) {
                    $query->with([
                        'matterUserAssigns' => function($query) {
                            $query->with([
                                'user'
                            ]);
                        },
                        'matterMemberAssigns' => function($query){
                            $query->with([
                                'member'
                            ]);
                        }

                    ]);
                }
            ])
            ->find($id);

        if(!$model)
        {
            throw new PosException('11', '001', 404);
        }
        return $model;
    }

    public function store($params)
    {
        return DB::transaction(function () use ($params) {
            $model = new Client();
            $model->fill($params)->save();
            if (!empty($params['clientRepresentatives'])) {
                foreach ($params['clientRepresentatives'] as $item) {
                    $model->clientRepresentatives()->create([
                        "client_id" => $model->id,
                        "name" => $item['name'],
                        "email" => $item['email'],
                        "tel" => $item['tel'],
                    ]);
                }
            }
            return $model::with(['clientRepresentatives'])->find( $model->id);
        });
    }

    public function update($params, $id)
    {
        $model = Client::query()
            ->with([
                'clientRepresentatives'
            ])
            ->find($id);
        if(!$model) {
            throw new PosException('11', '002', 404);
        }
        return DB::transaction(function () use ($params, $model) {
            $model->fill($params)->save();
            if ($model->clientRepresentatives()->count()) {
                $model->clientRepresentatives()->delete();
            }
            if (!empty($params['clientRepresentatives'])) {
                foreach ($params['clientRepresentatives'] as $item) {
                    $model->clientRepresentatives()->create([
                        "client_id" => $model->id,
                        "name" => $item['name'],
                        "email" => $item['email'],
                        "tel" => $item['tel'],
                    ]);
                }
            }
            return $model::with(['clientRepresentatives'])->find( $model->id);
        });
    }

    public function destroy($id)
    {
        $model = Client::find($id);
        if(!$model) {
            throw new PosException('11', '003', 404);
        }
        $model->delete();
        return null;
    }

    public function all()
    {
        $this->query = Client::query();
        return $this->query->get();
    }
}
