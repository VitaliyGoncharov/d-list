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

    public function index($profileNameOrId,ILeftMenu $ILeftMenu,User $user)
    {
        $userData = $user->findByLink($profileNameOrId);

        if($userData)
        {
            $leftMenuLinks = $ILeftMenu->getLinks();

            // get the dat from bounded table `profile`
            $userData->profile = $userData->profile();

            return view('profile',[
                'leftMenuLinks' => $leftMenuLinks,
                'user' => $userData
            ]);
        }
        else
        {
            abort(404);
        }
    }
}