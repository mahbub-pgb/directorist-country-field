<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// Fetch all languages (terms)
$all_lang_terms = get_terms([
    'taxonomy'   => 'atbdp_language',
    'hide_empty' => false,
    'orderby'    => 'name',
    'order'      => 'ASC',
]);

if ( is_wp_error( $all_lang_terms ) ) {
    $all_lang_terms = [];
}

// Ensure $value is always an array
$value = $data['value'] ?? [];
if ( ! is_array( $value ) ) {
    $value = [$value]; // convert single value to array
}

// --- Handle repeated URL params for pre-selection ---
$query_values = [];

// Normal $_GET handling
if ( isset( $_GET['atbdp_language']['atbdp_language'] ) ) {
    $raw = $_GET['atbdp_language']['atbdp_language'];
    if ( is_array( $raw ) ) {
        $query_values = array_map( 'sanitize_text_field', $raw );
    } else {
        $query_values = array_map(
            'sanitize_text_field',
            array_filter( array_map( 'trim', explode( ',', (string) $raw ) ) )
        );
    }
}

// Also parse raw QUERY_STRING to catch repeated keys without []
if ( isset( $_SERVER['QUERY_STRING'] ) && $_SERVER['QUERY_STRING'] !== '' ) {
    if ( preg_match_all(
        '/(?:^|&)(?:atbdp_language%5Batbdp_language%5D(?:%5B%5D)?)=([^&]+)/',
        $_SERVER['QUERY_STRING'],
        $m
    ) ) {
        foreach ( $m[1] as $raw ) {
            $query_values[] = sanitize_text_field( urldecode( $raw ) );
        }
    }
}

// Merge and deduplicate
if ( ! empty( $query_values ) ) {
    $value = array_values( array_unique( array_merge( $value, $query_values ) ) );
}

$widget_name = $data['widget_name'] ?? 'atbdp_language';
$label       = $data['label'] ?? '';
$placeholder = ! empty( $data['placeholder'] ) ? $data['placeholder'] : __( 'Select Language', 'directorist' );
?>

<div class="directorist-search-field directorist-form-group">
    <div class="directorist-select directorist-search-field__input">
        <?php if ( $label !== '' ) : ?>
            <label class="directorist-search-field__label">
                <?php echo esc_html( $label ); ?>
            </label>
        <?php endif; ?>

        <select id="atbdp_language_select" name="custom_field[<?php echo esc_attr( $widget_name ); ?>][]" 
                class="directorist-search-select" 
                data-isSearch="true" 
                data-placeholder="<?php echo esc_attr( $placeholder ); ?>" 
                multiple>

            <?php foreach ( $all_lang_terms as $lang ) :
                if ( ! $lang instanceof WP_Term ) continue;
                $term_id_str = (string) $lang->term_id;
                $is_selected = in_array( $term_id_str, $value, true );
                ?>
                <option value="<?php echo esc_attr( $term_id_str ); ?>" <?php selected( $is_selected ); ?>>
                    <?php echo esc_html( $lang->name ); ?>
                </option>
            <?php endforeach; ?>

        </select>
    </div>

    <div class="directorist-search-field__btn directorist-search-field__btn--clear" role="button" tabindex="0" aria-label="Clear selected languages">
        <?php if ( function_exists( 'directorist_icon' ) ) { directorist_icon( 'fas fa-times-circle' ); } ?>
    </div>
</div>
