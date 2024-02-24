<?php

namespace App\Services;

use App\Models\MatterApplication;
use App\Traits\ListTrait;
use App\Models\Matter;
use App\Exceptions\PosException;
use Illuminate\Support\Facades\DB;
use App\Models\MatterMemberAssign;
use Illuminate\Support\Carbon;
use App\Mail\MainMailable;
use App\Models\Member;
use App\Models\Client;
use App\Models\Information;

class MatterService
{
    use ListTrait;

    public function index($params)
    {
        $this->query = Matter::query()->with([
            'client',
            'fieldTypeMatters.fieldType',
            'matterApplications',
            'matterMemberAssigns',
            'matterUserAssigns',
            'industryType',
        ]);
        $this->params = $params;
        $this->addParams('subject', true);
        $this->addParams('project_name', true);
        $this->addParams('client_id', false);
        if (!empty($params['user_id'])) {
            $this->query->whereRelation('matterUserAssigns', 'user_id', '=', $params['user_id']);
        }
        if (!empty($params['member_id'])) {
            $this->query->whereRelation('matterMemberAssigns', 'member_id', '=', $params['member_id']);
        }
        $this->addParams('matter_status', false);
        $this->addParams('contract_status', false);
        $this->addParams('publication_range', false);
        if (!empty($params['free_text'])) {
            $this->query->where(function ($query) use ($params) {
                $query->where('overview', 'LIKE', '%' . $params['free_text'] . '%')
                    ->orWhere('reward', 'LIKE', '%' . $params['free_text'] . '%')
                    ->orWhere('period', 'LIKE', '%' . $params['free_text'] . '%')
                    ->orWhere('area', 'LIKE', '%' . $params['free_text'] . '%')
                    ->orWhere('target_company', 'LIKE', '%' . $params['free_text'] . '%')
                    ->orWhere('sales_scale', 'LIKE', '%' . $params['free_text'] . '%')
                    ->orWhere('business_content', 'LIKE', '%' . $params['free_text'] . '%')
                    ->orWhere('work_style', 'LIKE', '%' . $params['free_text'] . '%')
                    ->orWhere('weekly_working_days', 'LIKE', '%' . $params['free_text'] . '%')
                    ->orWhere('qualifications', 'LIKE', '%' . $params['free_text'] . '%');
            });
        }
        if (!empty($params['status'])) {
            $this->query->whereIn('status', $params['status']);
        }
        return $this->query();
    }

    public function show($id)
    {
        $model = Matter::query()
            ->with([
                'client',
                'fieldTypeMatters.fieldType',
                'matterApplications.member',
                'matterMemberAssigns.member',
                'matterUserAssigns.user',
                'industryType'
            ])
            ->find($id);
        if (!$model) {
            throw new PosException('18', '001', 404);
        }
        return $model;
    }

    public function showByUser($userId)
    {
        $model = Matter::query()
            ->with([
                'client',
                'industryType'
            ])
            ->whereRelation('matterUserAssigns', 'user_id', '=', $userId)->get();
        return $model;
    }

    public function showByClient($clientId)
    {
        $model = Matter::query()
            ->with([
                'matterMemberAssigns',
                'matterUserAssigns',
                'members',
                'users'
            ])
            ->whereRelation('Client', 'client_id', '=', $clientId)->get();
        return $model;
    }

    public function store($params)
    {
        return DB::transaction(function () use ($params) {
            $model = new Matter();
            $model->fill($params);
            $model['is_private'] = true;
            $model['status'] = Matter::STATUS_PRIVATE;

            if (!empty($model['order_date']) && !empty($model['client_id'])) {
                $date = explode('-', $model['order_date']);
                $total = Client::find($model['client_id'])->matters()->count();
                if ($total == 0) $total = 1;
                $total = $total + 1;
                $model['matter_billing_code'] = $date[0] . "-" . $date[1] . "-" . $model['client_id'] . "_" . $total;
            } else {
                $model['matter_billing_code'] = null;
            }

            $model->save();

            if (!empty($params['assign_users'])) {
                $model->users()->sync($params['assign_users']);
            }
            if (!empty($params['field_types'])) {
                $model->fieldTypes()->sync($params['field_types']);
            }

            return $model::with([
                'fieldTypeMatters',
                'matterUserAssigns',
                'client',
            ])->find($model->id);
        });
    }

