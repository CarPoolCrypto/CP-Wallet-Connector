import { BrowserWallet } from '@meshsdk/core';

export class CardanoWalletApi {
    constructor() {
        this.wallet = null;
    }

    async connect(walletName) {
        const availableWallets = await BrowserWallet.getAvailableWallets();
        const walletInfo = availableWallets.find(wallet => wallet.name === walletName);
        if (walletInfo) {
            this.wallet = await BrowserWallet.enable(walletName);
            return true;
        } else {
            console.error(`${walletName} wallet is not available`);
            return false;
        }
    }

    async getDelegationInfo() {
        const rewardAddresses = await this.wallet.getRewardAddresses();
        const address = rewardAddresses[0];
        const account = await fetch(`https://cardano-mainnet.blockfrost.io/api/v0/accounts/${address}`, {
            headers: {
                project_id: 'your_blockfrost_project_id_here'
            }
        }).then(res => res.json());

        const delegation = account.pool_id ? {
            poolId: account.pool_id,
            amount: account.controlled_amount,
            duration: account.active_epoch_no - account.first_delegation_epoch
        } : null;

        return delegation;
    }

    async delegate(poolId) {
        // Create and submit delegation transaction
    }
}
