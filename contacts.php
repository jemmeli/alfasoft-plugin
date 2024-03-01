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
    wp_enqueue_style( 'bootstrap-4', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css' );
    //main style
    wp_enqueue_style( 'style', plugin_dir_url( __FILE__ ) . 'css/contacts.css', array(), time() , 'all' ); 
}
add_action('wp_enqueue_scripts', 'contacts_plugin_enqueue_styles');

//scripts
function contacts_script_footer(){
	wp_enqueue_script( 'jquery', 'https://code.jquery.com/jquery-1.12.4.js' );
	wp_enqueue_script( 'bootstrapjs', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js' );
	wp_enqueue_script( 'pooperjs', 'https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js' );

    //vue and axios
	wp_enqueue_script('vue', 'https://cdn.jsdelivr.net/npm/vue/dist/vue.js', [], '2.7.14');
    wp_enqueue_script('axios' , plugin_dir_url( __FILE__ ) . 'js/vendor/axios.js', 'vue', true );

    //main js file
    wp_enqueue_script('vueapp', plugin_dir_url( __FILE__ ) . 'js/main.js', 'vue' , true );

    //ajax
	wp_register_script( 'ajaxHandle', plugin_dir_url( __FILE__ ) . 'js/myajax.js', array(), rand(1, 1000), true  );
	wp_enqueue_script( 'ajaxHandle' );
	wp_localize_script( 'ajaxHandle', 'ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
} 

add_action('wp_footer', 'contacts_script_footer');


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
		PRIMARY KEY (id),
        UNIQUE KEY contact_unique (country_code, number),
        FOREIGN KEY (person_id) REFERENCES " . $wpdb->prefix . "person(id) ON DELETE CASCADE
	) $charset_collate;";
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
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