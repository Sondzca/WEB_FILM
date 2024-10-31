import { WalletAdapterNetwork } from '@solana/wallet-adapter-base';
import { ConnectionProvider, useWallet, WalletProvider } from '@solana/wallet-adapter-react';
import { WalletModalProvider, WalletMultiButton } from '@solana/wallet-adapter-react-ui';
import '@solana/wallet-adapter-react-ui/styles.css';
import { UnsafeBurnerWalletAdapter } from '@solana/wallet-adapter-wallets';
import { clusterApiUrl, SystemProgram, Transaction, Keypair, Connection, LAMPORTS_PER_SOL, PublicKey } from '@solana/web3.js';
import axios from 'axios';
import { FC, useEffect, useMemo, useCallback } from 'react';

const WalletComponent: FC = () => {
    const { publicKey, sendTransaction } = useWallet();

    useEffect(() => {
        if (publicKey) {
            const walletData = {
                wallet: publicKey.toString(),
            };
            localStorage.setItem('walletData', JSON.stringify(walletData));
            axios.post('http://127.0.0.1:8000/api/wallet', walletData)
                .then(response => {
                    console.log('Post request successful:', response.data);
                })
                .catch(error => {
                    console.error('Error in POST request:', error);
                });
        }
    }, [publicKey]);
    return (
        <>
            <WalletMultiButton />
        </>
    );
};

export const Wallet: FC = () => {
    const network = WalletAdapterNetwork.Devnet;
    const endpoint = useMemo(() => clusterApiUrl(network), [network]);

    const wallets = useMemo(
        () => [
            new UnsafeBurnerWalletAdapter(),
        ],
        [network]
    );

    return (
        <ConnectionProvider endpoint={endpoint}>
            <WalletProvider wallets={wallets} autoConnect>
                <WalletModalProvider>
                    <WalletComponent />
                </WalletModalProvider>
            </WalletProvider>
        </ConnectionProvider>
    );
};

export default Wallet;
