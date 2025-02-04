<?php
// File: includes/woocommerce-integration.php

function apply_carpool_discount($cart) {
    if (!is_user_logged_in()) return;

    $user_id = get_current_user_id();
    $discount_tier = get_user_meta($user_id, 'carpool_discount_tier', true);

    $discount_percentage = 0;

    switch ($discount_tier) {
        case 'Whale Diamond':
            $discount_percentage = 20;
            break;
        case 'Shark Platinum':
            $discount_percentage = 15;
            break;
        case 'Dolphin Gold':
            $discount_percentage = 10;
            break;
        case 'Fish Silver':
            $discount_percentage = 5;
            break;
        case 'Minnow Bronze':
            $discount_percentage = 2;
            break;
    }

    if ($discount_percentage > 0) {
        $discount = $cart->subtotal * ($discount_percentage / 100);
        $cart->add_fee('CarPool Discount', -$discount);
    }
}

add_action('woocommerce_cart_calculate_fees', 'apply_carpool_discount');
