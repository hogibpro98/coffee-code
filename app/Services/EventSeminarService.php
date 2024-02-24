<?php

namespace App\Services;

use App\Commons\PosConst;
use App\Exports\EventSeminarApplicationExport;
use App\Models\EventSeminarDate;
use App\Traits\ListTrait;
use App\Exceptions\PosException;
use App\Models\EventSeminar;
use App\Mail\MainMailable;
use App\Models\Information;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\EventSeminarApplication;

class EventSeminarService
{
    use ListTrait;
    const LIST_SEMINAR_TIME_APPLICATION_VALUE = ['1','2','3','4'];
    const LIST_SEMINAR_TIME_START_VALUE = ['5','6'];

    public function index($params)
    {
        $this->query = EventSeminar::query()->with([
            'eventSeminarDates.eventSeminarApplications','eventSeminarDates' => function ($query) use ($params) {
                if(isset($params['start_time']) && in_array($params['start_time'],self::LIST_SEMINAR_TIME_START_VALUE))
                {
                    $data = PosConst::getConstDataItem(PosConst::LIST_SEMINAR_TIME, $params['start_time']);
                    if($data) {
                        $addTimeFunc = $data["func"];
                        $comparedDate = Carbon::now()->$addTimeFunc();
                        $query->whereBetween($data['field'], [Carbon::now(), $comparedDate]);
                    }
                }
            }
        ]);
        $this->params = $params;
        $this->addParams('title', true);

        if( !empty($params['type']) && is_array($params['type'])){
            $this->query->whereIn('type', $params['type']);
        }
        if( !empty($params['status']) && is_array($params['status'])){
            $this->query->whereIn('status', $params['status']);
        }

        if(isset($params['registration_time']) && in_array($params['registration_time'],self::LIST_SEMINAR_TIME_APPLICATION_VALUE))
        {
            $data = PosConst::getConstDataItem(PosConst::LIST_SEMINAR_TIME, $params['registration_time']);
            if($data) {
                $addTimeFunc = $data["func"];
                $comparedDate = Carbon::now()->$addTimeFunc();
                $this->query->whereBetween($data['field'], [Carbon::now(), $comparedDate]);
            }
        }
        return $this->query();
    }

    public function show($id)
    {
        $model = EventSeminar::with(['eventSeminarDates' => function($query) {
            $query->with(['eventSeminarApplications' => function($query) {
                $query->with('member');
            }]);
        }])->find($id);
        if(!$model) {
            throw new PosException('19', '001', 404);
        }
        return $model;
    }

    public function store($params)
    {
        if($params['fee_type'] == 1) {
            if($this->isBlank($params, 'fee')) {
                throw new PosException('19', '021', 404);
            }
        }
        $model = new EventSeminar();
        $model->fill($params)->save();

        return $model;
    }

    public function update($params, $id)
    {
        $model = EventSeminar::query()->find($id);
        if (!$model) {
            throw new PosException('19', '002', 404);
        }
        $model->fill([
            'title' => $params['title'],
            'content' => $params['content'],
            'application_start_date' => $params['application_start_date'],
            'application_end_date' => $params['application_end_date'],
        ])->save();

        return $model;
    }

    public function cancel($params, $id)
    {
        $model = EventSeminar::query()
                ->with([
                     'eventSeminarDates' => function($query) use ($params) {
                        $query->with([
                            'eventSeminarApplications' => function($query) use ($params) {
                                $query->whereRelation('member', 'member_id',  $params['member_id']);
                            }
                        ]);
                    }
                ])
                ->find($id);

        if (empty($model->eventSeminarDates[0]->eventSeminarApplications)) {
            throw new PosException('19', '004', 404);
        }

        $eventSeminarDates = $model->eventSeminarDates;

        foreach ($eventSeminarDates as $eventSeminarDate) {
            $eventSeminarApplications = $eventSeminarDate->eventSeminarApplications;
            foreach ($eventSeminarApplications as $eventSeminarApplication) {
                $eventSeminarApplication->is_canceled = 1;
                $eventSeminarApplication->save();
            }
        }

        return $model;
    }

    public function public($id)
    {
        $model = EventSeminar::query()
                ->with([
                     'eventSeminarDates' => function($query) {
                         $query->with([
                             'eventSeminarApplications' => function($query) {
                                 $query->with('member');
                             }
                         ]);
                     }
                ])
                ->find($id);

        if (!$model) {
            throw new PosException('19', '001', 404);
        }

        if ($model->eventSeminarDates()->count() === 0) {
            throw new PosException('19', '007', 422);
        }

        $model->is_private = 0;
        $model->published_date = ($model->published_date) ? ($model->published_date) : date('Y-m-d H:i:s');

        $model->save();

        $eventSeminarDates = $model->eventSeminarDates;

        foreach ($eventSeminarDates as $key => $eventSeminarDate) {
            $mailTemplate = new MainMailable(20);
            $mailTemplate->setViewData([
                "name" => $eventSeminarDate->eventSeminarApplications[$key]->member->name_kanji,
                "title" => $model['title'],
                "content" => $model['content'],
                "fee" => $model['fee"'],
                "application_start_date" => $eventSeminarDate['start_time'],
                "application_end_date" => $eventSeminarDate['end_time'],
                "type" => $model['type'],
                "url" => $eventSeminarDate['press_release_url']
            ]);
            $mailTemplate->sendMail($eventSeminarDate->eventSeminarApplications[$key]->member->email);
        }

        $info = new Information();
        $info->fill([
            "title" => mb_convert_encoding('新着案件情報', "UTF-8"),
            "content" => "カラム",
            "display_start_date" => null,
            "display_end_date" => null,
            "is_private" => 0,
            "status" => 2,
            "type" => 3,
            "detail_path" => '/event-seminar/'. $model->id
        ])->save();

        return $model;
    }

