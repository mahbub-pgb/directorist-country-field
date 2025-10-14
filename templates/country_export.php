<?php

global $wpdb;
$plugin_file = 'directorist-country-field/directorist-country-field.php';
$active_plugins = maybe_unserialize(
    $wpdb->get_var( "SELECT option_value FROM {$wpdb->prefix}options WHERE option_name = 'active_plugins'" )
);

$is_active = in_array( $plugin_file, (array) $active_plugins, true );
if ( ! $is_active ) return;

$placeholder = $data['placeholder'] ?? 'Select Countries';

// Get all countries using your custom SQL function
$all_countries = get_all_country_expert(); // This should return an array of term objects

// Get selected countries only if editing
global $post;
$current_ids = [];

if ( isset( $post->ID ) && ! empty( $post->ID ) ) {
    $current_terms = $listing_form->add_listing_terms('country_expert');
    $current_ids   = wp_list_pluck($current_terms, 'term_id');
}
?>

<div class="directorist-form-group directorist-form-country-field">
    <?php $listing_form->field_label_template($data, 'country_expert'); ?>

    <select 
        name="country_expert[]" 
        id="country_expert_list" 
        class="directorist-form-element" 
        multiple 
        data-placeholder="<?php echo esc_attr($placeholder); ?>"
    >
        <?php if ( ! empty( $all_countries ) ) : ?>
            <?php foreach ( $all_countries as $country ) : ?>
                <option 
                    value="<?php echo esc_attr( $country->term_id ); ?>"
                    <?php echo in_array( $country->term_id, $current_ids, true ) ? 'selected="selected"' : ''; ?>
                >
                    <?php echo esc_html( $country->name ); ?>
                </option>
            <?php endforeach; ?>
        <?php else : ?>
            <option disabled>No countries found.</option>
        <?php endif; ?>
    </select>

    <?php $listing_form->field_description_template($data); ?>
</div>

<!-- Inline JS to initialize Select2 -->
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
