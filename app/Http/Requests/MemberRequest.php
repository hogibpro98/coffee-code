<?php

namespace App\Http\Requests;

use App\Models\MemberCareerHistory;
use App\Models\MemberEducationHistory;
use App\Models\MemberFirldType;
use Illuminate\Http\Request;

class MemberRequest extends BaseFormRequest
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
        $memberEducationHistories = 'member_education_histories.*.';
        $memberCareerHistories = 'member_career_histories.*.';
        $memberOwnedQualifications = 'member_owned_qualifications.*.';
        $fieldTypes = 'field_types.*.';

        return [
            'id' => ['bail', 'nullable'],
            'name_kanji' => ['bail', 'required', 'string', 'max:100'],
            'name_furigana' => ['bail', 'required', 'string', 'max:100'],
            'email' => ['bail', 'required', 'email', 'max:255'],
            'email_for_update' => ['bail', 'nullable', 'email', 'max:255'],
            'token_for_update_email' => ['bail', 'nullable', 'max:100'],
            'birthdate' => ['bail', 'required', 'date'],
            'gender' => ['bail', 'nullable', 'integer', 'min:0'],
            'office_name' => ['bail', 'required','string', 'max:255'],
            'postal_code' => ['bail', 'required','string', 'max:20'],
            'prefecture' => ['bail', 'required','integer', 'min:0'],
            'address1' => ['bail', 'required','string', 'max:255'],
            'address2' => ['bail', 'required','string', 'max:255'],
            'tel1' => ['bail', 'required','string', 'max:10'],
            'tel2' => ['bail', 'required','string', 'max:10'],
            'tel3' => ['bail', 'required','string', 'max:10'],
            'owned_qualifications' => ['bail', 'nullable', 'string'],
            'advisory_experience_years' => ['bail', 'required','integer', 'min:1', 'max:5'],
            'other_specialized_field' => ['bail', 'nullable','string', 'max:255'],
            'experience' => ['bail', 'required','string', 'max:255'],
            'note' => ['bail', 'nullable', 'string'],
            'is_partner' => ['bail', 'required', 'boolean'],
            'is_release_working_status' => ['bail', 'required', 'boolean'],
            'member_education_histories' => ['bail', 'required', 'array'],
            'member_career_histories' => ['bail', 'required', 'array'],
            'field_types' => ['bail', 'required', 'array'],
            $memberEducationHistories.'id' => ['bail', 'nullable', 'integer'],
            $memberEducationHistories.'member_id' => ['bail', 'nullable', 'exists:members,id', 'integer'],
            $memberEducationHistories.'admission' => ['bail', 'required', 'date'],
            $memberEducationHistories.'graduation' => ['bail', 'required', 'date'],
            $memberEducationHistories.'school_name' => ['bail', 'required','string', 'max:255'],
            $memberCareerHistories.'id' => ['bail', 'nullable', 'integer'],
            $memberCareerHistories.'member_id' => ['bail', 'nullable', 'exists:members,id', 'integer'],
            $memberCareerHistories.'find_work' => ['bail', 'required', 'date'],
            $memberCareerHistories.'retirement' => ['bail', 'nullable', 'date'],
            $memberCareerHistories.'office_name' => ['bail', 'required','string', 'max:255'],
            $memberCareerHistories.'status' => ['bail', 'required', 'integer'],
            $memberCareerHistories.'free_entry' => ['bail', 'nullable','string'],
            'member_owned_qualifications' => ['bail', 'nullable', 'array'],
            $memberOwnedQualifications.'owned_qualification' => ['bail', 'required','integer'],
            $fieldTypes.'field_id' => ['bail', 'required', 'integer'],
            $fieldTypes.'type' => ['bail', 'required', 'integer'],
            $memberOwnedQualifications.'other_qualification' => ['bail', 'nullable','string'],
            'certified_accountant_number' => ['bail', 'nullable' ,'string', 'max:10'],
            'us_certified_accountant_number' => ['bail', 'nullable', 'string', 'max:10'],
            'tax_accountant_number' => ['bail',  'nullable', 'string', 'max:10'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        $memberEducationHistories = 'member_education_histories.*.';
        $memberCareerHistories = 'member_career_histories.*.';
        $memberOwnedQualifications = 'member_owned_qualifications.*.';
        $fieldTypes = 'field_types.*.';

        return [
            'name_kanji' => '氏名（漢字）',
            'name_furigana' => '氏名（フリガナ）',
            'email' => 'メールアドレス',
            'email_for_update' => '新しいメールアドレス',
            'token_for_update_email' => 'メールアドレス変更用トークン',
            'birthdate' => '生年月日',
            'gender' => '性別',
            'office_name' => '事業所名',
            'postal_code' => '郵便番号',
            'prefecture' => '都道府県',
            'address1' => '市区町村',
            'address2' => '番地以下',
            'tel1' => '市外局番',
            'tel2' => '市内局番',
            'tel3' => '加入者番号',
            'certified_accountant_number' => '公認会計士登録番号',
            'us_certified_accountant_number' => '米国公認会計士登録番号',
            'tax_accountant_number' => '税理士登録番号',
            'advisory_experience_years' => 'アドバイザリー経験年数',
            'other_specialized_field' => 'その他専門分野',
            'experience' => '要約',
            'note' => '備考',
            'is_partner' => 'Partnerフラグ',
            'is_release_working_status' => '稼働状況公開フラグ',
            'member_education_histories' => '会員学歴',
            'member_career_histories' => '会員職務経歴',
            $memberEducationHistories.'member_id' => '本会員ID',
            $memberEducationHistories.'admission' => '入学年月',
            $memberEducationHistories.'graduation' => '卒業年月',
            $memberEducationHistories.'school_name' => '学校名',
            $memberCareerHistories.'member_id' => '本会員ID',
            $memberCareerHistories.'find_work' => '就職年月',
            $memberCareerHistories.'retirement' => '退職年月',
            $memberCareerHistories.'office_name' => '会社名',
            $memberCareerHistories.'status' => 'ステータス',
            $memberCareerHistories.'free_entry' => '職務詳細',
            $memberOwnedQualifications.'owned_qualification' => '保有資格',
            $memberOwnedQualifications.'other_qualification' => 'その他保有資格',
            $fieldTypes.'field_id' => '分野管理ID',
            $fieldTypes.'type' => '種別',
        ];
    }
}
