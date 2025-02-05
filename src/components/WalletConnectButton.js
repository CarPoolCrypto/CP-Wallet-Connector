import React, { useState } from 'react';
import { CardanoWalletApi } from '../lib/CardanoWalletApi';
import './WalletConnectButton.css';

const WalletConnectButton = ({ onConnect }) => {
    const [showModal, setShowModal] = useState(false);
    const [walletName, setWalletName] = useState('');

    const handleConnect = async () => {
        const walletApi = new CardanoWalletApi();
        const connected = await walletApi.connect(walletName);
        if (connected) {
            onConnect(walletApi);
            setShowModal(false);
        }
    };

    return (
        <div>
            <button className="wallet-connect-button" onClick={() => setShowModal(true)}>
                Connect Wallet
            </button>
            {showModal && (
                <div className="modal">
                    <div className="modal-content">
                        <h2>Select Wallet</h2>
                        <ul>
                            <li onClick={() => setWalletName('eternl')}>Eternl</li>
                            <li onClick={() => setWalletName('vespr')}>VESPR</li>
                            <li onClick={() => setWalletName('lace')}>LACE</li>
                            <li onClick={() => setWalletName('begin')}>Begin</li>
                            <li onClick={() => setWalletName('nufi')}>NuFi</li>
                            <li onClick={() => setWalletName('yoroi')}>Yoroi</li>
                        </ul>
                        <button className="wallet-connect-button" onClick={handleConnect}>
                            Connect
                        </button>
                    </div>
                </div>
            )}
        </div>
    );
};

export default WalletConnectButton;
