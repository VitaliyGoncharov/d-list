<?php

namespace App\Http\Controllers;

use App\Http\Middleware\RedirectIfNotAuthenticated;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function __construct()
    {
        $this->middleware(RedirectIfNotAuthenticated::class);
    }

    public function index()
    {
        echo request()->user()->id;
        return response()->json([
            'test' => request()->user()->id
        ]);
    }
}
