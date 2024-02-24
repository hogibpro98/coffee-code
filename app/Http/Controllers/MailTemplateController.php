<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\MailTemplateRequest;
use App\Services\MailTemplateService;
use Illuminate\Http\Request;

class MailTemplateController extends Controller
{
    private $service;

    public function __construct(MailTemplateService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return response()->json($this->service->index(), 200);
    }

    public function show($code)
    {
        return response()->json($this->service->show($code), 200);
    }

    public function update(MailTemplateRequest $request, $code)
    {
        return response()->json($this->service->update($request->all(), $code), 200);
    }

}
