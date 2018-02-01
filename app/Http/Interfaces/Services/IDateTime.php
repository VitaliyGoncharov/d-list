<?php
namespace App\Http\Interfaces\Services;

use Illuminate\Http\Request;

interface IDateTime
{
    public function __construct($request);

    public function changeDateTime(string $dateTime);
}