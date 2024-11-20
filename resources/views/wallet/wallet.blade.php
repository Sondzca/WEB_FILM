@extends('LayoutUser.master')

@section('title')
    Connect Wallet
@endsection

@section('content')
    <div class="container">
        <h2 class="text-center">Connect Phantom Wallet</h2>

        <!-- Thông báo khi chưa kết nối ví -->
        <div id="notConnectedMessage" class="text-center mt-3" style="color: red; font-size: 18px;">
            Bạn chưa kết nối ví, vui lòng kết nối để có thể thanh toán.
        </div>

        <!-- Nút kết nối ví -->
        <button id="connectWalletButton" class="btn btn-primary mt-3">Connect Wallet</button>

        <!-- Hiển thị khi đã kết nối ví -->
        <div id="connectedWalletInfo" style="display: none;">
            <p id="walletAddress" class="mt-3" style="color: green; font-size: 18px;"></p>

            <form action="{{route('wallet.destroy',  Auth::user()->id)}}">
                @csrf
                @method('delete')
                <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Disconnect Wallet</button>
            </form>
            
            <div id="walletBalances" class="mt-3">
                <h5>Your Wallet Balances:</h5>
                <ul id="balanceList"></ul>
            </div>
        </div>
    </div>

    <script>
        async function fetchWalletBalances(publicKey) {
            const balanceList = document.getElementById('balanceList');
            balanceList.innerHTML = '';
            try {
                // Dùng API từ blockchain hoặc dịch vụ ví để lấy số dư
                const balances = {
                    SOL: '1.23', // Số dư ví giả lập
                    USDT: '100.45', // Số dư ví giả lập
                };
                for (const [token, amount] of Object.entries(balances)) {
                    const li = document.createElement('li');
                    li.innerText = `${token}: ${amount}`;
                    balanceList.appendChild(li);
                }
            } catch (error) {
                console.error('Error fetching wallet balances:', error);
                alert('Không thể lấy số dư ví. Vui lòng thử lại.');
            }
        }

        function checkWalletConnection() {
            if (window.localStorage.getItem('walletConnected') === 'true') {
                const walletAddress = window.localStorage.getItem('walletAddress');
                document.getElementById('walletAddress').innerText = `Wallet Address: ${walletAddress}`;
                document.getElementById('notConnectedMessage').style.display = 'none';
                document.getElementById('connectedWalletInfo').style.display = 'block';
                document.getElementById('connectWalletButton').style.display = 'none';
                fetchWalletBalances(walletAddress);
            } else {
                document.getElementById('notConnectedMessage').style.display = 'block';
                document.getElementById('connectedWalletInfo').style.display = 'none';
                document.getElementById('connectWalletButton').style.display = 'inline-block';
            }
        }
        checkWalletConnection();

        // Connect wallet
        document.getElementById('connectWalletButton').addEventListener('click', async () => {
            if (window.solana && window.solana.isPhantom) {
                try {
                    const response = await window.solana.connect();
                    const walletAddress = response.publicKey.toString();

                    window.localStorage.setItem('walletConnected', 'true');
                    window.localStorage.setItem('walletAddress', walletAddress);

                    checkWalletConnection();

                    // Lưu địa chỉ ví vào bảng 'users'
                    fetch("{{ route('wallet.store') }}", {
                            method: "POST",
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                wallet: walletAddress
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            alert('Wallet address saved successfully.');
                        })
                        .catch(error => console.error('Error saving wallet address:', error));
                } catch (error) {
                    console.error('Connection failed', error);
                    alert('Failed to connect to Phantom wallet. Please try again.');
                }
            } else {
                alert('Phantom Wallet is not installed. Please install Phantom Wallet and try again.');
            }
        });
    </script>
@endsection