    public function update($params, $id)
    {
        $model = Matter::query()
            ->with([
                'fieldTypeMatters',
                'matterUserAssigns'
            ])
            ->find($id);
        if (!$model) {
            throw new PosException('18', '002', 404);
        }
        return DB::transaction(function () use ($params, $model) {
            unset($params['is_private']);
            $model->fill($params);
            if ($model['is_private'] == true) {
                $model['status'] = Matter::STATUS_PRIVATE;
            } else {
                if (!empty($model['application_start_date']) && Carbon::today()->toDateString() < $model['application_start_date']) {
                    $model->status = Matter::STATUS_BEFORE_THE_APPLICATION_PERIOD;
                } else if ( !empty($model['application_end_date']) && $model['application_end_date'] < Carbon::today()->toDateString()) {
                    $model->status = Matter::STATUS_END_OF_THE_APPLICATION_PERIOD;
                } else {
                    $model->status = Matter::STATUS_DURING_THE_APPLICATION_PERIOD;
                }
            }
            $model->save();
            if (!empty($params['assign_users'])) {
                $model->users()->sync($params['assign_users']);
            }
            if (!empty($params['field_types'])) {
                $model->fieldTypes()->sync($params['field_types']);
            }

            return $model::with([
                'client',
                'fieldTypeMatters.fieldType',
                'matterApplications.member',
                'matterMemberAssigns.member',
                'matterUserAssigns.user'
            ])->find($model->id);
        });
    }

    public function destroy($id)
    {
        $data = Matter::with('matterApplications')->find($id);

        if (!$data) {
            throw new PosException('18', '003', 404);
        }
        if ($data->matterApplications()->count() > 0) {
            throw new PosException('18', '005', 422);
        }

        $data->delete();
    }

    public function assignMember($id, $memberId)
    {
        $model = Matter::find($id);
        if (!$model) {
            throw new PosException('18', '001', 404);
        }

        $model = Matter::query()->with([
            'matterMemberAssigns' => function ($query) {
                $query->with('matter');
            },
            "matterApplications"
        ])->whereRelation('matterApplications', 'member_id', $memberId)->find($id);

        if (!$model) {
            throw new PosException('18', '009', 404);
        }

        $model->matterApplications()->where('member_id', $memberId)->update(['status' => 2]);
        $model->members()->syncWithoutDetaching($memberId);
        $model->refresh()->with([
            'matterMemberAssigns' => function ($query) {
                $query->with('matter');
            }
        ]);
        $model = Matter::query()->with([
            'matterMemberAssigns' => function ($query) {
                $query->with('member');
            },
        ])->whereRelation('matterMemberAssigns', 'member_id', $memberId)->find($id);
        return $model->matterMemberAssigns;
    }

    public function assignUser($id, $userId)
    {
        $model = Matter::query()->with([
            "matterUserAssigns"
        ])
            ->find($id);
        if (!$model) {
            throw new PosException('18', '001', 404);
        }
        $model->users()->syncWithoutDetaching($userId);
        return $model;
    }

    public function automaticCancel($params, $id)
    {
        $models = MatterApplication::query()
            ->with(['matter', 'member'])
            ->where('matter_id', $id)
            ->whereIn('member_id', $params['member_id'])->get();
        if ($models->isEmpty()) {
            throw new PosException('18', '003', 404);
        }
        foreach ($models as $item) {
            $item->automatic_email_send_time = is_null($item->automatic_email_send_time) ?
                Carbon::now() : $item->automatic_email_send_time;
            if ($item->status != 3) {
                $item->status = 3;
                $mailTemplate = new MainMailable(24);
                $mailTemplate->setViewData([
                    "name" => $item->matter['project_name'],
                    "subject" => $item->matter['subject']
                ]);
                $mailTemplate->sendMail($item->member->email);
            }
            $item->save();
        }
        return null;
    }

    public function manualCancel($params, $id)
    {
        $model = Matter::with('applications')->find($id);
        if (!$model) {
            throw new PosException('18', '001', 404);
        }
        foreach ($params['member_id'] as $item) {
            $application = $model->applications()->where('member_id', $item)->first();
            if (!$application) {
                throw new PosException('18', '003', 404);
            }

            if (!empty($application) && $application->pivot->status != 4) {
                $application->pivot->status = 4;
                $application->pivot->save();
            }
        }
        return null;
    }

    public function entryStop($id)
    {
        $model = Matter::find($id);
        if (!$model) {
            throw new PosException('18', '001', 404);
        }
        if ($model->status == Matter::STATUS_PRIVATE) {
            throw new PosException('18', '006', 422);
        }
        $model['status'] = Matter::STATUS_END_OF_RECRUITMENT;
        $model->save();
        return $model;
    }

    public function unassignMember($id, $memberId)
    {
        $model = MatterMemberAssign::where('matter_id', $id)->where('member_id', $memberId);
        if (!$model->first()) {
            throw new PosException('18', '004', 404);
        }
        $model->delete();
        return null;
    }

