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

        // pri( $template );


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

        $country_experts = isset( $_POST['country_expert'] ) ? (array) $_POST['country_expert'] : [];
        $country_experts = array_map( 'intval', $country_experts );

        wp_set_object_terms( $post_id, $country_experts, 'country_expert', false );

        update_post_meta( $post_id, '_country_expert', $country_experts );

    }
}
