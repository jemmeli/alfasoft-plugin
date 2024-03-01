<?php
global $wpdb;

$person_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
?>
<div class="wrap">
    <h1 class="wp-heading-inline"> Contact</h1>
    <p><a href="<?php echo admin_url('admin.php?page=contact_manager'); ?>" class="button button-primary">Return To Lists (Admin)</a></p>

    <?php
    $contact_id = isset($_GET['contact_id']) ? intval($_GET['contact_id']) : 0;
    //die($contact_id);
    $contact = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}contact WHERE id = $contact_id");

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Process form submission
        $person_id = sanitize_text_field($_POST['person_id']);
        $contact_id = sanitize_text_field($_POST['contact_id']);
        $country_code = sanitize_text_field($_POST['country_code']);
        $number = sanitize_text_field($_POST['number']);

        if ($contact_id) {
            // Update existing contact
            $wpdb->update(
                "{$wpdb->prefix}contact",
                array('country_code' => $country_code, 'number' => $number),
                array('id' => $contact_id),
                array('%s', '%s'),
                array('%d')
            );
        } else {
            // Insert new contact
            $wpdb->insert(
                "{$wpdb->prefix}contact",
                array('person_id' => $person_id, 'country_code' => $country_code, 'number' => $number),
                array('%d', '%s', '%s')
            );
        }
    }
    ?>

    <form method="post" action="?page=add_edit_contact">
        <!-- Hidden input to store and retrieve person_id -->
        <input type="hidden" name="person_id" value="<?php echo esc_attr($person_id); ?>">

        <label for="country_code">Country Code:</label>
        <input type="text" name="country_code" value="<?php if( isset($contact) ) { echo esc_attr($contact->country_code); } ?>" required>
        <br>

        <label for="number">Number:</label>
        <input type="text" name="number" pattern="[A-Za-z0-9]{9}" value="<?php if( isset($contact) ) { echo esc_attr($contact->number); } ?>" required>
        <br>
        <?php if( isset($_GET['id']) ) {?>
        <input type="hidden" id="person_id" name="person_id" value="<?php if( isset($_GET['id']) ) { echo $_GET['id']; } ?>" />
        <?php } ?>

        <?php if( isset($_GET['contact_id']) ) {?>
        <input type="hidden" id="contact_id" name="contact_id" value="<?php if( isset($_GET['contact_id']) ) { echo $_GET['contact_id']; } ?>" />
        <?php } ?>

        <input type="submit" class="button button-primary" value="<?php echo $contact_id ? 'Update' : 'Add'; ?> Contact">
    </form>
</div>