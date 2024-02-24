<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ClientService;
use App\Http\Requests\ClientRequest;



class ClientController extends Controller
{
    private $service;

    public function __construct(ClientService $service)
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

    public function store(ClientRequest $request)
    {
        return response()->json($this->service->store($request->all()), 201);
    }

    public function update(ClientRequest $request, $id)
    {
        return response()->json($this->service->update($request->all(), $id), 200);
    }

    public function destroy($id)
    {
        return response()->json($this->service->destroy($id), 204);
    }

    public function all(Request $request)
    {
        return response()->json($this->service->all($request), 200);
    }

}
