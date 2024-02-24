<?php


namespace App\Services;


use App\Exceptions\PosException;
use App\Models\IndustryType;
use App\Traits\ListTrait;
use Illuminate\Support\Facades\DB;

class IndustryTypeService
{
    use ListTrait;

    public function index($params)
    {
        $this->query = IndustryType::query();
        $this->params = $params;
        $this->addParams('name', true);
        return $this->query();
    }

    public function getAll($params)
    {
        $this->query = IndustryType::query();
        $this->params = $params;
        return $this->query->get();
    }

    public function show($id)
    {
        $model = IndustryType::find($id);
        if(!$model) {
            throw new PosException('02', '001', 404);
        }
        return $model;
    }

    public function update($params, $id)
    {
        $model = IndustryType::find($id);
        if(!$model) {
            throw new PosException('02', '002', 404);
        }
        $model->fill($params)->save();
        return $model;
    }

    public function store($params)
    {
        return DB::transaction(function () use ($params) {
           $model = new IndustryType();
           $model->fill($params)->save();
           return $model;
        });
    }

    public function destroy($id)
    {
        $model = IndustryType::find($id);
        if(!$model) {
            throw new PosException('02', '003', 404);
        }
        $model->delete();
    }

}
