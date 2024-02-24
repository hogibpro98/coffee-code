<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class EventSeminarRequest extends BaseFormRequest
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
    public function rules(Request $request)
    {
        if ($request->is('api/v1/event-seminar/*/piece')) {
            return [
                'times' => ['bail', 'required', 'integer', 'max:999'],
                'start_time' => ['bail', 'required', 'date'],
                'end_time' => ['bail', 'required', 'date'],
                'postal_code' => ['bail', 'nullable', 'string', 'max:10'],
                'prefecture' => ['bail', 'nullable', 'integer', 'max:999'],
                'address1' => ['bail', 'nullable', 'string', 'max:1024'],
                'address2' => ['bail', 'nullable', 'string', 'max:255'],
                'capacity' => ['bail', 'nullable', 'integer'],
                'zoom_url' => ['bail', 'nullable', 'string', 'max:1024'],
                'zoom_meeting_id' => ['bail', 'nullable', 'string', 'max:20'],
                'zoom_password' => ['bail', 'nullable', 'string', 'max:20'],
                'archive_url' => ['bail', 'nullable', 'string', 'max:21845'],
                'zoom_org_data' => ['bail', 'nullable', 'string', 'max:21845'],
                'remarks_for_manager' => ['bail', 'nullable', 'string', 'max:21845'],
                'remarks_for_mail' => ['bail', 'required', 'string', 'max:21845'],
            ];
        }

        if ($request->is('api/v1/event-seminar/*/piece/*')) {
            return [
                'id' => ['bail', 'required', 'integer'],
                'postal_code' => ['bail', 'nullable', 'string', 'max:10'],
                'prefecture' => ['bail', 'nullable', 'integer', 'max:999'],
                'address1' => ['bail', 'nullable', 'string', 'max:1024'],
                'address2' => ['bail', 'nullable', 'string', 'max:255'],
                'archive_url' => ['bail', 'nullable', 'string', 'max:21845'],
            ];
        }

        if ($request->is('api/v1/event-seminar/*/times')) {
            return [
                'times' => ['bail', 'required', 'integer', 'between:1,999'],
                'times_title' => ['bail', 'required', 'string', 'max:21845'],
                'times_content' => ['bail', 'required', 'string', 'max:21845'],
            ];
        }

        $rulesPost = [
            'title' => ['bail', 'required', 'string', 'max:255'],
            'type' => ['bail', 'required', 'integer', 'between:1,2'],
            'is_private' => ['bail', 'integer', 'between:0,1'],
            'content' => ['bail', 'required', 'string', 'max:21845'],
            'application_start_date' => ['bail', 'required', 'date'],
            'application_end_date' => ['bail', 'required', 'date'],
            'fee_type' => ['bail', 'required', 'integer', 'between:1,2'],
            'fee' => ['bail', 'nullable', 'integer'],
            'capacity_type' => ['bail', 'required', 'integer', 'between:1,2'],
            'holding_type' => ['bail', 'required', 'integer', 'between:1,2'],
            'holding_time_type' => ['bail', 'required', 'integer', 'between:1,3'],
            'status' => ['bail', 'integer', 'between:1,6'],
            'published_date' => ['bail', 'nullable', 'date'],
            'cpe_registration' => ['bail', 'nullable', 'string', 'max:255'],
            'organizer' => ['bail', 'nullable', 'string', 'max:255'],
            'times_infomation' => ['bail', 'nullable', 'string', 'max:21845']
        ];

        $rulesPut = [
            'title' => ['bail', 'required', 'string', 'max:255'],
            'content' => ['bail', 'required', 'string', 'max:21845'],
            'application_start_date' => ['bail', 'required', 'date'],
            'application_end_date' => ['bail', 'required', 'date']
        ];

        return ($request->isMethod('post')) ? $rulesPost : $rulesPut;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'title' => 'タイトル',
            'type' => '種別',
            'is_private' => '非公開フラグ',
            'content' => '内容',
            'application_start_date' => '申込開始日',
            'application_end_date' => '申込終了日',
            'fee_type' => '参加費タイプ',
            'fee' => '参加費',
            'capacity_type' => '定員数タイプ',
            'holding_type' => '開催種別',
            'holding_time_type' => '開催日時タイプ',
            'status' => 'ステータス',
            'published_date' => '公開日時',
            'cpe_registration' => 'CPE登録',
            'organizer' => '主催者',
            'times_infomation' => '回情報'
        ];
    }

}

