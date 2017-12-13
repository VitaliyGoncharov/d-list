<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class SaveFileController extends Controller
{
	public function save()
	{
		echo $_FILES['photo']['name'];
	}
}