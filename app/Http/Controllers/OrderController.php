<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $user = auth()->user(); // Get the currently authenticated user

        // Retrieve the user's cart
        $cart = Cart::where('user_id', $user->id)->first();

        if ($cart) {
            // Retrieve all items in the cart, along with their ticket details
            $cartItems = CartItem::where('cart_id', $cart->id)->with('ticket')->get();
            $totalPrice = $cartItems->sum('total'); // Sum the total prices of all cart items
        } else {
            // If no cart found, return an empty collection and a total price of 0
            $cartItems = collect();
            $totalPrice = 0;
        }

        // Pass the user, cart items, and total price to the view
        return view('carts.checkout', compact('user', 'cartItems', 'totalPrice'));
    }



    public function create() {}


    public function store(Request $request) {}


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }
}
