<?php
namespace DLF;

if ( ! defined( 'ABSPATH' ) ) exit;

class Common {

    public function __construct() {
        // Register custom taxonomy

        // Save taxonomy terms when listing is inserted or updated
        add_action( 'save_post', [ $this, 'save_countries' ] );
        add_filter( 'directorist_template', [ $this, 'change_template' ], 20, 2 );

        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets_admin' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets_front' ] );
    }

    public function change_template( $template, $args ){


        if ( 'listing-form/fields/country_expert' == $template ) {
            $template = DLF_PLUGIN_DIR . 'templates/country_expert.php';
             if ( file_exists( $template ) ) {

                dcf_load_template( $template, $args );
                
                return false;
            }
        }
        return $template;

    }

   

    /**
     * Save taxonomy terms when listing is inserted or updated
     */
    public function save_countries( $listing_id ) {
        if ( isset( $_POST['country_expert'] ) && is_array( $_POST['country_expert'] ) ) {
            $country_ids = array_map( 'intval', $_POST['country_expert'] );
            wp_set_object_terms( $listing_id, $country_ids, 'country_expert', false );
        }
    }

     /**
     * Enqueue CSS/JS for admin
     */
    public function enqueue_assets_admin() {
        wp_enqueue_style( 'select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', [], '4.1.0' );
    
        // JS
        wp_enqueue_script( 'select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', ['jquery'], '4.1.0', true );
        wp_enqueue_style(
            'dlf-common',
            DLF_PLUGIN_URL . 'assets/css/common.css',
            [],
            '1.0.0'
        );
        wp_enqueue_script(
            'dlf-common',
            DLF_PLUGIN_URL . 'assets/js/common.js',
            [ 'jquery' ],
            '1.0.0',
            true
        );
    }

    /**
     * Enqueue CSS/JS for frontend
     */
    public function enqueue_assets_front() {
        wp_enqueue_style(
            'dlf-frontend',
            DLF_PLUGIN_URL . 'assets/css/frontend.css',
            [],
            '1.0.0'
        );
        wp_enqueue_script(
            'dlf-frontend',
            DLF_PLUGIN_URL . 'assets/js/frontend.js',
            [ 'jquery' ],
            '1.0.0',
            true
        );
    }

   
}
