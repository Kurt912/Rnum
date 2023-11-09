<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RandomNumberGeneration extends Controller
{
    public function getRandomNum($length)
    {
        $length = (int) $length;
        $randumNum = [];
        for ($i = 0; $i < $length; $i++) {
            $randumNum[] = rand(0, 9);
        }
  
        return response()->json($randumNum);
    }
}
