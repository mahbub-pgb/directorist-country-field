<?php
$placeholder   = $data['placeholder'] ?? 'Select Countries';
$all_countries = get_terms( [ 'taxonomy' => 'at_biz_dir-location', 'hide_empty' => false ] );
$current_terms = $listing_form->add_listing_terms( 'country_expert' );
$current_ids   = wp_list_pluck( $current_terms, 'term_id' );
?>
<div class="directorist-form-group directorist-form-country-field">
    <?php $listing_form->field_label_template( $data, 'country_expert' ); ?>
    <select name="country_expert[]" id="country_expert" class="directorist-form-element" multiple data-placeholder="<?php echo esc_attr( $placeholder ); ?>">
        <?php foreach ( $all_countries as $country ): ?>
            <option value="<?php echo esc_attr( $country->term_id ); ?>" <?php selected( in_array( $country->term_id, $current_ids ) ); ?>>
                <?php echo esc_html( $country->name ); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <?php $listing_form->field_description_template( $data ); ?>
</div>

<!-- Inline JS to initialize Select2 -->
<script type="text/javascript">
jQuery(document).ready(function($){
    if( $('#country_expert').length ) {
        $('#country_expert').select2({
            placeholder: $('#country_expert').data('placeholder'),
            width: '100%',      // make full width
            allowClear: true,   // allow clearing selections
            closeOnSelect: false // keep dropdown open for multiple select
        });
    }
});
</script>
