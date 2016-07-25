<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use League\Glide\Server;

class HomeController extends Controller
{
    /**
     * Get the home page.
     *
     * @return \Illuminate\Http\Response 
     */
    public function index()
    {
        return view('app');
    }
}