    public function restart($id)
    {
        $model = Matter::find($id);
        if (!$model) {
            throw new PosException('18', '001', 404);
        }
        if ($model->status != 4) {
            throw new PosException('18', '007', 422);
        }
        if (!empty($model['application_start_date']) && Carbon::today()->toDateString() < $model['application_start_date']) {
            $model->status = Matter::STATUS_BEFORE_THE_APPLICATION_PERIOD;
        } else if ( !empty($model['application_end_date']) && $model['application_end_date'] < Carbon::today()->toDateString()) {
            $model->status = Matter::STATUS_END_OF_THE_APPLICATION_PERIOD;
        } else {
            $model->status = Matter::STATUS_DURING_THE_APPLICATION_PERIOD;
        }
        $model->save();
        return $model;
    }

    public function public($id)
    {
        $model = Matter::find($id);
        if (!$model) {
            throw new PosException('18', '001', 404);
        }
        $japaneseKey = [
            'subject' => '件名',
            'industry_type_id' => '業種',
            'overview' => '概要',
            'business_content' => '業務内容',
            'reward' => '報酬',
            'period' => '期間',
            'area' => '地域',
            'weekly_working_days' => '週の稼働日数',
            'publication_range' => '公開範囲',
            'application_start_date' => '申込開始日',
            'application_end_date' => '申込終了日',
        ];

        collect($model)->map(function ($item, $key) use (&$errorMessage, $japaneseKey) {
            if (array_key_exists($key, $japaneseKey) && is_null($item)) {
                $errorMessage = $errorMessage . $japaneseKey[$key] . '、';
            }
        });

        if ($errorMessage) {
            throw new PosException('18', false, 422, rtrim($errorMessage, '、') . "が入力されていないため公開できません。");
        }

        $model->is_private = false;
        if ( !empty($model['application_start_date']) && Carbon::today()->toDateString() < $model['application_start_date'] ) {
            $model->status = Matter::STATUS_BEFORE_THE_APPLICATION_PERIOD;
        } else if ( !empty($model['application_end_date']) && $model['application_end_date'] < Carbon::today()->toDateString()) {
            $model->status = Matter::STATUS_END_OF_THE_APPLICATION_PERIOD;
        } else {
            $model->status = Matter::STATUS_DURING_THE_APPLICATION_PERIOD;
        }

        if (is_null($model->published_date)) {
            $info = new Information();
            $info->fill([
                "title" => $model['subject'],
                "content" => null,
                "display_start_date" => null,
                "display_end_date" => null,
                "is_private" => false,
                "status" => 2,
                "type" => 2,
                "detail_path" => "/matter/" . $model['id']
            ])->save();

            $model->published_date = Carbon::now()->format('Y-m-d H:i:s');
            if ($model['application_start_date'] <= Carbon::today()->toDateString() && $model['application_end_date'] >= Carbon::today()->toDateString() && $model['is_private'] == false) {
                $members = $model->publication_rangeMember == 1 ?
                    Member::all() : Member::where('is_partner', true)->get();
                foreach ($members as $member) {
                    $mailTemplate = new MainMailable(15);
                    $mailTemplate->setViewData([
                        "name" => $model['project_name'],
                        "subject" => $model['subject'],
                        "industry_type" => $model['industry_type_id'],
                        "overview" => $model['overview"'],
                        "content" => $model['business_content'],
                        "reward" => $model['reward'],
                        "period" => $model['period'],
                        "area" => $model['area'],
                        "weekly_working_days" => $model['weekly_working_days'],
                        "target_company" => $model['target_company'],
                        "sales_scale" => $model['sales_scale'],
                        "work_style" => $model['work_style'],
                        "qualifications" => $model['qualifications'],
                        "publication_range" => $model['publication_range'],
                        "application_start_date" => $model['application_start_date'],
                        "application_end_date" => $model['application_end_date'],
                        "url" => $model['press_release_url']
                    ]);
                    $mailTemplate->sendMail($member->email);
                }
            }
        } else {
            $info = null;
        }
        $model->save();
        return ['matter' => $model, 'information' => $info];
    }

    public function private($id)
    {
        $model = Matter::with('matterApplications')->find($id);
        if (!$model) {
            throw new PosException('18', '001', 404);
        }

        if ($model->matterApplications()->count() > 0) {
            throw new PosException('18', '008', 422);
        }
        $model->is_private = true;
        $model->status = Matter::STATUS_PRIVATE;
        $model->save();
        return $model;
    }
}
