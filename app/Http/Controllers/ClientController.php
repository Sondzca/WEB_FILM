<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Ticket;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $tickets = Ticket::all();
        return view('shop.index', compact('tickets'));
    }

    public function shop()
    {
        $categories = Category::withCount('tickets')->get();
        $tickets = Ticket::all();

        return view('shop.shop', compact('categories', 'tickets'));
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
    public function checkout()
    {
        return view('carts.checkout');
    }
}