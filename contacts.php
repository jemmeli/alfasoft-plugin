<?php
/*
* Plugin Name: Contacts Plugin
* Plugin URI: https://wordpress.org/plugins/Alfasoft/
* Description: Contact Management Plugin.
* Version: 0.0.1
* Author: Made with Jemmeli Nejmeddine
* Author URI: https://github.com/jemmeli
* Author Email: jemmeli84@gmail.com
*/

/*======================
===Styles & Scripts=====
=======================*/
//styles
function contacts_plugin_enqueue_styles() {
    //bootstrap
    //wp_enqueue_style( 'bootstrap-4', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css' );
    //main style
    wp_enqueue_style( 'style', plugin_dir_url( __FILE__ ) . 'css/contacts.css', array(), time() , 'all' ); 
}
add_action('admin_enqueue_scripts', 'contacts_plugin_enqueue_styles');

//scripts
function contacts_script_footer(){
	wp_enqueue_script( 'jquery', 'https://code.jquery.com/jquery-1.12.4.js' );
	wp_enqueue_script( 'bootstrapjs', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js' );
	wp_enqueue_script( 'pooperjs', 'https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js' );

    //main js file
    wp_enqueue_script('main-js', plugin_dir_url( __FILE__ ) . 'js/main.js', array(), rand(1, 1000) , true );

    //ajax
	wp_register_script( 'ajaxHandle', plugin_dir_url( __FILE__ ) . 'js/myajax.js', array(), rand(1, 1000), true  );
	wp_enqueue_script( 'ajaxHandle' );
	wp_localize_script( 'ajaxHandle', 'ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
} 

add_action('admin_enqueue_scripts', 'contacts_script_footer');


/*======================
===General Func=========
=======================*/
function cc($var){
	echo "<pre>";
	print_r($var); 
	echo "</pre>";
	die();
}

/*======================
===plugin activated=====
=======================*/
function contacts_plugin_activate(){

    global $wpdb;
    
    $person = $wpdb->prefix . 'person';
	$charset_collate = $wpdb->get_charset_collate();
	$sql = "CREATE TABLE $person (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		name VARCHAR(255) NOT NULL,
		email VARCHAR(255) NOT NULL,
        avatar_url VARCHAR(255),
        deleted TINYINT NOT NULL DEFAULT 0,
        UNIQUE KEY email_unique (email),
		PRIMARY KEY (id)
	) $charset_collate;";
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

    $contact = $wpdb->prefix . 'contact';
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $contact (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        person_id INT NOT NULL,
        country_code VARCHAR(10) NOT NULL,
        number VARCHAR(9) NOT NULL,
        deleted TINYINT NOT NULL DEFAULT 0,
        PRIMARY KEY (id),
        UNIQUE KEY contact_unique (country_code, number)
        /*FOREIGN KEY (person_id) REFERENCES wp_person(id) ON DELETE CASCADE*/
    ) $charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__ , "contacts_plugin_activate");

/*======================
===plugin Deactivated===
=======================*/
function contacts_plugin_deactivate(){

    global $wpdb;

    //delete person table
    $person = $wpdb->prefix . "person";
    $sql = "DROP TABLE IF EXISTS $person";
    $wpdb->query($sql);
    delete_option("my_plugin_db_version");


    //delete contact table
    $contact = $wpdb->prefix . "contact";
    $sql = "DROP TABLE IF EXISTS $contact";
    $wpdb->query($sql);
    delete_option("my_plugin_db_version");
}
register_deactivation_hook(__FILE__ , "contacts_plugin_deactivate");


/*======================
===LISTS PERSONS PAGE===
=======================*/
// Add admin menu
function contact_manager_plugin_menu() {
    add_menu_page('Contact Manager', 'Contact Manager', 'manage_options', 'contact_manager', 'admin_list_person_page');
}

function admin_list_person_page() {
    include(plugin_dir_path(__FILE__) . 'admin/admin-list-person.php');
}

// Hook for adding admin menus
add_action('admin_menu', 'contact_manager_plugin_menu');

/*=========================
===ADD/EDIT PERSONS PAGE===
==========================*/
function contact_manager_submenu() {
    add_submenu_page(
        'contact_manager',
        'Add/Edit Person',
        'Add/Edit Person',
        'manage_options',
        'add_edit_person',
        'admin_add_edit_person_page'
    );
}

function admin_add_edit_person_page() {
    include(plugin_dir_path(__FILE__) . 'admin/admin-add-edit-person.php');
}

// Hook for adding submenu
add_action('admin_menu', 'contact_manager_submenu');

/*=========================
===ADD/EDIT CONTACTS PAGE===
==========================*/
function contact_manager_contact_submenu() {
    add_submenu_page(
        'contact_manager',
        'Add/Edit Contact',
        'Add/Edit Contact',
        'manage_options',
        'add_edit_contact',
        'admin_add_edit_contact_page'
    );
}

function admin_add_edit_contact_page() {
    include(plugin_dir_path(__FILE__) . 'admin/admin-add-edit-contact.php');
}

// Hook for adding submenu
add_action('admin_menu', 'contact_manager_contact_submenu');

/*=========================
===DELETE PERSON===========
==========================*/
function delete_manager_contact_submenu() {
    add_submenu_page(
        'contact_manager',
        'DELETE Person',
        'DELETE Person',
        'manage_options',
        'delete_person',
        'admin_delete_person_page'
    );
}

function admin_delete_person_page() {
    include(plugin_dir_path(__FILE__) . 'admin/delete_person.php');
}

// Hook for adding submenu
add_action('admin_menu', 'delete_manager_contact_submenu');