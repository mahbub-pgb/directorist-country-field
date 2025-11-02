<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// Fetch all countries
$all_countries = get_terms([
    'taxonomy'   => 'country_expert',
    'hide_empty' => false,
    'orderby'    => 'name',
    'order'      => 'ASC',
]);

// Get selected values from $data or $_GET
$value = [];

// Check if data has value
if ( ! empty( $data['value'] ) ) {
    $value = is_array( $data['value'] ) ? $data['value'] : explode( ',', $data['value'] );
}

// Check if URL has selected values
if ( ! empty( $_GET['custom_field']['country_expert'] ) ) {
    $get_values = $_GET['custom_field']['country_expert'];
    if ( ! is_array( $get_values ) ) {
        $get_values = explode( ',', $get_values );
    }
    // Merge and make unique
    $value = array_unique( array_merge( $value, $get_values ) );
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
            <?php foreach ( $all_countries as $country ) : ?>
                <option value="<?php echo esc_attr( $country->term_id ); ?>" 
                    <?php selected( in_array( $country->term_id, $value ) ); ?>>
                    <?php echo esc_html( $country->name ); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="directorist-search-field__btn directorist-search-field__btn--clear">
        <?php directorist_icon( 'fas fa-times-circle' ); ?>
    </div>
</div>