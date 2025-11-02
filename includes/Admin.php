<?php
namespace DLF;

if ( ! defined( 'ABSPATH' ) ) exit;

class Admin {

    public function __construct() {
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets' ] );
        add_filter( 'atbdp_form_custom_widgets', [ $this, 'register_country_and_language_fields' ] );
        add_filter( 'atbdp_single_listing_other_fields_widget', [ $this, 'register_country_expert_widget_single_content' ] );
        add_filter( 'directorist_search_form_widgets', [ $this, 'directorist_search_form_widgets' ] );
    }

    public function directorist_search_form_widgets( $search_form_widgets ){
         // Add "Expert Countries" widget under "Other Fields"
        $search_form_widgets['other_widgets']['widgets']['country_expert'] = [
            'label'   => __( 'Expert Countries', 'directorist' ),
            'icon'    => 'las la-globe', // example icon, you can change
            'options' => [
                'label' => [
                    'type'  => 'text',
                    'label' => __( 'Label', 'directorist' ),
                    'value' => __( 'Country Expert', 'directorist' ),
                ],
                'placeholder' => [
                    'type'  => 'text',
                    'label' => __( 'Placeholder', 'directorist' ),
                    'value' => __( 'Select Country', 'directorist' ),
                ],
            ],
        ];

        $search_form_widgets['other_widgets']['widgets']['language'] = [
            'label'   => __( 'Language', 'directorist' ),
            'icon'    => 'las la-language',
            'options' => [
                'label' => [
                    'type'  => 'text',
                    'label' => __( 'Label', 'directorist' ),
                    'value' => __( 'Language', 'directorist' ),
                ],
                'placeholder' => [
                    'type'  => 'text',
                    'label' => __( 'Placeholder', 'directorist' ),
                    'value' => __( 'Select Language', 'directorist' ),
                ],
            ],
        ];

        return $search_form_widgets;
    }

    

    
    public function register_country_expert_widget_single_content( $widgets ) {
        // 🔹 Add your custom Country Expert field widget
        $widgets['country_expert'] = [
            'type'          => 'widget',
            'label'         => __( 'Country Expert', 'directorist-country-field' ),
            'icon'          =>  'las la-globe', // Any line-awesome icon
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

        $widgets['language'] = [
            'type'          => 'widget',
            'label'         => __( 'language', 'directorist-country-field' ),
            'icon'          =>  'las la-language', // Any line-awesome icon
            'allowMultiple' => false,
            'options'       => [
                'label' => [
                    'type'  => 'text',
                    'label' => __( 'Label', 'directorist-country-field' ),
                    'value' => __( 'language', 'directorist-country-field' ),
                ],
                'placeholder' => [
                    'type'  => 'text',
                    'label' => __( 'Placeholder', 'directorist-country-field' ),
                    'value' => __( 'Selected language', 'directorist-country-field' ),
                ],
            ],
        ];

        return $widgets;
    }

    /**
     * Register custom fields for Directorist form
     */
    public function register_country_and_language_fields( $fields ) {

        global $custom_field_meta_key_field;
        if ( empty( $custom_field_meta_key_field ) || ! is_array( $custom_field_meta_key_field ) ) {
            $custom_field_meta_key_field = [];
        }

        // --------------------------
        // 🔹 Country Expert Field
        // --------------------------
        $countries = get_terms([
            'taxonomy'   => 'country_expert',
            'hide_empty' => false,
        ]);

        $country_options = [];
        if ( ! is_wp_error( $countries ) && ! empty( $countries ) ) {
            foreach ( $countries as $country ) {
                $country_options[ $country->term_id ] = $country->name;
            }
        } else {
            $country_options = [ '' => __( 'No countries found', 'directorist-country-field' ) ];
        }

        $fields['country_expert'] = [
            'label'   => __( 'Country Expert', 'directorist-country-field' ),
            'icon'    => 'las la-globe',
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
                    'options'  => $country_options,
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
            ],
        ];

        // --------------------------
        // 🔹 Language Field
        // --------------------------
        $languages = get_terms([
            'taxonomy'   => 'language',
            'hide_empty' => false,
        ]);

        $language_options = [];
        if ( ! is_wp_error( $languages ) && ! empty( $languages ) ) {
            foreach ( $languages as $lang ) {
                $language_options[ $lang->term_id ] = $lang->name;
            }
        } else {
            $language_options = [ '' => __( 'No languages found', 'directorist-language-field' ) ];
        }

        $fields['language'] = [
            'label'   => __( 'Language', 'directorist-language-field' ),
            'icon'    => 'las la-language',
            'options' => [
                'type' => [
                    'type'  => 'hidden',
                    'value' => 'language',
                ],
                'field_key' => array_merge(
                    $custom_field_meta_key_field,
                    [ 'value' => 'custom-language-field' ]
                ),
                'label' => [
                    'type'  => 'text',
                    'label' => __( 'Label', 'directorist-language-field' ),
                    'value' => __( 'Language', 'directorist-language-field' ),
                ],
                'options' => [
                    'type'     => 'select',
                    'label'    => __( 'Available Languages', 'directorist-language-field' ),
                    'options'  => $language_options,
                    'multiple' => true,
                ],
                'required' => [
                    'type'  => 'toggle',
                    'label' => __( 'Required', 'directorist-language-field' ),
                    'value' => false,
                ],
                'only_for_admin' => [
                    'type'  => 'toggle',
                    'label' => __( 'Admin Only', 'directorist-language-field' ),
                    'value' => false,
                ],
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
