<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;

class FormatRequest extends BaseFormRequest
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
            'is_private' => ['bail', 'required', 'boolean'],
            'title' => ['bail', 'required', 'string', 'max:255'],
            'file_name' => ['bail', 'nullable', 'string', 'max:255'],
            'file_path' => ['bail', 'nullable', 'string', 'max:1024'],
            'mime_type' => ['bail', 'nullable', 'string', 'max:100'],
            'content' => ['bail', 'nullable', 'string', 'max:21845'],
            'tag' => ['bail', 'nullable', 'string', 'max:1431655765'],
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
            'tag' => 'タグ',
            'file_name' => 'ファイル名',
            'file_path' => 'ファイルパス',
            'mime_type' => 'mimeタイプ'
        ];
    }
}
