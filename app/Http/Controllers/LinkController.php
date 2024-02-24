<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LinkService;
use App\Http\Requests\LinkRequest;

class LinkController extends Controller
{
    private $service;

    public function __construct(LinkService $service)
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

    public function update(LinkRequest $request, $id)
    {
        return response()->json($this->service->update($request->all(), $id), 200);
    }

    public function store(LinkRequest $request)
    {
        return response()->json($this->service->store($request->all()), 201);
    }


}
