@extends('LayoutClients.master')

@section('title')
    Carts
@endsection

@section('content_client')
    <script src="https://cdn.jsdelivr.net/npm/@solana/web3.js@latest/dist/solana-web3.min.js"></script>
    
    <div class="bg-light py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12 mb-0"><a href="{{ route('index') }}">Home</a> <span class="mx-2 mb-0">/</span> <a
                        href="{{ route('carts.index') }}">Cart</a> <span class="mx-2 mb-0">/</span> <strong class="text-black">Checkout</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="site-section text-center">
        <div class="container">
            <div class="row">
                <!-- Your Order Section -->
                <div class="col-md-8 offset-md-2">
                    <h2 class="h3 mb-5 text-black" style="font-size: 2.5rem;">Your Order</h2>
                    <div class="p-4 p-lg-5 border" style="background-color: #f9f9f9;">
                        <ul class="list-unstyled">
                            <li class="d-flex mb-4">
                                <span class="text-black" style="font-size: 1.25rem; font-weight: bold;">Product</span>
                                <span class="text-black ml-auto" style="font-size: 1.25rem; font-weight: bold;">Total</span>
                            </li>

                            <!-- Loop through the cart items -->
                            @php $totalAmount = 0; @endphp
                            @foreach ($cartItems as $item)
                                @php
                                    $itemTotal = $item->quantity * $item->ticket->price; // Calculate the total for this item
                                    $totalAmount += $itemTotal; // Add the item total to the overall total
                                @endphp
                                <li class="d-flex mb-4 align-items-center">
                                    <div class="d-flex align-items-center" style="width: 60%;">
                                        <img src="{{ asset('storage/' . $item->ticket->image) }}"
                                            alt="{{ $item->ticket->name }}"
                                            style="width: 50px; height: 50px; object-fit: cover; margin-right: 15px;">
                                        <span class="text-black"
                                            style="font-size: 1.125rem;">{{ $item->ticket->name }}</span>
                                        <span class="text-muted ml-3">x{{ $item->quantity }}</span>
                                    </div>
                                    <span class="text-black ml-auto"
                                        style="font-size: 1.125rem;">${{ number_format($itemTotal, 2) }}</span>
                                </li>
                            @endforeach

                            <!-- Total Amount -->
                            <li class="d-flex mb-4">
                                <span class="text-black" style="font-size: 1.25rem; font-weight: bold;">Total Amount</span>
                                <span class="text-black ml-auto" style="font-size: 1.25rem; font-weight: bold;">
                                    ${{ number_format($totalAmount, 2) }}
                                </span>
                            </li>
                        </ul>

                        <!-- Payment Option: Phantom Wallet -->
                        @if ($hasWallet)
                            <div class="form-group mb-4">
                                <label for="phantom-wallet" class="d-flex align-items-center" style="font-size: 1.125rem;">
                                    <input type="radio" id="phantom-wallet" name="payment_method" value="phantom"
                                        style="margin-right: 10px;">
                                    Pay with Phantom Wallet
                                </label>
                                <div id="phantom-balance" class="mt-3">
                                    <p style="font-size: 1.125rem;">Your Solana balance: <span
                                            id="sol-balance">{{ number_format($walletBalance, 2) }} SOL</span></p>
                                </div>
                            </div>
                        @else
                            <div class="form-group mb-4">
                                <p class="text-danger" style="font-size: 1.125rem;">
                                    Please connect your Phantom Wallet to proceed with this payment option.
                                    <a href="{{ route('wallet.index') }}" class="btn btn-link btn-sm"
                                        style="font-size: 1.125rem;" 
                                        onclick="return confirm('chuyển đến trang kết nối ví')">Connect Wallet</a>
                                </p>
                            </div>
                        @endif

                        <!-- Proceed to Checkout Button -->
                        <div class="form-group">
                            <a href="{{route('orders.store')}}" type="submit" id="phantom-wallet" class="btn btn-primary btn-lg py-3 btn-block"
                                style="font-size: 1.25rem;">Proceed To Checkout</a>
                        </div>
                    </div>
                </div>
                <!-- End of Your Order Section -->
            </div>
        </div>
    </div>

    <script>
        // JavaScript để kiểm tra trạng thái ví và hiển thị cảnh báo
        document.querySelector('#phantom-wallet').addEventListener('click', function() {
            @if (!$hasWallet)
                confirm('You need to connect your Phantom Wallet to proceed.');
                window.location.href = "{{ route('wallet.index') }}";
            @endif
        });
    </script>
@endsection
