<?php
/**
 * Current Language Search Field Template
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Fetch all languages, sorted by name
$all_lang_terms = get_terms([
    'taxonomy'   => 'atbdp_language',
    'hide_empty' => false,
    'orderby'    => 'name',
    'order'      => 'ASC',
]);

// Ensure $value is always an array
$value = $data['value'] ?? [];
if ( ! is_array( $value ) ) {
    $value = [$value]; // convert single value to array
}
?>

<div class="directorist-search-field directorist-form-group">
    <div class="directorist-select directorist-search-field__input">
        <?php if ( ! empty( $data['label'] ) ) : ?>
            <label class="directorist-search-field__label">
                <?php echo esc_html( $data['label'] ); ?>
            </label>
        <?php endif; ?>

        <select name="atbdp_language[atbdp_language][]" 
                class="directorist-search-select" 
                data-isSearch="true" 
                data-placeholder="<?php echo esc_attr( ! empty( $data['placeholder'] ) ? $data['placeholder'] : __( 'Select Language', 'directorist' ) ); ?>" 
                multiple>

            <?php if ( ! empty( $all_lang_terms ) && ! is_wp_error( $all_lang_terms ) ) : ?>
                <?php foreach ( $all_lang_terms as $lang ) : ?>
                    <option value="<?php echo esc_attr( $lang->term_id ); ?>" 
                        <?php echo in_array( $lang->term_id, $value ) ? 'selected' : ''; ?> >
                        <?php echo esc_html( $lang->name ); ?>
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>

        </select>
    </div>

    <div class="directorist-search-field__btn directorist-search-field__btn--clear">
        <?php directorist_icon( 'fas fa-times-circle' ); ?>
    </div>
</div>
