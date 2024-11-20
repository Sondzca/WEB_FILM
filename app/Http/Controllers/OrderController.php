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
        return view('carts.checkout', compact('user', 'cartItems', 'totalPrice', 'hasWallet', 'walletBalance'));
    }

    public function store(Request $request)
    {
        $user = Auth::user(); 
        if (!$user->wallet) {
            return back()->with('error', 'Vui lòng kết nối ví Phantom trước khi thanh toán.');
        }

        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart) {
            return back()->with('error', 'Giỏ hàng của bạn hiện không có sản phẩm.');
        }

        // Lấy tất cả các item trong giỏ hàng
        $cartItems = CartItem::where('cart_id', $cart->id)->with('ticket_id')->get();
        
        // Tính tổng số lượng và tổng tiền của các sản phẩm trong giỏ hàng
        $totalQuantity = $cartItems->sum('quantity');
        $totalAmount = $cartItems->sum('total');

        // Kiểm tra số dư Solana của người dùng
        $solBalance = $this->getSolanaBalance($user->wallet);
        
        if ($solBalance < $totalAmount) {
            return back()->route('carts.index')->with('error', 'Số dư Solana không đủ để thanh toán.');
        }

        // Tiến hành thanh toán: trừ số dư Solana của người dùng và cộng vào ví của admin
        $this->processPayment($user, $totalAmount);

        // Tạo đơn hàng mới
        $order = Order::create([
            'user_id' => $user->id,
            'quantity' => $totalQuantity,
            'total_amount' => $totalAmount,
            'status' => 1, // Chờ thanh toán
            'message' => 'Đơn hàng chờ thanh toán.',
        ]);

        // Thêm các chi tiết đơn hàng vào bảng order_details
        foreach ($cartItems as $item) {
            OrderDetail::create([
                'order_id' => $order->id,
                'ticket_id' => $item->ticket_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'total' => $item->total,
            ]);

            // Cập nhật số lượng sản phẩm trong bảng tickets
            $ticket = $item->ticket;
            $ticket->decrement('quantity', $item->quantity);
            $ticket->increment('sell_quantity', $item->quantity);
        }

        // Xóa các sản phẩm trong giỏ hàng
        CartItem::where('cart_id', $cart->id)->delete();

        return back()->route('carts.index')->with('success', 'Đơn hàng đã được tạo thành công!');
    }

    protected function getSolanaBalance($walletAddress)
    {
        // Logic để lấy số dư Solana từ địa chỉ ví (Phantom Wallet)
        // Đây là ví dụ đơn giản, bạn sẽ cần tích hợp với API Solana để lấy số dư thực tế
        return 10; // Trả về số dư giả sử
    }

    protected function processPayment($user, $totalAmount)
    {
        // Trừ số dư Solana của người dùng và chuyển vào ví của admin
        // Cập nhật số dư Solana của người dùng
        $this->updateWalletBalance($user->wallet, -$totalAmount);

        // Lấy thông tin ví của admin
        $admin = User::where('role', 2)->first();
        if ($admin) {
            $this->updateWalletBalance($admin->wallet, $totalAmount);
        }
    }

    protected function updateWalletBalance($walletAddress, $amount)
    {
        // Logic để cập nhật số dư ví trong database
        // Đây là ví dụ đơn giản, bạn sẽ cần tích hợp với API Solana để cập nhật số dư thực tế
    }
}
