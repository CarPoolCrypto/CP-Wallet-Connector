<?php
/*
Plugin Name: CarPool Wallet Connect
Description: Connect Cardano wallets and provide tiered discounts for CarPool delegators.
Version: 1.1
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
        add_action('wp_ajax_check_delegation', array($this, 'check_delegation'));
        add_action('wp_ajax_nopriv_check_delegation', array($this, 'check_delegation'));
        add_filter('the_content', array($this, 'check_content_permissions'));
    }

    public function init_plugin() {
        new CarPool_Wallet_Connect();
        new CarPool_WooCommerce_Integration();
        new CarPool_Cron();
    }

    public function enqueue_scripts() {
        wp_enqueue_script('carpool-wallet-connect', plugin_dir_url(__FILE__) . 'build/index.js', array(), '1.1', true);
        wp_localize_script('carpool-wallet-connect', 'carpoolWalletData', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('carpool-wallet-connect'),
        ));
    }

    public function wallet_connect_shortcode($atts) {
        $a = shortcode_atts(array(
            'icon_color' => '#000000',
        ), $atts);

        return '<div id="carpool-wallet-connect" data-icon-color="' . esc_attr($a['icon_color']) . '"></div>';
    }

    public function stake_button_shortcode($atts) {
        $a = shortcode_atts(array(
            'text' => 'Stake with CarPool',
            'color' => '#000000',
        ), $atts);

        return '<div class="carpool-stake-button" data-text="' . esc_attr($a['text']) . '" data-color="' . esc_attr($a['color']) . '"></div>';
    }

    public function check_delegation() {
        // Implement delegation check logic here
        // This should query the Cardano blockchain or your database
        // Return delegation info as JSON
    }

    public function check_content_permissions($content) {
        if (is_singular() && in_the_loop() && is_main_query()) {
            $requires_delegation = get_post_meta(get_the_ID(), 'requires_carpool_delegation', true);
            if ($requires_delegation && !$this->user_is_delegated()) {
                return 'This content is only available to CarPool delegators.';
            }
        }
        return $content;
    }

    private function user_is_delegated() {
        // Implement logic to check if the current user is delegated to CarPool
        // This might involve checking user meta or querying the blockchain
    }
}

new CarPool_Wallet_Connect_Plugin();
