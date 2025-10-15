<?php
namespace DLF;

if ( ! defined( 'ABSPATH' ) ) exit;

class Front {

    public function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
        add_action( 'wp_head', [ $this, 'head' ] );
        add_filter( 'directorist_template', [ $this, 'change_template' ], 20, 2 );
        // add_filter( 'directorist_all_listings_query_arguments', [ $this, 'directorist_all_listings' ], 20, 2 );
    }

    public function head(){
        // $all_countries = get_terms([
        //     'taxonomy'   => 'country_expert',
        //     'hide_empty' => false,
        // ]);




        // pri( get_all_country_expert() );
    }

    public function change_template( $template, $args ){
        // pri( $template );
        if ( 'single/fields/country_expert' == $template ) {
            $template = DLF_PLUGIN_DIR . 'templates/single/country_expert.php';
             if ( file_exists( $template ) ) {

                dcf_load_template( $template, $args );
                
                return false;
            }
        }

        if ( 'search-form/fields/country_expert' == $template ) {
            $template = DLF_PLUGIN_DIR . 'templates/search/country_expert.php';
             if ( file_exists( $template ) ) {

                dcf_load_template( $template, $args );
                
                return false;
            }
        }
        return $template;
    }

    public function directorist_all_listings(  $args, $listings ){
        // Check if user selected country_expert in search form
        if ( ! empty( $_GET['custom_field']['country_expert'] ) ) {
            pri( $_GET['custom_field'] );
            $country_ids = array_map( 'intval', (array) $_GET['custom_field']['country_expert'] );

            $tax_query = [
                'taxonomy' => 'country_expert',
                'field'    => 'term_id',
                'terms'    => $country_ids,
            ];

            if ( ! empty( $args['tax_query'] ) ) {
                // Append to existing tax queries
                $args['tax_query'][] = $tax_query;
            } else {
                $args['tax_query'] = [ $tax_query ];
            }
        }

        return $args;
    }
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
    }
}
