<?php
namespace App\Http\Services;

use App\Http\Interfaces\Services\ILeftMenu;
use App\ProfileLink;
use Illuminate\Support\Facades\Auth;

class LeftMenuService implements ILeftMenu
{
    public function __construct($profileLink,$auth)
    {
        $this->profileLink = $profileLink;
        $this->auth = $auth;
    }

    public function getLinks()
    {
        $userId = $this->auth::user()->id;
        $link = $this->profileLink->where('user_id',$userId)->select('link')->get();

        $leftMenuLinks = [
            'profile' => '/profile/'.$link[0]->link
        ];

        return $leftMenuLinks;
    }
}