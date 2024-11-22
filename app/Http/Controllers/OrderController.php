<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{

    public function getWalletBalance($walletAddress)
    {
        try {
            // Địa chỉ RPC của Solana Mainnet
            $rpcEndpoint = "https://api.mainnet-beta.solana.com";

            // Payload yêu cầu lấy số dư
            $payload = [
                "jsonrpc" => "2.0",
                "id" => 1,
                "method" => "getBalance",
                "params" => [$walletAddress] // Địa chỉ ví Phantom
            ];

            // Gửi request đến Solana RPC để lấy số dư
            $response = Http::post($rpcEndpoint, $payload);
            $data = $response->json();

            // Kiểm tra kết quả và chuyển đổi từ Lamport sang SOL
            if (isset($data['result']['value'])) {
                $balanceLamport = $data['result']['value'];
                $balanceSOL = $balanceLamport / 1000000000; // Chuyển Lamport sang SOL
                return number_format($balanceSOL, 2); // Trả về số dư SOL
            } else {
                return 'Không thể lấy số dư ví.';
            }
        } catch (\Exception $e) {
            return 'Có lỗi khi kết nối đến Solana RPC API: ' . $e->getMessage();
        }
    }
    public function index()
    {
        $adminWallet = User::where('role', 2)->first()->wallet_address;

        // Kiểm tra xem ví có tồn tại không
        if (!$adminWallet) {
            // Nếu không có ví, có thể thông báo lỗi hoặc sử dụng giá trị mặc định
            $adminWallet = 'Default wallet address';
        }

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

        // Check if the user has connected a wallet
        $hasWallet = !is_null($user->wallet);
        $walletBalance = null;

        if ($hasWallet) {
            $walletBalance = $this->getWalletBalance($user->wallet);
        }

        // Pass the user, cart items, total price, and wallet status to the view
        return view('carts.checkout', compact('user' ,'adminWallet', 'cartItems', 'totalPrice', 'hasWallet', 'walletBalance'));
    }

    public function store(Request $request)
{
    try {
        $userPublicKey = $request->input('userPublicKey');
        $cartItems = $request->input('cartItems'); // Chi tiết các mặt hàng trong giỏ hàng
        $totalAmount = $request->input('totalAmount');
        $adminWallet = $request->input('adminWallet');
        $transactionHash = $request->input('transactionHash'); // Nhận transaction hash từ frontend

        // Lưu thông tin đơn hàng vào bảng orders
        $order = Order::create([
            'user_id' => auth()->user()->id,
            'transaction_hash' => $transactionHash,
            'quantity' => collect($cartItems)->sum('quantity'), // Tính tổng số lượng sản phẩm
            'total_amount' => $totalAmount,
            'status' => 1, // Giả sử đơn hàng đang chờ thanh toán
        ]);

        // Lưu thông tin chi tiết đơn hàng vào bảng order_details
        foreach ($cartItems as $item) {
            OrderDetail::create([
                'order_id' => $order->id,
                'ticket_id' => $item['ticket_id'], // Id của vé
                'quantity' => $item['quantity'],
                'price' => $item['price'], // Giá của vé
                'total' => $item['total'], // Tổng giá của sản phẩm
            ]);
        }

        return redirect()->route('carts.index')->with('success', 'Thành công');

    } catch (\Exception $e) {
        return back()->with('erorr', 'thất bại: ' . $e->getMessage());
    }
}

    

}
