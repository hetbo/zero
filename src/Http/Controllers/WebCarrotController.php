<?php

namespace Hetbo\Zero\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Hetbo\Zero\Services\CarrotService;
use Hetbo\Zero\Services\CarrotableService;

class WebCarrotController extends Controller
{
    public function __construct(
        private CarrotService $carrotService,
        private CarrotableService $carrotableService
    ) {}

    public function index(): View
    {
        $carrots = $this->carrotService->getAllCarrots();
        return view('zero::carrots.index', compact('carrots'));
    }

    public function componentContent(string $modelType, int $modelId, string $role): View
    {
        $modelClass = urldecode($modelType);
        $model = $modelClass::findOrFail($modelId);

        $attachedCarrots = $model->getCarrotsByRole($role);

        return view('zero::carrots.partials.component-content', [
            'attachedCarrots' => $attachedCarrots,
            'role' => $role,
            'model' => $model
        ]);
    }

    public function modal(string $modelType, int $modelId, string $role): View
    {
        $modelClass = urldecode($modelType);
        $model = $modelClass::findOrFail($modelId);

        $attachedCarrots = $this->carrotableService->getModelCarrotsByRole($model, $role);
        $attachedIds = $attachedCarrots->pluck('id')->toArray();

        $availableCarrots = $this->carrotService->getAllCarrots()
            ->whereNotIn('id', $attachedIds)
            ->take(10);

        return view('zero::carrots.modal', [
            'model' => $model,
            'modelType' => $modelType,
            'role' => $role,
            'attachedCarrots' => $attachedCarrots,
            'availableCarrots' => $availableCarrots,
            'hasMore' => $availableCarrots->count() === 10
        ]);
    }

    public function loadMore(Request $request, string $modelType, int $modelId, string $role): View
    {
        $page = $request->get('page', 1);
        $modelClass = urldecode($modelType);
        $model = $modelClass::findOrFail($modelId);

        $attachedCarrots = $this->carrotableService->getModelCarrotsByRole($model, $role);
        $attachedIds = $attachedCarrots->pluck('id')->toArray();

        $availableCarrots = $this->carrotService->getAllCarrots()
            ->whereNotIn('id', $attachedIds)
            ->skip(($page - 1) * 10) // Fixed pagination calculation
            ->take(10);

        return view('zero::carrots.partials.available-carrots', [
            'availableCarrots' => $availableCarrots,
            'modelType' => $modelType,
            'modelId' => $modelId,
            'role' => $role,
            'page' => $page + 1,
            'hasMore' => $availableCarrots->count() === 10
        ]);
    }

    public function attach(Request $request, string $modelType, int $modelId): Response
    {
        $validated = $request->validate([
            'carrot_id' => 'required|integer|exists:carrots,id',
            'role' => 'required|string'
        ]);

        $modelClass = urldecode($modelType);
        $model = $modelClass::findOrFail($modelId);

        $this->carrotableService->attachCarrotToModel($model, $validated['carrot_id'], $validated['role']);

        // Return the updated modal content with trigger for main component
        $attachedCarrots = $this->carrotableService->getModelCarrotsByRole($model, $validated['role']);
        $attachedIds = $attachedCarrots->pluck('id')->toArray();

        $availableCarrots = $this->carrotService->getAllCarrots()
            ->whereNotIn('id', $attachedIds)
            ->take(10);

        return response()
            ->view('zero::carrots.modal', [
                'model' => $model,
                'modelType' => $modelType,
                'role' => $validated['role'],
                'attachedCarrots' => $attachedCarrots,
                'availableCarrots' => $availableCarrots,
                'hasMore' => $availableCarrots->count() === 10
            ])
            ->header('HX-Trigger', 'carrotAttached');
    }

    public function detach(string $modelType, int $modelId, int $carrotId, string $role): Response
    {
        $modelClass = urldecode($modelType);
        $model = $modelClass::findOrFail($modelId);

        $this->carrotableService->detachCarrotFromModel($model, $carrotId, $role);

        // Just return empty response with trigger event
        return response('', 200, [
            'HX-Trigger' => 'carrotDetached'
        ]);
    }
}