import { Connection, PublicKey, clusterApiUrl, LAMPORTS_PER_SOL } from '@solana/web3.js';

// Kết nối tới mạng Solana devnet
const connection = new Connection(clusterApiUrl('devnet'), 'confirmed');

// Hàm lấy số dư Solana của một ví
async function getBalance(walletAddress) {
    try {
        const publicKey = new PublicKey(walletAddress); // Chuyển walletAddress thành PublicKey

        // Lấy số dư (lamports) của ví
        const balance = await connection.getBalance(publicKey);
        const solBalance = balance / LAMPORTS_PER_SOL; // Chuyển lamports thành SOL

        console.log(`Số dư của ví ${walletAddress}: ${solBalance.toFixed(2)} SOL`);
        return solBalance;
    } catch (error) {
        console.error('Lỗi khi lấy số dư ví:', error);
        return 0;
    }
}

// Kiểm tra ví Phantom đã được kết nối chưa và lấy địa chỉ ví
async function connectWallet() {
    if (window.solana && window.solana.isPhantom) {
        try {
            // Kết nối ví và lấy địa chỉ ví
            const response = await window.solana.connect();
            const walletAddress = response.publicKey.toString(); // Địa chỉ ví

            console.log('Wallet Address:', walletAddress);

            // Gọi hàm lấy số dư ví
            const balance = await getBalance(walletAddress);
            console.log('Balance:', balance);

        } catch (error) {
            console.error('Lỗi khi kết nối ví Phantom:', error);
        }
    } else {
        alert('Phantom Wallet chưa được cài đặt!');
    }
}

// Khi người dùng nhấn nút kết nối ví
document.getElementById('connectWalletButton').addEventListener('click', connectWallet);
