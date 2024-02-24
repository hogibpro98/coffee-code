<?php


namespace App\Services;


use App\Exceptions\PosException;
use App\Models\Information;
use App\Traits\ListTrait;
use Illuminate\Support\Facades\DB;
use Exception;
use Carbon\Carbon;

class InformationService
{
    use ListTrait;

    public function index($params)
    {
        $this->query = Information::query();
        $this->params = $params;
        $this->addParams('title', true);
        $this->addParams('content', true);
        if(isset($params['status']) && is_array($params['status']))
        {
            $this->query->whereIn('status', $params['status']);
        }

        return $this->query();
    }

    public function show($id)
    {
        $data = Information::find($id);

        if(!$data) {
            throw new PosException('02', '001', 404);
        }

        return $data;
    }

    public function update($params, $id)
    {
        $data = Information::find($id);
        $dateNows = \Carbon\Carbon::createFromFormat('Y-m-d', \Carbon\Carbon::now()->format('Y-m-d'));

        if(!$data) {
            throw new PosException('02', '002', 404);
        }

        if( isset($params['is_private']) && $params['is_private'] == 0 )
        {

            if(!$this->isBlank($params, 'display_start_date') && !$this->isBlank($params, 'display_end_date'))
            {
              
                if($this->convertDate($params['display_start_date'])->lte($dateNows) && $dateNows->lte($this->convertDate($params['display_end_date'])))
                {
                    $data['status'] = Information::BEING_PUBLISHED;
                }

                if($this->convertDate($params['display_start_date'])->lt($dateNows) && $this->convertDate($params['display_end_date'])->lt($dateNows))
                {
                    $data['status'] = Information::END_OF_PUBLIC;
                }

                if($this->convertDate($params['display_start_date'])->gt($dateNows) && $this->convertDate($params['display_end_date'])->gt($dateNows))
                {
                    $data['status'] = Information::BEFORE_PUBLIC;
                }
            }

            if(!$this->isBlank($params, 'display_start_date') && $this->isBlank($params, 'display_end_date'))
            {
                if($dateNows->lt($this->convertDate($params['display_start_date'])))
                {
                    $data['status'] = Information::BEFORE_PUBLIC;
                }

                if($dateNows->eq($this->convertDate($params['display_start_date'])))
                {
                    $data['status'] = Information::BEING_PUBLISHED;
                }

                if($dateNows->gt($this->convertDate($params['display_start_date'])))
                {
                    $data['status'] = Information::BEING_PUBLISHED;
                }
            }

            if($this->isBlank($params, 'display_start_date') && !$this->isBlank($params, 'display_end_date'))
            {
                if($this->convertDate($params['display_end_date'])->gte($dateNows))
                {
                    $data['status'] = Information::BEING_PUBLISHED;
                }

                if($this->convertDate($params['display_end_date'])->lt($dateNows))
                {
                    $data['status'] = Information::END_OF_PUBLIC;
                }
            }

            if($this->isBlank($params, 'display_start_date') && $this->isBlank($params, 'display_end_date'))
            {
                $data['status'] = Information::BEING_PUBLISHED;
            }

        }else{
            $data['status'] = Information::NOT_PUBLISHED;
        }

        $data->fill($params)->save();

        return $data;
    }

    public function store($params)
    {
        $data = new Information();
        $dateNows = \Carbon\Carbon::createFromFormat('Y-m-d', \Carbon\Carbon::now()->format('Y-m-d'));

        if( isset($params['is_private']) && $params['is_private'] == 0 )
        {

            if(!$this->isBlank($params, 'display_start_date') && !$this->isBlank($params, 'display_end_date'))
            {
               
                if($this->convertDate($params['display_start_date'])->lte($dateNows) && $dateNows->lte($this->convertDate($params['display_end_date'])))
                {
                    $data['status'] = Information::BEING_PUBLISHED;
                }

                if($this->convertDate($params['display_start_date'])->lt($dateNows) && $this->convertDate($params['display_end_date'])->lt($dateNows))
                {
                    $data['status'] = Information::END_OF_PUBLIC;
                }

                if($this->convertDate($params['display_start_date'])->gt($dateNows) && $this->convertDate($params['display_end_date'])->gt($dateNows))
                {
                    $data['status'] = Information::BEFORE_PUBLIC;
                }
            }

            if(!$this->isBlank($params, 'display_start_date') && $this->isBlank($params, 'display_end_date'))
            {
                if($dateNows->lt($this->convertDate($params['display_start_date'])))
                {
                    $data['status'] = Information::BEFORE_PUBLIC;
                }

                if($dateNows->eq($this->convertDate($params['display_start_date'])))
                {
                    $data['status'] = Information::BEING_PUBLISHED;
                }

                if($dateNows->gt($this->convertDate($params['display_start_date'])))
                {
                    $data['status'] = Information::BEING_PUBLISHED;
                }
            }

            if($this->isBlank($params, 'display_start_date') && !$this->isBlank($params, 'display_end_date'))
            {
                if($this->convertDate($params['display_end_date'])->gte($dateNows))
                {
                    $data['status'] = Information::BEING_PUBLISHED;
                }

                if($this->convertDate($params['display_end_date'])->lt($dateNows))
                {
                    $data['status'] = Information::END_OF_PUBLIC;
                }
            }

            if($this->isBlank($params, 'display_start_date') && $this->isBlank($params, 'display_end_date'))
            {
                $data['status'] = Information::BEING_PUBLISHED;
            }

        }else{
            $data['status'] = Information::NOT_PUBLISHED;
        }

        $data->fill($params)->save();

        return $data;
    }

    public function destroy($id)
    {
        $data = Information::find($id);

        if(!$data) {
            throw new PosException('02', '003', 404);
        }

        $data->delete();
    }

    private function isBlank($params, $key){
        if(!isset($params[$key])) return true;
        if($params[$key] === null) return true;
        if(empty($params[$key])) return true;
        return false;
    }

    public function convertDate($params = '')
    {
        return \Carbon\Carbon::createFromFormat('Y-m-d', $params);
    }

    public function changeStatus()
    {
        $list = Information::all();
        foreach ($list as $value) {
            // 非公開
            if ($value->is_private) {
                $value->status = Information::NOT_PUBLISHED;
                $value->save();
                continue;
            }

            $start = $value->display_start_date ? new Carbon($value->display_start_date): null;
            $end = $value->display_end_date ? new Carbon($value->display_end_date) : null;
            $today = Carbon::now();

            // 無制限公開
            if ($start == null && $end == null) {
                $value->status = Information::BEING_PUBLISHED;
                $value->save();
                continue;
            }

            // 公開日指定
            if ($start != null && $end != null) {
                if ($today->between($start, $end)) {
                    $value->status = Information::BEING_PUBLISHED;
                    $value->save();
                    continue;
                }
                if ($start->gte($today)) {
                    $value->status = Information::BEFORE_PUBLIC;
                    $value->save();
                    continue;
                }
                if ($end->lte($today)) {
                    $value->status = Information::END_OF_PUBLIC;
                    $value->save();
                    continue;
                }
            }

            // 開始日のみ指定
            if ($start != null) {
                $value->status = Information::BEFORE_PUBLIC;
                if ($start->lte($today)) {
                    $value->status = Information::BEING_PUBLISHED;
                    $value->save();
                    continue;
                }
            }

            // 終了日のみ指定
            if ($end != null) {
                $value->status = Information::END_OF_PUBLIC;
                if ($end->gte($today)) {
                    $value->status = Information::BEING_PUBLISHED;
                    $value->save();
                    continue;
                }
            }
            $value->save();
        }

    }

}
