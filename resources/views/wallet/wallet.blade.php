@extends('LayoutUser.master')

@section('title')
    Connect Wallet
@endsection

@section('content')
<div class="container">
    <h2 class="text-center">Checkout</h2>
    
    <!-- Hiển thị địa chỉ ví -->
    <p id="walletAddress" class="mt-3" style="color: red;">Wallet Address: Not connected</p>

    <!-- Nút kết nối ví Phantom -->
    <button id="connectWalletButton" class="btn btn-primary">Connect Wallet</button>

    <!-- Nút ngắt kết nối ví Phantom -->
    <button id="disconnectWalletButton" class="btn btn-danger" style="display: none;">Disconnect Wallet</button>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Kiểm tra ví đã được kết nối chưa khi trang Checkout được tải
        if (window.localStorage.getItem('walletConnected') === 'true') {
            const walletAddress = window.localStorage.getItem('walletAddress');
            document.getElementById('walletAddress').innerText = `Wallet Address: ${walletAddress}`;
            document.getElementById('walletAddress').style.color = 'green'; // Hiển thị địa chỉ ví
            document.getElementById('connectWalletButton').style.display = 'none'; // Ẩn nút kết nối ví
            document.getElementById('disconnectWalletButton').style.display = 'inline-block'; // Hiển thị nút ngắt kết nối
        } else {
            document.getElementById('walletAddress').innerText = 'Wallet Address: Not connected';
            document.getElementById('walletAddress').style.color = 'red'; // Hiển thị trạng thái chưa kết nối
            document.getElementById('connectWalletButton').style.display = 'inline-block'; // Hiển thị nút kết nối ví
            document.getElementById('disconnectWalletButton').style.display = 'none'; // Ẩn nút ngắt kết nối
        }
    });

    // Hàm kết nối ví khi nhấn vào nút Connect Wallet
    document.getElementById('connectWalletButton').addEventListener('click', async () => {
        if (window.solana && window.solana.isPhantom) {
            try {
                const response = await window.solana.connect();
                const walletAddress = response.publicKey.toString();

                window.localStorage.setItem('walletConnected', 'true');
                window.localStorage.setItem('walletAddress', walletAddress);

                // Cập nhật lại trạng thái kết nối ví
                document.getElementById('walletAddress').innerText = `Wallet Address: ${walletAddress}`;
                document.getElementById('walletAddress').style.color = 'green';
                document.getElementById('connectWalletButton').style.display = 'none';
                document.getElementById('disconnectWalletButton').style.display = 'inline-block';
            } catch (error) {
                console.error('Connection failed', error);
                alert('Failed to connect to Phantom wallet. Please try again.');
            }
        } else {
            alert('Phantom Wallet is not installed. Please install Phantom Wallet and try again.');
        }
    });

    // Hàm ngắt kết nối ví khi nhấn vào nút Disconnect Wallet
    document.getElementById('disconnectWalletButton').addEventListener('click', () => {
        const confirmation = confirm('Are you sure you want to disconnect your Phantom Wallet?');
        if (confirmation) {
            window.localStorage.removeItem('walletConnected');
            window.localStorage.removeItem('walletAddress');

            // Cập nhật lại trạng thái ví sau khi ngắt kết nối
            document.getElementById('walletAddress').innerText = 'Wallet Address: Not connected';
            document.getElementById('walletAddress').style.color = 'red';
            document.getElementById('connectWalletButton').style.display = 'inline-block';
            document.getElementById('disconnectWalletButton').style.display = 'none';
        }
    });
</script>
@endsection
