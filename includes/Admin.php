<?php
namespace DLF;

if ( ! defined( 'ABSPATH' ) ) exit;

class Admin {

    public function __construct() {
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets' ] );
        add_filter( 'atbdp_form_custom_widgets', [ $this, 'register_country_field' ] );
        add_filter( 'atbdp_single_listing_other_fields_widget', [ $this, 'register_country_expert_widget_single_content' ] );
    }

    

    
    public function register_country_expert_widget_single_content( $widgets ) {

        

        // 🔹 Add your custom Country Expert field widget
        $widgets['country_expert'] = [
            'type'          => 'widget',
            'label'         => __( 'Country Expert', 'directorist-country-field' ),
            'icon'          => 'las la-globe', // Any line-awesome icon
            'allowMultiple' => false,
            'options'       => [
                'label' => [
                    'type'  => 'text',
                    'label' => __( 'Label', 'directorist-country-field' ),
                    'value' => __( 'Country Expert', 'directorist-country-field' ),
                ],
                'placeholder' => [
                    'type'  => 'text',
                    'label' => __( 'Placeholder', 'directorist-country-field' ),
                    'value' => __( 'Selected Countries', 'directorist-country-field' ),
                ],
            ],
        ];

        return $widgets;
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
        // Select2 CSS
        wp_enqueue_style(
            'select2',
            'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            [],
            '4.1.0'
        );

        // Select2 JS
        wp_enqueue_script(
            'select2',
            'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
            [ 'jquery' ],
            '4.1.0',
            true
        );
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
}
