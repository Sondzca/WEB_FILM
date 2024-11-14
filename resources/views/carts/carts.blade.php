@extends('LayoutClients.master')

@section('title')
    Cart
@endsection

@section('content_client')
    <div class="bg-light py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12 mb-0"><a href="">Home</a> <span class="mx-2 mb-0">/</span> <strong
                        class="text-black">Cart</strong></div>
            </div>
        </div>
    </div>

    <div class="site-section">
        <div class="container">
            <div class="row mb-5">
                <form class="col-md-12" action="" method="POST">
                    @csrf
                    <div class="site-blocks-table">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="product-thumbnail">Image</th>
                                    <th class="product-name">Ticket</th>
                                    <th class="product-price">Price</th>
                                    <th class="product-quantity">Quantity</th>
                                    <th class="product-total">Total</th>
                                    <th class="product-remove">Remove</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $subtotal = 0;
                                @endphp

                                @forelse($cartItems as $item)
                                    <tr data-id="{{ $item->id }}">
                                        <td class="product-thumbnail">
                                            @if ($item->ticket)
                                                <img src="{{ asset('storage/' . $item->ticket->image) }}"
                                                    alt="{{ $item->ticket->name }}" class="img-fluid">
                                            @else
                                                <img src="{{ asset('storage/default-image.jpg') }}"
                                                    alt="Default Ticket Image" class="img-fluid">
                                            @endif
                                        </td>
                                        <td class="product-name">
                                            <h2 class="h5 text-black">{{ $item->ticket->name }}</h2>
                                        </td>
                                        <td class="product-price">{{ number_format($item->ticket->price, 0, ',', '.') }} VNĐ
                                        </td>
                                        <td>
                                            <div class="input-group mb-3" style="max-width: 120px;">
                                                <div class="input-group-prepend">
                                                    <button class="btn btn-outline-primary js-btn-minus"
                                                        type="button">&minus;</button>
                                                </div>
                                                <input type="number" class="form-control text-center quantity"
                                                    name="quantity[{{ $item->id }}]" value="{{ $item->quantity }}"
                                                    min="1" aria-label="Quantity"
                                                    data-price="{{ $item->ticket->price }}">
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-primary js-btn-plus"
                                                        type="button">&plus;</button>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="product-total">
                                            {{ number_format($item->quantity * $item->ticket->price, 0, ',', '.') }} VNĐ
                                        </td>
                                        <td class="product-remove">
                                            <button type="button" class="btn btn-danger btn-sm remove-item"
                                                data-id="{{ $item->id }}">X</button>
                                        </td>
                                    </tr>
                                    @php
                                        $subtotal += $item->quantity * $item->ticket->price;
                                    @endphp
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Your cart is empty</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>

            <div class="row">
                <div class="col-md-6">
                    {{-- <button class="btn btn-primary btn-sm btn-block">Update Cart</button> --}}
                    <a href="{{ route('index') }}" class="btn btn-outline-primary btn-sm btn-block">Continue Shopping</a>
                </div>

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

    <!-- AJAX Script for Update Quantity and Remove Item -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
            if (!csrfTokenMeta) {
                console.error("CSRF token meta tag not found!");
                return;
            }

            const csrfToken = csrfTokenMeta.getAttribute('content');

            // Hàm cập nhật subtotal và total
            function updateCartTotals(subtotal) {
                document.getElementById('subtotal').textContent = numberWithCommas(subtotal) + ' VNĐ';
                document.getElementById('total').textContent = numberWithCommas(subtotal) + ' VNĐ';
            }

            // Hàm định dạng số có dấu phân cách
            function numberWithCommas(x) {
                return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }

            // Sự kiện tăng/giảm số lượng
            document.querySelectorAll('.js-btn-plus, .js-btn-minus').forEach(button => {
                button.addEventListener('click', function() {
                    const inputField = this.closest('td').querySelector('.quantity');
                    let currentQuantity = parseInt(inputField.value);

                    // Điều chỉnh số lượng tăng hoặc giảm từng số 1
                    let newQuantity = this.classList.contains('js-btn-plus') ? currentQuantity + 0 :
                        currentQuantity - 0;

                    // Đảm bảo số lượng không giảm xuống dưới 1
                    if (newQuantity < 1) return;

                    const cartItemId = this.closest('tr').dataset.id;
                    const price = parseInt(inputField.dataset.price);

                    // Cập nhật số lượng ngay trên giao diện
                    inputField.value = newQuantity;

                    // Cập nhật tổng cho sản phẩm
                    const totalCell = this.closest('tr').querySelector('.product-total');
                    totalCell.textContent = numberWithCommas(newQuantity * price) + ' VNĐ';

                    // Gửi yêu cầu cập nhật số lượng đến server
                    fetch(`/user/carts/${cartItemId}`, {
                            method: 'PUT',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                quantity: newQuantity
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Cập nhật lại tổng giỏ hàng từ server mà không cần reload trang
                                updateCartTotals(data.subtotal);
                            } else {
                                alert('Không thể cập nhật số lượng');
                            }
                        })
                        .catch(error => console.error('Error:', error));
                });
            });



            // Sự kiện xóa sản phẩm
            document.querySelectorAll('.remove-item').forEach(button => {
                button.addEventListener('click', function() {
                    const itemId = this.dataset.id;

                    if (confirm("Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?")) {
                        fetch(`/user/carts/${itemId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Content-Type': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Xóa dòng sản phẩm trên giao diện
                                    this.closest('tr').remove();
                                    updateCartTotals(data.subtotal);
                                } else {
                                    alert('Không thể xóa sản phẩm');
                                }
                            })
                            .catch(error => console.error('Error:', error));
                    }
                });
            });
        });
    </script>
@endsection
