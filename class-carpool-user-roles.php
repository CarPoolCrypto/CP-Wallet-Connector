<?php
// class-carpool-user-roles.php

class CarPool_User_Roles {
    public function __construct() {
        add_action('init', array($this, 'register_user_roles'));
    }

    public function register_user_roles() {
        add_role('carpool_delegator', 'CarPool Delegator', array('read' => true));
        add_role('carpool_shrimp', 'CarPool Shrimp', array('read' => true));
        add_role('carpool_tuna', 'CarPool Tuna', array('read' => true));
        add_role('carpool_dolphin', 'CarPool Dolphin', array('read' => true));
        add_role('carpool_shark', 'CarPool Shark', array('read' => true));
        add_role('carpool_whale', 'CarPool Whale', array('read' => true));
    }

    public function update_user_role($user_id, $delegated_amount) {
        $user = new WP_User($user_id);
        
        // Remove all CarPool roles
        $user->remove_role('carpool_delegator');
        $user->remove_role('carpool_shrimp');
        $user->remove_role('carpool_tuna');
        $user->remove_role('carpool_dolphin');
        $user->remove_role('carpool_shark');
        $user->remove_role('carpool_whale');

        // Assign new role based on delegated amount
        if ($delegated_amount >= 1000000) {
            $user->add_role('carpool_whale');
        } elseif ($delegated_amount >= 100000) {
            $user->add_role('carpool_shark');
        } elseif ($delegated_amount >= 10000) {
            $user->add_role('carpool_dolphin');
        } elseif ($delegated_amount >= 1000) {
            $user->add_role('carpool_tuna');
        } elseif ($delegated_amount > 0) {
            $user->add_role('carpool_shrimp');
        }

        if ($delegated_amount > 0) {
            $user->add_role('carpool_delegator');
        }
    }
}
