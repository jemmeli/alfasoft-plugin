<div class="wrap">
    <h1 class="wp-heading-inline">Add/Edit Person</h1>
    <p><a href="<?php echo admin_url('admin.php?page=contact_manager'); ?>" class="button button-primary">Return To Lists (Admin)</a></p>

    <?php
    global $wpdb;

    $person_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $person = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}person WHERE id = $person_id");

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Process form submission
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);

        if ($person_id) {
            // Update existing person
            $wpdb->update(
                "{$wpdb->prefix}person",
                array('name' => $name, 'email' => $email),
                array('id' => $person_id),
                array('%s', '%s'),
                array('%d')
            );

        } else {
            // Insert new person
            $wpdb->insert(
                "{$wpdb->prefix}person",
                array('name' => $name, 'email' => $email),
                array('%s', '%s')
            );

        }
        
        
    }
    ?>

    <form method="post" action="?page=add_edit_person">
        <label for="name">Name:</label>
        <input type="text" name="name" value="<?php if( isset($person) ) { echo esc_attr($person->name); } ?>" required>
        <br>

        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php if( isset($person) ) { echo esc_attr($person->email); } ?>" required>
        <br>

        <input type="submit" class="button button-primary" value="<?php echo $person_id ? 'Update' : 'Add'; ?> Person">
    </form>
</div>