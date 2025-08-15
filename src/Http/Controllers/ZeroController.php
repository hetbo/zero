<?php
namespace Hetbo\Zero\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ZeroController extends Controller
{
    public function index()
    {
        return view('zero::index', ['title' => 'Zero Package']);
    }

    public function show($id)
    {
        return view('zero::show', ['id' => $id, 'data' => 'Sample data']);
    }
}