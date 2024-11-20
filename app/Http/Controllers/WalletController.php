<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{

    public function index()
    {
        $wallet = Auth::user()->wallet;

        return view('wallet.wallet', compact('wallet'));
    }


    public function create() {}


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

        return redirect()->route('wallet.index')->with('status', 'Wallet address saved successfully.');
    }


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
        /**
         * @var User $user
         */
        // Lấy thông tin người dùng hiện tại
        $user = auth()->user();

        // Kiểm tra xem ví có tồn tại
        if ($user->wallet) {
            // Xóa địa chỉ ví trong cơ sở dữ liệu
            $user->wallet = null;
            $user->save();

            // Xử lý các thay đổi trong session hoặc localStorage
            $request->session()->flash('message', 'Wallet disconnected successfully.');
            return redirect()->route('wallet.index');  // Redirect về trang ví hoặc trang nào đó
        }

        // Nếu không có ví, hiển thị thông báo lỗi
        $request->session()->flash('error', 'No wallet connected.');
        return redirect()->route('wallet.index');
    }


    public function destroy($userId)
    {
        /**
         * @var User $user
         */
        // Kiểm tra xem người dùng hiện tại có phải là người sở hữu ví này không
        $user = Auth::user();

        if ($user->id == $userId) {
            // Xóa địa chỉ ví khỏi bảng users
            $user->wallet = null; // Giả sử bạn lưu ví trong trường `wallet`
            $user->save();

            return redirect()->back()->with('success', 'Wallet disconnected successfully.');
        }

        return redirect()->back()->with('error', 'Unauthorized action.');
    }
}
