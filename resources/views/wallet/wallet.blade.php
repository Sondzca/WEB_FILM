@extends('LayoutUser.master')

@section('title')
    Dashboard
@endsection

@section('content')
<div class="container">
    <h1>Connect Phantom Wallet</h1>

    <!-- Hiển thị trạng thái nếu có -->
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <!-- Nút kết nối ví Phantom -->
    <button id="connectWalletButton" class="btn btn-primary">Connect Wallet</button>
    <p id="walletAddress" class="mt-3">Wallet Address: Not connected</p>
</div>

@section('scripts')
<script>
    document.getElementById('connectWalletButton').addEventListener('click', async () => {
        if (window.solana && window.solana.isPhantom) {
            try {
                // Kết nối ví Phantom và lấy địa chỉ ví
                const response = await window.solana.connect();
                const walletAddress = response.publicKey.toString();

                // Hiển thị địa chỉ ví trong giao diện người dùng
                document.getElementById('walletAddress').innerText = `Wallet Address: ${walletAddress}`;

                // Gửi địa chỉ ví đến backend (Laravel)
                fetch("{{ route('wallet.save') }}", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ wallet_address: walletAddress })
                })
                .then(response => response.json())
                .then(data => {
                    // Thông báo thành công
                    alert('Wallet address saved successfully.');
                })
                .catch(error => console.error('Error saving wallet address:', error));

            } catch (error) {
                console.error('Connection failed', error);
            }
        } else {
            alert('Phantom Wallet is not installed. Please install Phantom Wallet and try again.');
        }
    });
</script>
@endsection


