<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        return view('shop.index');
    }

    public function shop()
    {
        return view('shop.shop');
    }

    public function carts()
    {
        return view('carts.carts');
    }

    public function contact()
    {
        return view('about.contact');
    }
    public function about()
    {
        return view('about.about');
    }
    public function detail()
    {
        return view('shop.detail');
    }
}
