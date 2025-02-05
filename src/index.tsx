import React from 'react';
import ReactDOM from 'react-dom';
import HeaderWalletConnect from './components/HeaderWalletConnect';
import StakeButton from './components/StakeButton';

document.addEventListener('DOMContentLoaded', () => {
  // Render HeaderWalletConnect
  const headerWalletConnectContainer = document.getElementById('carpool-wallet-connect');
  if (headerWalletConnectContainer) {
    const iconColor = headerWalletConnectContainer.getAttribute('data-icon-color') || '#000000';
    ReactDOM.render(<HeaderWalletConnect iconColor={iconColor} />, headerWalletConnectContainer);
  }

  // Render StakeButtons
  const stakeButtons = document.getElementsByClassName('carpool-stake-button');
  Array.from(stakeButtons).forEach((button) => {
    const text = button.getAttribute('data-text') || 'Stake with CarPool';
    const color = button.getAttribute('data-color') || '#000000';
    ReactDOM.render(
      <StakeButton 
        text={text} 
        color={color} 
        onStake={() => {
          // Implement staking logic here
          console.log('Staking with CarPool');
        }} 
      />, 
      button
    );
  });
});
