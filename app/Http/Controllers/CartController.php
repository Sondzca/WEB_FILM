<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Ticket;
use Illuminate\Http\Request;


class CartController extends Controller
{
    public function index()
    {
        return view('carts.carts');
    }

    public function update(Request $request)
    {
        // Lấy giỏ hàng hiện tại từ session
        $cart = session()->get('cart', []);

        // Kiểm tra xem có dữ liệu quantity được gửi từ form không
        if ($request->has('quantity')) {
            // Duyệt qua các sản phẩm trong giỏ hàng
            foreach ($request->quantity as $id => $quantity) {
                if (isset($cart[$id])) {
                    // Cập nhật số lượng cho sản phẩm
                    $cart[$id]['quantity'] = max(1, (int)$quantity);
                    // Tính lại tổng giá tiền của từng sản phẩm
                    $cart[$id]['total'] = $cart[$id]['price'] * $cart[$id]['quantity'];
                }
            }

            // Lưu lại giỏ hàng đã cập nhật vào session
            session()->put('cart', $cart);

            // Chuyển hướng với thông báo thành công
            return redirect()->route('cart.index')->with('success', 'Cart updated successfully');
        }

        // Nếu không có dữ liệu quantity, chuyển hướng lại với lỗi
        return redirect()->back()->withErrors('No quantities provided for update.');
    }

    // In CartController.php

    public function remove($id)
    {
        $cart = session()->get('cart', []);

        // Remove item from cart by id
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        $subtotal = array_reduce($cart, function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);

        return response()->json([
            'success' => true,
            'subtotal' => $subtotal,
            'subtotalFormatted' => number_format($subtotal, 0, ',', '.') . ' VNĐ',
        ]);
    }




    public function addToCart(Request $request)
    {
        // Tìm vé theo ID
        $ticket = Ticket::findOrFail($request->ticket_id);

        // Lấy giỏ hàng từ session hoặc khởi tạo mảng rỗng nếu chưa có
        $cart = session()->get('cart', []);

        // Kiểm tra nếu vé đã có trong giỏ hàng
        if (isset($cart[$ticket->id])) {
            // Nếu đã tồn tại, tăng số lượng
            $cart[$ticket->id]['quantity']++;
        } else {
            // Nếu chưa có, thêm vé mới vào giỏ hàng
            $cart[$ticket->id] = [
                'id' => $ticket->id,
                'name' => $ticket->name,
                'price' => $ticket->price,
                'quantity' => 1,
                'image' => $ticket->image,
                'category' => $ticket->category->name
            ];
        }

        // Cập nhật giỏ hàng trong session
        session()->put('cart', $cart);

        // Chuyển hướng lại với thông báo thành công
        return redirect()->back()->with('success', 'Đã thêm vé vào giỏ hàng thành công!');
    }
}