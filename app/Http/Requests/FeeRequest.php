<?php

namespace App\Http\Requests;


class FeeRequest extends BaseFormRequest
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
            'professional_member_fee' => ['bail','required','integer','min:0','max:2147483647'],
            'proAttend_partner_fee' => ['bail','required','integer','min:0','max:2147483647']
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
            'professional_member_fee' => 'Professional Member',
            'proAttend_partner_fee' => 'ProAttend Partner'
        ];
    }
}
