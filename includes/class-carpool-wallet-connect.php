<?php

class CarPool_Wallet_Connect {
    private $api;
    private $user_roles;

    public function __construct() {
        $this->api = new CarPool_API();
        $this->user_roles = new CarPool_User_Roles();

        add_action('wp_ajax_carpool_connect_wallet', array($this, 'connect_wallet'));
        add_action('wp_ajax_nopriv_carpool_connect_wallet', array($this, 'connect_wallet'));
    }

    public function connect_wallet() {
        check_ajax_referer('carpool-wallet-connect', 'nonce');

        $address = sanitize_text_field($_POST['address']);
        $user_id = get_current_user_id();

        if ($user_id === 0) {
            wp_send_json_error('User not logged in');
            return;
        }

        update_user_meta($user_id, 'carpool_wallet_address', $address);

        $delegation_info = $this->api->get_delegation_info($address);
        update_user_meta($user_id, 'carpool_delegation_info', $delegation_info);

        $this->user_roles->update_user_role($user_id, $delegation_info);

        wp_send_json_success($delegation_info);
    }
}

