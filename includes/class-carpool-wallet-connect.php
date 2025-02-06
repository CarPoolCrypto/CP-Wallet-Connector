<?php
// class-carpool-wallet-connect.php

class CarPool_Wallet_Connect {
    private $api;
    private $user_roles;

    public function __construct() {
        $this->api = new CarPool_API();
        $this->user_roles = new CarPool_User_Roles();

        add_action('wp_ajax_connect_wallet', array($this, 'connect_wallet'));
        add_action('wp_ajax_nopriv_connect_wallet', array($this, 'connect_wallet'));
        add_action('wp_ajax_update_delegation_info', array($this, 'update_delegation_info'));
    }

    public function connect_wallet() {
        check_ajax_referer('carpool-wallet-connect', 'nonce');
        
        $wallet_address = sanitize_text_field($_POST['wallet_address']);
        $user_id = get_current_user_id();

        if (!$user_id) {
            wp_send_json_error(array('message' => 'User not logged in.'));
        }

        update_user_meta($user_id, 'carpool_wallet_address', $wallet_address);

        $this->update_delegation_info();

        wp_send_json_success(array('message' => 'Wallet connected successfully'));
    }

    public function update_delegation_info() {
        $user_id = get_current_user_id();

        if (!$user_id) {
            wp_send_json_error(array('message' => 'User not logged in.'));
        }

        $delegated_amount = $this->api->get_user_delegated_amount($user_id);
        $delegation_epochs = $this->api->get_user_delegation_epochs($user_id);

        update_user_meta($user_id, 'carpool_delegated_amount', $delegated_amount);
        update_user_meta($user_id, 'carpool_delegation_epochs', $delegation_epochs);

        $this->user_roles->update_user_role($user_id, $delegated_amount);

        wp_send_json_success(array(
            'delegated_amount' => $delegated_amount,
            'delegation_epochs' => $delegation_epochs
        ));
    }
}
