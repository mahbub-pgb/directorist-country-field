<?php
namespace DLF;

if ( ! defined( 'ABSPATH' ) ) exit;

class Common {

    public function __construct() {
        // Register custom taxonomy

        // Save taxonomy terms when listing is inserted or updated
        add_action( 'save_post_at_biz_dir', [ $this, 'save_country_expert_field' ], 100, 3 );
        add_action( 'init', [ $this ,'register_country_taxonomy'], 20 );
        add_filter( 'directorist_template', [ $this, 'change_template' ], 20, 2 );
        add_filter( 'plugins_loaded', [ $this, 'load_plugin' ], 10, 2 );
        add_filter( 'atbdp_listing_meta_user_submission', [ $this, 'save_listing_submission' ], 10, 1 );

    }

    public function save_listing_submission( $meta_data ) {

        if ( isset( $_POST['atbdp_language'] ) ) {
            $meta_data['_language'] =  $_POST['atbdp_language'] ;
        }

        if ( isset( $_POST['country_expert'] ) ) {
            $meta_data['_country_expert'] = sanitize_text_field( $_POST['country_expert'] );
        }
        return $meta_data;
    }


    public function load_plugin(  ){
        if ( ! class_exists( 'Directorist_Fields' ) ) return;

        require_once DLF_PLUGIN_DIR . 'includes/class-directorist-country-field.php';

        add_filter( 'directorist_field_types', function ( $fields ) {
            $fields['country_expert'] = 'Directorist_Country_Expert_Field';
            return $fields;
        });

         // Add field to builder
        add_filter( 'directorist_form_field_options', function ( $fields ) {
            $fields['country'] = [
                'type'        => 'country',
                'label'       => __( 'Country', 'directorist' ),
                'taxonomy'    => 'country_expert',
                'field_class' => 'Directorist_Languages_Field',
            ];
            return $fields;
        });
    }

    public function change_template( $template, $args ){

        // dlf_pri( $template );


        if ( 'listing-form/fields/country_expert' == $template ) {
            $template = DLF_PLUGIN_DIR . 'templates/country_expert.php';
             if ( file_exists( $template ) ) {

                dcf_load_template( $template, $args );
                
                return false;
            }
        }

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

        if ( 'search-form/fields/language' == $template ) {
            $template = DLF_PLUGIN_DIR . 'templates/search/language.php';
             if ( file_exists( $template ) ) {

                dcf_load_template( $template, $args );
                
                return false;
            }
        }
        
        if ( 'single/header-parts/listing-title' == $template ) {
            $template = DLF_PLUGIN_DIR . 'templates/single/social.php';
             if ( file_exists( $template ) ) {

                dcf_load_template( $template, $args );
                
                return false;
            }
        }
        
        return $template;

    }

    /**
     * Register the 'country_expert' taxonomy
     */
    public function register_country_taxonomy() {
        register_taxonomy(
            'country_expert',
            'at_biz_dir',
            [
                'labels' => [
                    'name'              => 'Expert Countries',
                    'singular_name'     => 'Expert Country ',
                    'search_items'      => 'Search Countries',
                    'all_items'         => 'All Countries',
                    'edit_item'         => 'Edit Country',
                    'update_item'       => 'Update Country',
                    'add_new_item'      => 'Add New Country',
                    'new_item_name'     => 'New Country Name',
                    'menu_name'         => 'Expert Countries',
                ],
                'hierarchical'      => false,
                'show_ui'           => true,
                'show_admin_column' => false,
                'show_in_rest'      => false,
                'public'            => true,
                'rewrite'           => [ 'slug' => 'country-expert' ],
            ]
        );
        add_all_countries_to_country_expert();
    }   

    public function save_country_expert_field( $post_id, $post, $update ) {
        // Safety checks
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
        if ( wp_is_post_revision( $post_id ) ) return;
        if ( $post->post_type !== 'at_biz_dir' ) return;

        /**
         * --------------------------
         * Save Country Expert Field
         * --------------------------
         */
        if ( isset( $_POST['country_expert'] ) ) {
            $country_experts = (array) $_POST['country_expert'];
            $country_experts = array_map( 'intval', $country_experts );

            wp_set_object_terms( $post_id, $country_experts, 'country_expert', false );
            update_post_meta( $post_id, '_country_expert', $country_experts );
        }

        /**
         * --------------------------
         * Save Language Field
         * --------------------------
         */

        if ( isset( $_POST['tax_input']['atbdp_language'] ) ) {
            $languages_raw = $_POST['tax_input']['atbdp_language'];

            // Convert to array (split by comma)
            $languages = array_map( 'trim', explode( ',', $languages_raw ) );

            $term_ids = [];

            foreach ( $languages as $lang ) {
                if ( ! empty( $lang ) ) {
                    // Check if the term already exists
                    $term = term_exists( $lang, 'atbdp_language' );

                    // Create it if not found
                    if ( ! $term ) {
                        $term = wp_insert_term( $lang, 'atbdp_language' );
                    }

                    // Store the term ID if valid
                    if ( ! is_wp_error( $term ) && isset( $term['term_id'] ) ) {
                        $term_ids[] = intval( $term['term_id'] );
                    }
                }
            }

            // Assign taxonomy terms to post
            wp_set_object_terms( $post_id, $term_ids, 'atbdp_language', false );

            // Save meta if you want to reference later
            update_post_meta( $post_id, '_language', $term_ids );
        }
       
    }


}
