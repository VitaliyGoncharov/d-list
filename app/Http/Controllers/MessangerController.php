<?php

namespace App\Http\Controllers;

use App\Http\Interfaces\Services\ILeftMenu;
use App\Http\Middleware\RedirectIfNotAuthenticated;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessangerController extends Controller
{
    public function __construct()
    {
        $this->middleware(RedirectIfNotAuthenticated::class);
    }

    public function index(ILeftMenu $ILeftMenu, Conversation $conv, User $user)
    {
        $userId = Auth::user()->id;

        // load profile link from `profile_link` table for left menu
        $leftMenuLinks = $ILeftMenu->getLinks();

        $convs = $conv->get($userId);
        $convs_data = [];

        foreach ($convs as $conv) {
            if (($partnerId = $conv->user_id1) !== $userId) {
                array_push($convs_data, $user->get($partnerId));
            }
            elseif (($partnerId = $conv->user_id2) !== $userId) {
                array_push($convs_data, $user->get($partnerId));
            }
        }

        return view('messanger',compact('leftMenuLinks','convs_data'));
    }

    public function write()
    {

    }
}
