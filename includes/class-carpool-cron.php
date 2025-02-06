<?php

class CarPool_Cron {
    private $api;
    private $user_roles;

    public function __construct() {
        $this->api = new CarPool_API();
        $this->user_roles = new CarPool_User_Roles();

        add_action('carpool_update_delegation_info', array($this, 'update_all_users_delegation_info'));
        
        if (!wp_next_scheduled('carpool_update_delegation_info')) {
            wp_schedule_event(time(), 'daily', 'carpool_update_delegation_info');
        }
    }

    public function update_all_users_delegation_info() {
        $users = get_users(array('role' => 'carpool_delegator'));
        
        foreach ($users as $user) {
            $wallet_address = get_user_meta($user->ID, 'carpool_wallet_address', true);
            
            if ($wallet_address) {
                $delegation_info = $this->api->get_delegation_info($wallet_address);
                update_user_meta($user->ID, 'carpool_delegation_info', $delegation_info);
                $this->user_roles->update_user_role($user->ID, $delegation_info);
            }
        }
    }
}

