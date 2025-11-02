<?php
/**
 * Plugin Name: Directorist Country Field
 * Description: Adds ISO 639-1 country selector to Directorist listings.
 * Version: 1.1.0
 * Author: Mahbub
 * Text Domain: directorist-country-field
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'DLF_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'DLF_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'DLF_PLUGIN_FILE', __FILE__ );

// Load Composer autoloader
require_once 'vendor/autoload.php';

// Initialize plugin after WordPress loads
add_action( 'plugins_loaded', function() {

    new DLF\Common();

    if ( is_admin() ) {
        new DLF\Admin();
    } else {
        new DLF\Front();
    }
});
