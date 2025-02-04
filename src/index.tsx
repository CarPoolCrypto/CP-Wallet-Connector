// File: src/index.tsx

import React from 'react';
import ReactDOM from 'react-dom';
import WalletConnector from './components/WalletConnector';

document.addEventListener('DOMContentLoaded', function() {
  const container = document.getElementById('carpool-wallet-connect');
  if (container) {
    ReactDOM.render(<WalletConnector />, container);
  }
});
