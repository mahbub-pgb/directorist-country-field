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

        register_taxonomy(
        'dl_language', // taxonomy slug
        'at_biz_dir',     // post type
            [
                'labels' => [
                    'name'              => 'Languages',
                    'singular_name'     => 'Language',
                    'search_items'      => 'Search Languages',
                    'all_items'         => 'All Languages',
                    'edit_item'         => 'Edit Language',
                    'update_item'       => 'Update Language',
                    'add_new_item'      => 'Add New Language',
                    'new_item_name'     => 'New Language Name',
                    'menu_name'         => 'Directory Languages',
                ],
                'hierarchical'      => false, // false like tags, true like categories
                'show_ui'           => true,
                'show_admin_column' => false,
                'show_in_rest'      => false,
                'public'            => true,
                'rewrite'           => [ 'slug' => 'language' ],
            ]
        );

        add_all_language_to_dl_language();

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
        if ( isset( $_POST['atbdp_language'] ) ) {
            $languages = (array) $_POST['atbdp_language'];

            // Handle nested array: atbdp_language[atbdp_language][]
            if ( isset( $languages['atbdp_language'] ) && is_array( $languages['atbdp_language'] ) ) {
                $languages = $languages['atbdp_language'];
            }

            $languages = array_map( 'intval', $languages );

            wp_set_object_terms( $post_id, $languages, 'atbdp_language', false );
            update_post_meta( $post_id, '_atbdp_language', $languages );
        }
    }


}
