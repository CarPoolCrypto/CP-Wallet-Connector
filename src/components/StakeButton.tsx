import type React from "react"
import { Button } from "@/components/ui/button"

interface StakeButtonProps {
  onStake: () => void
}

const StakeButton: React.FC<StakeButtonProps> = ({ onStake }) => {
  return (
    <Button
      onClick={onStake}
      className="
        font-ubuntu text-[#0cc43e] border border-[#0cc43e] rounded-[10px] bg-transparent
        hover:bg-[#0cc43e] hover:text-white transition-all duration-300
        px-4 py-2
      "
    >
      Stake with CarPool
    </Button>
  )
}

export default StakeButton


