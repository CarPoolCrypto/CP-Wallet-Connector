"use client"

import { useState, useEffect } from "react"
import { BrowserWallet } from "@meshsdk/core"

export const useWallet = () => {
  const [wallet, setWallet] = useState<any>(null)
  const [balance, setBalance] = useState<string>("0")
  const [address, setAddress] = useState<string>("")

  const connectWallet = async (walletName: string) => {
    try {
      const connectedWallet = await BrowserWallet.enable(walletName)
      setWallet(connectedWallet)
      return connectedWallet
    } catch (error) {
      console.error("Error connecting wallet:", error)
      return null
    }
  }

  const disconnectWallet = () => {
    setWallet(null)
    setBalance("0")
    setAddress("")
  }

  const getBalance = async () => {
    if (wallet) {
      const lovelace = await wallet.getLovelace()
      setBalance((Number.parseInt(lovelace) / 1000000).toFixed(2))
    }
  }

  const getAddress = async () => {
    if (wallet) {
      const addresses = await wallet.getUsedAddresses()
      if (addresses.length > 0) {
        setAddress(addresses[0])
      }
    }
  }

  const stakeWithCarPool = async () => {
    if (wallet) {
      // Implement staking logic here
      console.log("Staking with CarPool")
    }
  }

  useEffect(() => {
    if (wallet) {
      getBalance()
      getAddress()
    }
  }, [wallet, getAddress]) // Added getAddress to dependencies

  return {
    wallet,
    balance,
    address,
    connectWallet,
    disconnectWallet,
    stakeWithCarPool,
  }
}

