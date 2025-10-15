<?php
/**
 * Custom Directorist country_expert field template
 */

// Placeholder text
$placeholder = $data['placeholder'] ?? 'Select Countries';

// Get all countries (should return array of term objects)
$all_countries = get_all_country_expert(); // Example: returns terms from 'country_expert' taxonomy

// Initialize selected country IDs
$current_ids = [];

// Get selected terms (only if editing an existing listing)
if ( ! empty( $listing_form ) && method_exists( $listing_form, 'add_listing_terms' ) ) {
    $current_terms = $listing_form->add_listing_terms( 'country_expert' );

    if ( ! empty( $current_terms ) && is_array( $current_terms ) ) {
        $current_ids = wp_list_pluck( $current_terms, 'term_id' );
    }
}

?>

<div class="directorist-form-group directorist-form-country-field">
    <?php $listing_form->field_label_template( $data, 'country_expert' ); ?>

    <select 
        name="country_expert[]" 
        id="country_expert_list" 
        class="directorist-form-element" 
        multiple 
        data-placeholder="<?php echo esc_attr( $placeholder ); ?>"
    >
        <?php if ( ! empty( $all_countries ) ) : ?>
            <?php foreach ( $all_countries as $country ) : ?>
                <?php
                // Ensure $country is a term object
                if ( ! is_object( $country ) ) {
                    continue;
                }

                $selected = in_array( (int) $country->term_id, (array) $current_ids, true ) ? 'selected="selected"' : '';
                ?>
                <option value="<?php echo esc_attr( $country->term_id ); ?>" <?php echo $selected; ?>>
                    <?php echo esc_html( $country->name ); ?>
                </option>
            <?php endforeach; ?>
        <?php else : ?>
            <option disabled>No countries found.</option>
        <?php endif; ?>
    </select>

    <?php $listing_form->field_description_template( $data ); ?>
</div>

<!-- Initialize Select2 -->
<script type="text/javascript">
jQuery(document).ready(function ($) {
    const $select = $('#country_expert_list');
    if ($select.length) {
        $select.select2({
            placeholder: $select.data('placeholder'),
            width: '100%',
            allowClear: true,
            closeOnSelect: false
        });
    }
});
</script>
