<?php
/**
 * Country Expert Search Field Template
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$all_countries = get_terms([
    'taxonomy'   => 'country_expert',
    'hide_empty' => false,
]);

$value = $data['value'] ?? '';
?>

<div class="directorist-search-field directorist-form-group">
    <div class="directorist-select directorist-search-field__input">

        <?php if ( ! empty( $data['label'] ) ) : ?>
            <label class="directorist-search-field__label"><?php echo esc_html( $data['label'] ); ?></label>
        <?php endif; ?>

        <select name="custom_field[custom_field][]" 
                class="directorist-search-select" 
                data-isSearch="true" 
                data-placeholder="<?php echo esc_attr( ! empty( $data['placeholder'] ) ? $data['placeholder'] : __( 'Select Country', 'directorist' ) ); ?>" 
                multiple>

            <?php if ( ! empty( $all_countries ) && ! is_wp_error( $all_countries ) ) : ?>
                <?php foreach ( $all_countries as $country ) : ?>
                    <option value="<?php echo esc_attr( $country->term_id ); ?>" <?php echo is_array( $value ) && in_array( $country->term_id, $value ) ? 'selected' : ''; ?>>
                        <?php echo esc_html( $country->name ); ?>
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>

        </select>
    </div>

    <div class="directorist-search-field__btn directorist-search-field__btn--clear">
        <?php directorist_icon( 'fas fa-times-circle' ); ?>
    </div>
</div>

<script>
jQuery(document).ready(function($){
    $('select.directorist-search-select').select2({
        placeholder: function(){
            return $(this).data('placeholder');
        },
        allowClear: true,
        width: '100%'
    });
});
</script>
