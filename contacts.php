<?php
/*
* Plugin Name: Contacts Plugin
* Plugin URI: https://wordpress.org/plugins/Viager/
* Description: Contact Management Plugin.
* Version: 0.0.1
* Author: Made with Jemmeli Nejmeddine
* Author URI: https://github.com/jemmeli
* Author Email: jemmeli84@gmail.com
*/

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

add_action('wp_footer', 'add_this_script_footer');