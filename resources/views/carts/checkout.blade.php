@extends('LayoutClients.master')

@section('title')
    Carts
@endsection

@section('content_client')
    <div class="bg-light py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12 mb-0"><a href="{{ route('index') }}">Home</a> <span class="mx-2 mb-0">/</span> <a
                        href="cart.html">Cart</a> <span class="mx-2 mb-0">/</span> <strong class="text-black">Checkout</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center">
        <div class="border p-4 rounded" role="alert">
            Returning customer? <a href="#">Click here</a> to login
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
                        @if (auth()->user()->wallet)
                            <div class="form-group mb-4">
                                <label for="phantom-wallet" class="d-flex align-items-center" style="font-size: 1.125rem;">
                                    <input type="radio" id="phantom-wallet" name="payment_method" value="phantom"
                                        style="margin-right: 10px;">
                                    Pay with Phantom Wallet
                                </label>
                                <div id="phantom-balance" class="mt-3" style="display: none;">
                                    <p style="font-size: 1.125rem;">Your Solana balance: <span
                                            id="sol-balance">Loading...</span> SOL</p>
                                </div>
                            </div>
                        @else
                            <div class="form-group mb-4">
                                <p class="text-danger" style="font-size: 1.125rem;">
                                    Please connect your Phantom Wallet to proceed with this payment option.
                                    <a href="{{ route('wallet.index') }}" class="btn btn-link btn-sm"
                                        style="font-size: 1.125rem;"
                                        onclick="return confirm('chuyển đén trang kết nối ví')">Connect Wallet</a>
                                </p>
                            </div>
                        @endif

                        <!-- Proceed to Checkout Button -->
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-lg py-3 btn-block"
                                style="font-size: 1.25rem;">Proceed To Checkout</button>
                        </div>
                    </div>
                </div>
                <!-- End of Your Order Section -->
            </div>
        </div>
    </div>

    <script>
        // Kiểm tra ví Phantom và hiển thị số dư
        if (window.solana && window.solana.isPhantom) {
            const phantomWallet = window.solana;

            const phantomRadio = document.getElementById('phantom-wallet');
            const balanceDiv = document.getElementById('phantom-balance');
            const balanceSpan = document.getElementById('sol-balance');

            phantomRadio.addEventListener('change', async () => {
                if (phantomRadio.checked) {
                    if (!{{ auth()->user()->wallet ? 'true' : 'false' }}) {
                        // Nếu ví chưa được kết nối, hỏi người dùng có muốn chuyển hướng đến trang kết nối ví không
                        const userConfirmed = confirm(
                            "You have not connected a Phantom Wallet. Would you like to connect your wallet?"
                        );
                        if (userConfirmed) {
                            window.location.href =
                            "{{ route('wallet.index') }}"; // Chuyển hướng đến trang kết nối ví
                        } else {
                            phantomRadio.checked = false; // Hủy chọn "Pay with Phantom Wallet"
                        }
                    } else {
                        try {
                            // Kết nối ví Phantom
                            await phantomWallet.connect();
                            const walletAddress = phantomWallet.publicKey.toString();
                            console.log("Connected to Phantom wallet: " + walletAddress);

                            // Kết nối đến Solana mạng
                            const connection = new solanaWeb3.Connection(solanaWeb3.clusterApiUrl(
                                'mainnet-beta'), 'confirmed');
                            const walletPublicKey = new solanaWeb3.PublicKey(walletAddress);

                            // Lấy số dư Solana từ ví
                            const balance = await connection.getBalance(walletPublicKey);
                            const solBalance = balance / solanaWeb3
                            .LAMPORTS_PER_SOL; // Chuyển đổi lamports sang SOL

                            balanceSpan.textContent = solBalance.toFixed(2); // Hiển thị số dư
                            balanceDiv.style.display = 'block'; // Hiển thị phần số dư

                            // Thông báo kết nối ví thành công
                            document.querySelector('.form-group.mb-4 p').textContent =
                                "Wallet connected successfully!";
                            document.querySelector('.form-group.mb-4 a').style.display =
                            'none'; // Ẩn nút kết nối ví
                        } catch (error) {
                            console.error("Error connecting to Phantom wallet: ", error);
                        }
                    }
                } else {
                    balanceDiv.style.display = 'none'; // Ẩn phần số dư nếu không chọn phương thức thanh toán
                }
            });
        } else {
            console.log("Phantom wallet is not installed");
        }
    </script>
@endsection
