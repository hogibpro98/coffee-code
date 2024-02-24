<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;

class MailTemplateRequest extends BaseFormRequest
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
            'name' => ['bail', 'required', 'string', 'max:255'],
            'type' => ['bail', 'required', 'integer', 'min:1', 'max:3'],
            'from' => ['bail', 'required', 'string', 'max:255'],
            'title' => ['bail', 'required', 'string', 'max:255'],
            'content' => ['bail', 'required', 'string', 'max:1431655765'],
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
            'name' => '名前',
            'type' => 'タイプ',
            'from' => '差出人',
            'title' => 'タイトル',
            'content' => 'コンテンツ',
        ];
    }
}
