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


/* ============================================================
 * ✅ Language ISO + Taxonomy
 * ============================================================ */

if ( ! function_exists( 'get_directorist_language_iso' ) ) {
    /**
 * Get all countries with ISO 639-1 codes for Directorist listing select field
 *
 * @return array Array of countries ['US' => 'United States', 'FR' => 'France', ...]
 */
    function get_directorist_language_iso() {
            $languages = [
                'ab' => 'Abkhaz',
                'aa' => 'Afar',
                'af' => 'Afrikaans',
                'ak' => 'Akan',
                'sq' => 'Albanian',
                'am' => 'Amharic',
                'ar' => 'Arabic',
                'an' => 'Aragonese',
                'hy' => 'Armenian',
                'as' => 'Assamese',
                'av' => 'Avaric',
                'ae' => 'Avestan',
                'ay' => 'Aymara',
                'az' => 'Azerbaijani',
                'bm' => 'Bambara',
                'ba' => 'Bashkir',
                'eu' => 'Basque',
                'be' => 'Belarusian',
                'bn' => 'Bengali',
                'bh' => 'Bihari languages',
                'bi' => 'Bislama',
                'bs' => 'Bosnian',
                'br' => 'Breton',
                'bg' => 'Bulgarian',
                'my' => 'Burmese',
                'ca' => 'Catalan',
                'ch' => 'Chamorro',
                'ce' => 'Chechen',
                'ny' => 'Chichewa',
                'zh' => 'Chinese',
                'cv' => 'Chuvash',
                'kw' => 'Cornish',
                'co' => 'Corsican',
                'cr' => 'Cree',
                'hr' => 'Croatian',
                'cs' => 'Czech',
                'da' => 'Danish',
                'dv' => 'Divehi',
                'nl' => 'Dutch',
                'dz' => 'Dzongkha',
                'en' => 'English',
                'eo' => 'Esperanto',
                'et' => 'Estonian',
                'ee' => 'Ewe',
                'fo' => 'Faroese',
                'fj' => 'Fijian',
                'fi' => 'Finnish',
                'fr' => 'French',
                'ff' => 'Fulah',
                'gl' => 'Galician',
                'ka' => 'Georgian',
                'de' => 'German',
                'el' => 'Greek',
                'gn' => 'Guarani',
                'gu' => 'Gujarati',
                'ht' => 'Haitian',
                'ha' => 'Hausa',
                'he' => 'Hebrew',
                'hz' => 'Herero',
                'hi' => 'Hindi',
                'ho' => 'Hiri Motu',
                'hu' => 'Hungarian',
                'ia' => 'Interlingua',
                'id' => 'Indonesian',
                'ie' => 'Interlingue',
                'ga' => 'Irish',
                'ig' => 'Igbo',
                'ik' => 'Inupiaq',
                'io' => 'Ido',
                'is' => 'Icelandic',
                'it' => 'Italian',
                'iu' => 'Inuktitut',
                'ja' => 'Japanese',
                'jv' => 'Javanese',
                'kl' => 'Kalaallisut',
                'kn' => 'Kannada',
                'kr' => 'Kanuri',
                'ks' => 'Kashmiri',
                'kk' => 'Kazakh',
                'km' => 'Khmer',
                'ki' => 'Kikuyu',
                'rw' => 'Kinyarwanda',
                'ky' => 'Kyrgyz',
                'kv' => 'Komi',
                'kg' => 'Kongo',
                'ko' => 'Korean',
                'ku' => 'Kurdish',
                'kj' => 'Kwanyama',
                'la' => 'Latin',
                'lb' => 'Luxembourgish',
                'lg' => 'Ganda',
                'li' => 'Limburgish',
                'ln' => 'Lingala',
                'lo' => 'Lao',
                'lt' => 'Lithuanian',
                'lu' => 'Luba-Katanga',
                'lv' => 'Latvian',
                'gv' => 'Manx',
                'mk' => 'Macedonian',
                'mg' => 'Malagasy',
                'ms' => 'Malay',
                'ml' => 'Malayalam',
                'mt' => 'Maltese',
                'mi' => 'Māori',
                'mr' => 'Marathi',
                'mh' => 'Marshallese',
                'mn' => 'Mongolian',
                'na' => 'Nauru',
                'nv' => 'Navajo',
                'nd' => 'North Ndebele',
                'ne' => 'Nepali',
                'ng' => 'Ndonga',
                'nb' => 'Norwegian Bokmål',
                'nn' => 'Norwegian Nynorsk',
                'no' => 'Norwegian',
                'ii' => 'Sichuan Yi',
                'nr' => 'South Ndebele',
                'oc' => 'Occitan',
                'oj' => 'Ojibwa',
                'cu' => 'Old Church Slavonic',
                'om' => 'Oromo',
                'or' => 'Oriya',
                'os' => 'Ossetian',
                'pa' => 'Panjabi',
                'pi' => 'Pali',
                'fa' => 'Persian',
                'pl' => 'Polish',
                'ps' => 'Pashto',
                'pt' => 'Portuguese',
                'qu' => 'Quechua',
                'rm' => 'Romansh',
                'rn' => 'Rundi',
                'ro' => 'Romanian',
                'ru' => 'Russian',
                'sa' => 'Sanskrit',
                'sc' => 'Sardinian',
                'sd' => 'Sindhi',
                'se' => 'Northern Sami',
                'sm' => 'Samoan',
                'sg' => 'Sango',
                'sr' => 'Serbian',
                'gd' => 'Scottish Gaelic',
                'sn' => 'Shona',
                'si' => 'Sinhala',
                'sk' => 'Slovak',
                'sl' => 'Slovenian',
                'so' => 'Somali',
                'st' => 'Southern Sotho',
                'es' => 'Spanish',
                'su' => 'Sundanese',
                'sw' => 'Swahili',
                'ss' => 'Swati',
                'sv' => 'Swedish',
                'ta' => 'Tamil',
                'te' => 'Telugu',
                'tg' => 'Tajik',
                'th' => 'Thai',
                'ti' => 'Tigrinya',
                'bo' => 'Tibetan',
                'tk' => 'Turkmen',
                'tl' => 'Tagalog',
                'tn' => 'Tswana',
                'to' => 'Tonga',
                'tr' => 'Turkish',
                'ts' => 'Tsonga',
                'tt' => 'Tatar',
                'tw' => 'Twi',
                'ty' => 'Tahitian',
                'ug' => 'Uyghur',
                'uk' => 'Ukrainian',
                'ur' => 'Urdu',
                'uz' => 'Uzbek',
                've' => 'Venda',
                'vi' => 'Vietnamese',
                'vo' => 'Volapük',
                'wa' => 'Walloon',
                'cy' => 'Welsh',
                'wo' => 'Wolof',
                'fy' => 'Western Frisian',
                'xh' => 'Xhosa',
                'yi' => 'Yiddish',
                'yo' => 'Yoruba',
                'za' => 'Zhuang',
                'zu' => 'Zulu',
            ];


        return $languages;
    }
}


if ( ! function_exists( 'add_all_language_to_dl_language' ) ) {
    /**
     * Add all languages to 'dl_language' taxonomy if missing.
     */
    function add_all_language_to_dl_language() {
        $taxonomy  = 'dl_language';
        $languages = get_directorist_language_iso();

        foreach ( $languages as $code => $label ) {
            $exists = term_exists( $label, $taxonomy );

            if ( ! $exists ) {
                $result = wp_insert_term( $label, $taxonomy );

                if ( is_wp_error( $result ) ) {
                    error_log( "❌ Failed to add language: {$label} - " . $result->get_error_message() );
                } else {
                    error_log( "✅ Added language: {$label}" );
                }
            }
        }
    }
}
