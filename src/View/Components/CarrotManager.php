<?php

namespace Hetbo\Zero\View\Components;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route; // <-- Import the Route facade
use Illuminate\View\Component;
use Illuminate\Support\Collection;
use InvalidArgumentException; // <-- Import exception class

class CarrotManager extends Component
{
    public Model $model;
    public string $role;
    public Collection $carrots;

    /**
     * Create a new component instance.
     * It can accept an explicit model or try to discover it from the route.
     */
    public function __construct(string $role, ?Model $model = null)
    {
        $this->role = $role;
        $this->model = $model ?? $this->discoverModelFromRoute();

        // After the model has been set (either explicitly or via discovery),
        // we can load its carrots.
        $this->carrots = $this->model->getCarrotsByRole($this->role);
    }

    /**
     * The magic happens here.
     */
    protected function discoverModelFromRoute(): Model
    {
        // Get the currently resolved route
        $route = Route::current();

        if (!$route) {
            throw new InvalidArgumentException('CarrotManager component could not detect the current route.');
        }

        // Iterate over the route's parameters (e.g., $food, $shop)
        foreach ($route->parameters() as $parameter) {
            // Check if the parameter is an Eloquent Model instance
            if ($parameter instanceof Model) {
                // We found it! Return the first model we find.
                return $parameter;
            }
        }

        // If we get here, no model was passed and none could be found in the route.
        throw new InvalidArgumentException(
            'CarrotManager component could not discover a model from the route parameters. Please bind it explicitly using :model="$yourModel".'
        );
    }

    public function render()
    {
        return view('zero::components.manager');
    }
}