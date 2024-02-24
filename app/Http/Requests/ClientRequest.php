<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ClientRequest extends BaseFormRequest
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
        $representatives = 'client_representatives.*.';
        return [
            'industry_type_id' => ['bail', 'required', 'exists:industry_types,id', 'integer'],
            'client_name_fullwidth' => ['bail', 'required', 'string', 'max:255'],
            'client_name_katakana' => ['bail', 'nullable', 'string', 'max:255'],
            'client_name_english' => ['bail', 'nullable', 'string', 'max:255'],
            'postal_code'=> ['bail', 'required', 'string', 'max:10'],
            'prefecture'=> ['bail', 'required', 'integer', 'max:255'],
            'address1' => ['bail', 'required', 'string', 'max:255'],
            'address2' => ['bail', 'required', 'string', 'max:255'],
            'note' => ['bail', 'nullable', 'string', 'max:21845'],
            $representatives.'name' => ['bail', 'nullable', 'string', 'max:255'],
            $representatives.'email' => ['bail', 'nullable', 'email', 'max:255'],
            $representatives.'tel' => ['bail', 'nullable', 'string', 'max:20'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        $representatives = 'client_representatives.*.';
        return [
            'industry_type_id' => '業種ID',
            'client_name_fullwidth' => '社名（全角）',
            'client_name_katakana' => '社名（全角）',
            'client_name_english' => '社名（英）',
            'postal_code'=> '郵便番号 ',
            'prefecture'=> '都道府県',
            'address1' => '市区町村 ',
            'address2' => '番地以下',
            'note' => '備考',
            $representatives.'name' => '名前',
            $representatives.'email' => 'メールアドレス',
            $representatives.'tel' => '電話番号',
        ];
    }
}
