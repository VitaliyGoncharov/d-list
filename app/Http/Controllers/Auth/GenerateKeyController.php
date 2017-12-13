<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class GenerateKeyController extends Controller
{
    public function generate_key($num)
    {
        $arr = array('a','b','c','d','e','f',
                   'g','h','i','j','k','l',
                   'm','n','o','p','r','s',
                   't','u','v','x','y','z',
                   'A','B','C','D','E','F',
                   'G','H','I','J','K','L',
                   'M','N','O','P','R','S',
                   'T','U','V','X','Y','Z',
                   '1','2','3','4','5','6',
                   '7','8','9','0','@','*',
                   '+','-','~');
        $key = "";

        // $activation_key = md5(uniqid(rand(), true));

        for($i=0; $i < $num; $i++)
        {
            $index = rand(0, count($arr) - 1);
            $key .= $arr[$index];
        }
        return $key;
    }
}
