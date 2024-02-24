<?php

namespace App\Http\Requests;


class FieldTypeRequest extends BaseFormRequest
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
            'name' => ['bail', 'required', 'string', 'max:255'],
            'grouping_list' => ['bail', 'required', 'array'],
            'note' => ['bail', 'nullable', 'string', 'max:21845']
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
            'grouping_list' => 'グルーピングリスト',
            'note' => '備考'
        ];
    }
}
