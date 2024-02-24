<?php

namespace App\Services;

use App\Commons\PosConst;
use App\Traits\ListTrait;
use App\Exceptions\PosException;
use App\Mail\MainMailable;
use App\Models\Interview;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InterviewService
{
    use ListTrait;

    public function index($params)
    {
        $this->query = Interview::query()->with('temporaryMember');
        $this->params = $params;
        if (!empty($params['interview_fixed_date_from'])) {
            $this->query->whereDate('interview_fixed_date', '>=', $params['interview_fixed_date_from']);
        }
        if (!empty($params['interview_fixed_date_to'])) {
            $this->query->whereDate('interview_fixed_date', '<=', $params['interview_fixed_date_to']);
        }
        if (!empty($params['time'])) {
            $comparedTime = PosConst::getConstDataText(PosConst::TIME_ZONE, $params['time']);
            $this->query->whereTime('interview_fixed_date', '>=', $comparedTime[0])
                ->whereTime('interview_fixed_date', '<=', $comparedTime[1]);
        }
        if (!empty($params['name'])) {
            $this->query->whereRelation('temporaryMember', 'name_kanji', 'LIKE', '%' . $params['name'] . '%');
        }
        if (!empty($params['email'])) {
            $this->query->whereRelation('temporaryMember', 'email', 'LIKE', '%' . $params['email'] . '%');
        }
        if (!empty($params['status']) && $params['status'] && is_array($params['status'])) {
            $this->query->whereIn('status', $params['status']);
        }

        return $this->query();
    }

    public function show($id)
    {
        $model = Interview::query()
            ->with([
                'temporaryMember'
            ])
            ->find($id);
        if (!$model) {
            throw new PosException('14', '001', 404);
        }
        return $model;
    }

    public function store($params)
    {
        return DB::transaction(function () use ($params) {
            $model = new Interview();
            unset($params['interview_fixed_date'], $params['note']);
            $params['status'] = 1;
            $params['email_send_time'] = now();
            $model->fill($params)->save();
            $model->temporaryMember()->update(['interview_status' => 2]);

            $value = Interview::query()->with('temporaryMember')->find($model->id);

            $mailTemplate = new MainMailable(6);
            $mailTemplate->setViewData([
                "name" => $value['temporaryMember']->name_kanji,
                "content" => $value->insertion_text_to_mail_template
            ]);
            $mailTemplate->sendMail($value['temporaryMember']->email);
            
            return Interview::query()->with('temporaryMember')->find($model->id);
        });
    }

    public function update($params, $id)
    {
        return DB::transaction(function () use ($params, $id) {
            $model = Interview::query()->with('temporaryMember')->find($id);

            if (!$model) {
                throw new PosException('14', '002', 404);
            }
            if (
                !$this->isBlank($params, 'interview_fixed_date') &&
                !$this->isBlank($params, 'interview_start_time') &&
                !$this->isBlank($params, 'interview_end_time') ||
                $this->isBlank($params, 'interview_fixed_date') &&
                $this->isBlank($params, 'interview_start_time') &&
                $this->isBlank($params, 'interview_end_time')
            ) {
                if (
                    $this->isBlank($params, 'interview_fixed_date') &&
                    $this->isBlank($params, 'interview_start_time') &&
                    $this->isBlank($params, 'interview_end_time')
                ) {
                    $data["status"] = 1;
                } else if (
                    !$this->isBlank($params, 'interview_fixed_date') &&
                    !$this->isBlank($params, 'interview_start_time') &&
                    !$this->isBlank($params, 'interview_end_time')
                ) {
                    $data["status"] = ($params['interviewed'] == 1) ? 3 : 2;
                }
                $model->temporaryMember()->update(['interview_status' => $data["status"]]);

                $model->fill([
                    'interview_fixed_date' => !$this->isBlank($params, 'interview_fixed_date') ? $params['interview_fixed_date'] : $model['interview_fixed_date'],
                    'interview_start_time' => !$this->isBlank($params, 'interview_start_time') ? $params['interview_start_time'] : $model['interview_start_time'],
                    'interview_end_time' => !$this->isBlank($params, 'interview_end_time') ? $params['interview_end_time'] : $model['interview_end_time'],
                    'note' => !$this->isBlank($params, 'note') ? $params['note'] : $model['note'],
                    'status' => $data["status"] ? $data["status"] : $model['status'],
                ])->save();

                return $model->refresh();
            }

            throw new PosException('14', '003', 404);
        });
    }

    private function isBlank($params, $key)
    {
        if (!isset($params[$key])) return true;
        if ($params[$key] === null) return true;
        if (empty($params[$key])) return true;
        return false;
    }
}