    public function private($id)
    {
        $eventSeminarDates = EventSeminarDate::
            with(['eventSeminarApplications'])
            ->where('event_seminar_id',$id)->get();
        $eventSeminar = EventSeminar::find($id);

        if(count($eventSeminarDates) < 1)
        {
            if(!$eventSeminar) {
                throw new PosException('19', '001', 404);
            }
            $eventSeminar->is_private = 1;
        }
        else {
            foreach ($eventSeminarDates as $item) {
                if(empty($item->eventSeminarApplications)) {
                    if(!$eventSeminar) {
                        throw new PosException('19', '001', 404);
                    }
                    $eventSeminar->is_private = 1;
                }
                else {
                    if($eventSeminar->status != 5) {
                        throw new PosException('19', '009', 422);
                    }
                    if(!$eventSeminar) {
                        throw new PosException('19', '001', 404);
                    }
                    $eventSeminar->is_private = 1;
                }
            }
        }

        $eventSeminar->save();
        return $eventSeminar;

    }

    public function entryStop($id)
    {
        $model = EventSeminar::query()->find($id);

        if (!$model) {
            throw new PosException('19', '001', 404);
        }

        if ($model->status === 5 || $model->status === 6) {
            throw new PosException('19', '011', 422);
        }

        if ($model->status === 4) {
            throw new PosException('19', '012', 422);
        }

        $model->status = 4;

        $model->save();

        return $model;
    }

    public function restart($id)
    {
        $model = EventSeminar::query()->find($id);

        if (!$model) {
            throw new PosException('19', '001', 404);
        }

        if ($model->status !== 4) {
            throw new PosException('19', '020', 422);
        }

        $dayNow = date('Y-m-d');

        if ($dayNow < $model->application_start_date) $model->status = 1;

        if ($dayNow > $model->application_start_date && $dayNow <= $model->application_end_date) $model->status = 2;

        if ($dayNow >= $model->application_end_date) $model->status = 3;

        $model->save();

        return $model;
    }

    public function piece($params, $id)
    {
        $model = EventSeminar::query()
                 ->with('eventSeminarDates')
                 ->find($id);

        if (!$model) {
            throw new PosException('19', '001', 404);
        }

        if ($model->published_date) {
            throw new PosException('19', '016', 422);
        }

        $model->eventSeminarDates()->updateOrCreate([
            'event_seminar_id' => $model->id,
            'times' => $params['times'],
            'start_time' => $params['start_time'],
            'end_time' => $params['end_time'],
            'postal_code' => $params['postal_code'],
            'prefecture' => $params['prefecture'],
            'address1' => $params['address1'],
            'address2' => $params['address2'],
            'capacity' => $params['capacity'],
            'zoom_url' => $params['zoom_url'],
            'zoom_meeting_id' => $params['zoom_meeting_id'],
            'zoom_password' => $params['zoom_password'],
            'archive_url' => $params['archive_url'],
            'zoom_org_data' => $params['zoom_org_data'],
            'remarks_for_manager' => $params['remarks_for_manager'],
            'remarks_for_mail' => $params['remarks_for_mail']
        ]);

        return $model;
    }

    public function updatePiece($params, $id, $pieceId)
    {
        $model = EventSeminar::query()->find($id);

        if (!$model) {
            throw new PosException('19', '001', 404);
        }

        $model->whereRelation('eventSeminarDates', 'id', $pieceId);

        if ($model->eventSeminarDates->count() === 0) {
            throw new PosException('19', '003', 404);
        }

        $model->eventSeminarDates->find($params['id'])->update([
            'postal_code' => $params['postal_code'],
            'prefecture' => $params['prefecture'],
            'address1' => $params['address1'],
            'address2' => $params['address2'],
            'archive_url' => $params['archive_url']
        ]);

        return $model;
    }

    public function destroy($id)
    {
        $model = EventSeminar::with(['eventSeminarDates' => function($query) {
            $query->with(['eventSeminarApplications']);
        }])->find($id);
        if(!$model) {
            throw new PosException('19', '001', 404);
        }
        if($model->eventSeminarDates)
        {
            foreach ($model->eventSeminarDates as $item) {
                if($item->eventSeminarApplications()->count() > 0) {
                    throw new PosException('19', '008', 422);
                }
            }

        }
        $model->delete();
        return null;
    }

