<?php
/**
 * Country Expert Search Field
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Fetch all countries
$all_countries = get_terms([
    'taxonomy'   => 'country_expert',
    'hide_empty' => false,
    'orderby'    => 'name',
    'order'      => 'ASC',
]);

// Ensure $value is always an array
$value = ! empty( $data['value'] ) ? $data['value'] : [];
if ( ! is_array( $value ) ) {
    $value = array_map( 'trim', explode( ',', $value ) );
}
?>

<div class="directorist-search-field directorist-form-group">

    <div class="directorist-select directorist-search-field__input">

        <?php if ( ! empty( $data['label'] ) ) : ?>
            <label class="directorist-search-field__label">
                <?php echo esc_html( $data['label'] ); ?>
            </label>
        <?php endif; ?>

        <select 
            id="country_expert_select"
            name="custom_field[<?php echo esc_attr( $data['widget_name'] ); ?>][]" 
            class="directorist-search-select js-country-expert-select"
            data-isSearch="true"
            multiple="multiple"
            data-placeholder="Select Country"
            style="width: 100%;">
            <?php if ( ! empty( $all_countries ) && ! is_wp_error( $all_countries ) ) : ?>
                <?php foreach ( $all_countries as $country ) : ?>
                    <option value="<?php echo esc_attr( $country->term_id ); ?>" 
                        <?php selected( in_array( $country->term_id, $value ) ); ?>>
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



