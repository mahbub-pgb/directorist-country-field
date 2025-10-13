<?php
namespace DLF;

if ( ! defined( 'ABSPATH' ) ) exit;

class Frontend {

    public function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
    }

    public function enqueue_assets() {
        // Add frontend JS or CSS if needed later
    }
}
