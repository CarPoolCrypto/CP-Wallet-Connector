"use client"

import type React from "react"
import { useState } from "react"
import { Button } from "@/components/ui/button"
import { Wallet } from "lucide-react"
import WalletConnector from "./WalletConnector"

interface ConnectWalletButtonProps {
  isConnected: boolean
}

const ConnectWalletButton: React.FC<ConnectWalletButtonProps> = ({ isConnected }) => {
  const [isOpen, setIsOpen] = useState(false)

  return (
    <div className="relative">
      <Button
        variant="outline"
        className={`
          font-ubuntu text-[#0cc43e] border border-[#0cc43e] rounded-[10px] bg-transparent
          hover:bg-[#0cc43e] hover:text-white transition-all duration-300
          ${isConnected ? "p-2" : "px-4 py-2"}
        `}
        onClick={() => setIsOpen(!isOpen)}
      >
        {isConnected ? (
          <>
            <Wallet className="w-6 h-6" />
            <span className="ml-2 w-2 h-2 bg-[#0cc43e] rounded-full"></span>
          </>
        ) : (
          "Connect Wallet"
        )}
      </Button>
      {isOpen && (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
          <div className="bg-[#131c1c] p-6 rounded-lg">
            <WalletConnector onClose={() => setIsOpen(false)} />
          </div>
        </div>
      )}
    </div>
  )
}

export default ConnectWalletButton

