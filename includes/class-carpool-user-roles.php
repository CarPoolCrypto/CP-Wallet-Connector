<?php

class CarPool_User_Roles {
    public function __construct() {
        add_action('init', array($this, 'register_user_roles'));
    }

    public function register_user_roles() {
        add_role('carpool_delegator', 'CarPool Delegator', array('read' => true));
        add_role('carpool_whale', 'CarPool Whale', array('read' => true));
        add_role('carpool_shark', 'CarPool Shark', array('read' => true));
        add_role('carpool_dolphin', 'CarPool Dolphin', array('read' => true));
        add_role('carpool_tuna', 'CarPool Tuna', array('read' => true));
        add_role('carpool_shrimp', 'CarPool Shrimp', array('read' => true));
    }

    public function update_user_role($user_id, $delegation_info) {
        $user = new WP_User($user_id);
        
        // Remove all CarPool roles
        $user->remove_role('carpool_delegator');
        $user->remove_role('carpool_whale');
        $user->remove_role('carpool_shark');
        $user->remove_role('carpool_dolphin');
        $user->remove_role('carpool_tuna');
        $user->remove_role('carpool_shrimp');

        if ($delegation_info['is_delegated']) {
            $user->add_role('carpool_delegator');

            // Assign role based on delegated amount
            $amount = $delegation_info['amount'];
            if ($amount >= 500000) {
                $user->add_role('carpool_whale');
            } elseif ($amount >= 150000) {
                $user->add_role('carpool_shark');
            } elseif ($amount >= 50000) {
                $user->add_role('carpool_dolphin');
            } elseif ($amount >= 10000) {
                $user->add_role('carpool_tuna');
            } else {
                $user->add_role('carpool_shrimp');
            }
        }
    }
}

