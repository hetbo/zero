<?php

namespace Hetbo\Zero\Http\Controllers;

use Hetbo\Zero\Services\CarrotService;
use Illuminate\Routing\Controller;

class TheCarrotsController extends Controller {

    public function __construct(protected CarrotService $carrotService){}

}