<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SaveUtcController extends Controller
{
    public function saveAction(Request $request)
    {
        $utc = $request->input('utc');
        $request->session()->put('utc', $utc);

        echo json_encode("utc = $utc");
    }
}