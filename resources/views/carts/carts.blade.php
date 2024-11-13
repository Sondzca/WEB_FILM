@extends('LayoutClients.master')

@section('title')
    Cart
@endsection

@section('content_client')
    <div class="bg-light py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12 mb-0"><a href="{{ route('index') }}">Home</a> <span class="mx-2 mb-0">/</span> <strong
                        class="text-black">Cart</strong></div>
            </div>
        </div>
    </div>

    <div class="site-section">
        <div class="container">
            <div class="row mb-5">
                <form class="col-md-12" action="{{ route('cart.update') }}" method="POST">
                    @csrf
                    <div class="site-blocks-table">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="product-thumbnail">Image</th>
                                    <th class="product-name">Product</th>
                                    <th class="product-price">Price</th>
                                    <th class="product-quantity">Quantity</th>
                                    <th class="product-total">Total</th>
                                    <th class="product-remove">Remove</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $cart = session()->get('cart', []);
                                    $subtotal = 0;
                                @endphp

                                @forelse($cart as $item)
                                    <tr data-id="{{ $item['id'] }}">
                                        <td class="product-thumbnail">
                                            <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}"
                                                class="img-fluid">
                                        </td>
                                        <td class="product-name">
                                            <h2 class="h5 text-black">{{ $item['name'] }}</h2>
                                        </td>
                                        <td class="product-price">{{ number_format($item['price'], 0, ',', '.') }} VNĐ</td>
                                        <td>
                                            <div class="input-group mb-3" style="max-width: 120px;">
                                                <div class="input-group-prepend">
                                                    <button class="btn btn-outline-primary js-btn-minus"
                                                        type="button">&minus;</button>
                                                </div>
                                                <input type="number" class="form-control text-center quantity"
                                                    name="quantity[{{ $item['id'] }}]" value="{{ $item['quantity'] }}"
                                                    min="1" aria-label="Quantity" data-price="{{ $item['price'] }}">
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-primary js-btn-plus"
                                                        type="button">&plus;</button>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="product-total">
                                            {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }} VNĐ
                                        </td>
                                        <td class="product-remove">
                                            <button type="button" class="btn btn-danger btn-sm remove-item"
                                                data-id="{{ $item['id'] }}">X</button>
                                        </td>
                                    </tr>
                                    @php
                                        $subtotal += $item['price'] * $item['quantity'];
                                    @endphp
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Your cart is empty</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <button class="btn btn-primary btn-sm btn-block">Update Cart</button>
                    <a href="{{ route('index') }}" class="btn btn-outline-primary btn-sm btn-block">Continue Shopping</a>
                </div>
                </form>

                <div class="col-md-6 pl-5">
                    <div class="row justify-content-end">
                        <div class="col-md-7">
                            <div class="row">
                                <div class="col-md-12 text-right border-bottom mb-5">
                                    <h3 class="text-black h4 text-uppercase">Cart Totals</h3>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <span class="text-black">Subtotal</span>
                                </div>
                                <div class="col-md-6 text-right">
                                    <strong class="text-black" id="subtotal">{{ number_format($subtotal, 0, ',', '.') }}
                                        VNĐ</strong>
                                </div>
                            </div>
                            <div class="row mb-5">
                                <div class="col-md-6">
                                    <span class="text-black">Total</span>
                                </div>
                                <div class="col-md-6 text-right">
                                    <strong class="text-black" id="total">{{ number_format($subtotal, 0, ',', '.') }}
                                        VNĐ</strong>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-primary btn-lg py-3 btn-block">Proceed To Checkout</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- AJAX Script for Remove Item -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
            if (!csrfTokenMeta) {
                console.error("CSRF token meta tag not found!");
                return; // Dừng nếu không có CSRF token
            }

            const csrfToken = csrfTokenMeta.getAttribute('content');

            document.querySelectorAll('.remove-item').forEach(button => {
                button.addEventListener('click', function() {
                    const itemId = this.dataset.id;

                    // Thêm thông báo xác nhận trước khi xóa
                    const isConfirmed = confirm(
                        "Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng không?");
                    if (!isConfirmed) {
                        return; // Dừng lại nếu người dùng chọn "Cancel"
                    }

                    // Nếu người dùng xác nhận, tiến hành xóa sản phẩm
                    fetch(`/cart/remove/${itemId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                document.querySelector(`tr[data-id="${itemId}"]`).remove();
                                document.getElementById('subtotal').textContent = data
                                    .subtotalFormatted;
                                document.getElementById('total').textContent = data
                                    .subtotalFormatted;
                            } else {
                                alert('Failed to remove item');
                            }
                        })
                        .catch(error => console.error('Error:', error));
                });
            });
        });
    </script>
@endsection
