@extends('LayoutClients.master')

@section('title')
    Carts
@endsection

@section('content_client')
    <div class="bg-light py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12 mb-0">
                    <a href="{{ route('index') }}">Home</a>
                    <span class="mx-2 mb-0">/</span>
                    <strong class="text-black">Cart</strong>
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
                                    $itemTotal = $item->quantity * $item->ticket->price;
                                    $totalAmount += $itemTotal;
                                @endphp
                                <li class="d-flex mb-4 align-items-center">
                                    <div class="d-flex align-items-center" style="width: 60%;">
                                        <img src="{{ asset('storage/' . $item->ticket->image) }}"
                                             alt="{{ $item->ticket->name }}"
                                             style="width: 50px; height: 50px; object-fit: cover; margin-right: 15px;">
                                        <span class="text-black" style="font-size: 1.125rem;">{{ $item->ticket->name }}</span>
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

                        <!-- Proceed to Checkout Button -->
                        <div class="form-group">
                            <button type="button" id="pay-now" class="btn btn-primary btn-lg py-3 btn-block"
                                    style="font-size: 1.25rem;" disabled>Proceed To Checkout</button>
                        </div>
                    </div>
                </div>
                <!-- End of Your Order Section -->
            </div>
        </div>
    </div>

    <!-- Include Solana Web3.js -->
    <script src="https://cdn.jsdelivr.net/npm/@solana/web3.js@2.1.0-canary-20241115192830/dist/solana-web3.min.js"></script>

    <script>
        let walletAddress = '';
        const totalAmount = {{ $totalAmount }}; // Số tiền cần thanh toán

        // Kiểm tra ví Phantom đã kết nối và lấy thông tin ví từ localStorage
        if (window.localStorage.getItem('walletAddress')) {
            walletAddress = window.localStorage.getItem('walletAddress');
            console.log('Wallet already connected: ', walletAddress);
            document.getElementById('phantom-wallet').checked = true;
            getBalance(walletAddress);  // Lấy số dư ví
        }

        // Kiểm tra ví Phantom
        if (window.solana && window.solana.isPhantom) {
            const phantomWallet = window.solana;

            phantomWallet.on('connect', async () => {
                walletAddress = phantomWallet.publicKey.toString();
                console.log('Connected to Phantom Wallet:', walletAddress);
                window.localStorage.setItem('walletAddress', walletAddress); // Lưu địa chỉ ví vào localStorage
                await getBalance(walletAddress); // Lấy số dư
            });

            phantomWallet.on('disconnect', () => {
                walletAddress = '';
                document.getElementById('sol-balance').textContent = 'Not connected';
                document.getElementById('pay-now').disabled = true;
                window.localStorage.removeItem('walletAddress'); // Xóa ví khỏi localStorage
            });

            async function connectWallet() {
                try {
                    await phantomWallet.connect();
                } catch (err) {
                    console.error('Error connecting wallet:', err);
                }
            }

            async function getBalance(walletAddress) {
                // Sử dụng lớp Connection và PublicKey trực tiếp
                const connection = new solanaWeb3.Connection(
                    solanaWeb3.clusterApiUrl('devnet'),
                    'confirmed'
                );
                try {
                    const publicKey = new solanaWeb3.PublicKey(walletAddress);
                    const balance = await connection.getBalance(publicKey);
                    const solBalance = balance / solanaWeb3.LAMPORTS_PER_SOL;

                    document.getElementById('sol-balance').textContent = `${solBalance.toFixed(2)} SOL`;

                    if (solBalance >= totalAmount) {
                        document.getElementById('pay-now').disabled = false;
                        alert('Sufficient balance for payment.');
                    } else {
                        alert('Insufficient balance in your Phantom Wallet.');
                    }
                } catch (error) {
                    console.error('Error fetching balance:', error);
                    document.getElementById('sol-balance').textContent = 'Error fetching balance';
                }
            }

            document.getElementById('phantom-wallet').addEventListener('change', () => {
                if (document.getElementById('phantom-wallet').checked) {
                    connectWallet();
                }
            });
        } else {
            alert('Phantom Wallet is not installed!');
        }
    </script>
@endsection
