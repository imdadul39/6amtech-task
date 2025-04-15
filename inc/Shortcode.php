<?php

namespace OurAutoLoadPlugin\Inc;

defined('ABSPATH') or die('Direct access not allowed');

class Shortcode
{
    public function __construct()
    {
        add_shortcode('contact_list', [$this, 'contact_list_shortcode']);
    }

    public function contact_list_shortcode()
    {
        ob_start();
?>

        <div class="container mt-4">
            <h4>Contact List</h4>
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Address</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    global $wpdb;
                    $table_name = $wpdb->prefix . 'contact_list';
                    $contacts = $wpdb->get_results("SELECT * FROM $table_name");

                    $total_contacts = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
                    $per_page = 5;
                    $page = isset($_GET['cpage']) ? max(1, intval($_GET['cpage'])) : 1;
                    $offset = ($page - 1) * $per_page;
                    $contacts = $wpdb->get_results("SELECT * FROM $table_name LIMIT $offset, $per_page");
                    $total_pages = ceil($total_contacts / $per_page);
                    $i = $offset + 1;

                    $i = 1;
                    if (!empty($contacts)) {
                        foreach ($contacts as $contact) {
                    ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo esc_html($contact->name); ?></td>
                                <td><?php echo esc_html($contact->email); ?></td>
                                <td><?php echo esc_html($contact->mobile); ?></td>
                                <td><?php echo esc_html($contact->address); ?></td>
                                <td>

                                    <button type="button" class="btn btn-sm btn-primary edit-btn" data-bs-toggle="modal" data-bs-target="#myFormModal" data-id="<?php echo $contact->id; ?>"
                                        data-name="<?php echo esc_attr($contact->name); ?>"
                                        data-email="<?php echo esc_attr($contact->email); ?>"
                                        data-mobile="<?php echo esc_attr($contact->mobile); ?>"
                                        data-address="<?php echo esc_attr($contact->address); ?>">
                                        Edit
                                    </button>
                                    <button class=" btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><a class="delete_btn" href="<?php echo admin_url('admin-post.php?action=delete_contact&id=' . $contact->id); ?>">Delete</a></button>
                                </td>
                            </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <nav>
                    <ul class="pagination justify-content-center">
                        <!-- Previous -->
                        <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                            <a class="page-link" href="<?php echo admin_url('admin.php?page=6amtech-admin-menu&cpage=' . ($page - 1)); ?>">Previous</a>
                        </li>

                        <!-- Page Numbers -->
                        <?php for ($p = 1; $p <= $total_pages; $p++) : ?>
                            <li class="page-item <?php if ($p == $page) echo 'active'; ?>">
                                <a class="page-link" href="<?php echo admin_url('admin.php?page=6amtech-admin-menu&cpage=' . $p); ?>"><?php echo $p; ?></a>
                            </li>
                        <?php endfor; ?>

                        <!-- Next -->
                        <li class="page-item <?php if ($page >= $total_pages) echo 'disabled'; ?>">
                            <a class="page-link" href="<?php echo admin_url('admin.php?page=6amtech-admin-menu&cpage=' . ($page + 1)); ?>">Next</a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>

            <div class="modal fade" id="myFormModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="<?php echo admin_url('admin-post.php'); ?>" method="post">

                            <input type="hidden" name="action" value="update_contact_data">
                            <input type="hidden" id="edit-id" name="id">

                            <div class="modal-header">
                                <h5 class="modal-title" id="formModalLabel">Data Update</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label>Name</label>
                                    <input id="edit-name" type="text" name="edit-name" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Email</label>
                                    <input id="edit-email" type="email" name="edit-email" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label>Mobile</label>
                                    <input id="edit-mobile" type="tel" name="edit-mobile" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Address</label>
                                    <textarea id="edit-address" name="edit-address" class="form-control" rows="3" required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">Update</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
<?php
        return ob_get_clean();
    }
}
