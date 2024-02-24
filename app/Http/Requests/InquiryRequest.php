<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class InquiryRequest extends BaseFormRequest
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
            'member_id' => ['bail', 'nullable', 'exists:members,id', 'integer'],
            'user_id' => ['bail', 'nullable', 'exists:users,id', 'integer'],
            'content' => ['bail', 'required', 'string', 'max:21845'],
            'status' => ['bail', 'integer', Rule::in([1,2,3]) ],
            'title' => ['bail', 'max:255' ],
            'is_read' => ['bail' , 'integer'],
        ];

    }

    public function attributes()
    {
        return [
            'member_id' => '本会員ID',
            'user_id' => 'ユーザID',
            'content' => 'コメント本文',
            'status' => 'ステータス',
            'is_read' => '既読フラグ',
        ];
    }



}