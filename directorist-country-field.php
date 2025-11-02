<?php
/**
 * Plugin Name: Directorist Country Field
 * Description: Adds ISO 639-1 country selector to Directorist listings.
 * Version: 1.1.2
 * Author: Mahbub
 * Text Domain: directorist-country-field
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define constants
define( 'DLF_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'DLF_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'DLF_PLUGIN_FILE', __FILE__ );

// var_dump( file_exists( DLF_PLUGIN_DIR . 'includes/Admin.php' ) );
// exit;

// Include plugin classes manually
require_once DLF_PLUGIN_DIR . 'includes/Common.php';
require_once DLF_PLUGIN_DIR . 'includes/Admin.php';
require_once DLF_PLUGIN_DIR . 'includes/Front.php';

require_once DLF_PLUGIN_DIR . 'functions.php';

// Initialize plugin after WordPress loads
add_action( 'plugins_loaded', function() {

    if ( class_exists( 'DLF\Common' ) ) {
        new DLF\Common();

    }

    if ( is_admin() && class_exists( 'DLF\Admin' ) ) {
        new DLF\Admin();
    }

    if ( ! is_admin() && class_exists( 'DLF\Front' ) ) {
        new DLF\Front();
    }

    // Register Directorist country field if class exists
    if ( class_exists( 'DLF\Directorist_Country_Field' ) ) {
        new DLF\Directorist_Country_Field();
    }

  
  

});
