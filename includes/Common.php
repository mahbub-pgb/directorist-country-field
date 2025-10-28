<?php
namespace DLF;

if ( ! defined( 'ABSPATH' ) ) exit;

class Common {

    public function __construct() {
        // Register custom taxonomy

        // Save taxonomy terms when listing is inserted or updated
        add_action( 'save_post', [ $this, 'save_countries' ] );
        add_action( 'init', [ $this ,'register_country_taxonomy'], 20 );
        add_filter( 'directorist_template', [ $this, 'change_template' ], 20, 2 );
        add_filter( 'plugins_loaded', [ $this, 'load_plugin' ], 10, 2 );
        add_action( 'atbdp_before_listing_update', [$this, 'register_country_field'] );
    }


    public function load_plugin(  ){
        if ( ! class_exists( 'Directorist_Fields' ) ) return;

        require_once DLI_PATH . 'includes/class-directorist-country-field.php';

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

    public function register_country_field( $listing_id ){
        if ( empty( $_POST['country_expert'] ) ) {
            wp_set_object_terms( $listing_id, [], 'country_expert', false );
        }
    }
    public function change_template( $template, $args ){

 
        if ( 'search-form/fields/country_expert' == $template ) {
            $template = DLF_PLUGIN_DIR . 'templates/search/country_expert.php';
             
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

   

    /**
     * Save taxonomy terms when listing is inserted or updated
     */
    public function save_countries( $listing_id ) {
        if ( isset( $_POST['country_expert'] ) && is_array( $_POST['country_expert'] ) ) {
            $country_ids = array_map( 'intval', $_POST['country_expert'] );
            wp_set_object_terms( $listing_id, $country_ids, 'country_expert', false );
        }
    }

    

    

   
}