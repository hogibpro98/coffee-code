<?php

namespace App\Services;

use App\Commons\Helper;
use App\Models\Member;
use App\Traits\ListTrait;
use App\Models\TemporaryMember;
use App\Exceptions\PosException;
use App\Commons\PosConst;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Mail\MainMailable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TemporaryMemberService
{
    use ListTrait;

    public function index($params)
    {
        $this->query = TemporaryMember::query()
            ->with([
                'temporaryMemberCareer.temporaryMemberCareerHistories',
                'temporaryMemberCareer.temporaryMemberEducationHistories',
                'temporaryMemberCareer.temporaryMemberFieldTypes',
                'temporaryMemberCareer.temporaryMemberOwnedQualifications',
                'temporaryMemberQualifications'
            ]);

        if (!empty($params['qualification'])) {
            $this->query
            ->with('temporaryMemberQualifications')
                ->whereHas('temporaryMemberQualifications', function ($query) use($params) {
                        $query->whereIn('qualification', $params['qualification']);
                });
        }

        if(!empty($params['owned_qualification'])) {
            $ownedQualifications = [];
            array_filter(PosConst::OWNED_QUALIFICATIONS, function ($item) use ($params, &$ownedQualifications) {
                if(strpos($item['text'], $params['owned_qualification']) > -1) {
                    array_push($ownedQualifications, (int)$item['value']);
                    return $item;
                }
            });
            $this->query
                ->whereRelation('temporaryMemberCareer.temporaryMemberOwnedQualifications', 'other_qualification', 'LIKE', '%'. $params['owned_qualification'] . '%')
                ->orWhereHas('temporaryMemberCareer.temporaryMemberOwnedQualifications', function ($query) use ($ownedQualifications) {
                    $query->whereIn('owned_qualification', $ownedQualifications);
                });
        }

        if(!empty($params['name'])) {
            $this->query->where(function ($query) use ($params) {
                $query->where('name_kanji', 'LIKE', '%'. $params['name']. '%')
                    ->orWhere('name_furigana', 'LIKE', '%'. $params['name']. '%');
            });
        }
        if (!empty($params['office_name'])) {
            $this->query->whereRelation('temporaryMemberCareer', 'office_name', 'LIKE', '%'. $params['office_name']. '%');
        }

        if (!empty($params['interview_status'])) {
            $this->query->whereIn('interview_status', $params['interview_status']);
        }

        $this->params = $params;
        $this->addParams('email', true);
        $this->addParams('member_number', false);

        return $this->query();
    }

    public function show($code)
    {
        $model = TemporaryMember::query()
            ->with([
                'temporaryMemberQualifications',
                'interview',
                'temporaryMemberCareer' => function($query) {
                $query->with([
                    'temporaryMemberCareerHistories',
                    'temporaryMemberEducationHistories',
                    'temporaryMemberFieldTypes',
                    'temporaryMemberOwnedQualifications'
                ]);
            }])
            ->where('member_number',$code)->first();
        // 存在しなかったら404
        if (!$model) {
            throw new PosException('16', '001', 404);
        }
        if (!empty($model['temporaryMemberQualifications'])) {
            $qualifications = $model['temporaryMemberQualifications'];
            foreach ($qualifications as $qualification) {
                $qualification['qualification'] = PosConst::getConstDataText(PosConst::QUALIFICATIONS, $qualification['qualification']);
            }
        }
        if (!empty($model['temporaryMemberCareer']['prefecture'])) {
            $model['temporaryMemberCareer']['prefecture'] = PosConst::getConstDataName(PosConst::PREFECTURES, $model['temporaryMemberCareer']['prefecture']);
        }
        return $model;
    }

    public function disapproval($code)
    {
        $model = TemporaryMember::where('member_number',$code)->first();
        // 存在しなかったら404
        if (!$model) {
            throw new PosException('16', '003', 404);
        }
        $model->interview_status = TemporaryMember::INTERVIEW_STATUS_DISAPPROVAL;
        $model->save();
        $mailTemplate = new MainMailable(7);
        $mailTemplate->setViewData([
            'name' => $model->name,
        ]);
        $mailTemplate->sendMail($model->email);
        return $model;
    }

    public function approval($code)
    {
        $model = TemporaryMember::with([
            'member' => function($query) {
                $query->with([
                    'memberCareerHistories',
                    'memberEducationHistories',
                    'fieldTypes',
                    'memberOwnedQualifications'
                ]);
            },
            'temporaryMemberQualifications',
            'interview',
            'temporaryMemberCareer' => function($query) {
                $query->with([
                    'temporaryMemberCareerHistories',
                    'temporaryMemberEducationHistories',
                    'temporaryMemberFieldTypes',
                    'temporaryMemberOwnedQualifications'
                ]);
            }
        ])->where('member_number',$code)->first();
        // 存在しなかったら404
        if (!$model) {
            throw new PosException('16', '004', 404);
        }
        $memberCheck = Member::where(
            function ($query) use ($model) {
                if ($query->where([['member_number', $model->member_number]])->count()) {
                    throw new PosException('16', '005', 422);
                }
                if ($query->where([['email', $model->email]])->count()) {
                    throw new PosException('16', '006', 422);
                }
            }
        );
        $model->interview_status = TemporaryMember::INTERVIEW_STATUS_APPROVAL;
        $model->save();
        $datas = [
            'member_number' => $model->member_number,
            'name_kanji' => $model->name_kanji,
            'name_furigana' => $model->name_furigana,
            'email' => $model->email,
            'password' => $model->password,
        ];
        $tmMemberCareer = $model->temporaryMemberCareer;
        if ($tmMemberCareer) {
            $datas['birthdate'] = $tmMemberCareer['birthdate'];
            $datas['gender'] = $tmMemberCareer['gender'];
            $datas['office_name'] = $tmMemberCareer['office_name'];
            $datas['postal_code'] = $tmMemberCareer['postal_code'];
            $datas['prefecture'] = $tmMemberCareer['prefecture'];
            $datas['address1'] = $tmMemberCareer['address1'];
            $datas['address2'] = $tmMemberCareer['address2'];
            $datas['tel1'] = $tmMemberCareer['tel1'];
            $datas['tel2'] = $tmMemberCareer['tel2'];
            $datas['tel3'] = $tmMemberCareer['tel3'];
            $datas['certified_accountant_number'] = $tmMemberCareer['certified_accountant_number'];
            $datas['us_certified_accountant_number'] = $tmMemberCareer['us_certified_accountant_number'];
            $datas['tax_accountant_number'] = $tmMemberCareer['tax_accountant_number'];
            $datas['advisory_experience_years'] = $tmMemberCareer['advisory_experience_years'];
            $datas['other_specialized_field'] = $tmMemberCareer['other_specialized_field'];
            $datas['experience'] = $tmMemberCareer['experience'];
            $datas['reward_per_time'] = $tmMemberCareer['reward_per_time'];
            $datas['reward_per_month'] = $tmMemberCareer['reward_per_month'];
            $member = new Member($datas);
            $member->save();
            $ownedQualifications = $tmMemberCareer['temporaryMemberOwnedQualifications'];
            $memberOwnedQualifications = $member->memberOwnedQualifications();
            if ($ownedQualifications->count()) {
                    foreach ($ownedQualifications as $item) {
                        $memberOwnedQualifications->create([
                            "owned_qualification" => $item['owned_qualification'],
                            "other_qualification" => $item['other_qualification']
                        ]);
                    }
                }
            $careerHistories = $tmMemberCareer['temporaryMemberCareerHistories'];
            $memberCareerHistories = $member->memberCareerHistories();
            if ($careerHistories->count()) {
                foreach ($careerHistories as $item) {
                    $memberCareerHistories->create([
                        "find_work" => $item['find_work'],
                        "retirement" => $item['retirement'],
                        "office_name" => $item['office_name'],
                        "status" => $item['status'],
                        "free_entry" => $item['free_entry'],
                    ]);
                }
            }
            $educationHistories = $tmMemberCareer['temporaryMemberEducationHistories'];
            $memberEducationHistories = $member->memberEducationHistories();
            if ($educationHistories->count()) {
                foreach ($educationHistories as $item) {
                    $memberEducationHistories->create([
                        "admission" => $item['admission'],
                        "graduation" => $item['graduation'],
                        "school_name" => $item['school_name'],
                    ]);
                }
            }
            $fieldTypes = $tmMemberCareer['temporaryMemberFieldTypes']->toArray();
            if ($fieldTypes) {
                foreach ($fieldTypes as $item) {
                    $member->fieldTypes()->attach($item['field_id'], ['type' => $item['type']]);
                }
            }
        }
        if($model->interview) {
            $member->note = $model->interview->note;
        }
        $mailTemplate = new MainMailable(8);
        $mailTemplate->setViewData([
            'name' => $model->name,
            'email' => $model->email,
            'url' => config('app.member_site_url').'/login',
        ]);
        $mailTemplate->sendMail($model->email);

        $model = TemporaryMember::with([
            'member' => function($query) {
                $query->with([
                    'memberCareerHistories',
                    'memberEducationHistories',
                    'fieldTypes',
                    'memberOwnedQualifications'
                ]);
            },
            'temporaryMemberQualifications',
            'interview',
            'temporaryMemberCareer' => function($query) {
                $query->with([
                    'temporaryMemberCareerHistories',
                    'temporaryMemberEducationHistories',
                    'temporaryMemberFieldTypes',
                    'temporaryMemberOwnedQualifications'
                ]);
            }
        ])->where('member_number',$code)->first();
        return $model;
    }

    public function update($params, $code)
    {
        return DB::transaction(function () use ($params, $code) {
            $model = $this->getTemporaryMember($code);
            // 存在しなかったら404
            if (!$model) {
                throw new PosException('16', '002', 404);
            }

            if (isset($params['temporary_member_career']['temporary_member_owned_qualifications'])) {
                $model
                    ->temporaryMemberCareer
                    ->temporaryMemberOwnedQualifications()
                    ->delete();
                $requiredValues = [];
                foreach ($params['temporary_member_career']['temporary_member_owned_qualifications'] as $item) {

                    if ($item['owned_qualification'] === 1 &&
                        empty($params['temporary_member_career']['certified_accountant_number'])) {
                        $requiredValues['certified_accountant_number'] = true;
                        continue;
                    }
                    if ($item['owned_qualification'] === 2 &&
                        empty($params['temporary_member_career']['us_certified_accountant_number'])) {
                        $requiredValues['us_certified_accountant_number'] = true;
                        continue;
                    }
                    if ($item['owned_qualification'] === 3 &&
                        empty($params['temporary_member_career']['tax_accountant_number'])) {
                        $requiredValues['tax_accountant_number'] = true;
                        continue;
                    }

                    $model
                        ->temporaryMemberCareer
                        ->temporaryMemberOwnedQualifications()
                        ->create([
                            'owned_qualification' => $item['owned_qualification'],
                            'other_qualification' => isset($item['other_qualification']) ? $item['other_qualification'] : null
                        ]);
                }
                $this->validatorOwnedQualification($params->all(), $requiredValues);
            }

            unset($params['member_number'], $params['interview_status'], $params['password']);
            Helper::convertFullWidth($params, 'name_kanji');
            Helper::convertFullWidth($params, 'name_furigana');
            Helper::convertHalfWidth($params, 'email');
            if (!empty($params->temporary_member_career)) {
                $tmCareerParams = $params->temporary_member_career;
                Helper::convertHalfWidth($tmCareerParams, 'certified_accountant_number');
                Helper::convertHalfWidth($tmCareerParams, 'tax_accountant_number');
                Helper::convertHalfWidth($tmCareerParams, 'tel1');
                Helper::convertHalfWidth($tmCareerParams, 'tel2');
                Helper::convertHalfWidth($tmCareerParams, 'tel3');
                Helper::convertFullWidth($tmCareerParams, 'office_name');
                Helper::convertFullWidth($tmCareerParams, 'address1');
                Helper::convertFullWidth($tmCareerParams, 'address2');
                if ($tmCareerParams['birthdate']) $tmCareerParams['birthdate'] = date("Y-m-d", strtotime($tmCareerParams['birthdate']));
                $career = $model->temporaryMemberCareer;
                if ($career) {
                    $tmpCareerParam = $tmCareerParams;
                    unset($tmpCareerParam['temporary_member_career_histories'],
                        $tmpCareerParam['temporary_member_education_histories'],
                        $tmpCareerParam['temporary_member_field_types']
                    );
                    $career->update($tmpCareerParam);
                    if ($career->temporaryMemberCareerHistories) {
                        $career->temporaryMemberCareerHistories()->delete();
                        foreach ($tmCareerParams['temporary_member_career_histories'] as $item) {
                            if(empty($item['find_work'])
                                || empty($item['retirement'])
                                || empty($item['office_name'])
                                || empty($item['free_entry'])
                                || empty($item['status'])) continue;
                            $career->temporaryMemberCareerHistories()->create($item);
                        }
                    }
                    if (!empty($tmCareerParams['temporary_member_education_histories'])) {
                        $career->temporaryMemberEducationHistories()->delete();
                        foreach ($tmCareerParams['temporary_member_education_histories'] as $item) {
                            if(empty($item['admission'])
                                || empty($item['graduation'])
                                || empty($item['school_name'])) continue;
                            $career->temporaryMemberEducationHistories()->create($item);

                        }
                    }
                    if (!empty($params['temporary_member_career']['temporary_member_field_types'])) {
                        $model
                            ->temporaryMemberCareer
                            ->temporaryMemberFieldTypes()
                            ->delete();
                        foreach ($params['temporary_member_career']['temporary_member_field_types'] as $data) {
                            if(empty($data['field_id'])
                                || empty($data['type'])) continue;
                            $model
                                ->temporaryMemberCareer
                                ->temporaryMemberFieldTypes()
                                ->create([
                                    'field_id'=> $data['field_id'],
                                    'type' => $data['type']
                                ]);
                        }
                    }
                }
            }
            if ($model->temporaryMemberQualifications) {
                $model->temporaryMemberQualifications()->delete();
                foreach ($params->temporary_member_qualifications as $item) {
                    $model->temporaryMemberQualifications()->create(['qualification' => $item]);
                }
            }

            $tmpParams = $params->all();
            unset($tmpParams['temporary_member_career']);
            $model->fill($tmpParams)->save();
            $model = $this->getTemporaryMember($code);
            if (!empty($model['temporaryMemberQualifications'])) {
                $qualifications = $model['temporaryMemberQualifications'];
                foreach ($qualifications as $qualification) {
                    $qualification['qualification'] = PosConst::getConstDataText(PosConst::QUALIFICATIONS, $qualification['qualification']);
                }
            }
            return $model;
        });
    }

    private function getTemporaryMember($code)
    {
        return TemporaryMember::query()
            ->with(['temporaryMemberCareer' => function($query) {
                $query->with([
                    'temporaryMemberCareerHistories',
                    'temporaryMemberEducationHistories',
                    'temporaryMemberFieldTypes',
                    'temporaryMemberOwnedQualifications'
                ]);
            }])
            ->with(['temporaryMemberQualifications'])
            ->where('member_number',$code)->first();
    }

    private function validatorOwnedQualification($params, $requiredValues) {
        $validator = Validator::make($params, [
            'temporary_member_career.certified_accountant_number' => !empty($requiredValues['certified_accountant_number']) ? 'required' : 'nullable',
            'temporary_member_career.us_certified_accountant_number' => !empty($requiredValues['us_certified_accountant_number']) ? 'required' : 'nullable',
            'temporary_member_career.tax_accountant_number' => !empty($requiredValues['tax_accountant_number']) ? 'required' : 'nullable',
        ], [], [
            'temporary_member_career.certified_accountant_number' => '公認会計士登録番号',
            'temporary_member_career.us_certified_accountant_number' => '米国公認会計士登録番号',
            'temporary_member_career.tax_accountant_number' => '税理士登録番号',
        ]);

        if ($validator->fails()) {
            $response['errors']  = $validator->errors()->toArray();
            throw new HttpResponseException(
                response()->json($response, 422)
            );
        }
    }
}
