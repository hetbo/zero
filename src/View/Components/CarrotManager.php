<?php

namespace Hetbo\Zero\View\Components;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class CarrotManager extends Component
{
    // These properties will be populated by the `boot()` method.
    public Model $model;
    public string $role;
    public Collection $carrots;

    // This property is used to hold the explicitly passed model.
    protected ?Model $explicitModel;

    public function __construct(string $role, ?Model $explicitModel = null)
    {
        $this->role = $role;
        $this->explicitModel = $explicitModel;

        // Call our new setup method right away.
        $this->boot();
    }

    /**
     * This new method contains the core logic to prepare the component's state.
     */
    public function boot(): void
    {
        // Determine which model to use.
        $this->model = $this->explicitModel ?? $this->discoverModelFromRoute();

        // Now that we have a model, load its carrots.
        $this->carrots = $this->model->getCarrotsByRole($this->role);
    }

    /**
     * The render method is now extremely simple. Its only job is to return a view.
     * All the necessary data has already been prepared.
     */
    public function render()
    {
        return view('zero::components.manager');
    }

    protected function discoverModelFromRoute(): Model
    {
        // ... (This method remains exactly the same)
        $route = Route::current();
        if (!$route) { /* ... */ }
        foreach ($route->parameters() as $parameter) {
            if ($parameter instanceof Model) {
                return $parameter;
            }
        }
        throw new InvalidArgumentException(/* ... */);
    }
}