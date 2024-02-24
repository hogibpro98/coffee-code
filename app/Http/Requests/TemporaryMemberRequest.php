<?php

namespace App\Http\Requests;

use App\Models\TemporaryMemberCareerHistory;
use App\Models\TemporaryMemberEducationHistory;
use App\Models\TemporaryMemberFieldType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class TemporaryMemberRequest extends BaseFormRequest
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
        $tmCareer = !empty($request->temporary_member_career) ? 'temporary_member_career.' : 'temporary_member_career.*.';
        $tmCareerHistories = 'temporary_member_career_histories.*.';
        $tmEducationHistories = 'temporary_member_education_histories.*.';
        $tmFieldTypes = 'temporary_member_field_types.*.';
        $tmQualifications = 'temporary_member_qualifications.*.';
        $tmOwnedQualification = 'temporary_member_owned_qualifications.*.';
        return [
            //temporary_member
            'name_kanji' => ['bail', 'required', 'string', 'max:100'],
            'name_furigana' => ['bail', 'required', 'string', 'max:255'],
            'email' => ['bail', 'required', 'email', 'max:255'],
            'password' => ['bail', 'string', 'max:255'],
            'interview_status' => ['bail', 'nullable', 'integer', 'between:1,5'],
            'temporary_member_career' => ['bail', 'nullable'],
            //temporary_member_career
            $tmCareer.'id' => ['bail', 'required', 'integer'],
            $tmCareer.'temporary_member_id' => ['bail', 'required', 'exists:temporary_members,id', 'integer'],
            $tmCareer.'birthdate' => ['bail', 'required', 'date'],
            $tmCareer.'gender' => ['bail', 'nullable', 'integer'],
            $tmCareer.'office_name' => ['bail', 'required','string', 'max:255'],
            $tmCareer.'postal_code' => ['bail', 'required','string', 'max:20'],
            $tmCareer.'prefecture' => ['bail', 'required','integer'],
            $tmCareer.'address1' => ['bail', 'required','string', 'max:255'],
            $tmCareer.'address2' => ['bail', 'required','string', 'max:255'],
            $tmCareer.'tel1' => ['bail', 'required','string', 'max:10'],
            $tmCareer.'tel2' => ['bail', 'required','string', 'max:10'],
            $tmCareer.'tel3' => ['bail', 'required','string', 'max:10'],
            $tmCareer.'owned_qualifications' => ['bail', 'nullable','string', 'max:255'],
            $tmCareer.'advisory_experience_years' => ['bail', 'required','integer'],
            $tmCareer.'other_specialized_field' => ['bail', 'nullable','string'],
            $tmCareer.'experience' => ['bail', 'required','string'],
            $tmCareer.'temporary_member_career_histories' => ['bail', 'required','array'],
            $tmCareer.'temporary_member_education_histories' => ['bail', 'required','array'],
            $tmCareer.'temporary_member_field_types' => ['bail', 'required', 'array'],
            $tmCareer.'temporary_member_owned_qualifications' => ['bail', 'nullable', 'array'],
            //temporary_member_career_histories
            $tmCareer.$tmCareerHistories.'temporary_member_career_id' => ['bail', 'nullable','integer'],
            $tmCareer.$tmCareerHistories.'find_work' => ['bail', 'required', 'date'],
            $tmCareer.$tmCareerHistories.'retirement' => ['bail', 'nullable', 'date'],
            $tmCareer.$tmCareerHistories.'office_name' => ['bail', 'required','string', 'max:255'],
            $tmCareer.$tmCareerHistories.'status' => ['bail', 'required', 'integer'],
            $tmCareer.$tmCareerHistories.'free_entry' => ['bail', 'nullable','string'],
            //temporary_member_education_histories
            $tmCareer.$tmEducationHistories.'temporary_member_career_id' => ['bail', 'nullable','integer'],
            $tmCareer.$tmEducationHistories.'admission' => ['bail', 'required', 'date'],
            $tmCareer.$tmEducationHistories.'graduation' => ['bail', 'required', 'date'],
            $tmCareer.$tmEducationHistories.'school_name' => ['bail', 'required','string', 'max:255'],
            $tmCareer.$tmFieldTypes.'field_id' => ['bail', 'required', 'integer'],
            $tmCareer.$tmFieldTypes.'type' => ['bail', 'required', 'integer'],
            $tmCareer.'temporary_member_owned_qualifications.*.other_qualification' => ['bail', 'nullable', 'string', 'max:255'],
            $tmCareer.'temporary_member_owned_qualifications.*.owned_qualification' => ['bail', 'required', 'integer'],
            $tmCareer.'certified_accountant_number' => ['bail', 'nullable','string', 'max:10'],
            $tmCareer.'us_certified_accountant_number' => ['bail','nullable' ,'string', 'max:10'],
            $tmCareer.'tax_accountant_number' => ['bail', 'nullable','integer'],
            'temporary_member_qualifications' => ['bail', 'required', 'array'],
            'temporary_member_qualifications.*' => ['bail', 'integer'],
        ];

    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        $tmCareer = 'temporary_member_career.';// : 'temporary_member_career*.';
        $tmCareerHistories = 'temporary_member_career_histories.*.';
        $tmEducationHistories = 'temporary_member_education_histories.*.';
        $tmFieldTypes = 'temporary_member_field_types.*.';
        $tmQualifications = 'temporary_member_qualifications.*.';
        return [
            //temporary_member
            'name_kanji' => '氏名（漢字）',
            'name_furigana' => '氏名（フリガナ）',
            'email' => 'メールアドレス',
            'password' => 'パスワード',
            'interview_status' => '面談ステータス',
            //temporary_member_career
            $tmCareer.'temporary_member_id' => '仮会員ID',
            $tmCareer.'birthdate' => '生年月日',
            $tmCareer.'gender' => '性別',
            $tmCareer.'office_name' => '事業所名',
            $tmCareer.'postal_code' => '郵便番号',
            $tmCareer.'prefecture' => '都道府県',
            $tmCareer.'address1' => '市区町村',
            $tmCareer.'address2' => '番地以下',
            $tmCareer.'tel1' => '市外局番',
            $tmCareer.'tel2' => '市内局番',
            $tmCareer.'tel3' => '加入者番号',
            $tmCareer.'owned_qualifications' => '保有資格',
            $tmCareer.'certified_accountant_number' => '公認会計士登録番号',
            $tmCareer.'us_certified_accountant_number' => '米国公認会計士登録番号',
            $tmCareer.'tax_accountant_number' => '税理士登録番号',
            $tmCareer.'advisory_experience_years' => 'アドバイザリー経験年数',
            $tmCareer.'other_specialized_field' => 'その他専門分野',
            $tmCareer.'experience' => '要約',
            $tmCareer.'temporary_member_career_histories' => '仮会員職務経歴',
            $tmCareer.'temporary_member_education_histories' => '仮会員学歴',
            //temporary_member_career_histories
            $tmCareer.$tmCareerHistories.'temporary_member_career_id' => '仮会員経歴ID',
            $tmCareer.$tmCareerHistories.'find_work' => '就職年月',
            $tmCareer.$tmCareerHistories.'retirement' => '退職年月',
            $tmCareer.$tmCareerHistories.'office_name' => '会社名',
            $tmCareer.$tmCareerHistories.'status' => 'ステータス',
            $tmCareer.$tmCareerHistories.'free_entry' => '職務詳細',
            //temporary_member_education_histories
            $tmCareer.$tmEducationHistories.'temporary_member_career_id' => '仮会員経歴ID',
            $tmCareer.$tmEducationHistories.'admission' => '入学年月',
            $tmCareer.$tmEducationHistories.'graduation' => '卒業年月',
            $tmCareer.$tmEducationHistories.'school_name' => '学校名',

            $tmCareer.$tmFieldTypes.'field_id' => '分野管理ID',
            $tmCareer.$tmFieldTypes.'type' => '種別',
            //temporary_member_qualifications
            $tmQualifications.'temporary_member_id' => '仮会員ID',
            $tmQualifications.'qualification' => $tmQualifications.'qualification' ? '入会資格' : "",
            $tmCareer.'temporary_member_owned_qualifications.*.other_qualification' => "その他保有資格",
            $tmCareer.'temporary_member_field_types' => '専門分野',
            'temporary_member_qualifications' => '入会資格',
            'temporary_member_career' => '仮会員経歴',
            'temporary_member_qualifications.*.' => '仮会員経歴の要素',
        ];
    }
}
