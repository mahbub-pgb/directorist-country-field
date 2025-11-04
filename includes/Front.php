<?php
namespace DLF;

if ( ! defined( 'ABSPATH' ) ) exit;

class Front {

    public function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
        add_action( 'wp_head', [ $this, 'head' ] );
     
    }

    public function head(){
    }

   


// ATBDP_Permalink::get_directorist_listings_page_link()


    /**
     * Enqueue CSS/JS for frontend
     */
    public function enqueue_assets() {

        // Select2 CSS
        wp_enqueue_style(
            'select2',
            'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            [],
            '4.1.0'
        );

        // Select2 JS
        
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

        // Prepare localized data
        $localize_data = [
            'ajax_url'     => admin_url( 'admin-ajax.php' ),
            'nonce'        => wp_create_nonce( 'dlf_ajax_nonce' ),
            'listings_url' => \ATBDP_Permalink::get_directorist_listings_page_link(),
        ];

        // Pass data to your JS file
        wp_localize_script( 'dlf-frontend', 'DLF_JS', $localize_data );
    }
}
