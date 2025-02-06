import type React from "react"
import ReactDOM from "react-dom"
import ConnectWalletButton from "./components/ConnectWalletButton"
import StakeButton from "./components/StakeButton"
import { useWallet } from "./hooks/useWallet"

const App: React.FC = () => {
  const { wallet, connectWallet, disconnectWallet, stakeWithCarPool } = useWallet()

  return (
    <>
      <ConnectWalletButton isConnected={!!wallet} />
      <StakeButton onStake={stakeWithCarPool} />
    </>
  )
}

document.addEventListener("DOMContentLoaded", () => {
  const walletConnectContainer = document.getElementById("carpool-wallet-connect")
  if (walletConnectContainer) {
    ReactDOM.render(<App />, walletConnectContainer)
  }

  const stakeButtonContainer = document.getElementById("carpool-stake-button")
  if (stakeButtonContainer) {
    ReactDOM.render(<StakeButton onStake={() => console.log("Staking...")} />, stakeButtonContainer)
  }
})



