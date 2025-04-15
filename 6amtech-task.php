<?php

/**
 * Plugin Name:       6amtech Task
 * Plugin URI:        https://example.com/6amtech-task
 * Description:       A custom plugin developed for 6amtech assignment.
 * Version:           1.0
 * Requires at least: 5.0
 * Requires PHP:      7.4
 * Author:            Imdadul Haque
 * Author URI:        https://github.com/imdadul39
 * License:           GPL-2.0+
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       6amtech-task
 */

namespace OurAutoLoadPlugin;


defined('ABSPATH') or die('Direct access not allowed');


require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';


class Task_Work_6amtech
{
    public function __construct()
    {
        register_activation_hook(__FILE__, array($this, 'create_contact_list_table'));
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
        new \OurAutoLoadPlugin\Inc\Shortcode();
        new \OurAutoLoadPlugin\Inc\Add_Contact();
        new \OurAutoLoadPlugin\Inc\Welcome_Message();
    }

    // Create Contact List Table ********************
    public function create_contact_list_table()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'contact_list';
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE IF NOT EXISTS $table_name(
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            mobile VARCHAR(20) NOT NULL,
            address TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )$charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }




    function enqueue_admin_assets()
    {

        // Local toastr CSS
        wp_enqueue_style('toastr-css', plugin_dir_url(__FILE__) . 'assets/css/toastr.min.css');

        // Local Bootstrap CSS
        wp_enqueue_style('bootstrap-css', plugin_dir_url(__FILE__) . 'assets/css/bootstrap.min.css');

        // Custom CSS 
        wp_enqueue_style('custom-css', plugin_dir_url(__FILE__) . 'assets/css/style.css');

        // jQuery (WordPress built-in)
        wp_enqueue_script('jquery');

        // Local Bootstrap JS
        wp_enqueue_script('bootstrap-js', plugin_dir_url(__FILE__) . 'assets/js/bootstrap.bundle.min.js', array(), null, true);

        // Custom JS
        wp_enqueue_script('custom-js', plugin_dir_url(__FILE__) . 'assets/js/script.js', array(), null, true);

        // Local toastr JS
        wp_enqueue_script('toastr-js', plugin_dir_url(__FILE__) . 'assets/js/toastr.min.js', array('jquery'), null, true);
    }
}
new Task_Work_6amtech();
