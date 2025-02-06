<?php

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

// Remove plugin options
delete_option( 'carpool_blockfrost_project_id' );
delete_option( 'carpool_stake_pool_id' );

// Remove user meta data
$users = get_users( array( 'fields' => array( 'ID' ) ) );
foreach ( $users as $user ) {
    delete_user_meta( $user->ID, 'carpool_wallet_address' );
    delete_user_meta( $user->ID, 'carpool_delegation_info' );
}

// Remove custom user roles
remove_role( 'carpool_delegator' );
remove_role( 'carpool_whale' );
remove_role( 'carpool_shark' );
remove_role( 'carpool_dolphin' );
remove_role( 'carpool_tuna' );
remove_role( 'carpool_shrimp' );

// Remove scheduled cron job
wp_clear_scheduled_hook( 'carpool_update_delegation_info' );

