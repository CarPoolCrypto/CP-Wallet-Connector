import React from "react"
import ReactDOM from "react-dom"
import HeaderWalletConnect from "./components/HeaderWalletConnect"
import StakeButton from "./components/StakeButton"

document.addEventListener("DOMContentLoaded", () => {
  // Render HeaderWalletConnect
  const headerWalletConnectContainer = document.getElementById("carpool-wallet-connect")
  if (headerWalletConnectContainer) {
    const iconColor = headerWalletConnectContainer.getAttribute("data-icon-color") || "#000000"
    ReactDOM.render(React.createElement(HeaderWalletConnect, { iconColor }), headerWalletConnectContainer)
  }

  // Render StakeButtons
  const stakeButtons = document.getElementsByClassName("carpool-stake-button")
  Array.from(stakeButtons).forEach((button) => {
    const text =
      button instanceof HTMLElement ? button.getAttribute("data-text") || "Stake with CarPool" : "Stake with CarPool"
    const color = button instanceof HTMLElement ? button.getAttribute("data-color") || "#000000" : "#000000"
    ReactDOM.render(
      React.createElement(StakeButton, {
        text,
        color,
        onStake: () => {
          // Implement staking logic here
          console.log("Staking with CarPool")
        },
      }),
      button,
    )
  })
})

