<?php

namespace OurAutoLoadPlugin\Inc;

defined('ABSPATH') or die('Direct access not allowed');

class Add_Contact
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'custom_admin_menu']);
        add_action('init', [$this, 'handle_contact_form_submission']);
        add_action('admin_post_delete_contact', [$this, 'handle_delete_contact']);
        add_action('admin_post_update_contact_data', [$this, 'handle_contact_update']);
    }
    public function custom_admin_menu()
    {
        add_menu_page('Admin Menu', 'Admin Menu', 'manage_options', '6amtech-admin-menu', [$this, 'admin_menu_callback'], 'dashicons-admin-users', 81);
    }

    public function admin_menu_callback()
    {
?>
        <div class="container mt-4">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="contact-list-tab" data-bs-toggle="tab" data-bs-target="#contact-list" type="button" role="tab" aria-controls="contact-list" aria-selected="true"> Contact List </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="add-contact-tab" data-bs-toggle="tab" data-bs-target="#add-contact" type="button" role="tab" aria-controls="add-contact" aria-selected="false"> Add New Contact </button>
                </li>
            </ul>

            <div class="tab-content mt-3" id="myTabContent">
                <div class="tab-pane fade show active" id="contact-list" role="tabpanel" aria-labelledby="contact-list-tab">
                    <div class="container mt-4">
                        <h5>Shortcode: [contact_list]</h5>
                        <?php echo do_shortcode('[contact_list]'); ?>
                    </div>
                </div>

                <div class="tab-pane fade" id="add-contact" role="tabpanel" aria-labelledby="add-contact-tab">
                    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                        <script>
                            jQuery(document).ready(function($) {
                                setTimeout(function() {
                                    toastr.success('Added successfully!');
                                }, 300);
                            });
                        </script>
                    <?php endif; ?>
                    <?php
                    if (isset($_GET['delete']) && $_GET['delete'] === 'success') : ?>
                        <script>
                            jQuery(document).ready(function($) {
                                toastr.success('Deleted successfully!', 'Success');
                            }, 300);
                        </script>
                    <?php endif; ?>
                    <?php if (isset($_GET['updated']) && $_GET['updated'] == 'success'): ?>
                        <script>
                            jQuery(document).ready(function($) {
                                setTimeout(function() {
                                    toastr.success('Update successfully!');
                                }, 300);
                            });
                        </script>
                    <?php endif; ?>

                    <div class="container mt-4">
                        <form method="post" action="">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="contact_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="contact_email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mobile</label>
                                <input type="tel" name="contact_mobile" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <textarea name="contact_address" class="form-control" required></textarea>
                            </div>
                            <button type="submit" name="submit_contact_form" class="btn btn-success">Add Contact</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
<?php
    }

    public function handle_contact_form_submission()
    {
        if (isset($_POST['submit_contact_form'])) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'contact_list';

            $name = sanitize_text_field($_POST['contact_name']);
            $email = sanitize_email($_POST['contact_email']);
            $mobile = isset($_POST['contact_mobile']) ? preg_replace('/[^0-9]/', '', $_POST['contact_mobile']) : '';
            $address = sanitize_textarea_field($_POST['contact_address']);
            $wpdb->insert($table_name, array(
                'name'  => $name,
                'email'  => $email,
                'mobile'  => $mobile,
                'address'  => $address,
            ));
            wp_redirect(admin_url('admin.php?page=6amtech-admin-menu&success=1'));
            exit;
        }
    }

    public function handle_delete_contact()
    {
        if (isset($_GET['id'])) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'contact_list';
            $wpdb->delete($table_name, ['id' => $_GET['id']]);
        }
        wp_redirect(admin_url('admin.php?page=6amtech-admin-menu&delete=success'));
        exit;
    }

    public function handle_contact_update()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'contact_list';
        $id = $_POST['id'];
        $edit_name = sanitize_text_field($_POST['edit-name']);
        $edit_email = sanitize_text_field($_POST['edit-email']);
        $edit_mobile = sanitize_text_field($_POST['edit-mobile']);
        $edit_address = sanitize_textarea_field($_POST['edit-address']);


        $wpdb->update($table_name, array(
            'name' =>  $edit_name,
            'email' => $edit_email,
            'mobile' => $edit_mobile,
            'address' => $edit_address,
        ), array('id' =>  $id));
        wp_redirect(admin_url('admin.php?page=6amtech-admin-menu&updated=success'));
        exit;
    }
}
