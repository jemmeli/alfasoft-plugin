<?php
// Assuming you have $person_id from your $_GET parameters
$person_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Soft delete the person by setting 'deleted' to 1
global $wpdb;
$wpdb->update(
    "{$wpdb->prefix}person",
    array('deleted' => 1),
    array('id' => $person_id),
    array('%d'),
    array('%d')
);
?>

<p>Person Deleted ! <a href="<?php echo admin_url('admin.php?page=contact_manager'); ?>" class="button button-primary">Return To Lists</a></p>

<?php
// Redirect to the person list
wp_redirect(admin_url('admin.php?page=person_list'));
exit();