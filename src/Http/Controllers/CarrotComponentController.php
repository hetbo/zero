<?php

namespace Hetbo\Zero\Http\Controllers;

use Hetbo\Zero\Traits\HasCarrots;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Hetbo\Zero\Models\Carrot;
use Hetbo\Zero\Services\CarrotService;

class CarrotComponentController extends Controller
{
    public function __construct(protected CarrotService $carrotService) {}

    public function attach(Request $request)
    {
        $data = $request->validate([
            'model_type' => 'required|string',
            'model_id'   => 'required|integer',
            'role'       => 'required|string',
            'carrot_id'  => 'required|exists:carrots,id',
        ]);

        $model = $this->findModelOrFail($data['model_type'], $data['model_id']);
        $carrot = Carrot::find($data['carrot_id']);

        $model->attachCarrot($carrot, $data['role']);

        return back()->with('success', 'Carrot attached.');
    }

    public function detach(Request $request)
    {
        $data = $request->validate([
            'model_type' => 'required|string',
            'model_id'   => 'required|integer',
            'role'       => 'required|string',
            'carrot_id'  => 'required|exists:carrots,id',
        ]);

        $model = $this->findModelOrFail($data['model_type'], $data['model_id']);

        // Use the generic relationship to detach
        $model->carrots()->wherePivot('role', $data['role'])->detach($data['carrot_id']);

        return back()->with('success', 'Carrot detached.');
    }

    /**
     * Helper to safely find the parent model.
     * It also checks if the model uses the HasCarrots trait.
     */
    private function findModelOrFail(string $modelType, int $modelId): Model
    {
        if (!class_exists($modelType)) {
            abort(404, 'Model type not found.');
        }

        $model = $modelType::find($modelId);

        if (!$model) {
            abort(404, 'Model instance not found.');
        }

        if (!in_array(HasCarrots::class, class_uses_recursive($model))) {
            abort(500, 'Model does not use the HasCarrots trait.');
        }

        return $model;
    }
}