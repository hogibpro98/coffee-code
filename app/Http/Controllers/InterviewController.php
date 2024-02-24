<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\InterviewService;
use App\Http\Requests\InterviewRequest;

class InterviewController extends Controller
{
    private $service;

    public function __construct(InterviewService $service)
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

    public function store(InterviewRequest $request)
    {
        return response()->json($this->service->store($request->all()), 201);
    }

    public function update(InterviewRequest $request, $id)
    {
        return response()->json($this->service->update($request->all(), $id), 200);
    }

}
