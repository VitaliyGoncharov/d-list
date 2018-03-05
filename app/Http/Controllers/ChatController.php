<?php

namespace App\Http\Controllers;

use App\Http\Interfaces\Services\ILeftMenu;
use App\Http\Middleware\RedirectIfNotAuthenticated;
use App\Models\Conversation;
use App\Models\Participant;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class ChatController extends Controller
{
    public function __construct(ILeftMenu $leftMenu, Conversation $conversation)
    {
        $this->middleware(RedirectIfNotAuthenticated::class);

        $this->leftMenu = $leftMenu;
        $this->conversation = $conversation;
    }

    public function index($to, User $user)
    {
        $userRec = $user->checkIfExists($to);

        if (!$userRec) {
            dump('Such user wasn\'t found');
            exit;
        }

        $profileLink = '/profile/'.$userRec->link;

        $from = Auth::user()->id;

        // need a check that user already have a dialog with such user ($to)
        // if record in dialogs table doesn't exist then create it
        $convRecord = $this->conversation->checkIfExists($from, $to);

        if (!$convRecord) {
            $this->conversation->create($from, $to);
        }
        else {
        }

        $leftMenuLinks = $this->leftMenu->getLinks();

        return view('chat',compact('leftMenuLinks', 'userRec','profileLink'));
    }
}
