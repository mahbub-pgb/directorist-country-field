<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Expected $data keys:
 * - 'widget_name' (string, required): the meta/field key, e.g. 'country_expert'
 * - 'label'       (string, optional): field label
 * - 'value'       (array|string|int, optional): prefilled values
 */

// 1) Fetch all countries (terms)
$all_countries = get_terms( [
    'taxonomy'   => 'country_expert',
    'hide_empty' => false,
    'orderby'    => 'name',
    'order'      => 'ASC',
] );

if ( is_wp_error( $all_countries ) ) {
    $all_countries = [];
}

// 2) Normalize initial $value to an array of strings
$value = [];
if ( ! empty( $data['value'] ) ) {
    if ( is_array( $data['value'] ) ) {
        $value = array_map( 'strval', $data['value'] );
    } else {
        $value = array_map( 'strval', array_filter( array_map( 'trim', explode( ',', (string) $data['value'] ) ) ) );
    }
}

// 3) Pull values from the query string — support [], repeated keys, and CSV
$query_values = [];

// 3a) First, if URL uses [] (PHP will already give us an array in $_GET)
if ( isset( $_GET['custom_field']['country_expert'] ) ) {
    $raw = $_GET['custom_field']['country_expert'];
    if ( is_array( $raw ) ) {
        // e.g. ?custom_field[country_expert][]=461&...[]=464
        $query_values = array_map( static function( $v ) {
            return sanitize_text_field( (string) $v );
        }, $raw );
    } else {
        // Could be a single or CSV: ?custom_field[country_expert]=461,464  OR last value if repeated w/o []
        $query_values = array_map(
            'sanitize_text_field',
            array_filter( array_map( 'trim', explode( ',', (string) $raw ) ) )
        );
    }
}

// 3b) Also detect repeated keys WITHOUT [] which PHP collapses (grab them via raw QUERY_STRING)
if ( isset( $_SERVER['QUERY_STRING'] ) && $_SERVER['QUERY_STRING'] !== '' ) {
    // Matches both:
    //   custom_field%5Bcountry_expert%5D=461
    //   custom_field%5Bcountry_expert%5D%5B%5D=461
    if ( preg_match_all(
        '/(?:^|&)(?:custom_field%5Bcountry_expert%5D(?:%5B%5D)?)=([^&]+)/',
        $_SERVER['QUERY_STRING'],
        $m
    ) ) {
        foreach ( $m[1] as $raw ) {
            $query_values[] = sanitize_text_field( urldecode( $raw ) );
        }
    }
}

// 3c) Merge, dedupe, and ensure strings for strict in_array later
if ( ! empty( $query_values ) ) {
    $value = array_values( array_unique( array_map( 'strval', array_merge( $value, $query_values ) ) ) );
}

$widget_name = isset( $data['widget_name'] ) ? (string) $data['widget_name'] : 'country_expert';
$label       = isset( $data['label'] ) ? (string) $data['label'] : '';
$placeholder = 'Select Country';
?>
<div class="directorist-search-field directorist-form-group">
    <div class="directorist-select directorist-search-field__input">
        <?php if ( $label !== '' ) : ?>
            <label class="directorist-search-field__label">
                <?php echo esc_html( $label ); ?>
            </label>
        <?php endif; ?>

        <select
            id="country_expert_select"
            name="custom_field[<?php echo esc_attr( $widget_name ); ?>][]"
            class="directorist-search-select js-country-expert-select"
            data-isSearch="true"
            multiple="multiple"
            data-placeholder="<?php echo esc_attr( $placeholder ); ?>"
            style="width: 100%;">

            <?php foreach ( $all_countries as $country ) :
                if ( ! $country instanceof WP_Term ) { continue; }
                $term_id_str = (string) $country->term_id;
                $is_selected = in_array( $term_id_str, $value, true );
                ?>
                <option value="<?php echo esc_attr( $term_id_str ); ?>" <?php selected( $is_selected ); ?>>
                    <?php echo esc_html( $country->name ); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="directorist-search-field__btn directorist-search-field__btn--clear" role="button" tabindex="0" aria-label="Clear selected countries">
        <?php if ( function_exists( 'directorist_icon' ) ) { directorist_icon( 'fas fa-times-circle' ); } ?>
    </div>
</div>