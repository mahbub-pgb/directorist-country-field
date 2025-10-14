<?php
namespace DLF;

if ( ! defined( 'ABSPATH' ) ) exit;

class Front {

    public function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
        // add_filter( 'directorist_field_template', [ $this, 'load_field_template' ], 20, 2 );
    }

    public function load_field_template( $template, $field_data ) {

        // Check if this is our custom field
        if ( 'listing-form/fields/dlf_language' === $template ) {

            // Full path to our custom template
            $custom_template = DLF_PLUGIN_DIR . 'templates/front-multilanguage-field.php';

                // dlf_pri( $custom_template );
            if ( file_exists( $custom_template ) ) {
                $template = $custom_template;
            }
        }

        return $template;
    }


    public function enqueue_assets() {
        // Add frontend JS or CSS if needed later
    }
}
