import React from 'react';
import ReactDOM from 'react-dom';
import WalletConnector from './components/WalletConnector';
import './styles/globals.css';

document.addEventListener('DOMContentLoaded', () => {
  const container = document.getElementById('carpool-wallet-connect');
  if (container) {
    ReactDOM.render(<WalletConnector />, container);
  }
});
