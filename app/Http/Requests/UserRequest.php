<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;

class UserRequest extends BaseFormRequest
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
            'email' => ['bail', 'required', 'email', 'max:255', 'unique:users,email,' . $request->id],
            'belong' => ['bail', 'required', 'max:255'],
            'expiration_date' => ['bail', 'nullable', 'date_format:Y-m-d'],
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
            'belong' => '所属',
            'email' => 'メールアドレス'
        ];
    }
}
