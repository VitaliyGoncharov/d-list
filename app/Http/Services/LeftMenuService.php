<?php
namespace App\Http\Services;

use App\Http\Interfaces\Services\ILeftMenu;
use App\Models\ProfileLink;
use Illuminate\Support\Facades\Auth;

class LeftMenuService implements ILeftMenu
{
    public function __construct(ProfileLink $profileLink)
    {
        $this->profileLink = $profileLink;
    }

    public function getLinks()
    {
        $this->auth = Auth::user();

        $userId = $this->auth->id;
        $link = $this->profileLink->where('user_id',$userId)->select('link')->get();

        $leftMenuLinks = [
            'profile' => '/profile/'.$link[0]->link
        ];

        return $leftMenuLinks;
    }
}