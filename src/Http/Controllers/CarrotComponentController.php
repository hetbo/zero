<?php

namespace Hetbo\Zero\Http\Controllers;

use Hetbo\Zero\Models\Carrot;
use Hetbo\Zero\Rules\CarrotNotAttached;
use Hetbo\Zero\Services\CarrotService;
use Hetbo\Zero\Traits\HasCarrots;
use Hetbo\Zero\View\Components\CarrotManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\ValidationException;

class CarrotComponentController extends Controller
{
    public function __construct(protected CarrotService $carrotService) {}

    public function attach(Request $request)
    {
        $model = $this->findModelOrFail($request->input('model_type'), $request->input('model_id'));
        $role = $request->input('role');

        try {
            $data = $request->validate([
                'model_type' => 'required|string',
                'model_id'   => 'required|integer',
                'role'       => 'required|string',
                'carrot_id'  => [
                    'required',
                    'exists:carrots,id',
                    new CarrotNotAttached($model, $role),
                ],
            ], [
                'carrot_id.exists' => 'No carrot with this ID was found.',
            ]);

            $carrot = Carrot::find($data['carrot_id']);
            $model->attachCarrot($carrot, $data['role']);

            return $this->renderComponent($model, $data['role']);

        } catch (ValidationException $e) {
            return $this->renderComponent($model, $role, $e->errors());
        }
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
        $model->carrots()->wherePivot('role', $data['role'])->detach($data['carrot_id']);

        return $this->renderComponent($model, $data['role']);
    }

    private function findModelOrFail(string $modelType, int $modelId): Model
    {
        if (!class_exists($modelType) || !in_array(HasCarrots::class, class_uses_recursive($modelType))) {
            abort(500, 'Invalid model type provided.');
        }
        return $modelType::findOrFail($modelId);
    }

    private function renderComponent(Model $model, string $role, ?array $errors = [])
    {
        $component = new CarrotManager($role, $model);
        $view = $component->render();
        $data = $component->data();
        $data['errors'] = new MessageBag($errors);

        return $view->with($data);
    }
}