<?php
namespace App\Http\Services;

use App\Http\Interfaces\Services\ILeftMenu;
use App\Models\ProfileLink;
use Illuminate\Support\Facades\Auth;

class LeftMenuService implements ILeftMenu
{
    public function getLinks()
    {
        $link = Auth::user()->link;

        $leftMenuLinks = [
            'profile' => '/profile/'.$link
        ];

        return $leftMenuLinks;
    }
}