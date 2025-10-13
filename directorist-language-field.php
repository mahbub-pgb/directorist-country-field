<?php
/**
 * Plugin Name: Directorist Language Field
 * Description: Adds ISO 639-1 Language selector to Directorist listings.
 * Version: 1.1.0
 * Author: Mahbub
 * Text Domain: directorist-language-field
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'DLF_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'DLF_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'DLF_PLUGIN_FILE', __FILE__ );

// Load Composer autoloader
if ( file_exists( DLF_PLUGIN_DIR . 'vendor/autoload.php' ) ) {
    require_once DLF_PLUGIN_DIR . 'vendor/autoload.php';
} else {
    // fallback for non-composer environments
    require_once DLF_PLUGIN_DIR . 'functions.php';
    require_once DLF_PLUGIN_DIR . 'includes/class-dlf-loader.php';
}

add_action( 'plugins_loaded', function() {
    new DLF\Loader();
});
