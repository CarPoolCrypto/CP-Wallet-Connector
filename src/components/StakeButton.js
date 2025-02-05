import React from 'react';
import './StakeButton.css';

const StakeButton = ({ onStake }) => {
    return (
        <button className="stake-button" onClick={onStake}>
            Stake with CarPool
        </button>
    );
};

export default StakeButton;
