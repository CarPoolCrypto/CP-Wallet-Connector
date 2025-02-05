import type React from "react"
import { Button } from "@/components/ui/button"

interface StakeButtonProps {
  text: string
  color?: string
  onStake: () => void
}

const StakeButton: React.FC<StakeButtonProps> = ({ text, color = "#000000", onStake }) => {
  return (
    <Button onClick={onStake} style={{ backgroundColor: color }}>
      {text}
    </Button>
  )
}

export default StakeButton

