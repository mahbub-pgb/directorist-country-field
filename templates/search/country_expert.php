<?php
/**
 * Country Expert Search Field Template
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Fetch all countries, sorted by name
$all_countries = get_terms([
    'taxonomy'   => 'country_expert',
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
    
<?php 
    // pri( $args['searchform']->form_data[1]['fields'][] );
    // pri( $data );
 ?>
   
       

        <?php if ( ! empty( $data['label'] ) ) : ?>
            <label class="directorist-search-field__label">
                <?php echo esc_html( $data['label'] ); ?>
            </label>
        <?php endif; ?>

        <select name="country_expert[country_expert][]" 
                class="directorist-search-select" 
                data-isSearch="true" 
                data-placeholder="<?php echo esc_attr( ! empty( $data['placeholder'] ) ? $data['placeholder'] : __( 'Select Country', 'directorist' ) ); ?>" 
                multiple>

            <?php if ( ! empty( $all_countries ) && ! is_wp_error( $all_countries ) ) : ?>
                <?php foreach ( $all_countries as $country ) : ?>
                    <option value="<?php echo esc_attr( $country->term_id ); ?>" 
                        <?php echo in_array( $country->term_id, $value ) ? 'selected' : ''; ?>>
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
    const $select = $('select.directorist-search-select');

    // Initialize select2 only once
    if(!$select.hasClass('select2-hidden-accessible')) {
        $select.select2({
            placeholder: function(){
                return $(this).data('placeholder');
            },
            allowClear: true,
            width: '100%'
        });
    }

    // Preselect values from URL
    const params = new URLSearchParams(window.location.search);
    if(params.has('country_expert')) {
        let selected = params.get('country_expert').split(',');
        $select.val(selected).trigger('change.select2');
    }

    // Handle change and reload page
    $select.on('change', function(e){
        e.stopPropagation();
        e.preventDefault();

        let selectedValues = $(this).val() || [];
        let valueString = selectedValues.join(',');

        // Get current URL without losing other params
        let url = new URL(window.location.href);

        if(valueString.length > 0){
            url.searchParams.set('country_expert', valueString);
        } else {
            url.searchParams.delete('country_expert');
        }

        // ✅ Decode before reloading to remove %2C encoding
        window.location.href = decodeURIComponent(url.toString());
    });
});
</script>





