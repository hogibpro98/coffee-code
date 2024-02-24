<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndustryTypeRequest;
use App\Services\IndustryTypeService;
use Illuminate\Http\Request;

class IndustryTypeController extends Controller
{
    private $service;

    public function __construct(IndustryTypeService $service)
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

    public function store(IndustryTypeRequest $request)
    {
        return response()->json($this->service->store($request->all()), 201);
    }

    public function show($id)
    {
        return response()->json($this->service->show($id), 200);
    }

    public function update(IndustryTypeRequest $request, $id)
    {
        return response()->json($this->service->update($request->all(), $id), 200);
    }

    public function destroy($id)
    {
        return response()->json($this->service->destroy($id), 204);
    }
}
