<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BillingService;
use App\Http\Requests\BillingRequest;
use Barryvdh\DomPDF\Facade\Pdf;

class BillingController extends Controller
{
    private $service;

    public function __construct(BillingService $service)
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

    public function pdf($id)
    {
        return $this->service->pdf($id);
    }

    // TODO: ※後日修正
    // public function retry()
    // {   
    // }

    public function update(BillingRequest $request, $id)
    {
        return response()->json($this->service->update($request->all(), $id), 200);
    }

    public function fix($yearMonth)
    {
        return response()->json($this->service->fix($yearMonth), 200);
    }

    public function exclude(Request $request)
    {
        return response()->json($this->service->exclude($request->all()), 200);
    }

    public function target(Request $request)
    {
        return response()->json($this->service->target($request->all()), 200);
    }

    public function updateBill($id)
    {
        return response()->json($this->service->updateBill($id), 200);
    }

    public function cancel($id)
    {
        return response()->json($this->service->cancel($id), 200);
    }
}
