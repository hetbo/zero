<?php

namespace Hetbo\Zero\Http\Controllers;

use Hetbo\Zero\Contracts\UserContract;
use Hetbo\Zero\Models\Carrot;
use Hetbo\Zero\Services\CarrotService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class CarrotController extends Controller
{
    public function __construct(protected CarrotService $carrotService) {}

    public function index()
    {
        $user = Auth::user();

        // Add a check to ensure the user is the correct type.
        if (!$user instanceof UserContract) {
            // This should never happen if the app is configured correctly,
            // but it's a safeguard.
            abort(500, 'Authenticated user does not implement UserWithCarrots interface.');
        }

        $carrots = $this->carrotService->getUserCarrots($user);

        return view('zero::carrots.index', compact('carrots'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'length' => 'required|integer|min:1',
        ]);

        $user = Auth::user();

        // The same check here makes the code robust.
        if (!$user instanceof UserContract) {
            abort(500, 'Authenticated user does not implement UserWithCarrots interface.');
        }

        $this->carrotService->addCarrotForUser($user, $request->only('name', 'length'));

        return back()->with('success', 'Carrot added!');
    }

    public function destroy(Carrot $carrot)
    {
        // ... (destroy method is fine as it is) ...
        if ($carrot->user_id !== Auth::id()) {
            abort(403, 'This is not your carrot to delete.');
        }

        $this->carrotService->removeCarrot($carrot->id);

        return back()->with('success', 'Carrot deleted!');
    }
}