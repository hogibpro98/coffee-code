<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InformationRequest extends BaseFormRequest
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
            'title' => ['bail','required', 'string', 'max:255'],
            'content' => ['bail','required'],
            'is_private' => ['bail','required', 'boolean'],
            'type' => ['bail','required', 'integer', Rule::in([1,2,3])],
            'display_start_date' => ['bail','nullable','date_format:Y-m-d'],
            'display_end_date' => ['bail','nullable','date_format:Y-m-d', 'after_or_equal:display_start_date'],
        ];
    }

    public function attributes()
    {
        return [
            'title' => 'タイトル',
            'content' => '内容',
            'is_private' => '非公開フラグ',
            'display_start_date' => '表示開始日',
            'display_end_date' => '表示終了日',
            'type' => '種別',
        ];
    }


}
