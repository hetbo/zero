<?php

namespace Hetbo\Zero\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Hetbo\Zero\Services\CarrotService;
use Hetbo\Zero\DTOs\CreateCarrotData;
use Hetbo\Zero\DTOs\UpdateCarrotData;
use Hetbo\Zero\Http\Requests\CreateCarrotRequest;
use Hetbo\Zero\Http\Requests\UpdateCarrotRequest;

class CarrotController extends Controller
{
    public function __construct(
        private CarrotService $carrotService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $search = $request->get('search');

        if ($search) {
            $carrots = $this->carrotService->searchCarrots($search);
            return response()->json(['data' => $carrots]);
        }

        if ($request->get('paginate', true)) {
            $carrots = $this->carrotService->getPaginatedCarrots($perPage);
        } else {
            $carrots = $this->carrotService->getAllCarrots();
        }

        return response()->json($carrots);
    }

    public function show(int $id): JsonResponse
    {
        $carrot = $this->carrotService->getCarrotOrFail($id);
        return response()->json(['data' => $carrot]);
    }

    public function store(CreateCarrotRequest $request): JsonResponse
    {
        $data = CreateCarrotData::fromArray($request->validated());
        $carrot = $this->carrotService->createCarrot($data);

        return response()->json(['data' => $carrot], 201);
    }

    public function update(UpdateCarrotRequest $request, int $id): JsonResponse
    {
        $data = UpdateCarrotData::fromArray($request->validated());
        $carrot = $this->carrotService->updateCarrot($id, $data);

        return response()->json(['data' => $carrot]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->carrotService->deleteCarrot($id);
        return response()->json(['message' => 'Carrot deleted successfully']);
    }

    public function searchByName(Request $request): JsonResponse
    {
        $name = $request->get('name');
        if (!$name) {
            return response()->json(['error' => 'Name parameter is required'], 400);
        }

        $carrots = $this->carrotService->findCarrotsByName($name);
        return response()->json(['data' => $carrots]);
    }

    public function searchByLength(Request $request): JsonResponse
    {
        $minLength = $request->get('min_length');
        $maxLength = $request->get('max_length');

        if ($minLength === null || $maxLength === null) {
            return response()->json(['error' => 'Both min_length and max_length parameters are required'], 400);
        }

        $carrots = $this->carrotService->findCarrotsByLengthRange((int) $minLength, (int) $maxLength);
        return response()->json(['data' => $carrots]);
    }
}