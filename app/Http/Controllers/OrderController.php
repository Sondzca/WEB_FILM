<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $cart = Cart::where('user_id', $user->id)->first();

        if ($cart) {
            $cartItems = CartItem::where('cart_id', $cart->id)->with('ticket')->get();
            $totalPrice = $cartItems->sum('total'); // Tổng tiền (giả sử đơn vị là USD hoặc VND)

            // Cập nhật giá SOL theo API (giả sử 1 SOL = 22.5 USD)
            $solPrice = 22.5;
            $totalPriceInSol = $totalPrice / $solPrice;
        } else {
            $cartItems = collect();
            $totalPrice = 0;
            $totalPriceInSol = 0;
        }

        return view('carts.checkout', compact('user', 'cartItems', 'totalPrice', 'totalPriceInSol'));
    }


    public function storeSolanaTransaction(Request $request)
    {
        $transactionHash = $request->input('transactionHash');
        $amount = $request->input('amount');
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        // Lưu giao dịch vào cơ sở dữ liệu
        Transaction::create([
            'user_id' => $user->id,
            'transaction_hash' => $transactionHash,
            'blockchain' => 'solana',
            'status' => 'pending',
            'amount' => $amount,
        ]);

        return response()->json(['message' => 'Transaction saved successfully']);
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
