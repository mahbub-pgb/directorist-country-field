<?php
namespace DLF;

if ( ! defined( 'ABSPATH' ) ) exit;

class Front {

    public function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
        add_action( 'wp_head', [ $this, 'head' ] );
        add_filter( 'directorist_template', [ $this, 'change_template' ], 20, 2 );
        add_filter( 'directorist_listings_query_results', [ $this, 'query_results' ], 999999, 1 );
    }

    public function head(){
        $post_id = 3092;
        $post_terms = wp_get_post_terms( $post_id, 'country_expert', ['fields' => 'ids'] );

        // $selected = $_GET['country_expert']; 
        // $ids = explode(',', $selected); 
        // pri( $post_terms );
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

        // if ( 'search-form/fields/country_expert' == $template ) {
        //     $template = DLF_PLUGIN_DIR . 'templates/search/country_expert.php';
        //      if ( file_exists( $template ) ) {

        //         dcf_load_template( $template, $args );
                
        //         return false;
        //     }
        // }

        // if ( 'search-form/fields/language' == $template ) {
        //     $template = DLF_PLUGIN_DIR . 'templates/search/language.php';
        //      if ( file_exists( $template ) ) {

        //         dcf_load_template( $template, $args );
                
        //         return false;
        //     }
        // }
        return $template;
    }

    public function query_results( $query_result ) {
        if ( ! empty( $query_result->ids ) && is_array( $query_result->ids ) ) {

            // Filter by country_expert
            if ( isset( $_GET['country_expert'] ) && ! empty( $_GET['country_expert'] ) ) {
                $selected = sanitize_text_field( $_GET['country_expert'] );
                $query_result->ids = get_at_biz_dir_ids_by_country( $selected );
            }

            // Filter by atbdp_language
            if ( isset( $_GET['atbdp_language'] ) && ! empty( $_GET['atbdp_language'] ) ) {
                $selected = sanitize_text_field( $_GET['atbdp_language'] );
                $ids = array_map( 'intval', explode( ',', $selected ) );

                $filtered_ids = [];
                foreach ( $query_result->ids as $post_id ) {
                    $post_terms = wp_get_post_terms( $post_id, 'atbdp_language', ['fields' => 'ids'] );
                    if ( array_intersect( $post_terms, $ids ) ) {
                        $filtered_ids[] = $post_id;
                    }
                }

                $query_result->ids = $filtered_ids;
            }

            // Update totals after filtering
            $query_result->ids = array_values( $query_result->ids );
            $query_result->total = count( $query_result->ids );
            $query_result->total_pages = ! empty( $query_result->per_page ) && $query_result->per_page > 0
                ? ceil( $query_result->total / $query_result->per_page )
                : 1;
        }

        return $query_result;
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
