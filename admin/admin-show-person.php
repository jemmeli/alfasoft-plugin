<div class="wrap">
    <h1 class="wp-heading-inline">Person Details</h1>
    <p><a href="<?php echo admin_url('admin.php?page=contact_manager'); ?>" class="button button-primary">Back to Person List</a></p>

    <?php
    global $wpdb;

    // Assuming you have $person_id from your $_GET parameters
    $person_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    // Fetch person information
    $person = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}person WHERE id = $person_id && deleted = 0");

    if ($person) {
        ?>
        <h2><?php echo esc_html($person->name); ?></h2>
        <p>Email: <?php echo esc_html($person->email); ?></p>

        <h3>Contact List</h3>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Country Code</th>
                    <th>Number</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

                <?php
                // Fetch contacts associated with the person
                $contacts = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}contact WHERE person_id = $person_id && deleted = 0");

                foreach ($contacts as $contact) {
                    ?>
                    <tr>
                        <td><?php echo $contact->id; ?></td>
                        <td><?php echo esc_html($contact->country_code); ?></td>
                        <td><?php echo esc_html($contact->number); ?></td>
                        <td>
                            <a href="<?php echo admin_url('admin.php?page=add_edit_contact&id=' . $person_id . '&contact_id=' . $contact->id); ?>">Edit</a>
                            |
                            <form method="post" action="<?php echo admin_url('admin.php?page=delete_contact&contact_id=' . $contact->id . '&person_id=' . $person_id); ?>" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this contact?');">
                                <button type="submit" style="background: none; border: none; color: #0073aa; cursor: pointer;">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>

            </tbody>
        </table>
        <?php
    } else {
        echo "Person not found!";
    }
    ?>
</div>