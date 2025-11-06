<?php
$placeholder   = $data['placeholder'] ?? 'Select Languages';
$all_languages = get_terms( [ 'taxonomy' => 'atbdp_language', 'hide_empty' => false ] );
$current_terms = $listing_form->add_listing_terms( 'atbdp_language' );
$current_ids   = wp_list_pluck( $current_terms, 'term_id' );
?>
<div class="directorist-form-group directorist-form-language-field">
    <?php $listing_form->field_label_template( $data, 'atbdp_language' ); ?>
    <select name="atbdp_language[]" id="atbdp_language" class="directorist-form-element" multiple data-placeholder="<?php echo esc_attr( $placeholder ); ?>">
        <?php foreach ( $all_languages as $language ): ?>
            <option value="<?php echo esc_attr( $language->term_id ); ?>" <?php selected( in_array( $language->term_id, $current_ids ) ); ?>>
                <?php echo esc_html( $language->name ); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?php $listing_form->field_description_template( $data ); ?>
</div>
