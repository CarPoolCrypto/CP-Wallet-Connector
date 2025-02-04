<?php
// File: includes/enqueue-scripts.php

function carpool_wallet_connect_enqueue_scripts() {
    wp_enqueue_script(
        'carpool-wallet-connect',
        plugins_url('../build/index.js', __FILE__),
        array('wp-element'),
        '1.0',
        true
    );

    wp_localize_script('carpool-wallet-connect', 'carpoolWalletData', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('carpool-wallet-nonce')
    ));
}

add_action('wp_enqueue_scripts', 'carpool_wallet_connect_enqueue_scripts');

function carpool_wallet_connect_shortcode() {
    return '<div id="carpool-wallet-connect"></div>';
}

add_shortcode('carpool_wallet_connect', 'carpool_wallet_connect_shortcode');
