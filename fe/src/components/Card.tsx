//Card.tsx
import { useEffect, useState } from 'react'
import { Web3ReactSelectedHooks } from '@web3-react/core'
import { Connector } from '@web3-react/types'


export default function Card({connector, hooks, name}: {connector: Connector, hooks: Web3ReactSelectedHooks, name: string}) {
  const {useSelectedAccount, useSelectedChainId, useSelectedIsActive, useSelectedIsActivating } = hooks
  const isActivating = useSelectedIsActivating(connector)
  const isActive = useSelectedIsActive(connector)
  const account = useSelectedAccount(connector)
  const chain = useSelectedChainId(connector)
  const [error, setError] = useState<Error | undefined>(undefined)
  const [connectionStatus, setConnectionStatus] = useState('Disconnected')
  const [storedAccount, setStoredAccount] = useState<string | null>(null)
  const savedAccount = localStorage.getItem('walletAddress')

  useEffect(() => {
   
    if (savedAccount) {
      setStoredAccount(savedAccount)
    }
  }, [])
  
  const handleToggleConnect = () => {
    setError(undefined) // clear error state

    if (isActive) {
      if(connector?.deactivate) {
        void connector.deactivate()
      } else {
        void connector.resetState()
      }
    }
    else if (!isActivating) {
      setConnectionStatus('Connecting..')
        Promise.resolve(connector.activate(1))
        .catch((e) => {
          connector.resetState()
          setError(e)
        }) 
      }
  }
  let connectButton;
  useEffect(() => {
    if (account) {
      localStorage.setItem('walletAddress', account)
    }
    if(isActive) {
      setConnectionStatus('Connected')
      if (account) {
        localStorage.setItem('walletAddress', account)
      }
    } else {
      setConnectionStatus('Disconnected')
    }
   
    // if (savedAccount){
    //   connectButton = <p>Connected address:{savedAccount}</p>;
    // }else{
    //   {
    //     isActive && (
    //       <h3>Address - {savedAccount ? savedAccount : "No Account Detected"}</h3>
    //     )
    //   }
    //   connectButton = <button onClick={handleToggleConnect} disabled={false}>
    //     {isActive ? "Disconnect" : "Connect Wallet"}
    //   </button>
    // }
    
  }
  ,[isActive])
  connectButton = 
  <button onClick={handleToggleConnect} disabled={false}>
    {isActive ? "Disconnect" : "Connect Wallet"}
  </button>
  

  return (
    <div>
      {/* <p>{name.toUpperCase()}</p> */}
      {/* <h3>Status - {(error?.message) ? ("Error: " + error.message) : connectionStatus}</h3> */}
      {isActive && (
         <h3>Address - { savedAccount ? savedAccount : "No Account Detected"}</h3>
      )}
      {/* <h3>ChainId -  {chain ? chain : 'No Chain Connected'}</h3> */}
      {/* <button  onClick={handleToggleConnect} disabled={false}>
        {isActive ? "Disconnect" : "Connect Wallet"}
      </button> */}
      {connectButton}
    </div>
  )
}
