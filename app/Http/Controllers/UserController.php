<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Requests\UserRequest;
use App\Http\Requests\PasswordRequest;

class UserController extends Controller
{
    private $service;

    public function __construct(UserService $service)
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

    public function update(UserRequest $request, $id)
    {
        return response()->json($this->service->update($request->all(), $id), 200);
    }

    public function store(UserRequest $request)
    {
        return response()->json($this->service->store($request->all()), 201);
    }

    public function destroy($id)
    {
        return response()->json($this->service->destroy($id), 204);
    }

    public function changePassword(PasswordRequest $request)
    {
        return response()->json($this->service->changePassword($request->all()), 200);
    }

    public function resetPassword($id)
    {
        return response()->json($this->service->resetPassword($id), 200);
    }
}
