<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;

class BillingRequest extends BaseFormRequest
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
     * @param Request $request
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            'member_note' => ['bail','nullable', 'string'],
            'user_note' => ['bail','nullable','string'],
            'status' => ['bail','integer', 'min:1', 'max:4','nullable'],

            'billing_details.*.name' => ['bail', 'required', 'max:255'],
            'billing_details.*.price' => ['bail','required','integer'], 
        ];
    }

    public function attributes()
    {
        return [
            'status' => 'ステータス',
            'member_note' => '会員表示用備考',
            'user_note' => '管理者用備考',

            'billing_details.*.name' => '費目名',
            'billing_details.*.note' => '備考',
            'billing_details.*.price' => '金額',
        ];
    }
}
