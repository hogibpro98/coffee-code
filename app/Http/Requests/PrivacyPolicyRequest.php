<?php

namespace App\Http\Requests;


class PrivacyPolicyRequest extends BaseFormRequest
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
            'privacy_policy' => ['bail', 'required', 'string', 'max:1431655765']
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
            'privacy_policy' => '個人情報取り扱い'
        ];
    }
}
