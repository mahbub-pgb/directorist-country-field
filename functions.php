<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/* ============================================================
 * ✅ Debug Helpers
 * ============================================================ */

if ( ! function_exists( 'dlf_pri' ) ) {
    function dlf_pri( $arg ) {
        echo '<pre style="background:#111;color:#0f0;padding:10px;border-radius:6px;">';
        print_r( $arg );
        echo '</pre>';
    }
}

if ( ! function_exists( 'dlf_log' ) ) {
    function dlf_log( $data ) {
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            error_log( print_r( $data, true ) );
        }
    }
}


/* ============================================================
 * ✅ Template Loader
 * ============================================================ */

if ( ! function_exists( 'dcf_load_template' ) ) {
    /**
     * Load a PHP template file and pass data to it.
     */
    function dcf_load_template( $file, $args = [], $echo = true ) {
        if ( ! file_exists( $file ) ) return;

        if ( is_array( $args ) && ! empty( $args ) ) {
            extract( $args, EXTR_SKIP );
        }

        ob_start();
        include $file;
        $output = ob_get_clean();

        if ( $echo ) {
            echo $output;
            return null;
        }

        return $output;
    }
}


/* ============================================================
 * ✅ Core Functions: Country Expert
 * ============================================================ */

if ( ! function_exists( 'get_at_biz_dir_ids_by_country' ) ) {
    /**
     * Get post IDs of 'at_biz_dir' filtered by country_expert taxonomy.
     *
     * @param string|array $country_ids Term IDs (comma-separated or array).
     * @return array Filtered post IDs.
     */
    function get_at_biz_dir_ids_by_country( $country_ids ) {
        if ( is_string( $country_ids ) ) {
            $country_ids = array_map( 'intval', explode( ',', $country_ids ) );
        } elseif ( is_array( $country_ids ) ) {
            $country_ids = array_map( 'intval', $country_ids );
        } else {
            return [];
        }

        if ( empty( $country_ids ) ) return [];

        $all_posts = get_posts([
            'post_type'      => 'at_biz_dir',
            'posts_per_page' => -1,
            'fields'         => 'ids',
            'post_status'    => 'publish',
        ]);

        $filtered_ids = [];
        foreach ( $all_posts as $post_id ) {
            $post_terms = wp_get_post_terms( $post_id, 'country_expert', [ 'fields' => 'ids' ] );
            if ( array_intersect( $post_terms, $country_ids ) ) {
                $filtered_ids[] = $post_id;
            }
        }

        return $filtered_ids;
    }
}


/* ============================================================
 * ✅ Country Data Functions
 * ============================================================ */

if ( ! function_exists( 'get_country_options' ) ) {
    function get_country_options() {
        $terms = get_terms([
            'taxonomy'   => 'country_expert',
            'hide_empty' => false,
        ]);

        $options = [];
        if ( is_wp_error( $terms ) || ! count( $terms ) ) return $options;

        foreach ( $terms as $term ) {
            $options[] = [
                'id'    => $term->term_id,
                'value' => $term->term_id,
                'label' => $term->name,
            ];
        }

        return $options;
    }
}


if ( ! function_exists( 'get_all_countries_list' ) ) {
    function get_all_countries_list() {
        return [
            'Afghanistan', 'Albania', 'Algeria', 'Andorra', 'Angola', 'Antigua and Barbuda',
            'Argentina', 'Armenia', 'Australia', 'Austria', 'Azerbaijan', 'Bahamas',
            'Bahrain', 'Bangladesh', 'Barbados', 'Belarus', 'Belgium', 'Belize', 'Benin',
            'Bhutan', 'Bolivia', 'Bosnia and Herzegovina', 'Botswana', 'Brazil', 'Brunei',
            'Bulgaria', 'Burkina Faso', 'Burundi', 'Cambodia', 'Cameroon', 'Canada',
            'Chile', 'China', 'Colombia', 'Costa Rica', 'Croatia', 'Cuba', 'Cyprus',
            'Czech Republic', 'Denmark', 'Dominican Republic', 'Ecuador', 'Egypt',
            'Finland', 'France', 'Germany', 'Ghana', 'Greece', 'Hungary', 'Iceland', 'India',
            'Indonesia', 'Iran', 'Iraq', 'Ireland', 'Israel', 'Italy', 'Jamaica', 'Japan',
            'Jordan', 'Kenya', 'Kuwait', 'Laos', 'Latvia', 'Lebanon', 'Lithuania',
            'Luxembourg', 'Malaysia', 'Maldives', 'Malta', 'Mexico', 'Monaco', 'Mongolia',
            'Morocco', 'Nepal', 'Netherlands', 'New Zealand', 'Nigeria', 'Norway', 'Oman',
            'Pakistan', 'Palestine State', 'Peru', 'Philippines', 'Poland', 'Portugal',
            'Qatar', 'Romania', 'Russia', 'Saudi Arabia', 'Serbia', 'Singapore', 'Slovakia',
            'Slovenia', 'South Africa', 'South Korea', 'Spain', 'Sri Lanka', 'Sweden',
            'Switzerland', 'Thailand', 'Turkey', 'Ukraine', 'United Arab Emirates',
            'United Kingdom', 'United States of America', 'Vietnam', 'Yemen', 'Zimbabwe'
        ];
    }
}


/* ============================================================
 * ✅ Auto-Insert Taxonomy Terms
 * ============================================================ */

