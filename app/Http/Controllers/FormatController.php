<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormatRequest;
use App\Services\FormatService;
use Illuminate\Http\Request;

class FormatController extends Controller
{
    private $service;

    public function __construct(FormatService $service)
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

    public function update(FormatRequest $request, $id)
    {
        return response()->json($this->service->update($request->all(), $id), 200);
    }

    public function store(FormatRequest $request)
    {
        return response()->json($this->service->store($request->all()), 201);
    }

    public function destroy($id)
    {
        return response()->json($this->service->destroy($id), 204);
    }

    public function download($id)
    {
        return $this->service->download($id);
    }

}
