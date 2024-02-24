<?php

namespace App\Traits;

trait ListTrait
{

    private $query;
    private $params;
    private $exclusionSort = [];

    public function query()
    {
        if(isset($this->params['sort']) && isset($this->params['order'])){
            if (!in_array($this->params['sort'], $this->exclusionSort)) {
                $this->query->orderBy($this->params['sort'], $this->params['order']);
            }
        }
        return $this->query->paginate(isset($this->params['per_page']) ? $this->params['per_page'] : config('app.per_page'));
    }

    public function addParams($key, $isLike)
    {
        if(!isset($this->params[$key]) || empty($this->params[$key])) return;
        if($isLike){
            $this->query->where($key, 'LIKE', '%'. $this->params[$key]. '%');
            return;
        }
        $this->query->where($key, $this->params[$key]);
    }

    public function addBooleanParam($key)
    {
        if(!isset($this->params[$key]) || !strlen($this->params[$key])) return;
        $this->query->where($key, $this->params[$key]);
    }
}
