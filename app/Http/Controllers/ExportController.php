<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use Validator, Input, Redirect ;
use Illuminate\Http\Request;

class ExportController extends Controller
{
	function __construct()
	{
		//
	}

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function export($obj, $name ,$type) 
    {
        return Excel::download($obj, $name.'.'.$type);
    }
}