    public function exportCSV($id, $pieceId)
    {
        $model = EventSeminarDate::with([
            'eventSeminar',
        ])
            ->where([
                ['id', '=', $pieceId],
                ['event_seminar_id', '=', $id]
            ])
            ->first();

        if(!$model) {
            throw new PosException('19', '005', 404);
        }

        $model->with([
            'eventSeminarApplications' => function($query) {
                $query->where('is_canceled', '<>', 1);
                $query->with('member');
            }
        ])->first();

        if($model->eventSeminarApplications()->count() === 0)
        {
            throw new PosException('19', '008', 404);

        }

        $eventSeminar = $model->eventSeminar;
        $fileName = $eventSeminar->title. '_'. $model->times. '_'.
        $eventSeminar->application_start_date. '_'. $eventSeminar->application_end_date. '.csv';
        $eventSeminarApplications= $model->eventSeminarApplications;

        return Excel::download(new EventSeminarApplicationExport($eventSeminarApplications), $fileName);
    }

    public function stopEvent($id)
    {
        $model = EventSeminar::with(['eventSeminarDates' => function($query) {
            $query->with(['eventSeminarApplications' => function($query) {
                $query->with('member');
            }]);
        }])->find($id);
        if (!$model) {
            throw new PosException('19', '001', 404);
        }
        if($model->status == 6) {
            throw new PosException('19', '013', 422);
        }
        if($model->status == 5) {
            throw new PosException('19', '014', 422);
        }
        $model->status = 5;
        if($model->eventSeminarDates)
        {
            foreach ($model->eventSeminarDates as $eventSeminarDate) {
                if($eventSeminarDate->eventSeminarApplications) {
                    foreach ($eventSeminarDate->eventSeminarApplications as $eventSeminarApplication) {
                        $eventSeminarApplication->is_canceled = 1;
                        $eventSeminarApplication->save();
                    }
                }
            }
        }
        $model->save();
        $model = EventSeminar::find($id);
        return $model;
    }

    public function deleteComma($id, $pieceId)
    {
        $model = EventSeminar::with(['eventSeminarDates' => function($query) {
            $query->with(['eventSeminarApplications' => function($query) {
                $query->with('member');
            }]);
        }])->find($id);
        if (!$model) {
            throw new PosException('19', '001', 404);
        }
        if(!is_null($model->published_date)) {
            throw new PosException('19', '017', 422);
        }
        $model = EventSeminarDate::where([
                ['id', '=', $pieceId],
                ['event_seminar_id', '=', $id]
            ])
            ->first();

        if(!$model) {
            throw new PosException('19', '005', 404);
        }
        $model->delete();
        return null;
    }

    public function showApplicationComma($id, $pieceId)
    {
        $model = EventSeminarDate::with([
            'eventSeminar',
            'eventSeminarApplications' => function($query) {
                $query->with('member');
            }
        ])
        ->where([
            ['id', '=', $pieceId],
            ['event_seminar_id', '=', $id]
        ])
        ->first();

        if(!$model) {
            throw new PosException('19', '005', 404);
        }

        $model->with([
            'eventSeminarApplications' => function($query) {
                $query->where('is_canceled', '<>', 1);
                $query->with('member');
            }
        ])->first();

        if($model->eventSeminarApplications()->count() === 0)
        {
            throw new PosException('19', '008', 404);

        }
        return $model->eventSeminarApplications;
    }

    public function registerTimes($params , $id)
    {
        $model = EventSeminar::find($id);
        if (!$model) {
            throw new PosException('19', '001', 404);
        }
        if(!is_null($model->published_date)) {
            throw new PosException('19', '018', 422);
        }
        $model->times_infomation = json_encode($params->all());
        $model->save();
        return $model;
    }

    public function updateTimes($params, $id, $timeNum)
    {
        $model = EventSeminar::find($id);
        if (!$model) {
            throw new PosException('19', '001', 404);
        }
        $timeInfo = json_decode($model->times_infomation, true);
        if($timeInfo['times'] != $timeNum) {
            throw new PosException('19', '010', 404);
        }
        $timeInfo['times_title'] = $params['times_title'];
        $timeInfo['times_content'] = $params['times_content'];
        $model->times_infomation = json_encode($timeInfo);
        $model->save();
        return $model;
    }

    public function deleteTimes($id, $timeNum)
    {
        $model = EventSeminar::with(['eventSeminarDates' => function($query) {
            $query->with(['eventSeminarApplications' => function($query) {
                $query->with('member');
            }]);
        }])->find($id);
        if (!$model) {
            throw new PosException('19', '001', 404);
        }
        if(!is_null($model->published_date)) {
            throw new PosException('19', '019', 422);
        }
        $model->eventSeminarDates()->where('times', $timeNum)->delete();
        $model->times_infomation = null;
        $model->save();
        return null;
    }

}
