<?php
// class-carpool-api.php

class CarPool_API {
    private $blockfrost_project_id;
    private $carpool_stake_pool_id;

    public function __construct() {
        $this->blockfrost_project_id = 'your_blockfrost_project_id_here';
        $this->carpool_stake_pool_id = 'your_stake_pool_id_here';
    }

    public function get_user_delegated_amount($user_id) {
        $wallet_address = get_user_meta($user_id, 'carpool_wallet_address', true);
        if (!$wallet_address) {
            return 0;
        }

        $url = "https://cardano-mainnet.blockfrost.io/api/v0/accounts/{$wallet_address}";
        $response = wp_remote_get($url, [
            'headers' => [
                'project_id' => $this->blockfrost_project_id,
            ],
        ]);

        if (is_wp_error($response)) {
            return 0;
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);
        return isset($body['controlled_amount']) ? $body['controlled_amount'] / 1000000 : 0; // Convert lovelace to ADA
    }

    public function get_user_delegation_epochs($user_id) {
        $wallet_address = get_user_meta($user_id, 'carpool_wallet_address', true);
        if (!$wallet_address) {
            return 0;
        }

        $url = "https://cardano-mainnet.blockfrost.io/api/v0/accounts/{$wallet_address}/delegations";
        $response = wp_remote_get($url, [
            'headers' => [
                'project_id' => $this->blockfrost_project_id,
            ],
        ]);

        if (is_wp_error($response)) {
            return 0;
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);
        $epochs = 0;
        foreach ($body as $delegation) {
            if ($delegation['pool_id'] === $this->carpool_stake_pool_id) {
                $epochs += $delegation['active_epoch'] - $delegation['epoch'];
            }
        }

        return $epochs;
    }
}
