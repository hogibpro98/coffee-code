<?php

namespace App\Services;

use App\Traits\ListTrait;
use App\Models\FieldType;
use App\Exceptions\PosException;
use Illuminate\Support\Facades\DB;

class FieldTypeService
{
    use ListTrait;

    public function index($params)
    {
        $this->query = FieldType::query();
        $this->params = $params;
        $this->addParams('name', true);
        $this->addParams('grouping_list', true);
        return $this->query();
    }

    public function show($id)
    {
        $model = FieldType::find($id);
        // 存在しなかったら404
        if (!$model) {
            throw new PosException('03', '001', 404);
        }
        return $model;
    }

    public function store($params)
    {
        return DB::transaction(function () use ($params) {
            $model = new FieldType();
            $model->fill($params);
            $model['grouping_list'] = json_encode($params['grouping_list'], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
            $model->save();
            return $model;
        });
    }

    public function update($params, $id)
    {
        $model = FieldType::find($id);
        if(!$model) {
            throw new PosException('03', '002', 404);
        }
        $model->fill($params);
        $model['grouping_list'] = json_encode($params['grouping_list'], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        $model->save();
        return $model;
    }

    public function destroy($id)
    {
        $model = FieldType::find($id);
        if(!$model) {
            throw new PosException('03', '003', 404);
        }
        $model->delete();
        return null;
    }

    public function all()
    {
        $model = FieldType::get();
        return $model;
    }

}
