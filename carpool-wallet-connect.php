<?php
/*
Plugin Name: CarPool Wallet Connect
Description: Connect Cardano wallets
Version: 1.0
Author: CarPoolHealth
*/

if (!defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . 'includes/class-carpool-api.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-carpool-user-roles.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-carpool-wallet-connect.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-carpool-woocommerce-integration.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-carpool-cron.php';

class CarPool_Wallet_Connect_Plugin {
    public function __construct() {
        add_action('plugins_loaded', array($this, 'init_plugin'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_shortcode('carpool_wallet_connect', array($this, 'wallet_connect_shortcode'));
        add_shortcode('carpool_stake_button', array($this, 'stake_button_shortcode'));
    }

    public function init_plugin() {
        new CarPool_Wallet_Connect();
        new CarPool_WooCommerce_Integration();
        new CarPool_Cron();
    }

    public function enqueue_scripts() {
        wp_enqueue_style('carpool-wallet-connect', plugin_dir_url(__FILE__) . 'assets/styles/main.css', array(), '1.0.0');
        wp_enqueue_script('carpool-wallet-connect', plugin_dir_url(__FILE__) . 'build/index.js', array('react', 'react-dom'), '1.0.0', true);
        wp_localize_script('carpool-wallet-connect', 'carpoolWalletData', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('carpool-wallet-connect'),
        ));
    }

    public function wallet_connect_shortcode() {
        return '<div id="carpool-wallet-connect"></div>';
    }

    public function stake_button_shortcode() {
        return '<div id="carpool-stake-button"></div>';
    }
}

new CarPool_Wallet_Connect_Plugin();

