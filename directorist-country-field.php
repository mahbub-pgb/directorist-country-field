<?php
/**
 * Plugin Name: Directorist Country Field
 * Plugin URI: https://techwithmahbub.com/
 * Description: Adds ISO 639-1 country selector to Directorist listings.
 * Version: 1.1.0
 * Author: Mahbub
 * Author URI: https://techwithmahbub.com/
 * Text Domain: directorist-country-field
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'DLF_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'DLF_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'DLF_PLUGIN_FILE', __FILE__ );

// ----------------------------
// Load Composer autoloader safely
// ----------------------------
$autoload_path = DLF_PLUGIN_DIR . 'vendor/autoload.php';
if ( file_exists( $autoload_path ) ) {
    include $autoload_path; // safe include
} else {
    // Optional: log missing autoload
    if ( defined('WP_DEBUG') && WP_DEBUG ) {
        error_log('DLF: vendor/autoload.php not found.');
    }
}

// ----------------------------
// Initialize plugin after WordPress loads
// ----------------------------
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

});
