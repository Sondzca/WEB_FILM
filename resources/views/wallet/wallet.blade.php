@extends('LayoutUser.master')

@section('title')
    Connect Wallet
@endsection

@section('content')
    <div class="container">
        <h2 class="text-center">Connect Phantom Wallet</h2>
        
        <button id="connectWalletButton" class="btn btn-primary">Connect Wallet</button>
        
        <button id="disconnectWalletButton" class="btn btn-danger" style="display: none;">Disconnect Wallet</button>

        <p id="walletAddress" class="mt-3" style="color: red;">Wallet Address: Not connected</p>
    </div>

    <script>
        function checkWalletConnection() {
            if (window.localStorage.getItem('walletConnected') === 'true') {
                const walletAddress = window.localStorage.getItem('walletAddress');
                document.getElementById('walletAddress').innerText = `Wallet Address: ${walletAddress}`;
                document.getElementById('walletAddress').style.color = 'green'; 
                document.getElementById('connectWalletButton').style.display = 'none'; 
                document.getElementById('disconnectWalletButton').style.display = 'inline-block'; 
            } else {
                document.getElementById('walletAddress').innerText = 'Wallet Address: Not connected';
                document.getElementById('walletAddress').style.color = 'red'; 
                document.getElementById('connectWalletButton').style.display = 'inline-block'; 
                document.getElementById('disconnectWalletButton').style.display = 'none'; 
            }
        }
        checkWalletConnection();

        document.getElementById('connectWalletButton').addEventListener('click', async () => {
            if (window.solana && window.solana.isPhantom) {
                try {
                    const response = await window.solana.connect();
                    const walletAddress = response.publicKey.toString();

                    window.localStorage.setItem('walletConnected', 'true');
                    window.localStorage.setItem('walletAddress', walletAddress);

                    checkWalletConnection();

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

        document.getElementById('disconnectWalletButton').addEventListener('click', () => {
  
            const confirmation = confirm('Are you sure you want to disconnect your Phantom Wallet?');

            if (confirmation) {
                window.localStorage.removeItem('walletConnected');
                window.localStorage.removeItem('walletAddress');

                checkWalletConnection();
            }
        });
    </script>
@endsection
