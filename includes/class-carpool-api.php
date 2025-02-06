<?php

class CarPool_API {
    private $blockfrost_project_id;
    private $carpool_stake_pool_id;

    public function __construct() {
        $this->blockfrost_project_id = get_option('carpool_blockfrost_project_id');
        $this->carpool_stake_pool_id = get_option('carpool_stake_pool_id');
    }

    public function get_delegation_info($address) {
        $url = "https://cardano-mainnet.blockfrost.io/api/v0/accounts/{$address}";
        $response = wp_remote_get($url, [
            'headers' => [
                'project_id' => $this->blockfrost_project_id,
            ],
        ]);

        if (is_wp_error($response)) {
            return false;
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);

        if (isset($body['stake_address'])) {
            $stake_address = $body['stake_address'];
            $delegation_url = "https://cardano-mainnet.blockfrost.io/api/v0/accounts/{$stake_address}/delegations";
            $delegation_response = wp_remote_get($delegation_url, [
                'headers' => [
                    'project_id' => $this->blockfrost_project_id,
                ],
            ]);

            if (!is_wp_error($delegation_response)) {
                $delegation_body = json_decode(wp_remote_retrieve_body($delegation_response), true);
                $latest_delegation = end($delegation_body);

                if ($latest_delegation && $latest_delegation['pool_id'] === $this->carpool_stake_pool_id) {
                    return [
                        'is_delegated' => true,
                        'amount' => $body['controlled_amount'] / 1000000, // Convert lovelace to ADA
                        'epochs' => count($delegation_body),
                    ];
                }
            }
        }

        return [
            'is_delegated' => false,
            'amount' => 0,
            'epochs' => 0,
        ];
    }
}

