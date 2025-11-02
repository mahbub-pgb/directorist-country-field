<?php
global $post;

if ( empty( $post->ID ) ) {
    return;
}

// Get selected country_expert terms for this listing
$selected_language = wp_get_post_terms( $post->ID, 'dl_language' );

if ( empty( $selected_language ) || is_wp_error( $selected_language ) ) {
    return;
}

$title = $args['data']['placeholder'];

// Extract country names
$language = wp_list_pluck( $selected_language, 'name' );
?>

<div class="directorist-country-expert-list" style="font-size: inherit; color: inherit; display: flex; align-items: center; gap: 6px;">
    <!-- Font Awesome SVG icon using Directorist icon mask -->
    <div class="directorist-country-expert-icon">
        <i class="fa-solid fa-globe"></i>
    </div>

   <?php echo $title . ' :' ?>
    <span><?php echo esc_html( implode( ', ', $language ) ); ?></span>
</div>
