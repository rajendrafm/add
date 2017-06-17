<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SayaController extends Controller
{
    //
public function __contract()
	{
		$this->middleware('auth');
	}

    public function inn()
    {
    $a='rajendra';
    return view('inn', compact($a));
	}
}
