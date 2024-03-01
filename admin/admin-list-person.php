<div class="wrap">
    <h1 class="wp-heading-inline">person List (Private)</h1>
    <p><a href="<?php echo admin_url('admin.php?page=add_edit_person'); ?>" class="button button-primary">Add New Contact</a></p>

    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>

            <?php
            global $wpdb;

            // Fetch person from the database
            $person = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}person");

            foreach ($person as $person) {
                ?>
                <tr>
                    <td><?php echo $person->id; ?></td>
                    <td><?php echo $person->name; ?></td>
                    <td><?php echo $person->email; ?></td>
                    <td>
                        <a href="<?php echo admin_url('admin.php?page=add_edit_person&id=' . $person->id); ?>">Edit</a>
                        |
                        <a href="<?php echo admin_url('admin.php?page=delete_person&id=' . $person->id); ?>">Delete</a>
                        |
                        <a href="<?php echo admin_url('admin.php?page=add_contact&id=' . $person->id); ?>">Add Contact</a>
                    </td>
                </tr>
            <?php } ?>

        </tbody>
    </table>
</div>