<?php
namespace DLF;

if ( ! defined( 'ABSPATH' ) ) exit;

class Admin {

    public function __construct() {
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets' ] );
        add_filter( 'atbdp_form_custom_widgets', [ $this, 'register_language_field' ] );
    }

    public function register_language_field( $fields ) {

        $languages = dlf_get_iso_639_1_languages(); // 🔥 from functions.php

        global $custom_field_meta_key_field;
        if ( empty( $custom_field_meta_key_field ) || ! is_array( $custom_field_meta_key_field ) ) {
            $custom_field_meta_key_field = [];
        }

        $fields['language'] = [
            'label'   => __( 'Language', 'directorist-language-field' ),
            'icon'    => 'uil uil-language',
            'options' => [
                'type' => [ 'type' => 'hidden', 'value' => 'language' ],
                'field_key' => array_merge(
                    $custom_field_meta_key_field,
                    [ 'value' => 'custom-language' ]
                ),
                'label' => [
                    'type'  => 'text',
                    'label' => __( 'Label', 'directorist-language-field' ),
                    'value' => __( 'Language', 'directorist-language-field' ),
                ],
                'options' => [
                    'type'     => 'select',
                    'label'    => __( 'Available Languages', 'directorist-language-field' ),
                    'options'  => $languages,
                    'multiple' => true,
                ],
            ],
        ];

        return $fields;
    }

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
