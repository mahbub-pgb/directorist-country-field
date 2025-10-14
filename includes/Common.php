<?php
namespace DLF;

if ( ! defined( 'ABSPATH' ) ) exit;

class Common {

    public function __construct() {
        // Register custom taxonomy

        // Save taxonomy terms when listing is inserted or updated
        // add_action( 'atbdp_listing_inserted', [ $this, 'save_countries' ] );
        // add_action( 'atbdp_listing_updated', [ $this, 'save_countries' ] );

        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets_admin' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets_front' ] );
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
        wp_enqueue_style(
            'dlf-admin',
            DLF_PLUGIN_URL . 'assets/css/admin.css',
            [],
            '1.0.0'
        );
        wp_enqueue_script(
            'dlf-admin',
            DLF_PLUGIN_URL . 'assets/js/admin.js',
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
