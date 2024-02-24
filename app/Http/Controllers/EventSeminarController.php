<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EventSeminarService;
use App\Http\Requests\EventSeminarRequest;
use App\Http\Requests\EventSeminarCancelRequest;

class EventSeminarController extends Controller
{
    private $service;

    public function __construct(EventSeminarService $service)
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

    public function store(EventSeminarRequest $request)
    {
        return response()->json($this->service->store($request->all()), 201);
    }

    public function update(EventSeminarRequest $request, $id)
    {
        return response()->json($this->service->update($request->all(), $id), 200);
    }

    public function cancel(EventSeminarCancelRequest $request,$id)
    {
        return response()->json($this->service->cancel($request->all(), $id), 200);
    }

    public function public($id)
    {
        return response()->json($this->service->public($id), 200);
    }

    public function private($id)
    {
        return response()->json($this->service->private($id), 200);
    }

    public function entryStop($id)
    {
        return response()->json($this->service->entryStop($id), 200);
    }

    public function restart($id)
    {
        return response()->json($this->service->restart($id), 200);
    }

    public function piece(EventSeminarRequest $request, $id)
    {
        return response()->json($this->service->piece($request, $id), 201);
    }

    public function updatePiece(EventSeminarRequest $request, $pieceId, $id)
    {
        return response()->json($this->service->updatePiece($request, $pieceId, $id), 201);
    }

    public function destroy($id)
    {
        return response()->json($this->service->destroy($id), 204);
    }

    public function exportCSV($id, $pieceId)
    {
        return $this->service->exportCSV($id, $pieceId);
    }

    public function stopEvent($id)
    {
        return response()->json($this->service->stopEvent($id), 200);
    }

    public function deleteComma($id, $pieceId)
    {
        return response()->json($this->service->deleteComma($id, $pieceId), 204);
    }

    public function showApplicationComma($id, $pieceId)
    {
        return response()->json($this->service->showApplicationComma($id, $pieceId), 200);
    }

    public function registerTimes(EventSeminarRequest $request, $id)
    {
        return response()->json($this->service->registerTimes($request, $id), 201);
    }

    public function updateTimes(Request $request, $id, $timeNum)
    {
        return response()->json($this->service->updateTimes($request, $id, $timeNum), 200);
    }

    public function deleteTimes($id, $timeNum)
    {
        return response()->json($this->service->deleteTimes($id, $timeNum), 204);
    }

}
