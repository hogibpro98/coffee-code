<?php

namespace App\Services;

use App\Traits\ListTrait;
use App\Models\SystemSetting;
use App\Exceptions\PosException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;

class SystemSettingService
{
    use ListTrait;

    public function feeDetail()
    {
        $model = SystemSetting::select('professional_member_fee','proAttend_partner_fee')->first();
        // 存在しなかったら404
        if (!$model) {
            throw new PosException('05', '001', 404);
        }
        return $model;
    }

    public function feeUpdate($params)
    {
        //find data
        $model = SystemSetting::first();
        // 存在しなかったら404
        if (!$model) {
            throw new PosException('05', '002', 404);
        }
        $model->fill([
            'professional_member_fee' => $params['professional_member_fee'],
            'proAttend_partner_fee' => $params['proAttend_partner_fee'],
        ])->save();

        return [
            'professional_member_fee' => $model->professional_member_fee,
            'proAttend_partner_fee' => $model->proAttend_partner_fee
        ];
    }

    public function memberPolicyDetail()
    {
        $model = SystemSetting::select('members_terms')->first();
        // 存在しなかったら404
        if (!$model) {
            throw new PosException('06', '001', 404);
        }
        return $model;
    }

    public function memberPolicyUpdate($params)
    {
        $model = SystemSetting::first();
        // 存在しなかったら404
        if (!$model) {
            throw new PosException('06', '002', 404);
        }
        $model->fill(['members_terms' => $params['members_terms']])->save();

        return ['members_terms' => $model->members_terms];
    }

    public function privacyPolicyDetail()
    {
        $model = SystemSetting::select('privacy_policy')->first();
        // 存在しなかったら404
        if (!$model) {
            throw new PosException('07', '001', 404);
        }
        return $model;
    }

    public function privacyPolicyUpdate($params)
    {
        $model = SystemSetting::first();
        // 存在しなかったら404
        if (!$model) {
            throw new PosException('07', '002', 404);
        }
        $model->fill(['privacy_policy' => $params['privacy_policy']])->save();

        return ['privacy_policy' => $model->privacy_policy];
    }
}
