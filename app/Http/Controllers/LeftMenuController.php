<?php
namespace App\Http\Controllers;

use Auth;
use App\ProfileLink;
use App\Profile;
use App\Http\Controllers\Controller;
use App\Http\Middleware\RedirectIfNotAuthenticated;
use Illuminate\Http\Request;

class LeftMenuController extends Controller
{

	public function index()
	{
		$userId = Auth::user()->id;
		$link = ProfileLink::where('user_id',$userId)->select('link')->get();

		$leftMenuLinks = [
			'profile' => '/profile/'.$link[0]->link
		];

		return $leftMenuLinks;
	}
}