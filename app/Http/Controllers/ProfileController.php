<?php

namespace App\Http\Controllers;

use App\Http\Interfaces\Services\ILeftMenu;
use App\Models\User;
use App\Models\ProfileLink;
use App\Http\Middleware\RedirectIfNotAuthenticated;

class ProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware(RedirectIfNotAuthenticated::class);
    }

    public function index($profileNameOrId,ILeftMenu $ILeftMenu)
    {
        $profileLink = ProfileLink::where('link',$profileNameOrId)->select('user_id','link')->get();

        if(isset($profileLink[0]))
        {
            $leftMenuLinks = $ILeftMenu->getLinks();

            $user_id = $profileLink[0]->user_id;

            $user = User::where('users.id',$user_id)
                ->join('profile','profile.user_id','=','users.id')
                ->select(
                    'users.id','users.surname','users.name','users.birth','users.avatar',
                    'profile.city','profile.school','profile.university'
                )->firstOrFail();

            return view('profile',compact('leftMenuLinks','user'));
        }
        else
        {
            abort(404);
        }
    }
}