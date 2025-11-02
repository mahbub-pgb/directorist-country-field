<?php
// Placeholder text
$placeholder = $data['placeholder'] ?? 'Select Countries';

// Get all countries from the 'country_expert' taxonomy
$all_countries = get_terms( [
    'taxonomy'   => 'country_expert',
    'hide_empty' => false,
] );

// Get selected countries for this listing (if editing)
$current_terms = $listing_form->add_listing_terms( 'country_expert' );
$current_ids   = wp_list_pluck( $current_terms, 'term_id' );
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
        <?php if ( ! empty( $all_countries ) && ! is_wp_error( $all_countries ) ): ?>
            <?php foreach ( $all_countries as $country ): ?>
                <option 
                    value="<?php echo esc_attr( $country->term_id ); ?>" 
                    <?php echo in_array( $country->term_id, $current_ids, true ) ? 'selected="selected"' : ''; ?>
                >
                    <?php echo esc_html( $country->name ); ?>
                </option>
            <?php endforeach; ?>
        <?php else: ?>
            <option disabled><?php esc_html_e( 'No countries found', 'dlf' ); ?></option>
        <?php endif; ?>
    </select>

    <?php $listing_form->field_description_template( $data ); ?>
</div>


