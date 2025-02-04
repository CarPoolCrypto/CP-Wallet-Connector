<?php
// File: includes/delegation-tracker.php

function update_delegation_info($user_id, $delegation_info) {
    update_user_meta($user_id, 'carpool_delegation_amount', $delegation_info['amount']);
    update_user_meta($user_id, 'carpool_delegation_epochs', $delegation_info['epochs']);
    
    $discount_tier = calculate_discount_tier($delegation_info['amount'], $delegation_info['epochs']);
    update_user_meta($user_id, 'carpool_discount_tier', $discount_tier);

    if ($delegation_info['pool'] === CARPOOL_STAKE_POOL_ID) {
        $user = new WP_User($user_id);
        $user->add_role('carpool_delegator');
    } else {
        $user = new WP_User($user_id);
        $user->remove_role('carpool_delegator');
    }
}

function update_all_users_delegation_info() {
    $users = get_users(array('role' => 'carpool_delegator'));
    foreach ($users as $user) {
        $address = get_user_meta($user->ID, 'cardano_wallet_address', true);
        if ($address) {
            $delegation_info = get_blockfrost_delegation_info($address);
            if ($delegation_info) {
                update_delegation_info($user->ID, $delegation_info);
            }
        }
    }
}
