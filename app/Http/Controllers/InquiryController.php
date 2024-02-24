<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\InquiryService;
use App\Http\Requests\InquiryRequest;



class InquiryController extends Controller
{
    private $service;

    public function __construct(InquiryService $service)
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

    public function updateRestart($id)
    {
        return response()->json($this->service->updateRestart($id), 200);
    }

    public function updateStart($id)
    {
        return response()->json($this->service->updateStart($id), 200);
    }

    public function updateSupportInEmail($id)
    {
        return response()->json($this->service->updateSupportInEmail($id), 200);
    }

    public function updateClose($id)
    {
        return response()->json($this->service->updateClose($id), 200);
    }

    public function storeComment(InquiryRequest $inquiryRequest, $id)
    {
        return response()->json($this->service->storeComment($inquiryRequest, $id), 201);
    }

    public function download($id)
    {
        return $this->service->download($id);
    }

    public function destroy($id)
    {
        return response()->json($this->service->destroy($id), 204);
    }

}
