<?php
/*
Plugin Name: CarPool Wallet Connect
Description: Connect Cardano wallets
Version: 1.2
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
        wp_enqueue_script('carpool-wallet-connect', plugin_dir_url(__FILE__) . 'build/index.js', array('wp-element'), '1.2', true);
        wp_enqueue_style('carpool-wallet-connect', plugin_dir_url(__FILE__) . 'build/style.css', array(), '1.2');
        wp_localize_script('carpool-wallet-connect', 'carpoolWalletData', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('carpool-wallet-connect'),
        ));
    }

    public function wallet_connect_shortcode($atts) {
        $a = shortcode_atts(array(
            'icon_color' => '#0cc43e',
        ), $atts);

        return '<div id="carpool-wallet-connect" data-icon-color="' . esc_attr($a['icon_color']) . '"></div>';
    }

    public function stake_button_shortcode($atts) {
        $a = shortcode_atts(array(
            'text' => 'Stake with CarPool',
            'color' => '#0cc43e',
        ), $atts);

        return '<div class="carpool-stake-button" data-text="' . esc_attr($a['text']) . '" data-color="' . esc_attr($a['color']) . '"></div>';
    }

    public function check_delegation() {
        check_ajax_referer('carpool-wallet-connect', 'nonce');
        
        $user_id = get_current_user_id();
        if (!$user_id) {
            wp_send_json_error(array('message' => 'User not logged in.'));
        }

        $delegated_amount = $this->api->get_user_delegated_amount($user_id);
        $delegation_epochs = $this->api->get_user_delegation_epochs($user_id);

        wp_send_json_success(array(
            'delegated_amount' => $delegated_amount,
            'delegation_epochs' => $delegation_epochs
        ));
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
        $user_id = get_current_user_id();
        $delegated_amount = get_user_meta($user_id, 'carpool_delegated_amount', true);
        return $delegated_amount > 0;
    }
}

new CarPool_Wallet_Connect_Plugin();
