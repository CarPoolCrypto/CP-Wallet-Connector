import { createRoot } from "react-dom/client"
import HeaderWalletConnect from "./components/HeaderWalletConnect"
import StakeButton from "./components/StakeButton"

document.addEventListener("DOMContentLoaded", () => {
  // Render HeaderWalletConnect
  const headerWalletConnectContainer = document.getElementById("carpool-wallet-connect")
  if (headerWalletConnectContainer) {
    const iconColor = headerWalletConnectContainer.getAttribute("data-icon-color") || "#000000"
    const root = createRoot(headerWalletConnectContainer)
    root.render(<HeaderWalletConnect iconColor={iconColor} />)
  }

  // Render StakeButtons
  const stakeButtons = document.getElementsByClassName("carpool-stake-button")
  Array.from(stakeButtons).forEach((button) => {
    const text =
      button instanceof HTMLElement ? button.getAttribute("data-text") || "Stake with CarPool" : "Stake with CarPool"
    const color = button instanceof HTMLElement ? button.getAttribute("data-color") || "#000000" : "#000000"
    const root = createRoot(button)
    root.render(
      <StakeButton
        text={text}
        color={color}
        onStake={() => {
          // Implement staking logic here
          console.log("Staking with CarPool")
        }}
      />,
    )
  })
})


