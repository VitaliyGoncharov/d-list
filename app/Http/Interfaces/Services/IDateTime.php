<?php
namespace App\Http\Interfaces\Services;

use Illuminate\Http\Request;

interface IDateTime
{
    public function __construct();

    public function changeDateTime(string $dateTime);
}