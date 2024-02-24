<?php

namespace App\Http\Controllers;

use App\Http\Requests\MemberRequest;
use App\Services\MemberService;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    private $service;

    public function __construct(MemberService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        return response()->json($this->service->index($request->all()), 200);
    }

    public function getAll(Request $request)
    {
        return response()->json($this->service->getAll($request->all()), 200);
    }

    public function show($id)
    {
        return response()->json($this->service->show($id), 200);
    }

    public function getOperatingStatus(Request $request, $id)
    {
        return response()->json($this->service->getOperatingStatus($request->all(), $id), 200);
    }

    public function leave($id)
    {
        return response()->json($this->service->leave($id), 200);
    }

    public function restore($id)
    {
        return response()->json($this->service->restore($id), 200);
    }

    public function update(MemberRequest $request, $id)
    {
        return response()->json($this->service->update($request->all(), $id), 200);
    }

    public function getResume($id)
    {
        return $this->service->getResume($id);
    }

}
