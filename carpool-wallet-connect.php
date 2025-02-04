<?php
// File: carpool-wallet-connect.php

/**
 * Plugin Name: CarPool Wallet Connect
 * Description: A Cardano wallet connector with delegation and discount features
 * Version: 1.0
 * Author: Your Name
 */

if (!defined('ABSPATH')) exit;

require_once plugin_dir_path(__FILE__) . 'includes/enqueue-scripts.php';
require_once plugin_dir_path(__FILE__) . 'includes/cardano-api.php';
require_once plugin_dir_path(__FILE__) . 'includes/user-roles.php';
require_once plugin_dir_path(__FILE__) . 'includes/woocommerce-integration.php';
require_once plugin_dir_path(__FILE__) . 'includes/delegation-tracker.php';
