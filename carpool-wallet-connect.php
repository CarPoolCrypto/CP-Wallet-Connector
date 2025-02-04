<?php
/*
Plugin Name: CarPool Wallet Connect
Description: Connect Cardano wallets and provide tiered discounts for CarPool delegators.
Version: 1.0
Author: Your Name
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

require_once plugin_dir_path(__FILE__) . 'includes/class-carpool-api.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-carpool-user-roles.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-carpool-wallet-connect.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-carpool-woocommerce-integration.php';

class CarPool_Wallet_Connect_Plugin {
    public function __construct() {
        add_action('plugins_loaded', array($this, 'init_plugin'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_shortcode('carpool_wallet_connect', array($this, 'wallet_connect_shortcode'));
        add_shortcode('carpool_discount_info', array($this, 'display_discount_info'));
    }

    public function init_plugin() {
        new CarPool_Wallet_Connect();
        new CarPool_WooCommerce_Integration();
    }

    public function enqueue_scripts() {
        wp_enqueue_script('carpool-wallet-connect', plugin_dir_url(__FILE__) . 'build/index.js', array(), '1.0.0', true);
        wp_localize_script('carpool-wallet-connect', 'carpoolWalletData', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('carpool-wallet-connect'),
        ));
    }

    public function wallet_connect_shortcode() {
        return '<div id="carpool-wallet-connect"></div>';
    }

    public function display_discount_info() {
        if (!is_user_logged_in()) {
            return 'Please log in to see your discount information.';
        }

        $user_id = get_current_user_id();
        $delegated_amount = get_user_meta($user_id, 'carpool_delegated_amount', true);
        $delegation_epochs = get_user_meta($user_id, 'carpool_delegation_epochs', true);

        $woocommerce_integration = new CarPool_WooCommerce_Integration();
        $amount_discount = $woocommerce_integration->get_amount_tier_discount($delegated_amount);
        $epoch_discount = $woocommerce_integration->get_epoch_tier_discount($delegation_epochs);
        $total_discount = $amount_discount + $epoch_discount;

        $output = "<div class='carpool-discount-info'>";
        $output .= "<h3>Your CarPool Discount</h3>";
        $output .= "<p>Delegated Amount: {$delegated_amount} ADA</p>";
        $output .= "<p>Delegation Duration: {$delegation_epochs} epochs</p>";
        $output .= "<p>Total Discount: {$total_discount}%</p>";
        $output .= "</div>";

        return $output;
    }
}

new CarPool_Wallet_Connect_Plugin();
