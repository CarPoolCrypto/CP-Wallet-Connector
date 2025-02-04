<?php
// cardano-api.php

require_once('blockfrost-api.php'); 

function get_delegation_info() {
    check_ajax_referer('carpool-wallet-nonce', 'nonce');

    $address = sanitize_text_field($_POST['address']);

    // Use Blockfrost API to get delegation info
    $delegation = get_blockfrost_delegation_info($address);

    if ($delegation) {
        $discount_tier = calculate_discount_tier($delegation['amount'], $delegation['epochs']);

        wp_send_json_success(array(
            'delegation' => $delegation,
            'discountTier' => $discount_tier
        ));
    } else {
        wp_send_json_error('Unable to fetch delegation info');
    }
}

add_action('wp_ajax_get_delegation_info', 'get_delegation_info');
add_action('wp_ajax_nopriv_get_delegation_info', 'get_delegation_info');

function calculate_discount_tier($amount, $epochs) {
    // ... (keep the existing logic)
}

function verify_transaction() {
    check_ajax_referer('carpool-wallet-nonce', 'nonce');

    $tx_hash = sanitize_text_field($_POST['txHash']);

    // Use Blockfrost API to verify the transaction
    $transaction = get_blockfrost_transaction($tx_hash);

    if ($transaction && $transaction['pool'] === CARPOOL_STAKE_POOL_ID) {
        wp_send_json_success('Transaction verified');
    } else {
        wp_send_json_error('Invalid transaction');
    }
}

add_action('wp_ajax_verify_transaction', 'verify_transaction');
add_action('wp_ajax_nopriv_verify_transaction', 'verify_transaction');
