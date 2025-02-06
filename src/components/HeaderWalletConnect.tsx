import type React from "react"
import { useState } from "react"
import { Button } from "@/components/ui/button"
import { Wallet } from "lucide-react"
import WalletConnector from "./WalletConnector"

interface HeaderWalletConnectProps {
  iconColor?: string
}

const HeaderWalletConnect: React.FC<HeaderWalletConnectProps> = ({ iconColor = "#000000" }) => {
  const [isOpen, setIsOpen] = useState(false)

  return (
    <div className="relative">
      <Button variant="ghost" size="icon" onClick={() => setIsOpen(!isOpen)} aria-label="Connect Wallet">
        <Wallet style={{ color: iconColor }} />
      </Button>
      {isOpen && (
        <div className="absolute right-0 mt-2 w-72">
          <WalletConnector onClose={() => setIsOpen(false)} />
        </div>
      )}
    </div>
  )
}

export default HeaderWalletConnect

