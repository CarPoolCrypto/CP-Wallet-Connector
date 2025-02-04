<?php
// class-carpool-cron.php

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
            $delegated_amount = $this->api->get_user_delegated_amount($user->ID);
            $delegation_epochs = $this->api->get_user_delegation_epochs($user->ID);

            update_user_meta($user->ID, 'carpool_delegated_amount', $delegated_amount);
            update_user_meta($user->ID, 'carpool_delegation_epochs', $delegation_epochs);

            $this->user_roles->update_user_role($user->ID, $delegated_amount);
        }
    }
}

new CarPool_Cron();
