<?php


namespace App\Services;


use App\Commons\Helper;
use App\Exceptions\PosException;
use App\Models\Member;
use App\Traits\ListTrait;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Commons\PosConst;
use App\Models\FieldType;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MemberService
{
    use ListTrait;

    public function index($params)
    {
        $this->query = Member::query()->with([
            'workingStatuses' => function ($query) {
                $query->orderBy('start_date' ,'asc')->where('start_date' ,'>' ,date("Y-m-d H:i:s"))->take(5);
            },
            'memberOwnedQualifications',
            'workingStatuses',
            'fieldTypes'
        ]);

        $this->params = $params;

        if(isset($params['field_type'])) {
            $this->query->whereRelation('fieldTypes', 'name', 'LIKE', '%'. $params['field_type'] . '%');
        }

        if(isset($params['owned_qualification'])) {
            $ownedQualifications = [];
            array_filter(PosConst::OWNED_QUALIFICATIONS, function ($item) use ($params, &$ownedQualifications) {
                if(strpos($item['text'], $params['owned_qualification']) > -1) {
                    array_push($ownedQualifications, (int)$item['value']);
                    return $item;
                }
            });
            //return $ownedQualifications;
            $this->query->whereRelation('memberOwnedQualifications', 'other_qualification', 'LIKE', '%'. $params['owned_qualification'] . '%')
                ->orWhereHas('memberOwnedQualifications', function ($query) use ($ownedQualifications) {
                    $query->whereIn('owned_qualification', $ownedQualifications);
                });
        }

        if(isset($params['name']) && strlen($params['name'])) {
            $this->query->where(function ($query) use($params) {
               $query->where('name_kanji', 'LIKE', '%'. $params['name']. '%')
                   ->orWhere('name_furigana', 'LIKE', '%'. $params['name']. '%');
            });
        }

        $this->addParams('email', true);
        $this->addParams('member_number', true);
        $this->addParams('office_name', true);
        $this->addBooleanParam('is_partner');

        if(isset($params['is_leave']) && $params['is_leave'] == "true") {
            $this->query->withTrashed();
        }

        return $this->query();
    }

    public function getAll($param)
    {
        return Member::select(['id', 'name_kanji', 'name_furigana'])->get();
    }

    public function show($id)
    {
        $model = Member::query()
            ->with([
                'memberCareerHistories',
                'memberEducationHistories',
                'memberOwnedQualifications',
                'fieldTypes',
                'businessCards',
                'inquiry',
                'matterMemberAssigns' => function ($query) {
                    $query->with('matter.client');
                },
            ])
            ->withTrashed()
            ->find($id);
        if(!$model)
        {
            throw new PosException('17', '001', 404);
        }
        return $model;
    }

    public function getOperatingStatus($params, $id)
    {
        $model = Member::query()
            ->with(['workingStatuses' => function($query) use($params) {
                isset($params['date']) ?
                $query->orderBy('start_date' ,'asc')->where('start_date' , '>', $params['date'])->limit(10) :
                $query->orderBy('start_date' ,'asc')->whereDate('start_date', date('Y-m-d'))->limit(10);
            }])->find($id);

        if(!$model)
        {
            throw new PosException('17', '001', 404);
        }

        if(isset($model->workingStatuses) && $model->workingStatuses->isEmpty())
        {
            throw new PosException('17', '002', 404);
        }

        return $model;
    }

    public function getResume($id)
    {

        $model = Member::query()
            ->with([
                'memberCareerHistories',
                'fieldTypes',
            ])
            ->withTrashed()->find($id);
        if(!$model)
        {
            throw new PosException('17', '001', 404);
        }
        $today = date("Y-m-d");
        $diff = date_diff(date_create($model->birthdate), date_create($today));
        $age = floor($diff->format('%y') / 10) * 10;
        $careerHistories = [];
        if(!empty($model->memberCareerHistories)) {
            $careerHistories = $model->memberCareerHistories->map(function ($item) {
                $item->find_work = date('Y', strtotime($item->find_work)). '年'. date('n', strtotime($item->find_work)). '月 - ';
                $item->retirement = $item->retirement ?
                    date('Y', strtotime($item->retirement)). '年'. date('n', strtotime($item->retirement)). '月' : '現在';
                return $item;
            });
        }
        $fieldTypeNames = [];
        if(!empty($model->fieldTypes)) {
            $fieldTypeNames = $model->fieldTypes->map(function ($item) {
               return $item->name;
            });
        }
        $advisoryExperienceYears = '';
        $advisoryExperienceYears = PosConst::getConstDataText(PosConst::ADVISORIES, $model->advisory_experience_years);
        $ownedQualifications = json_decode($model->owned_qualifications, true);

        $members = [
            'name_kanji' =>$model->name_kanji,
            'owned_qualifications' => $ownedQualifications ? $ownedQualifications : [],
            'birthdate' => $age. '年代',
            'advisory_experience_years' => $advisoryExperienceYears,
            'name' => $model->name,
            'experience' =>$model->experience,
            'career_histories' => $careerHistories,
            'field_type_names' => $fieldTypeNames,
        ];
        $customPaper = array(0,0,820,540);
        $pdf = PDF::loadView('members.resume', $members)->setPaper($customPaper,'portrait');
        return $pdf->download($model->name_kanji. '_resume.pdf');

    }

    public function leave($id)
    {
        $model = Member::withTrashed()->find($id);
        if(!$model) {
            throw new PosException('17', '004', 404);
        }
        if($model->deleted_at) {
            throw new PosException('17', '005', 400);
        }
        $model->delete();
    }

    public function restore($id)
    {
        $model = Member::query()->withTrashed()->find($id);
        if(!$model) {
            throw new PosException('17', '006', 404);
        }
        if(!$model->deleted_at) {
            throw new PosException('17', '007', 400);
        }
        $model->restore();
    }

    public function update($params, $id)
    {
        return DB::transaction(function () use ($params, $id) {
            $model = Member::with(['memberEducationHistories', 'fieldTypes', 'memberCareerHistories', 'memberOwnedQualifications'])->find($id);
            if (!$model) {
                throw new PosException('17', '008', 404);
            }

            if (!empty($params['member_owned_qualifications'])) {
                $requiredValues = [];
                $ownedQualifications = PosConst::OWNED_QUALIFICATIONS;
                foreach ($params['member_owned_qualifications'] as $data) {
                    if ($data['owned_qualification'] == $ownedQualifications[0]['value']
                        && empty($params['certified_accountant_number'])) {
                        $requiredValues['certified_accountant_number'] = true;
                        continue;
                    }
                    if ($data['owned_qualification'] == $ownedQualifications[1]['value']
                        && empty($params['us_certified_accountant_number'])) {
                        $requiredValues['us_certified_accountant_number'] = true;
                        continue;
                    }
                    if ($data['owned_qualification'] == $ownedQualifications[2]['value']
                        && empty($params['tax_accountant_number'])) {
                        $requiredValues['tax_accountant_number'] = true;
                        continue;
                    }
                }
                $this->validatorOwnedQualification($params, $requiredValues);
            }

            if (!empty($params['field_types'])) {
                $model->fieldTypes()->detach();
                foreach ($params['field_types'] as $data) {
                    if (empty($data['field_id'])
                        || empty($data['type'])) continue;
                    $model->fieldTypes()->attach(
                        $data['field_id'],
                        ['type' => $data['type']]
                    );
                }
                $params['field_updated_at'] = date('y-m-d H:i:s');
            }

            if (isset($params['member_owned_qualifications'])) {
                $model->memberOwnedQualifications()->delete();
                foreach ($params['member_owned_qualifications'] as $data) {
                    $model->memberOwnedQualifications()->create([
                        'owned_qualification' => $data['owned_qualification'],
                        'other_qualification' => isset($data['other_qualification']) ? $data['other_qualification'] : null,
                    ]);
                }
            }

            if (!empty($params['member_career_histories'])) {
                $model->memberCareerHistories()->delete();
                foreach ($params['member_career_histories'] as $data) {
                    if (empty($data['find_work'])
                        || empty($data['retirement'])
                        || empty($data['office_name'])
                        || empty($data['free_entry'])
                        || empty($data['status'])) continue;

                    $model->memberCareerHistories()->create($data);
                }
            }

            if (!empty($params['member_education_histories'])) {
                $model->memberEducationHistories()->delete();
                foreach ($params['member_education_histories'] as $data) {
                    if (empty($data['admission'])
                        || empty($data['graduation'])
                        || empty($data['school_name'])) continue;

                    $model->memberEducationHistories()->create($data);

                }
            }

            Helper::convertFullWidth($params, 'name_kanji');
            Helper::convertFullWidth($params, 'name_furigana');
            Helper::convertHalfWidth($params, 'email');
            Helper::convertHalfWidth($params, 'certified_accountant_number');
            Helper::convertHalfWidth($params, 'tax_accountant_number');
            Helper::convertHalfWidth($params, 'tel1');
            Helper::convertHalfWidth($params, 'tel2');
            Helper::convertHalfWidth($params, 'tel3');
            Helper::convertFullWidth($params, 'office_name');
            Helper::convertFullWidth($params, 'address1');
            Helper::convertFullWidth($params, 'address2');

            if (isset($params['birthdate']))
                $params['birthdate'] = date("Y-m-d", strtotime($params['birthdate']));

            if (isset($params['advisory_experience_years']) && $params['advisory_experience_years'] != $model->advisory_experience_years) {
                $params['advisory_updated_at'] = date('y-m-d H:i:s');
            }

            if (isset($params['experience']) && $params['experience'] != $model->experience) {
                $params['experience_updated_at'] = date('y-m-d H:i:s');
            }

            $model->fill($params)->save();
            return $model->refresh();
        });
    }

    private function validatorOwnedQualification($params, $requiredValues) {
        $validator = Validator::make($params, [
            'certified_accountant_number' => !empty($requiredValues['certified_accountant_number']) ? 'required' : 'nullable',
            'us_certified_accountant_number' => !empty($requiredValues['us_certified_accountant_number']) ? 'required' : 'nullable',
            'tax_accountant_number' => !empty($requiredValues['tax_accountant_number']) ? 'required' : 'nullable',
        ], [], [
            'certified_accountant_number' => '公認会計士登録番号',
            'us_certified_accountant_number' => '米国公認会計士登録番号',
            'tax_accountant_number' => '税理士登録番号',
        ]);

        if ($validator->fails()) {
            $response['errors']  = $validator->errors()->toArray();
            throw new HttpResponseException(
                response()->json($response, 422)
            );
        }
    }
}
