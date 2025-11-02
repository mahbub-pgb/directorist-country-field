<?php
/**
 * Language Selector Field Template
 * Adds multi-language selector for Directorist listings.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Placeholder text
$placeholder = $data['placeholder'] ?? 'Select Languages';

// Get all languages from taxonomy
$all_languages = get_terms([
    'taxonomy'   => 'dl_language',
    'hide_empty' => false,
    'orderby'    => 'name',
    'order'      => 'ASC',
]);

// Get selected languages for this listing (if editing)
$current_terms = $listing_form->add_listing_terms('dl_language');
$current_ids   = wp_list_pluck( $current_terms, 'term_id' );
?>

<div class="directorist-form-group directorist-form-language-field">
    <?php $listing_form->field_label_template($data, 'dl_language'); ?>

    <select
        name="dl_language[]"
        id="dl_language_list"
        class="directorist-form-element"
        multiple
        data-placeholder="<?php echo esc_attr($placeholder); ?>"
    >
        <?php if (!empty($all_languages) && !is_wp_error($all_languages)): ?>
            <?php foreach ($all_languages as $language): ?>
                <option
                    value="<?php echo esc_attr($language->term_id); ?>"
                    <?php echo in_array($language->term_id, $current_ids, true) ? 'selected="selected"' : ''; ?>
                >
                    <?php echo esc_html($language->name); ?>
                </option>
            <?php endforeach; ?>
        <?php else: ?>
            <option disabled><?php esc_html_e('No languages found', 'directorist-language-field'); ?></option>
        <?php endif; ?>
    </select>

    <?php $listing_form->field_description_template($data); ?>
</div>

<!-- Initialize Select2 for multi-select -->
<script type="text/javascript">
jQuery(document).ready(function($){
    const $select = $('#dl_language_list');
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
