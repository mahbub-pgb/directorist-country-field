<?php
namespace DLF;

if ( ! defined( 'ABSPATH' ) ) exit;

class Loader {

    public function __construct() {
        // Load shared field registration

        new Common();

        // Load admin or frontend logic
        if ( is_admin() ) {
            new Admin();
        } else {
            new Front();
        }
    }
}
