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
                            <a href="#" id="proceed-to-checkout" class="btn btn-primary btn-lg py-3 btn-block"
                                style="font-size: 1.25rem;">Proceed To
                                Checkout</a>
                        </div>
                    </div>
                </div>
                <!-- End of Your Order Section -->
            </div>
        </div>
    </div>

    <script>
        document.querySelector('#proceed-to-checkout').addEventListener('click', async function(e) {
            e.preventDefault();

            if (!window.solana || !window.solana.isPhantom) {
                alert('Bạn cần cài đặt Phantom Wallet để thanh toán.');
                return;
            }

            const provider = window.solana;

            try {
                // Kết nối Phantom Wallet
                const {
                    publicKey
                } = await provider.connect();
                const userPublicKey = publicKey.toString();
                const adminWallet = "{{ $adminWallet }}";

                // Xác thực khóa công khai
                try {
                    const sender = new solanaWeb3.PublicKey(userPublicKey);
                    const recipient = new solanaWeb3.PublicKey(adminWallet);
                } catch (e) {
                    alert("Public key không hợp lệ. Vui lòng kiểm tra lại.");
                    console.error(e);
                    return;
                }

                const totalAmount = @json($totalAmount);
                const solAmount = totalAmount / 25; // Tỷ giá USD/SOL giả định

                if (solAmount <= 0) {
                    alert("Tổng tiền thanh toán không hợp lệ.");
                    return;
                }

                const connection = new solanaWeb3.Connection(solanaWeb3.clusterApiUrl('testnet'), 'confirmed');
                const balance = await connection.getBalance(new solanaWeb3.PublicKey(userPublicKey));
                if (balance < solanaWeb3.LAMPORTS_PER_SOL * solAmount) {
                    alert("Không đủ SOL để thực hiện giao dịch.");
                    return;
                }

                const {
                    blockhash
                } = await connection.getLatestBlockhash();
                const transaction = new solanaWeb3.Transaction({
                    recentBlockhash: blockhash,
                    feePayer: new solanaWeb3.PublicKey(userPublicKey),
                });

                const transferInstruction = solanaWeb3.SystemProgram.transfer({
                    fromPubkey: new solanaWeb3.PublicKey(userPublicKey),
                    toPubkey: new solanaWeb3.PublicKey(adminWallet),
                    lamports: solanaWeb3.LAMPORTS_PER_SOL * solAmount,
                });

                transaction.add(transferInstruction);

                const signedTransaction = await provider.signTransaction(transaction);
                const txId = await connection.sendRawTransaction(signedTransaction.serialize(), {
                    skipPreflight: false,
                    preflightCommitment: 'confirmed',
                });

                await connection.confirmTransaction(txId, 'confirmed');

                // Gửi transaction hash đến backend
                const response = await fetch("{{ route('orders.store') }}", {   
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        userPublicKey: userPublicKey,
                        cartItems: @json($cartItems),
                        totalAmount: totalAmount,
                        adminWallet: adminWallet,
                        transactionHash: txId,
                    }),
                });

                // Chờ phản hồi từ backend, không cần JSON nếu bạn chuyển hướng từ server
                if (response.redirected) {
                    window.location.href = response.url; // Chuyển hướng nếu backend yêu cầu
                } else {
                    const result = await response.json();
                    if (result.success) {
                        alert("Thanh toán thành công! Đơn hàng của bạn đã được xử lý.");
                        window.location.href = "{{ route('carts.index') }}";
                    } else {
                        alert(result.message || "Thanh toán thất bại, vui lòng thử lại.");
                    }
                }
            } catch (error) {
                alert("Có lỗi xảy ra trong quá trình thanh toán.");
                console.error("Error during transaction:", error);
            }
        });
    </script>
@endsection
