<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class AdminWalletController extends Controller
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


    public function getTransactionDetailsDevnet($transactionHash)
    {
        try {
            $rpcEndpoint = "https://api.devnet.solana.com"; // RPC endpoint cho devnet

            $payload = [
                "jsonrpc" => "2.0",
                "id" => 1,
                "method" => "getTransaction",
                "params" => [$transactionHash, "json"] // "json" để lấy thông tin chi tiết
            ];

            // Gửi yêu cầu tới RPC
            $response = Http::post($rpcEndpoint, $payload);
            $data = $response->json();

            if (isset($data['result'])) {
                $transaction = $data['result'];

                // Lấy thông tin người gửi (signer) và danh sách người nhận
                $sender = $transaction['transaction']['message']['accountKeys'][0] ?? null;
                $receivers = [];
                foreach ($transaction['meta']['postTokenBalances'] ?? [] as $balance) {
                    $receivers[] = $balance['owner'];
                }

                return [
                    'sender' => $sender,
                    'receivers' => $receivers,
                ];
            } else {
                return [
                    'error' => 'Không tìm thấy thông tin giao dịch.',
                ];
            }
        } catch (\Exception $e) {
            return [
                'error' => 'Lỗi khi kết nối RPC: ' . $e->getMessage(),
            ];
        }
    }



    public function index()
    {
        $walletAddress = Auth::user()->wallet;

        if ($walletAddress) {
            // Gọi phương thức getWalletBalance để lấy số dư ví
            $balance = $this->getWalletBalance($walletAddress);

            // Lấy địa chỉ ví từ bảng users
            $walletAddress = Auth::user()->wallet;

            // Query bảng orders với user_id từ Auth
            $orders = DB::table('orders')
                ->where('user_id', Auth::id()) // Lọc theo user_id
                ->orderBy('created_at', 'desc') // Lấy giao dịch mới nhất trước
                ->get(['transaction_hash']);


            $transactions = [];
            foreach ($orders as $order) {
                $details = $this->getTransactionDetailsDevnet($order->transaction_hash);
                $transactions[] = $details;
            }
        } else {
            $balance = null;
            $transactions = [];
        }

        return view('wallet.adminWallet', compact('walletAddress', 'balance', 'transactions'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'wallet' => 'required|string|unique:users,wallet',
        ]);

        // Lưu địa chỉ ví vào bảng users
        /**
         * @var User $user
         */
        $user = Auth::user();
        $user->wallet = $request->wallet;
        $user->save();

        return back()->with('success', 'Wallet address saved successfully.');
    }

    public function destroy($userId)
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        if ($user->id == $userId) {
            // Xóa địa chỉ ví khỏi cơ sở dữ liệu
            $user->wallet = null;
            $user->save();

            // Quay lại trang trước đó và hiển thị thông báo thành công
            return redirect()->back()->with('success', 'Wallet disconnected successfully.');
        }

        // Trả về thông báo lỗi nếu hành động không được ủy quyền
        return redirect()->back()->with('error', 'Unauthorized action.');
    }
}
