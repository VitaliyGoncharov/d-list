<?php
namespace App\Http\Interfaces\Services;

use App\Models\ProfileLink;

interface ILeftMenu
{
    public function getLinks();
}