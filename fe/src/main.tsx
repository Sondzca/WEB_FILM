import React, { useMemo } from 'react'
import ReactDOM from 'react-dom/client'
import App from './App.tsx'
import './index.css'

import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { BrowserRouter } from 'react-router-dom'
import { ToastContainer } from 'react-toastify'
import 'react-toastify/dist/ReactToastify.css'
import { WalletAdapterNetwork } from '@solana/wallet-adapter-base'
import { clusterApiUrl } from '@solana/web3.js'
import { UnsafeBurnerWalletAdapter } from '@solana/wallet-adapter-wallets'
import { ConnectionProvider, WalletProvider } from '@solana/wallet-adapter-react'
import { WalletModalProvider } from '@solana/wallet-adapter-react-ui'
const network = WalletAdapterNetwork.Devnet;
const endpoint = clusterApiUrl(network);
const wallets = [new UnsafeBurnerWalletAdapter()]; 

const queryClient = new QueryClient()
ReactDOM.createRoot(document.getElementById('root')!).render(
  <ConnectionProvider endpoint={endpoint}>
    <WalletProvider wallets={wallets} autoConnect>
     <WalletModalProvider>
      <QueryClientProvider client={queryClient}>
        <React.StrictMode>
        <BrowserRouter>
          <ToastContainer />
          <App />
        </BrowserRouter>
        </React.StrictMode>,
        </QueryClientProvider>
     </WalletModalProvider>
    </WalletProvider>
  </ConnectionProvider>
)
