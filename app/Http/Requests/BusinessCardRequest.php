<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;

class BusinessCardRequest extends BaseFormRequest
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
            'status' => ['bail', 'required', 'integer', Rule::in([1,2,3,4])],
            'delivery_postal_code' => ['bail', 'required', 'string', 'max:20' ],
            'delivery_prefecture' => ['bail', 'required', 'integer', 'min:0', 'max:255'],
            'delivery_address1' => ['bail', 'required', 'string', 'max:255' ],
            'delivery_address2' => ['bail', 'required', 'string', 'max:255' ],
            'card_name_kanji' => ['bail', 'required', 'string', 'max:100' ],
            'card_name_roman' => ['bail', 'required', 'string', 'max:100' ],
            'card_email' => ['bail', 'required', 'email', 'max:255'],
            'card_office_name' => ['bail', 'required', 'string', 'max:255' ],
            'card_qualification' => ['bail', 'nullable','array','max:3' ],
            'note' => ['bail', 'required', 'string', 'max:21845'],
            'card_image_file' => ['bail', 'nullable', 'image'],
            'card_background_image_file' => ['bail', 'nullable', 'image']
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
            'status' => 'ステータス',
            'delivery_postal_code' => '郵便番号',
            'delivery_prefecture' => '都道府県',
            'delivery_address1' => '市区町村',
            'delivery_address2' => '番地以下',
            'card_name_kanji' => '氏名（漢字）',
            'card_name_roman' => '氏名（ローマ字）',
            'card_email' => 'メールアドレス',
            'card_office_name' => '事業所名',
            'card_qualification' => '名刺記載資格',
            'note' => '備考',
            'card_image_file' => '名刺画像',
            'card_background_image_file' => '名刺背景画像'
        ];
    }
}
