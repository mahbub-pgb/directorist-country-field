<?php
/**
 * @author  wpWax
 * @since   8.0
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$title   = $listing->get_title();
$tagline = $listing->get_tagline();
$socials = $listing->get_socials();

// Check if all main data fields are empty
if ( empty( $title ) && empty( $tagline ) && empty( $socials ) ) : ?>
    <div class="directorist-no-data">
        <p><?php esc_html_e( 'No listing data found.', 'directorist' ); ?></p>
    </div>
<?php
    return; // Stop rendering further sections
endif;
?>

<?php if ( $display_title && $title ) : ?>
    <h1 class="directorist-listing-details__listing-title"><?php echo esc_html( $title ); ?></h1>
<?php endif; ?>

<?php if ( $display_tagline && $tagline ) : ?>
    <p class="directorist-listing-details__tagline"><?php echo esc_html( $tagline ); ?></p>
<?php endif; ?>

<?php do_action( 'directorist_single_listing_after_title', $listing->id ); ?>

<?php
// SOCIAL LINKS
if ( ! empty( $socials ) ) :
?>
    <div class="directorist-single-info directorist-single-info-socials">
        <?php if ( ! empty( $data['label'] ) ) : ?>
            <div class="directorist-single-info__label">
                <span class="directorist-single-info__label-icon"><?php directorist_icon( $icon );?></span>
                <span class="directorist-single-info__label__text"><?php echo esc_html( $data['label'] ); ?></span>
            </div>
        <?php endif; ?>

        <div class="directorist-social-links">
            <?php foreach ( $socials as $social ) : 
                $icon = 'lab la-' . $social['id']; ?>
                <a target="_blank" href="<?php echo esc_url( $social['url'] ); ?>" class="<?php echo esc_attr( $social['id'] ); ?>">
                    <?php directorist_icon( $icon ); ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
