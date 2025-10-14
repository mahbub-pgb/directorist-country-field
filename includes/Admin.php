<?php
namespace DLF;

if ( ! defined( 'ABSPATH' ) ) exit;

class Admin {

    public function __construct() {
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets' ] );
        add_action( 'init', [ $this ,'register_country_taxonomy'], 20 );
        add_filter( 'atbdp_form_custom_widgets', [ $this, 'register_country_field' ] );
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
                    'name'              => 'Countries Expert',
                    'singular_name'     => 'Country Expert',
                    'search_items'      => 'Search Countries Expert',
                    'all_items'         => 'All Countries Expert',
                    'edit_item'         => 'Edit Country Expert',
                    'update_item'       => 'Update Country Expert',
                    'add_new_item'      => 'Add New Country Expert',
                    'new_item_name'     => 'New Country Expert Name',
                    'menu_name'         => 'Countries Expert',
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
     * Register custom fields for Directorist form
     */
    public function register_country_field( $fields ) {

        global $custom_field_meta_key_field;
        if ( empty( $custom_field_meta_key_field ) || ! is_array( $custom_field_meta_key_field ) ) {
            $custom_field_meta_key_field = [];
        }

        // 🔹 Fetch countries from taxonomy
        $countries = get_terms([
            'taxonomy'   => 'country_expert',
            'hide_empty' => false,
        ]);

        $options = [];
        if ( ! is_wp_error( $countries ) && ! empty( $countries ) ) {
            foreach ( $countries as $country ) {
                $options[ $country->term_id ] = $country->name;
            }
        } else {
            $options = [ '' => __( 'No countries found', 'directorist-country-field' ) ];
        }

        // 🔹 Add the Country Expert field
        $fields['country_expert'] = [
            'label'   => __( 'Country Expert', 'directorist-country-field' ),
            'icon'    => 'uil uil-globe',
            'options' => [
                'type' => [
                    'type'  => 'hidden',
                    'value' => 'country_expert',
                ],
                'field_key' => array_merge(
                    $custom_field_meta_key_field,
                    [ 'value' => 'custom-country-expert' ]
                ),
                'label' => [
                    'type'  => 'text',
                    'label' => __( 'Label', 'directorist-country-field' ),
                    'value' => __( 'Country Expert', 'directorist-country-field' ),
                ],
                'options' => [
                    'type'     => 'select',
                    'label'    => __( 'Available Countries', 'directorist-country-field' ),
                    'options'  => $options,
                    'multiple' => true,
                ],
                'required' => [
                    'type'  => 'toggle',
                    'label' => __( 'Required', 'directorist-country-field' ),
                    'value' => false,
                ],
                'only_for_admin' => [
                    'type'  => 'toggle',
                    'label' => __( 'Admin Only', 'directorist-country-field' ),
                    'value' => false,
                ],
                // 'assign_to' => [
                //     'type'  => 'toggle',
                //     'label' => __( 'Assign to Category', 'directorist-country-field' ),
                //     'value' => false,
                // ],
                // 'category' => get_country_options([
                //     'show_if' => [
                //         'where' => "self.assign_to",
                //         'conditions' => [
                //             [
                //                 'key'     => 'value',
                //                 'compare' => '=',
                //                 'value'   => true,
                //             ],
                //         ],
                //     ],
                // ]),
            ],
        ];

        return $fields;
    }

    /**
     * Enqueue admin assets
     */
    public function enqueue_assets() {
        wp_enqueue_script(
            'dlf-admin',
            DLF_PLUGIN_URL . 'assets/js/admin.js',
            [ 'jquery' ],
            '1.0.0',
            true
        );
    }
}
