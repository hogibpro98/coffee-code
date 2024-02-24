<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Commons\PosConst;
use App\Services\Common\CommonService;

class CommonController extends Controller
{

    private $service;
    public function __construct(CommonService $service)
    {
        $this->service = $service;
    }
    public function prefectures()
    {
        return response()->json(PosConst::PREFECTURES, 200);
    }

    public function qualifications()
    {
        return response()->json(PosConst::QUALIFICATIONS, 200);
    }

    public function groupingList()
    {
        return response()->json(PosConst::GROUPING_LISTS, 200);
    }

    public function ownedQualifications()
    {
        return response()->json(PosConst::OWNED_QUALIFICATIONS, 200);
    }

    public function matterStatus()
    {
        return response()->json(PosConst::MATTER_STATUSES, 200);
    }
    public function contractStatus()
    {
        return response()->json(PosConst::CONTRACT_STATUSES, 200);
    }

    public function seminarTimes()
    {
        return response()->json(PosConst::LIST_SEMINAR_TIME, 200);
    }

    public function advisories()
    {
        return response()->json(PosConst::ADVISORIES, 200);
    }

    public function agent()
    {
        return response()->json($this->service->agent(), 200);
    }

    public function dashboard()
    {
        return response()->json($this->service->dashboard(), 200);
    }

}
