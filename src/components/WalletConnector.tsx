// src/components/WalletConnector.tsx

import React, { useState, useEffect } from "react";
import { Button } from "@/components/ui/button";
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from "@/components/ui/card";
import { Alert, AlertDescription, AlertTitle } from "@/components/ui/alert";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";

interface CardanoWallet {
  name: string;
  icon: string;
  api: any;
}

interface DelegationInfo {
  pool: string;
  amount: number;
  epochs: number;
}

// This should be passed as a prop or fetched from a global state
const carpoolWalletData = { nonce: "your_nonce_here" };

const WalletConnector: React.FC = () => {
  const [availableWallets, setAvailableWallets] = useState<CardanoWallet[]>([]);
  const [selectedWallet, setSelectedWallet] = useState<CardanoWallet | null>(null);
  const [address, setAddress] = useState<string | null>(null);
  const [balance, setBalance] = useState<string | null>(null);
  const [delegation, setDelegation] = useState<DelegationInfo | null>(null);
  const [discountTier, setDiscountTier] = useState<string | null>(null);
  const [discountPercentage, setDiscountPercentage] = useState<number | null>(null);

  useEffect(() => {
    const checkWallets = async () => {
      const wallets: CardanoWallet[] = [];

      if ((window as any).cardano) {
        for (const walletKey in (window as any).cardano) {
          if (typeof (window as any).cardano[walletKey]?.enable === "function") {
            wallets.push({
              name: walletKey,
              icon: `/images/${walletKey.toLowerCase()}-icon.png`,
              api: (window as any).cardano[walletKey],
            });
          }
        }
      }

      setAvailableWallets(wallets);
    };

    checkWallets();
  }, []);

  const connectWallet = async (wallet: CardanoWallet) => {
    try {
      await wallet.api.enable();
      setSelectedWallet(wallet);

      const walletAddress = await wallet.api.getUsedAddresses();
      setAddress(walletAddress[0]);

      const walletBalance = await wallet.api.getBalance();
      setBalance((Number.parseInt(walletBalance) / 1000000).toFixed(2));

      const response = await fetch("/wp-admin/admin-ajax.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: new URLSearchParams({
          action: "connect_wallet",
          nonce: carpoolWalletData.nonce,
          wallet_address: walletAddress[0],
        }),
      });

      const data = await response.json();
      if (data.success) {
        setDelegation(data.delegation);
        setDiscountTier(data.discountTier);
        setDiscountPercentage(data.discountPercentage);
      }
    } catch (error) {
      console.error("Error connecting wallet:", error);
    }
  };

  const delegateToCarPool = async () => {
    if (!selectedWallet) return;

    console.log("Delegating to CarPool...");
    // Implement delegation logic here
  };

  return (
    <Card className="w-[350px]">
      <CardHeader>
        <CardTitle>CarPool Wallet Connect</CardTitle>
        <CardDescription>Connect your Cardano wallet and delegate to CarPool</CardDescription>
      </CardHeader>
      <CardContent>
        {selectedWallet ? (
          <div className="space-y-2">
            <p>Wallet: {selectedWallet.name}</p>
            {address && (
              <p>
                Address: {address.slice(0, 10)}...{address.slice(-10)}
              </p>
            )}
            {balance && <p>Balance: {balance} ADA</p>}
            {delegation && (
              <div>
                <p>Delegated to: {delegation.pool}</p>
                <p>Amount: {delegation.amount} ADA</p>
                <p>Epochs: {delegation.epochs}</p>
              </div>
            )}
            {discountTier && (
              <Alert>
                <AlertTitle>Discount Tier</AlertTitle>
                <AlertDescription>{discountTier}</AlertDescription>
              </Alert>
            )}
            {discountPercentage !== null && (
              <Alert>
                <AlertTitle>Your Discount</AlertTitle>
                <AlertDescription>{discountPercentage}% off all purchases</AlertDescription>
              </Alert>
            )}
          </div>
        ) : (
          <Select
            onValueChange={(value) => {
              const wallet = availableWallets.find((w) => w.name === value);
              if (wallet) connectWallet(wallet);
            }}
          >
            <SelectTrigger>
              <SelectValue placeholder="Select a wallet" />
            </SelectTrigger>
            <SelectContent>
              {availableWallets.map((wallet) => (
                <SelectItem key={wallet.name} value={wallet.name}>
                  <img
                    src={wallet.icon || "/placeholder.svg"}
                    alt={wallet.name}
                    className="w-6 h-6 mr-2 inline-block"
                  />
                  {wallet.name}
                </SelectItem>
              ))}
            </SelectContent>
          </Select>
        )}
      </CardContent>
      <CardFooter className="flex flex-col space-y-2">
        {selectedWallet && delegation?.pool !== "CarPool" && (
          <Button onClick={delegateToCarPool} className="w-full">
            Delegate to CarPool
          </Button>
        )}
      </CardFooter>
    </Card>
  );
};

export default WalletConnector;
