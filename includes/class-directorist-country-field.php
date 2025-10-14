<?php

use Directorist\Fields\Taxonomy_Field;

defined( 'ABSPATH' ) || exit;

class Directorist_Country_Field extends Taxonomy_Field {

    /**
     * Get the taxonomy key for this field.
     *
     * @return string
     */
    public function get_taxonomy() {
        return 'country_expert';
    }

    /**
     * Check if user can create new terms in this taxonomy.
     *
     * @return bool
     */
    public function user_can_create() {
        return $this->allow_new; // Optional: you can add logic to control this via settings
    }
}
