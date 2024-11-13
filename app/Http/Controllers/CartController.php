<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
{
    /**
     * @var User $user
     */
    $user = auth()->user();
    $cart = $user->cart()->first();

    if (!$cart) {
        $cart = Cart::create(['user_id' => $user->id]);
    }

    // Eager load mối quan hệ 'ticket' để lấy thông tin ticket ngay lập tức
    $cartItems = CartItem::with('ticket')->where('cart_id', $cart->id)->get();

    $totalQuantity = $cartItems->sum('quantity');
    $subtotal = $cartItems->sum(function ($item) {
        return $item->ticket ? $item->quantity * $item->ticket->price : 0;
    });
   
    return view('carts.carts', compact('cartItems', 'totalQuantity', 'subtotal'));
}


    public function addToCart(Request $request)
    {
        $ticketId = $request->ticket_id;

        if (!$ticketId) {
            return redirect()->back()->with('error', 'Ticket ID is required!');
        }
        $ticket = Ticket::find($ticketId);

        if (!$ticket) {
            return redirect()->back()->with('error', 'Ticket not found!');
        }
        $user = auth()->user();
        $cart = Cart::firstOrCreate(
            ['user_id' => $user->id],
            ['user_id' => $user->id]
        );
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('ticket_id', $ticket->id)
            ->first();
        if ($cartItem) {
            $cartItem->quantity += 1;
        } else {
            $cartItem = new CartItem([
                'cart_id' => $cart->id,
                'ticket_id' => $ticket->id,
                'quantity' => 1,
                'price' => $ticket->price,
            ]);
        }
        $cartItem->total = $cartItem->quantity * $cartItem->price;
        $cartItem->save();

        return redirect()->back()->with('success', 'Ticket added to cart successfully!');
    }

    public function update(Request $request, $cartItemId)
    {
        $cartItem = CartItem::findOrFail($cartItemId);
        $newQuantity = $request->input('quantity');

        // Cập nhật số lượng
        $cartItem->update(['quantity' => $newQuantity]);

        // Tính toán lại tổng tiền của giỏ hàng
        $cart = $cartItem->cart;
        $subtotal = $cart->items->sum(function ($item) {
            return $item->quantity * $item->ticket->price;
        });

        return response()->json([
            'success' => true,
            'subtotal' => $subtotal,
            'subtotalFormatted' => number_format($subtotal, 0, ',', '.') . ' VNĐ',
            'total' => $subtotal // Cập nhật total (nếu cần)
        ]);
    }

    // Xóa sản phẩm khỏi giỏ hàng
    // CartController.php

    public function destroy($id)
    {
        $cartItem = CartItem::find($id);

        if ($cartItem) {
            // Xóa sản phẩm khỏi giỏ hàng
            $cartItem->delete();

            // Tính lại tổng giỏ hàng
            $subtotal = CartItem::where('user_id', auth()->id())->sum(DB::raw('quantity * price'));

            // Quay lại trang giỏ hàng và trả về thông báo
            return back()->with('success', 'Sản phẩm đã được xóa khỏi giỏ hàng')->with('subtotal', $subtotal);
        }

        return back()->with('error', 'Không tìm thấy sản phẩm trong giỏ hàng');
    }
}
