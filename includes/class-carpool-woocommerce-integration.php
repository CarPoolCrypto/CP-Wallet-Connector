<?php

class CarPool_WooCommerce_Integration {
    public function __construct() {
        add_filter('woocommerce_cart_get_total', array($this, 'apply_carpool_discount'), 10, 1);
    }

    public function apply_carpool_discount($total) {
        if (!is_user_logged_in()) {
            return $total;
        }

        $user_id = get_current_user_id();
        $delegation_info = get_user_meta($user_id, 'carpool_delegation_info', true);

        if (!$delegation_info || !$delegation_info['is_delegated']) {
            return $total;
        }

        $amount_discount = $this->get_amount_tier_discount($delegation_info['amount']);
        $epoch_discount = $this->get_epoch_tier_discount($delegation_info['epochs']);

        $total_discount_percentage = $amount_discount + $epoch_discount;
        $discount_amount = $total * ($total_discount_percentage / 100);

        return $total - $discount_amount;
    }

    private function get_amount_tier_discount($amount) {
        $amount_tiers = array(
            'Whale' => array('min' => 1000000, 'discount' => 16),
            'Shark' => array('min' => 100000, 'discount' => 14),
            'Dolphin' => array('min' => 10000, 'discount' => 12),
            'Tuna' => array('min' => 1000, 'discount' => 10),
            'Shrimp' => array('min' => 0, 'discount' => 8)
        );

        foreach ($amount_tiers as $tier) {
            if ($amount >= $tier['min']) {
                return $tier['discount'];
            }
        }

        return 0;
    }

    private function get_epoch_tier_discount($epochs) {
        $epoch_tiers = array(
            'Diamond' => array('min' => 72, 'discount' => 10),
            'Platinum' => array('min' => 54, 'discount' => 8),
            'Gold' => array('min' => 36, 'discount' => 6),
            'Silver' => array('min' => 18, 'discount' => 4),
            'Bronze' => array('min' => 1, 'discount' => 2)
        );

        foreach ($epoch_tiers as $tier) {
            if ($epochs >= $tier['min']) {
                return $tier['discount'];
            }
        }

        return 0;
    }
}

