import { BrowserWallet } from "@meshsdk/core"

export const getAvailableWallets = async () => {
  return await BrowserWallet.getAvailableWallets()
}

export const connectWallet = async (walletName: string) => {
  try {
    const wallet = await BrowserWallet.enable(walletName)
    return wallet
  } catch (error) {
    console.error("Error connecting wallet:", error)
    return null
  }
}

export const getWalletBalance = async (wallet: any) => {
  if (wallet) {
    const lovelace = await wallet.getLovelace()
    return (Number.parseInt(lovelace) / 1000000).toFixed(2)
  }
  return "0"
}

export const getWalletAddress = async (wallet: any) => {
  if (wallet) {
    const addresses = await wallet.getUsedAddresses()
    return addresses.length > 0 ? addresses[0] : ""
  }
  return ""
}

export const stakeWithCarPool = async (wallet: any, poolId: string) => {
  if (wallet) {
    try {
      // This is a placeholder for the actual staking logic
      // You'll need to implement the correct transaction building and signing process
      console.log(`Staking with CarPool (Pool ID: ${poolId})`)
      // const transaction = await wallet.delegate({
      //   stakepoolId: poolId,
      // });
      // const signedTx = await wallet.signTx(transaction);
      // const txHash = await wallet.submitTx(signedTx);
      // return txHash;
    } catch (error) {
      console.error("Error staking with CarPool:", error)
      return null
    }
  }
  return null
}

