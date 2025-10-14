<?php
namespace DLF;

if ( ! defined( 'ABSPATH' ) ) exit;

class Front {

    public function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
        add_action( 'wp_head', [ $this, 'head' ] );
    }

    public function head(){
        // $all_countries = get_terms([
        //     'taxonomy'   => 'country_expert',
        //     'hide_empty' => false,
        // ]);




        // pri( get_all_country_expert() );
    }
    public function enqueue_assets() {
        // Add frontend JS or CSS if needed later
    }
}
