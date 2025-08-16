<?php

namespace Hetbo\Zero\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Hetbo\Zero\Services\CarrotableService;
use Hetbo\Zero\Http\Requests\AttachCarrotRequest;
use Hetbo\Zero\Http\Requests\SyncCarrotsRequest;

class CarrotableController extends Controller
{
    public function __construct(
        private CarrotableService $carrotableService
    ) {}

    public function attach(AttachCarrotRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $modelClass = $validated['model_type'];
        $model = $modelClass::findOrFail($validated['model_id']);

        $this->carrotableService->attachCarrotToModel(
            $model,
            $validated['carrot_id'],
            $validated['role']
        );

        return response()->json(['message' => 'Carrot attached successfully']);
    }

    public function detach(Request $request): JsonResponse
    {
        $request->validate([
            'model_type' => 'required|string',
            'model_id' => 'required|integer',
            'carrot_id' => 'required|integer',
            'role' => 'nullable|string',
        ]);

        $modelClass = $request->model_type;
        $model = $modelClass::findOrFail($request->model_id);

        $this->carrotableService->detachCarrotFromModel(
            $model,
            $request->carrot_id,
            $request->role
        );

        return response()->json(['message' => 'Carrot detached successfully']);
    }

    public function sync(SyncCarrotsRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $modelClass = $validated['model_type'];
        $model = $modelClass::findOrFail($validated['model_id']);

        $this->carrotableService->syncCarrotsForModel(
            $model,
            $validated['carrot_ids'],
            $validated['role']
        );

        return response()->json(['message' => 'Carrots synced successfully']);
    }

    public function getCarrotsByRole(Request $request): JsonResponse
    {
        $request->validate([
            'model_type' => 'required|string',
            'model_id' => 'required|integer',
            'role' => 'required|string',
        ]);

        $modelClass = $request->model_type;
        $model = $modelClass::findOrFail($request->model_id);

        $carrots = $this->carrotableService->getModelCarrotsByRole($model, $request->role);

        return response()->json(['data' => $carrots]);
    }

    public function getAllCarrots(Request $request): JsonResponse
    {
        $request->validate([
            'model_type' => 'required|string',
            'model_id' => 'required|integer',
        ]);

        $modelClass = $request->model_type;
        $model = $modelClass::findOrFail($request->model_id);

        $carrots = $this->carrotableService->getAllModelCarrots($model);

        return response()->json(['data' => $carrots]);
    }

    public function getRoles(Request $request): JsonResponse
    {
        $request->validate([
            'model_type' => 'required|string',
            'model_id' => 'required|integer',
        ]);

        $modelClass = $request->model_type;
        $model = $modelClass::findOrFail($request->model_id);

        $roles = $this->carrotableService->getModelCarrotRoles($model);

        return response()->json(['data' => $roles]);
    }
}