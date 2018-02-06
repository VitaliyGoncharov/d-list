<?php
namespace App\Http\Interfaces\Services;

use App\Models\ProfileLink;

interface ILeftMenu
{
    public function __construct(ProfileLink $profileLink);

    public function getLinks();
}