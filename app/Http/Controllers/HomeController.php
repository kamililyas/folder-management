<?php

namespace App\Http\Controllers;

use App\FileType;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fileTypes = FileType::all()->toJson();
        return view('home', array('fileTypes'=>$fileTypes));
    }
}
