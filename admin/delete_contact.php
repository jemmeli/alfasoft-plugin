<?php
// Assuming you have $contact_id from your $_GET parameters
$contact_id = isset($_GET['contact_id']) ? intval($_GET['contact_id']) : 0;
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Soft delete the contact by setting 'deleted' to 1
global $wpdb;
$wpdb->update(
    "{$wpdb->prefix}contact",
    array('deleted' => 1),
    array('id' => $contact_id),
    array('%d'),
    array('%d')
);
?>

<p>Contact Deleted! <a href="<?php echo admin_url('admin.php?page=contact_manager'); ?>" class="button button-primary">Return To Persons List</a></p>

<?php
// Redirect to the person list or any other desired page
wp_redirect(admin_url('admin.php?page=person_list'));
exit();