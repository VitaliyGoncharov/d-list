<?php

namespace App\Http\Controllers;

use App\Http\Interfaces\Services\ILeftMenu;
use App\Http\Middleware\RedirectIfNotAuthenticated;
use App\Models\FrdRequest;
use App\Models\Friend;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FriendsController extends Controller
{
    public function __construct()
    {
        $this->middleware(RedirectIfNotAuthenticated::class);
    }

    public function index(ILeftMenu $leftMenu, Friend $friend, User $user)
    {
        $userId = Auth::user()->id;

        $friends_pairs = $friend->get($userId);

        $friends = [];

        // get the friend data
        foreach ($friends_pairs as $friends_pair) {
            if ($friends_pair->user_id !== $userId) {
                $friend_data = $user->get($friends_pair->user_id);
                array_push($friends, $friend_data);
            }

            if ($friends_pair->friend_id !== $userId) {
                $friend_data = $user->get($friends_pair->friend_id);
                array_push($friends, $friend_data);
            }
        }

        $leftMenuLinks = $leftMenu->getLinks();

        return view('friends.main',compact('leftMenuLinks','friends'));
    }

    public function showFoundUsers(ILeftMenu $leftMenu, Friend $friend, FrdRequest $request, User $user)
    {
        $userId = Auth::user()->id;

        // load profile link from `profile_link` table for left menu
        $leftMenuLinks = $leftMenu->getLinks();

        // get first 10 users
        $people = $user->getUsers(10);

        foreach ($people as $person) {
            $userReq = $request->checkIfExists($userId, $person->id);

            if ($userReq) {
                $person->request = true;
            }
        }

        return view('friends.find',compact('leftMenuLinks','people'));
    }

    public function sendRequest(FrdRequest $request)
    {
        $to = request()->input('id');
        $userId = Auth::user()->id;

        return $request->add($userId, $to);
    }

    public function acceptRequest(FrdRequest $request, Friend $friend)
    {
        $to = request()->input('id');
        $userId = Auth::user()->id;

        $request->deleteRequest($userId, $to);

        return $friend->add($userId, $to);
    }

    public function cancelRequest(FrdRequest $request)
    {
        $to = request()->input('id');
        $userId = Auth::user()->id;

        return $request->deleteRequest($userId, $to);
    }

    public function showOutgoingRequests(ILeftMenu $leftMenu, FrdRequest $request, User $user)
    {
        $userId = Auth::user()->id;

        $requests = $request->getOutgoing($userId);

        if ($requests) {
            $users = [];

            foreach ($requests as $userRequest) {
                $person = $user->get($userRequest->to);
                array_push($users, $person);
            }
        }

        $leftMenuLinks = $leftMenu->getLinks();

        return view('friends.requests.outgoing', compact('leftMenuLinks','users'));
    }

    public function showIncomingRequests(ILeftMenu $leftMenu, FrdRequest $request, User $user)
    {
        $userId = Auth::user()->id;

        $requests = $request->getIncoming($userId);

        if ($requests) {
            $users = [];

            foreach ($requests as $request) {
                $person = $user->get($request->from);
                array_push($users, $person);
            }
        }

        $leftMenuLinks = $leftMenu->getLinks();

        return view('friends.requests.incoming', compact('leftMenuLinks','users'));
    }

    public function findUser(User $user)
    {
        $keys = explode(' ', request()->input('keyWords'));

        (count($keys) > 1) ? $multiple = true : $multiple = false;

        $friends = $user->findByNameOrSurname($keys, $multiple);

        return view('friends.friend',compact('friends'));
    }
}