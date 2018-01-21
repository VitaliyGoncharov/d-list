<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

	public function show(Request $request)
	{

		if($request->session()->has(['email', 'surname', 'name', 'pwd', 'gender', 'birth', '_token']))
        {
			return redirect()->route('home');
        }

        $utc = $request->session()->get('utc');

        return view('home', [
            'utc' => $utc
        ]);
	}
}