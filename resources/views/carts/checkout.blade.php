@extends('LayoutClients.master')

@section('title')
    Carts
@endsection

@section('content_client')
    <div class="bg-light py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12 mb-0"><a href="{{ route('index') }}">Home</a> <span class="mx-2 mb-0">/</span> <a
                        href="{{ route('carts.index') }}">Cart</a> <span class="mx-2 mb-0">/</span> <strong
                        class="text-black">Checkout</strong>
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
                            <a href="#" id="proceed-to-checkout"
                                class="btn btn-primary btn-lg py-3 btn-block" style="font-size: 1.25rem;">Proceed To
                                Checkout</a>
                        </div>
                    </div>
                </div>
                <!-- End of Your Order Section -->
            </div>
        </div>
    </div>

    <script>
        // JavaScript to check wallet status and process checkout
        document.querySelector('#proceed-to-checkout').addEventListener('click', async function(e) {
            e.preventDefault(); // Stop the default button action

            if (!window.solana || !window.solana.isPhantom) {
                alert('Bạn cần cài đặt Phantom Wallet để thanh toán.');
                return;
            }

            const provider = window.solana;

            try {
                // Connect with Phantom Wallet
                const { publicKey } = await provider.connect();
                const userPublicKey = publicKey.toString();
                const adminWallet = "{{ $adminWallet }}"; // Admin wallet address
                const totalAmount = @json($totalAmount); // Total amount to be paid in USD

                // Convert totalAmount to SOL (replace this conversion logic as needed)
                const solAmount = totalAmount; // For simplicity, assume 1 USD = 1 SOL for now

                // Create Solana connection
                const connection = new solanaWeb3.Connection(solanaWeb3.clusterApiUrl('mainnet-beta'), 'confirmed');

                // Create transaction
                const transaction = new solanaWeb3.Transaction();

                // Admin wallet address (recipient)
                const recipient = new solanaWeb3.PublicKey(adminWallet);

                // User's public key (sender)
                const sender = new solanaWeb3.PublicKey(userPublicKey);

                // Create transfer instruction
                const transferInstruction = solanaWeb3.SystemProgram.transfer({
                    fromPubkey: sender,
                    toPubkey: recipient,
                    lamports: solanaWeb3.LAMPORTS_PER_SOL * solAmount, // Convert SOL to lamports
                });

                // Add instruction to transaction
                transaction.add(transferInstruction);

                // Sign transaction with Phantom Wallet
                const signedTransaction = await provider.signTransaction(transaction);

                // Send transaction
                const txId = await connection.sendRawTransaction(signedTransaction.serialize(), {
                    skipPreflight: false,
                    preflightCommitment: 'confirmed'
                });

                // Wait for transaction confirmation
                await connection.confirmTransaction(txId, 'confirmed');

                // Send transaction hash to the backend
                const response = await fetch("{{ route('orders.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        userPublicKey: userPublicKey,
                        cartItems: @json($cartItems),
                        totalAmount: @json($totalAmount),
                        adminWallet: adminWallet,
                        transactionHash: txId, // Send transaction hash to backend
                    }),
                });

                const result = await response.json();

                if (result.success) {
                    alert("Thanh toán thành công! Đơn hàng của bạn đã được xử lý.");
                    window.location.href = "{{ route('orders.index') }}";
                } else {
                    alert("Thanh toán thất bại, vui lòng thử lại.");
                }
            } catch (error) {
                alert("Có lỗi xảy ra trong quá trình thanh toán.");
                console.error("Error during transaction:", error);
            }
        });
    </script>
@endsection
