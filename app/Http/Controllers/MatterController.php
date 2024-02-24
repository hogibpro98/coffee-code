<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MatterService;
use App\Http\Requests\MatterRequest;

class MatterController extends Controller
{
    private $service;

    public function __construct(MatterService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        return response()->json($this->service->index($request->all()), 200);
    }

    public function show($id)
    {
        return response()->json($this->service->show($id), 200);
    }

    public function store(MatterRequest $request)
    {
        return response()->json($this->service->store($request->all()), 201);
    }

    public function update(MatterRequest $request, $id)
    {
        return response()->json($this->service->update($request->all(), $id), 200);
    }

    public function destroy($id)
    {
        return response()->json($this->service->destroy($id), 204);
    }

    public function showByUser($userId)
    {
        return response()->json($this->service->showByUser($userId), 200);
    }

    public function showByClient($clientId)
    {
        return response()->json($this->service->showByClient($clientId), 200);
    }

    public function assignMember($id,$memberId)
    {
        return response()->json($this->service->assignMember($id,$memberId), 200);
    }

    public function assignUser($id,$userId)
    {
        return response()->json($this->service->assignUser($id,$userId), 201);
    }

    public function automaticCancel(Request $request,$id)
    {
        return response()->json($this->service->automaticCancel($request->all(),$id), 200);
    }

    public function manualCancel(Request $request,$id)
    {
        return response()->json($this->service->manualCancel($request->all(),$id), 200);
    }

    public function entryStop($id)
    {
        return response()->json($this->service->entryStop($id), 200);
    }

    public function unassignMember($id,$memberId)
    {
        return response()->json($this->service->unassignMember($id, $memberId), 204);
    }

    public function restart($id)
    {
        return response()->json($this->service->restart($id), 200);
    }

    public function public($id)
    {
        return response()->json($this->service->public($id), 200);
    }

    public function private($id)
    {
        return response()->json($this->service->private($id), 200);
    }
}