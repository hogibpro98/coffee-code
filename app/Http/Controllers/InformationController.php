<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\InformationService;
use App\Http\Requests\InformationRequest;

class InformationController extends Controller
{
    private $service;

    public function __construct(InformationService $service)
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

    public function update(InformationRequest $request, $id)
    {
        return response()->json($this->service->update($request->all(), $id), 200);
    }

    public function store(InformationRequest $request)
    {
        return response()->json($this->service->store($request->all()), 201);
    }

    public function destroy($id)
    {
        return response()->json($this->service->destroy($id), 204);
    }
}
