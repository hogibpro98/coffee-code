<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BusinessCardService;
use App\Http\Requests\BusinessCardRequest;

class BusinessCardController extends Controller
{
    private $service;

    public function __construct(BusinessCardService $service)
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

    public function update(BusinessCardRequest $request, $id)
    {
        return response()->json($this->service->update($request->all(), $id), 200);
    }

    public function support($id)
    {
        return response()->json($this->service->support($id), 200);
    }

    public function complete($id)
    {
        return response()->json($this->service->complete($id), 200);
    }

    public function download($id)
    {
        return $this->service->download($id);
    }


}
