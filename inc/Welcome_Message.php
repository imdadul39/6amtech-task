<?php

namespace OurAutoLoadPlugin\Inc;

defined('ABSPATH') or die('Direct access not allowed');

class Welcome_Message
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'add_settings_menu']);
        add_filter('the_content', [$this, 'add_welcome_message_to_content']);
    }

    // Add Setting Page 
    public function add_settings_menu()
    {
        add_options_page('Welcome Message Settings', 'Welcome Message', 'manage_options', 'welcome-message-settings', [$this, 'render_settings_page']);
    }
    // Render Settings Page
    public function render_settings_page()
    {
        if (isset($_POST['welcome_message_custom'])) {
            $message = sanitize_text_field($_POST['welcome_message_custom']);
            update_option('welcome_message', $message);
        }
        // Fetch saved data
        $saved_message = get_option('welcome_message');
?>
        <div class="wrap">
            <h1>Welcome Message Settings</h1>
            <form method="post">
                <label for="welcome_message_custom"><strong>Enter your welcome message:</strong></label><br>
                <textarea name="welcome_message_custom" id="welcome_message_custom" rows="5" cols="50"><?php echo esc_textarea($saved_message) ?></textarea><br><br>
                <input type="submit" class="button button-primary" value="Save Message">
            </form>
        </div>
<?php
    }




    // Add Message to Post Content
    public function add_welcome_message_to_content($content)
    {
        if (is_singular('post')) {
            $message = get_option('welcome_message');
            if ($message) {
                $welcome = '<div class="welcome-message" style="padding:10px; background:#f1f1f1; margin-bottom:20px;">' . wp_kses_post($message) . '</div>';
                $content = $welcome . $content;
            }
        }
        return $content;
    }
}
