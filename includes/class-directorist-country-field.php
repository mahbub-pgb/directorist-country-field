<?php
namespace DLF;

use Directorist\Fields\Taxonomy_Field;

defined( 'ABSPATH' ) || exit;

class Directorist_Country_Field extends Taxonomy_Field {

    public function get_taxonomy() {
        return 'country_expert';
    }

    public function user_can_create() {
        return $this->allow_new;
    }
}
