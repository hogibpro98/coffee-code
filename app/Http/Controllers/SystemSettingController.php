<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SystemSettingService;
use App\Http\Requests\FeeRequest;
use App\Http\Requests\MemberPolicyRequest;
use App\Http\Requests\PrivacyPolicyRequest;

class SystemSettingController extends Controller
{
    private $service;

    public function __construct(SystemSettingService $service)
    {
        $this->service = $service;
    }

    public function feeDetail()
    {
        return response()->json($this->service->feeDetail(), 200);
    }

    public function feeUpdate(FeeRequest $request)
    {
        return response()->json($this->service->feeUpdate($request->all()), 200);
    }

    public function memberPolicyDetail()
    {
        return response()->json($this->service->memberPolicyDetail(), 200);
    }

    public function memberPolicyUpdate(MemberPolicyRequest $request)
    {
        return response()->json($this->service->memberPolicyUpdate($request->all()), 200);
    }

    public function privacyPolicyDetail()
    {
        return response()->json($this->service->privacyPolicyDetail(), 200);
    }

    public function privacyPolicyUpdate(PrivacyPolicyRequest $request)
    {
        return response()->json($this->service->privacyPolicyUpdate($request->all()), 200);
    }

}
