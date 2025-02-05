import React, { useState } from 'react';
import ReactDOM from 'react-dom';
import WalletConnectButton from './components/WalletConnectButton';
import StakeButton from './components/StakeButton';
import './styles/index.css';

const CardanoWalletConnector = () => {
    const [walletApi, setWalletApi] = useState(null);
    const [delegationInfo, setDelegationInfo] = useState(null);

    const handleConnect = async (api) => {
        setWalletApi(api);
        const delegation = await api.getDelegationInfo();
        setDelegationInfo(delegation);
    };

    const handleStake = async () => {
        if (walletApi) {
            await walletApi.delegate('your_stake_pool_id_here');
            const delegation = await walletApi.getDelegationInfo();
            setDelegationInfo(delegation);
        }
    };

    return (
        <div>
            <WalletConnectButton onConnect={handleConnect} />
            {walletApi && (
                <div>
                    <p>Connected!</p>
                    {delegationInfo ? (
                        <div>
                            <p>Delegated to: {delegationInfo.poolId}</p>
                            <p>Delegated ADA: {delegationInfo.amount}</p>
                            <p>Delegation Duration: {delegationInfo.duration} epochs</p>
                        </div>
                    ) : (
                        <p>Loading delegation info...</p>
                    )}
                    <StakeButton onStake={handleStake} />
                </div>
            )}
        </div>
    );
};

ReactDOM.render(<CardanoWalletConnector />, document.getElementById('carpool-wallet-connect'));
