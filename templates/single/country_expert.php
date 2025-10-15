<?php
global $post;

if ( empty( $post->ID ) ) {
    return;
}

// Get selected country_expert terms for this listing
$selected_countries = wp_get_post_terms( $post->ID, 'country_expert' );

if ( empty( $selected_countries ) || is_wp_error( $selected_countries ) ) {
    return;
}

// Extract country names
$country_names = wp_list_pluck( $selected_countries, 'name' );
?>

<div class="directorist-country-expert-list" style="font-size: inherit; color: inherit; display: flex; align-items: center; gap: 6px;">
    <!-- Font Awesome SVG icon using Directorist icon mask -->
    <div class="directorist-country-expert-icon">
        <i class="fa-solid fa-globe"></i>
    </div>

    <strong><?php esc_html_e( 'Country Expert : ', 'directorist-country-field' ); ?></strong>
    <span><?php echo esc_html( implode( ', ', $country_names ) ); ?></span>
</div>
