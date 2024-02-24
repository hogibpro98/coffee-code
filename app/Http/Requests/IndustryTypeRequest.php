<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;

class IndustryTypeRequest extends BaseFormRequest
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
            'name' => ['bail', 'required', 'string', 'max:255'],
            'note' => ['bail', 'string', 'nullable', 'max:21845'],
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
            'name' => '名称',
            'note' => '備考'
        ];
    }
}
