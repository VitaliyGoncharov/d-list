<?php
namespace App\Http\Services;

use App\Http\Interfaces\Services\ILeftMenu;
use App\Models\ProfileLink;

class LeftMenuService implements ILeftMenu
{
    public function __construct(ProfileLink $profileLink)
    {
        $this->profileLink = $profileLink;
        $this->auth = request()->user();
    }

    public function getLinks()
    {
        $userId = $this->auth->id;
        $link = $this->profileLink->where('user_id',$userId)->select('link')->get();

        $leftMenuLinks = [
            'profile' => '/profile/'.$link[0]->link
        ];

        return $leftMenuLinks;
    }
}