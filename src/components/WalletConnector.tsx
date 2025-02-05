import React, { useState, useEffect } from "react";
import { Button } from "@/components/ui/button";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
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

interface WalletConnectorProps {
  onClose?: () => void;
  buttonColor?: string;
}

const WalletConnector: React.FC<WalletConnectorProps> = ({ onClose, buttonColor = '#000000' }) => {
  const [availableWallets, setAvailableWallets] = useState<CardanoWallet[]>([]);
  const [selectedWallet, setSelectedWallet] = useState<CardanoWallet | null>(null);
  const [address, setAddress] = useState<string | null>(null);
  const [delegation, setDelegation] = useState<DelegationInfo | null>(null);

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
      await checkDelegation(walletAddress[0]);
    } catch (error) {
      console.error("Error connecting wallet:", error);
    }
  };

  const checkDelegation = async (address: string) => {
    try {
      const response = await fetch("/wp-admin/admin-ajax.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: new URLSearchParams({
          action: "check_delegation",
          address: address,
        }),
      });
      const data = await response.json();
      if (data.success) {
        setDelegation(data.delegation);
      }
    } catch (error) {
      console.error("Error checking delegation:", error);
    }
  };

  return (
    <Card>
      <CardHeader>
        <CardTitle>Connect Wallet</CardTitle>
      </CardHeader>
      <CardContent>
        {selectedWallet ? (
          <div>
            <p>Connected: {selectedWallet.name}</p>
            {address && <p>Address: {address.slice(0, 8)}...{address.slice(-8)}</p>}
            {delegation && (
              <div>
                <p>Delegated to: {delegation.pool}</p>
                <p>Amount: {delegation.amount} ADA</p>
                <p>Epochs: {delegation.epochs}</p>
              </div>
            )}
            <Button onClick={onClose} style={{ backgroundColor: buttonColor }}>Close</Button>
          </div>
        ) : (
          <Select onValueChange={(value) => {
            const wallet = availableWallets.find(w => w.name === value);
            if (wallet) connectWallet(wallet);
          }}>
            <SelectTrigger>
              <SelectValue placeholder="Select a wallet" />
            </SelectTrigger>
            <SelectContent>
              {availableWallets.map((wallet) => (
                <SelectItem key={wallet.name} value={wallet.name}>
                  <img src={wallet.icon || "/placeholder.svg"} alt={wallet.name} className="w-6 h-6 mr-2 inline-block" />
                  {wallet.name}
                </SelectItem>
              ))}
            </SelectContent>
          </Select>
        )}
      </CardContent>
    </Card>
  );
};

export default WalletConnector;
