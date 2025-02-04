<?php
// File: includes/user-roles.php

function carpool_add_custom_roles() {
    add_role('carpool_delegator', 'CarPool Delegator', array(
        'read' => true,
        'access_carpool_content' => true
    ));
}

add_action('init', 'carpool_add_custom_roles');

function carpool_check_user_permissions() {
    if (current_user_can('access_carpool_content')) {
        return true;
    }
    return false;
}
