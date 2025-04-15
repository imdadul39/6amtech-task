<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}
global $wpdb;
$table_name = $wpdb->prefix . 'contact_list';
$wpdb->query("DROP TABLE IF EXIsTS $table_name");
