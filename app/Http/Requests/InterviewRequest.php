<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class InterviewRequest extends BaseFormRequest
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
        return [
            'temporary_member_id' => ($request->isMethod('post')) ?
                ['bail', 'required', 'exists:temporary_members,id', 'integer', 'unique:interviews'] :
                [],
            'interview_fixed_date' => ['bail', 'nullable', 'date_format:"Y-m-d'],
            'interview_start_time' => ['bail', 'nullable', 'date_format:"H:i'],
            'interview_end_time' => ['bail', 'nullable', 'date_format:"H:i', 'after:interview_start_time'],
            'insertion_text_to_mail_template' => ($request->isMethod('post')) ? ['bail', 'required', 'string', 'max:21845'] : [],
            'email_send_time' => ['bail', 'nullable', 'date_format:"Y-m-d H:i:s'],
            'note' => ($request->isMethod('put')) ? ['bail', 'nullable', 'string', 'max:21845'] : [],
        ];
    }

    public function attributes()
    {
        return [
            'temporary_member_id' => '仮会員ID',
            'interview_fixed_date' => '面談確定日',
            'interview_start_time' => '面談開始時間',
            'interview_end_time' => '面談終了時間',
            'insertion_text_to_mail_template' => 'メールテンプレートへの差し込み本文',
            'email_send_time' => 'メール送信日時',
            'note' => '備考',
            'status' => 'ステータス',
        ];
    }
}
