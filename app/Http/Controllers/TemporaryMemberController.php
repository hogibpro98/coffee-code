<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TemporaryMemberService;
use App\Http\Requests\TemporaryMemberRequest;

class TemporaryMemberController extends Controller
{
    private $service;

    public function __construct(TemporaryMemberService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        return response()->json($this->service->index($request->all()), 200);
    }

    public function show($code)
    {
        return response()->json($this->service->show($code), 200);
    }

    public function disapproval($code)
    {
        return response()->json($this->service->disapproval($code), 200);
    }

    public function approval($code)
    {
        return response()->json($this->service->approval($code), 200);
    }

    public function update(TemporaryMemberRequest $request, $id)
    {
        return response()->json($this->service->update($request, $id), 200);
    }

}
