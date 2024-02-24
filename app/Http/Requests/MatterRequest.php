<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class MatterRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'subject' => ['bail', 'nullable', 'string', 'max:255'],
            'industry_type_id' => ['bail', 'nullable', 'exists:industry_types,id', 'integer'],
            'overview' => ['bail', 'nullable', 'string', 'max:21845'],
            'business_content' => ['bail', 'nullable', 'string', 'max:21845'],
            'reward' => ['bail','nullable', 'string', 'max:255'],
            'period' => ['bail','nullable', 'string', 'max:255'],
            'area' => ['bail','nullable', 'string', 'max:255'],
            'weekly_working_days' => ['bail','nullable', 'string', 'max:255'],
            'target_company' => ['bail','nullable', 'string', 'max:255'],
            'sales_scale' => ['bail','nullable', 'string', 'max:255'],
            'work_style' => ['bail','nullable', 'string', 'max:255'],
            'application_start_date' => ['bail','nullable','date'],
            'application_end_date' => ['bail','nullable','date', 'after_or_equal:application_start_date'],
            'qualifications' => ['bail', 'nullable', 'string', 'max:21845'],
            'publication_range' => ['bail','nullable','integer', Rule::in([1,2])],
            'introduction_company_name' => ['bail','nullable', 'string', 'max:255'],
            'client_id' => ['bail','nullable', 'integer', 'exists:clients,id'],
            'order_date' => ['bail','nullable', 'date'],
            'project_name' => ['bail','nullable', 'string', 'max:255'],
            'gross_fee' => ['bail','nullable', 'string', 'max:255'],
            'net_fee' => ['bail','nullable', 'string', 'max:255'],
            'matter_status' => ['bail', 'nullable', 'integer', 'max:255'],
            'contract_status' => ['bail', 'nullable', 'integer', 'max:255'],
            'press_release_url' => ['bail','nullable', 'string', 'max:1024'],
            'note' => ['bail', 'nullable', 'string', 'max:21845'],
            'matter_billing_code' => ['bail','nullable', 'string', 'max:100'],
            "published_date" => ['bail','nullable', 'date'],
            'assign_users.*' => ['bail','exists:users,id'],
            'field_types.*' => ['bail','exists:field_types,id']
        ];

    }
    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'subject' => '件名',
            'industry_type_id' => '業種',
            'overview' => '概要',
            'business_content' => '業務内容',
            'reward' => '報酬',
            'period' => '期間',
            'area' => '地域',
            'weekly_working_days' => '週の稼働日数',
            'target_company' => '対象会社',
            'sales_scale' => '売上規模',
            'work_style' => '働き方',
            'application_start_date' => '申込開始日',
            'application_end_date' => '申込終了日',
            'qualifications' => 'その他応募条件',
            'publication_range' => '公開範囲',
            'introduction_company_name' => '仲介・紹介企業名',
            'client_id' => '依頼企業 ',
            'order_date' => '依頼年月日',
            'project_name' => 'プロジェクト名',
            'gross_fee' => 'Grossフィー',
            'net_fee' => 'ネットフィー',
            'matter_status' => '案件ステータス',
            'contract_status' => '契約ステータス',
            'press_release_url' => 'プレスリリースURL',
            'note' => '備考',
            'matter_billing_code' => '案件・請求コード',
            "published_date" => '公開日時',
            'assign_users.*' => '担当エージェント',
            'field_types.*' => '案件区分'
        ];
    }

}
