<?php 
global $post;

if ( empty( $post->ID ) ) {
    return;
}

// Get all available countries (custom function)
$all_countries = get_all_country_expert();

if ( empty( $all_countries ) || ! is_array( $all_countries ) ) {
    return;
}

// Get the selected country IDs for this listing
$selected_terms = wp_get_post_terms( $post->ID, 'country_expert', [ 'fields' => 'ids' ] );


$post_types = get_object_taxonomies( 'at_biz_dir' );
pri( $post_types );
return;

if ( empty( $selected_terms ) ) {
    return;
}

// Filter only selected countries from full list
$selected_countries = array_filter( $all_countries, function ( $country ) use ( $selected_terms ) {
    return in_array( (int) $country->term_id, $selected_terms, true );
} );

if ( empty( $selected_countries ) ) {
    return;
}

?>
<div class="directorist-country-expert-section atbd_content_module">
    <div class="atbd_content_module_title_area">
        <h4 class="atbd_content_module_title">
            <span class="la la-globe"></span>
            <?php esc_html_e( 'Country Expert', 'directorist-country-field' ); ?>
        </h4>
    </div>

    <div class="atbd_content_module_contents">
        <div class="table-responsive">
            <table class="directorist-country-expert-table">
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'Country Name', 'directorist-country-field' ); ?></th>
                        <th><?php esc_html_e( 'Slug', 'directorist-country-field' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( $selected_countries as $country ) : ?>
                        <tr>
                            <td>
                                <a href="<?php echo esc_url( get_term_link( $country ) ); ?>">
                                    <?php echo esc_html( $country->name ); ?>
                                </a>
                            </td>
                            <td><?php echo esc_html( $country->slug ); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
