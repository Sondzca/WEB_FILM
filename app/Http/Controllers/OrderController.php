<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Solana\RpcClient\PublicKey;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{

    public function getWalletBalance($walletAddress)
    {
        try {
            // Kiểm tra nếu ví rỗng hoặc không hợp lệ
            if (empty($walletAddress)) {
                return 'Địa chỉ ví không hợp lệ.';
            }

            // Địa chỉ RPC của Solana Mainnet
            $rpcEndpoint = "https://api.mainnet-beta.solana.com";

            // Payload yêu cầu lấy số dư
            $payload = [
                "jsonrpc" => "2.0",
                "id" => 1,
                "method" => "getBalance",
                "params" => [$walletAddress]
            ];

            // Gửi request đến Solana RPC để lấy số dư
            $response = Http::post($rpcEndpoint, $payload);

            if ($response->successful()) {
                $data = $response->json();

                // Kiểm tra kết quả và chuyển đổi từ Lamport sang SOL
                if (isset($data['result']['value'])) {
                    $balanceLamport = $data['result']['value'];
                    $balanceSOL = $balanceLamport / 1000000000; // Chuyển Lamport sang SOL
                    return number_format($balanceSOL, 2); // Trả về số dư SOL
                } else {
                    return 'Không thể lấy số dư từ RPC.';
                }
            } else {
                return 'Lỗi kết nối đến Solana RPC.';
            }
        } catch (\Exception $e) {
            // Log lỗi để theo dõi
            Log::error("Lỗi khi lấy số dư ví: " . $e->getMessage());
            return 'Có lỗi xảy ra khi kết nối đến RPC: ' . $e->getMessage();
        }
    }

    public function index()
    {
        try {
            // Lấy ví của Admin
            $adminWallet = User::where('role', 2)->value('wallet');

            // Sử dụng giá trị mặc định nếu không có ví Admin
            if (!$adminWallet) {
                $adminWallet = 'Ví admin không tồn tại';
            }

            $user = auth()->user();

            if (!$user) {
                return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục.');
            }

            // Lấy giỏ hàng của người dùng
            $cart = Cart::where('user_id', $user->id)->first();
            $cartItems = $cart ? CartItem::where('cart_id', $cart->id)->with('ticket')->get() : collect();
            $totalPrice = $cartItems->sum('total');

            // Kiểm tra ví người dùng
            $hasWallet = !is_null($user->wallet);
            $walletBalance = $hasWallet ? $this->getWalletBalance($user->wallet) : 'Ví chưa kết nối';

            // Trả dữ liệu về view
            return view('carts.checkout', compact('user', 'adminWallet', 'cartItems', 'totalPrice', 'hasWallet', 'walletBalance'));
        } catch (\Exception $e) {
            // Log lỗi để dễ dàng debug
            Log::error("Lỗi trong phương thức index: " . $e->getMessage());
            return back()->with('error', 'Đã xảy ra lỗi, vui lòng thử lại sau.');
        }
    }

    public function store(Request $request)
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return redirect()->route('carts.index')->with('error', 'Vui lòng đăng nhập để thực hiện hành động này.');
            }

            $userPublicKey = $request->input('userPublicKey');
            $cartItems = $request->input('cartItems');
            $totalAmount = $request->input('totalAmount');
            $adminWallet = $request->input('adminWallet');
            $transactionHash = $request->input('transactionHash');

            // Kiểm tra dữ liệu đầu vào
            if (empty($userPublicKey) || empty($cartItems) || empty($totalAmount) || empty($transactionHash)) {
                return redirect()->route('carts.index')->with('error', 'Thông tin đơn hàng không đầy đủ.');
            }

            // Lưu thông tin đơn hàng vào bảng orders
            $order = Order::create([
                'user_id' => $user->id,
                'transaction_hash' => $transactionHash,
                'quantity' => collect($cartItems)->sum('quantity'),
                'total_amount' => $totalAmount,
                'status' => 1, // Đơn hàng đang chờ xử lý
            ]);

            // Lưu chi tiết đơn hàng
            foreach ($cartItems as $item) {
                if (isset($item['ticket_id'], $item['quantity'], $item['price'], $item['total'])) {
                    OrderDetail::create([
                        'order_id' => $order->id,
                        'ticket_id' => $item['ticket_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'total' => $item['total'],
                    ]);
                } else {
                    Log::warning('Dữ liệu sản phẩm không hợp lệ: ' . json_encode($item));
                }
            }

            return redirect()->route('carts.index')->with('success', 'Đặt hàng thành công.');
        } catch (\Exception $e) {
            // Log lỗi để kiểm tra
            Log::error("Lỗi trong phương thức store: " . $e->getMessage());
            return redirect()->route('carts.index')->with('error', 'Đã xảy ra lỗi khi lưu đơn hàng: ' . $e->getMessage());
        }
    }
}
