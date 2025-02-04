<?php
// blockfrost-api.php

define('BLOCKFROST_API_KEY', 'mainnet3L1Kf0hHVJAdYBSGLxrQPXnyD38nXyn4');
define('BLOCKFROST_API_URL', 'https://cardano-mainnet.blockfrost.io/api/v0');
define('CARPOOL_STAKE_POOL_ID', '138031b823a08dec4535e583ca8ea91530abd9c62b1c0b768fd1f834');

function blockfrost_request($endpoint, $method = 'GET', $data = null) {
    $url = BLOCKFROST_API_URL . $endpoint;

    $args = array(
        'headers' => array(
            'project_id' => BLOCKFROST_API_KEY,
            'Content-Type' => 'application/json',
        ),
        'method' => $method,
    );

    if ($data) {
        $args['body'] = json_encode($data);
    }

    $response = wp_remote_request($url, $args);

    if (is_wp_error($response)) {
        return false;
    }

    $body = wp_remote_retrieve_body($response);
    return json_decode($body, true);
}

function get_blockfrost_delegation_info($address) {
    $account = blockfrost_request("/addresses/$address");
    
    if (!$account || !isset($account['stake_address'])) {
        return false;
    }

    $stake_address = $account['stake_address'];
    $delegation = blockfrost_request("/accounts/$stake_address");

    if (!$delegation) {
        return false;
    }

    return array(
        'pool' => $delegation['pool_id'],
        'amount' => $delegation['controlled_amount'] / 1000000, // Convert lovelace to ADA
        'epochs' => $delegation['active_epoch'],
    );
}

function get_blockfrost_transaction($tx_hash) {
    $transaction = blockfrost_request("/txs/$tx_hash");

    if (!$transaction) {
        return false;
    }

    // Check if this is a delegation transaction
    $certificates = blockfrost_request("/txs/$tx_hash/delegations");

    if (!$certificates || empty($certificates)) {
        return false;
    }

    return array(
        'pool' => $certificates[0]['pool_id'],
        'address' => $certificates[0]['address'],
    );
}