if ( ! function_exists( 'add_all_countries_to_country_expert' ) ) {
    function add_all_countries_to_country_expert() {
        $taxonomy  = 'country_expert';
        $countries = get_all_countries_list();

        foreach ( $countries as $country ) {
            $exists = term_exists( $country, $taxonomy );

            if ( ! $exists ) {
                $result = wp_insert_term( $country, $taxonomy );

                if ( is_wp_error( $result ) ) {
                    error_log( "❌ Failed to add country: {$country} - " . $result->get_error_message() );
                } else {
                    error_log( "✅ Added country: {$country}" );
                }
            }
        }
    }
}

if ( ! function_exists( 'get_iso_base_languages' )) {
    function get_iso_base_languages() {
        return [
            'af' => 'Afrikaans',
            'sq' => 'Albanian',
            'am' => 'Amharic',
            'ar' => 'Arabic',
            'hy' => 'Armenian',
            'az' => 'Azerbaijani',
            'eu' => 'Basque',
            'be' => 'Belarusian',
            'bn' => 'Bengali',
            'bs' => 'Bosnian',
            'bg' => 'Bulgarian',
            'ca' => 'Catalan',
            'ceb' => 'Cebuano',
            'ny' => 'Chichewa',
            'zh' => 'Chinese',
            'co' => 'Corsican',
            'hr' => 'Croatian',
            'cs' => 'Czech',
            'da' => 'Danish',
            'nl' => 'Dutch',
            'en' => 'English',
            'eo' => 'Esperanto',
            'et' => 'Estonian',
            'tl' => 'Filipino',
            'fi' => 'Finnish',
            'fr' => 'French',
            'fy' => 'Frisian',
            'gl' => 'Galician',
            'ka' => 'Georgian',
            'de' => 'German',
            'el' => 'Greek',
            'gu' => 'Gujarati',
            'ht' => 'Haitian Creole',
            'ha' => 'Hausa',
            'haw' => 'Hawaiian',
            'he' => 'Hebrew',
            'hi' => 'Hindi',
            'hmn' => 'Hmong',
            'hu' => 'Hungarian',
            'is' => 'Icelandic',
            'ig' => 'Igbo',
            'id' => 'Indonesian',
            'ga' => 'Irish',
            'it' => 'Italian',
            'ja' => 'Japanese',
            'jw' => 'Javanese',
            'kn' => 'Kannada',
            'kk' => 'Kazakh',
            'km' => 'Khmer',
            'ko' => 'Korean',
            'ku' => 'Kurdish',
            'ky' => 'Kyrgyz',
            'lo' => 'Lao',
            'la' => 'Latin',
            'lv' => 'Latvian',
            'lt' => 'Lithuanian',
            'lb' => 'Luxembourgish',
            'mk' => 'Macedonian',
            'mg' => 'Malagasy',
            'ms' => 'Malay',
            'ml' => 'Malayalam',
            'mt' => 'Maltese',
            'mi' => 'Maori',
            'mr' => 'Marathi',
            'mn' => 'Mongolian',
            'my' => 'Myanmar (Burmese)',
            'ne' => 'Nepali',
            'no' => 'Norwegian',
            'or' => 'Odia (Oriya)',
            'ps' => 'Pashto',
            'fa' => 'Persian',
            'pl' => 'Polish',
            'pt' => 'Portuguese',
            'pa' => 'Punjabi',
            'ro' => 'Romanian',
            'ru' => 'Russian',
            'sm' => 'Samoan',
            'gd' => 'Scots Gaelic',
            'sr' => 'Serbian',
            'st' => 'Sesotho',
            'sn' => 'Shona',
            'sd' => 'Sindhi',
            'si' => 'Sinhala',
            'sk' => 'Slovak',
            'sl' => 'Slovenian',
            'so' => 'Somali',
            'es' => 'Spanish',
            'su' => 'Sundanese',
            'sw' => 'Swahili',
            'sv' => 'Swedish',
            'tg' => 'Tajik',
            'ta' => 'Tamil',
            'tt' => 'Tatar',
            'te' => 'Telugu',
            'th' => 'Thai',
            'tr' => 'Turkish',
            'tk' => 'Turkmen',
            'uk' => 'Ukrainian',
            'ur' => 'Urdu',
            'ug' => 'Uyghur',
            'uz' => 'Uzbek',
            'vi' => 'Vietnamese',
            'cy' => 'Welsh',
            'xh' => 'Xhosa',
            'yi' => 'Yiddish',
            'yo' => 'Yoruba',
            'zu' => 'Zulu',
        ];
    }   

} ;

function reset_atbdp_languages_with_iso_list() {
    $taxonomy = 'atbdp_language';

    // 1️⃣ Delete all existing terms in the taxonomy
    $existing_terms = get_terms([
        'taxonomy'   => $taxonomy,
        'hide_empty' => false,
    ]);

    if (!is_wp_error($existing_terms) && !empty($existing_terms)) {
        foreach ($existing_terms as $term) {
            wp_delete_term($term->term_id, $taxonomy);
        }
    }

    // 2️⃣ Get ISO base language list
    $languages = get_iso_base_languages();

    if (empty($languages)) {
        return '❌ No languages found in ISO list.';
    }

    // 3️⃣ Add each language as a new term
    foreach ($languages as $code => $name) {
        wp_insert_term($name, $taxonomy, [
            'slug' => sanitize_title($code),
        ]);
    }

    return '✅ atbdp_language taxonomy reset completed successfully!';
}


