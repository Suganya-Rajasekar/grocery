<?php

namespace App\Http\Controllers;


class CustomerComplaintController extends Controller{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('complaint.index');
    }
}
