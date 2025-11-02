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
        $countries = [
            'AF' => 'Afghanistan',
            'AL' => 'Albania',
            'DZ' => 'Algeria',
            'AD' => 'Andorra',
            'AO' => 'Angola',
            'AG' => 'Antigua and Barbuda',
            'AR' => 'Argentina',
            'AM' => 'Armenia',
            'AU' => 'Australia',
            'AT' => 'Austria',
            'AZ' => 'Azerbaijan',
            'BS' => 'Bahamas',
            'BH' => 'Bahrain',
            'BD' => 'Bangladesh',
            'BB' => 'Barbados',
            'BY' => 'Belarus',
            'BE' => 'Belgium',
            'BZ' => 'Belize',
            'BJ' => 'Benin',
            'BT' => 'Bhutan',
            'BO' => 'Bolivia',
            'BA' => 'Bosnia and Herzegovina',
            'BW' => 'Botswana',
            'BR' => 'Brazil',
            'BN' => 'Brunei',
            'BG' => 'Bulgaria',
            'BF' => 'Burkina Faso',
            'BI' => 'Burundi',
            'CV' => 'Cabo Verde',
            'KH' => 'Cambodia',
            'CM' => 'Cameroon',
            'CA' => 'Canada',
            'CF' => 'Central African Republic',
            'TD' => 'Chad',
            'CL' => 'Chile',
            'CN' => 'China',
            'CO' => 'Colombia',
            'KM' => 'Comoros',
            'CG' => 'Congo',
            'CR' => 'Costa Rica',
            'HR' => 'Croatia',
            'CU' => 'Cuba',
            'CY' => 'Cyprus',
            'CZ' => 'Czech Republic',
            'DK' => 'Denmark',
            'DJ' => 'Djibouti',
            'DM' => 'Dominica',
            'DO' => 'Dominican Republic',
            'EC' => 'Ecuador',
            'EG' => 'Egypt',
            'SV' => 'El Salvador',
            'GQ' => 'Equatorial Guinea',
            'ER' => 'Eritrea',
            'EE' => 'Estonia',
            'ET' => 'Ethiopia',
            'FJ' => 'Fiji',
            'FI' => 'Finland',
            'FR' => 'France',
            'GA' => 'Gabon',
            'GM' => 'Gambia',
            'GE' => 'Georgia',
            'DE' => 'Germany',
            'GH' => 'Ghana',
            'GR' => 'Greece',
            'GD' => 'Grenada',
            'GT' => 'Guatemala',
            'GN' => 'Guinea',
            'GW' => 'Guinea-Bissau',
            'GY' => 'Guyana',
            'HT' => 'Haiti',
            'HN' => 'Honduras',
            'HU' => 'Hungary',
            'IS' => 'Iceland',
            'IN' => 'India',
            'ID' => 'Indonesia',
            'IR' => 'Iran',
            'IQ' => 'Iraq',
            'IE' => 'Ireland',
            'IL' => 'Israel',
            'IT' => 'Italy',
            'JM' => 'Jamaica',
            'JP' => 'Japan',
            'JO' => 'Jordan',
            'KZ' => 'Kazakhstan',
            'KE' => 'Kenya',
            'KI' => 'Kiribati',
            'KW' => 'Kuwait',
            'KG' => 'Kyrgyzstan',
            'LA' => 'Laos',
            'LV' => 'Latvia',
            'LB' => 'Lebanon',
            'LS' => 'Lesotho',
            'LR' => 'Liberia',
            'LY' => 'Libya',
            'LI' => 'Liechtenstein',
            'LT' => 'Lithuania',
            'LU' => 'Luxembourg',
            'MG' => 'Madagascar',
            'MW' => 'Malawi',
            'MY' => 'Malaysia',
            'MV' => 'Maldives',
            'ML' => 'Mali',
            'MT' => 'Malta',
            'MH' => 'Marshall Islands',
            'MR' => 'Mauritania',
            'MU' => 'Mauritius',
            'MX' => 'Mexico',
            'FM' => 'Micronesia',
            'MD' => 'Moldova',
            'MC' => 'Monaco',
            'MN' => 'Mongolia',
            'ME' => 'Montenegro',
            'MA' => 'Morocco',
            'MZ' => 'Mozambique',
            'MM' => 'Myanmar',
            'NA' => 'Namibia',
            'NR' => 'Nauru',
            'NP' => 'Nepal',
            'NL' => 'Netherlands',
            'NZ' => 'New Zealand',
            'NI' => 'Nicaragua',
            'NE' => 'Niger',
            'NG' => 'Nigeria',
            'KP' => 'North Korea',
            'MK' => 'North Macedonia',
            'NO' => 'Norway',
            'OM' => 'Oman',
            'PK' => 'Pakistan',
            'PW' => 'Palau',
            'PS' => 'Palestine',
            'PA' => 'Panama',
            'PG' => 'Papua New Guinea',
            'PY' => 'Paraguay',
            'PE' => 'Peru',
            'PH' => 'Philippines',
            'PL' => 'Poland',
            'PT' => 'Portugal',
            'QA' => 'Qatar',
            'RO' => 'Romania',
            'RU' => 'Russia',
            'RW' => 'Rwanda',
            'KN' => 'Saint Kitts and Nevis',
            'LC' => 'Saint Lucia',
            'VC' => 'Saint Vincent and the Grenadines',
            'WS' => 'Samoa',
            'SM' => 'San Marino',
            'ST' => 'Sao Tome and Principe',
            'SA' => 'Saudi Arabia',
            'SN' => 'Senegal',
            'RS' => 'Serbia',
            'SC' => 'Seychelles',
            'SL' => 'Sierra Leone',
            'SG' => 'Singapore',
            'SK' => 'Slovakia',
            'SI' => 'Slovenia',
            'SB' => 'Solomon Islands',
            'SO' => 'Somalia',
            'ZA' => 'South Africa',
            'KR' => 'South Korea',
            'SS' => 'South Sudan',
            'ES' => 'Spain',
            'LK' => 'Sri Lanka',
            'SD' => 'Sudan',
            'SR' => 'Suriname',
            'SE' => 'Sweden',
            'CH' => 'Switzerland',
            'SY' => 'Syria',
            'TJ' => 'Tajikistan',
            'TZ' => 'Tanzania',
            'TH' => 'Thailand',
            'TL' => 'Timor-Leste',
            'TG' => 'Togo',
            'TO' => 'Tonga',
            'TT' => 'Trinidad and Tobago',
            'TN' => 'Tunisia',
            'TR' => 'Turkey',
            'TM' => 'Turkmenistan',
            'TV' => 'Tuvalu',
            'UG' => 'Uganda',
            'UA' => 'Ukraine',
            'AE' => 'United Arab Emirates',
            'GB' => 'United Kingdom',
            'US' => 'United States of America',
            'UY' => 'Uruguay',
            'UZ' => 'Uzbekistan',
            'VU' => 'Vanuatu',
            'VA' => 'Vatican City',
            'VE' => 'Venezuela',
            'VN' => 'Vietnam',
            'YE' => 'Yemen',
            'ZM' => 'Zambia',
            'ZW' => 'Zimbabwe',
        ];

        return $countries;
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
