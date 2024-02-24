<?php

namespace App\Http\Requests;


class LinkRequest extends BaseFormRequest
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
            'is_private' => ['bail', 'required', 'boolean'],
            'title' => ['bail', 'required', 'string', 'max:255'],
            'content' => ['bail', 'nullable', 'string', 'max:21845'],
            'image' => ['bail', 'nullable', 'image'],
            'url' => ['bail', 'nullable', 'url', 'max:21845'],
            'display_order' => ['bail', 'nullable', 'integer', 'min:0', 'max:65535']
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
            'is_private' => '非公開フラグ',
            'title' => 'タイトル',
            'content' => '内容',
            'image' => 'バナー画像',
            'url' => 'リンク先URL',
            'display_order' => '表示順'
        ];
    }
}
