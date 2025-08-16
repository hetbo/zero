<?php

namespace Hetbo\Zero\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;

class AssetController extends Controller
{
    public function source()
    {
        $path = __DIR__.'/../../../dist/carrots.js'; // Points to the bundled file

        if (!File::exists($path)) {
            abort(404);
        }

        $contents = File::get($path);

        return new Response($contents, 200, [
            'Content-Type' => 'application/javascript',
            'Cache-Control' => 'public, max-age=31536000',
        ]);
    }
}