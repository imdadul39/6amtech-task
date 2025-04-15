jQuery(document).ready(function ($) {
    $('.edit-btn').on('click', function () {
        let id = $(this).data('id');
        let name = $(this).data('name');
        let email = $(this).data('email');
        let mobile = $(this).data('mobile');
        let address = $(this).data('address');

        $('#edit-id').val(id);
        $('#edit-name').val(name);
        $('#edit-email').val(email);
        $('#edit-mobile').val(mobile);
        $('#edit-address').val(address);
    });
});
