<?php
namespace App\Http\Interfaces\Services;

interface ILeftMenu
{
    public function __construct($profileLink,$auth);

    public function getLinks();
}