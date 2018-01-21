<?php
namespace App\Http\Controllers;

use App\User;
use App\ProfileLink;
use App\Profile;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LeftMenuController;
use App\Http\Middleware\RedirectIfNotAuthenticated;
use Illuminate\Http\Request;

class ProfileController extends Controller
{

	public function __construct()
	{
		$this->middleware(RedirectIfNotAuthenticated::class);
	}

	public function index($profileNameOrId, Request $request, LeftMenuController $leftMenuController)
	{
		$profileLink = ProfileLink::where('link',$profileNameOrId)->select('user_id','link')->get();

		if(isset($profileLink[0]))
		{
			$leftMenuLinks = $leftMenuController->index();

			$user_id = $profileLink[0]->user_id;

			$user = User::where('users.id',$user_id)
                ->join('profile','profile.user_id','=','users.id')
                ->select('users.surname','users.name','users.birth','users.avatar','profile.city','profile.school','profile.university')
                ->get();

			return view('profile', [
				'leftMenuLinks' => $leftMenuLinks,
                'user' => $user[0]
			]);
		}
		else
		{
			abort(404);
		}
	}
}