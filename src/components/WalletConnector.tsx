"use client"

import type React from "react"
import { useState, useEffect } from "react"
import { BrowserWallet } from "@meshsdk/core"
import { Button } from "@/components/ui/button"

interface WalletConnectorProps {
  onClose: () => void
}

const WalletConnector: React.FC<WalletConnectorProps> = ({ onClose }) => {
  const [availableWallets, setAvailableWallets] = useState<any[]>([])

  useEffect(() => {
    const getWallets = async () => {
      const wallets = await BrowserWallet.getAvailableWallets()
      setAvailableWallets(wallets)
    }
    getWallets()
  }, [])

  const connectWallet = async (walletName: string) => {
    try {
      const wallet = await BrowserWallet.enable(walletName)
      // Here you would typically store the wallet instance and update your app state
      console.log("Wallet connected:", wallet)
      onClose()
    } catch (error) {
      console.error("Error connecting wallet:", error)
    }
  }

  return (
    <div className="grid grid-cols-2 gap-4">
      {availableWallets.map((wallet) => (
        <Button
          key={wallet.name}
          onClick={() => connectWallet(wallet.name)}
          className="flex items-center justify-center p-4 bg-[#1e2a2a] hover:bg-[#2a3a3a] text-white rounded-lg"
        >
          <img src={wallet.icon || "/placeholder.svg"} alt={wallet.name} className="w-8 h-8 mr-2" />
          {wallet.name}
        </Button>
      ))}
    </div>
  )
}

export default WalletConnector

