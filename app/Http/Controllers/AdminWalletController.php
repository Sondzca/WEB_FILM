<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AdminWalletController extends Controller
{

    public function getTransactionHistory($walletAddress)
    {
        try {
            // Solscan API endpoint
            $url = "https://public-api.solscan.io/account/transactions?account=$walletAddress";
    
            // Gửi request đến Solscan API để lấy lịch sử giao dịch
            $response = Http::get($url);
            $data = $response->json();
    
            // Kiểm tra dữ liệu trả về và lấy 5 giao dịch gần nhất
            if (isset($data['data']) && count($data['data']) > 0) {
                $transactions = array_slice($data['data'], 0, 5);  // Lấy 5 giao dịch gần nhất
            } else {
                $transactions = [];  // Không có giao dịch, trả về mảng trống
            }
        } catch (\Exception $e) {
            // Nếu có lỗi, trả về mảng trống thay vì chuỗi lỗi
            $transactions = [];
        }
    
        return $transactions;
    }
    
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
    $walletAddress = Auth::user()->wallet;

    if ($walletAddress) {
        // Gọi phương thức getWalletBalance để lấy số dư ví
        $balance = $this->getWalletBalance($walletAddress);

        // Gọi phương thức lấy lịch sử giao dịch
        $transactions = $this->getTransactionHistory($walletAddress);
    } else {
        $balance = null;
        $transactions = [];
    }
    
    return view('wallet.adminWallet', compact('walletAddress', 'balance', 'transactions')); // Đảm bảo truyền transactions vào view
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
