<?php
namespace DLF;

if ( ! defined( 'ABSPATH' ) ) exit;

class Common {

    public function __construct() {
        // Register custom taxonomy
        add_action( 'init', [ $this, 'register_country_taxonomy' ], 11 );

        // Save taxonomy terms when listing is inserted or updated
        add_action( 'atbdp_listing_inserted', [ $this, 'save_countries' ] );
        add_action( 'atbdp_listing_updated', [ $this, 'save_countries' ] );

        // Reset taxonomy terms when empty
        add_action( 'atbdp_before_listing_update', [ $this, 'reset_countries' ] );

        // Hook into Directorist field registration
        add_action( 'plugins_loaded', [ $this, 'register_country_field' ] );
    }

    /**
     * Register the "Country Expert" taxonomy
     */
    public function register_country_taxonomy() {
        register_taxonomy(
            'country_expert',
            'at_biz_dir',
            [
                'labels' => [
                    'name'              => __( 'Countries Expert', 'directorist-country-field' ),
                    'singular_name'     => __( 'Country Expert', 'directorist-country-field' ),
                    'search_items'      => __( 'Search Countries Expert', 'directorist-country-field' ),
                    'all_items'         => __( 'All Countries Expert', 'directorist-country-field' ),
                    'edit_item'         => __( 'Edit Country Expert', 'directorist-country-field' ),
                    'update_item'       => __( 'Update Country Expert', 'directorist-country-field' ),
                    'add_new_item'      => __( 'Add New Country Expert', 'directorist-country-field' ),
                    'new_item_name'     => __( 'New Country Expert Name', 'directorist-country-field' ),
                    'menu_name'         => __( 'Countries Expert', 'directorist-country-field' ),
                ],
                'hierarchical'      => false,
                'show_ui'           => true,
                'show_admin_column' => true,
                'show_in_rest'      => true,
                'public'            => true,
                'rewrite'           => [ 'slug' => 'country-expert' ],
            ]
        );
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
     * Reset taxonomy terms if none selected
     */
    public function reset_countries( $listing_id ) {
        if ( empty( $_POST['country_expert'] ) ) {
            wp_set_object_terms( $listing_id, [], 'country_expert', false );
        }
    }

    /**
     * Register the custom field type with Directorist
     */
    public function register_country_field() {
        if ( ! class_exists( 'Directorist_Fields' ) ) return;

        // Include the custom field class
        require_once DLF_PLUGIN_DIR . 'includes/class-directorist-country-field.php';

        // Map the field key to our field class
        add_filter( 'directorist_field_types', function ( $fields ) {
            $fields['country_expert'] = 'Directorist_Country_Field';
            return $fields;
        });

        // Register the field in the form builder
        add_filter( 'directorist_form_field_options', function ( $fields ) {
            $fields['country_expert'] = [
                'type'        => 'country_expert',
                'label'       => __( 'Country Expert', 'directorist' ),
                'taxonomy'    => 'country_expert',
                'field_class' => 'Directorist_Country_Field',
            ];
            return $fields;
        });

        // Load the custom template for this field
        add_filter( 'directorist_field_template_path', function ( $template, $field_type ) {
            if ( 'country_expert' === $field_type ) {
                return DLF_PLUGIN_DIR . 'includes/template/country.php';
            }
            return $template;
        }, 10, 2 );
    }
}
