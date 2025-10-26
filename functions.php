<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! function_exists( 'pri' ) ) {
    function pri( $arg ) {
        echo '<pre style="background:#111;color:#0f0;padding:10px;border-radius:6px;">';
        print_r( $arg );
        echo '</pre>';
    }
}

/**
 * Get post IDs of 'at_biz_dir' filtered by country_expert taxonomy.
 *
 * @param string|array $country_ids Comma-separated string or array of term IDs.
 * @return array Filtered post IDs.
 */
function get_at_biz_dir_ids_by_country( $country_ids ) {
    // Ensure we have an array of integers
    if ( is_string( $country_ids ) ) {
        $country_ids = array_map( 'intval', explode( ',', $country_ids ) );
    } elseif ( is_array( $country_ids ) ) {
        $country_ids = array_map( 'intval', $country_ids );
    } else {
        return []; // Invalid input
    }

    if ( empty( $country_ids ) ) {
        return []; // No country selected
    }

    // Get all 'at_biz_dir' posts
    $all_posts = get_posts([
        'post_type'      => 'at_biz_dir',
        'posts_per_page' => -1,
        'fields'         => 'ids', // Only get IDs
        'post_status'    => 'publish',
    ]);

    // Filter by country_expert terms
    $filtered_ids = [];
    foreach ( $all_posts as $post_id ) {
        $post_terms = wp_get_post_terms( $post_id, 'country_expert', ['fields' => 'ids'] );
        if ( array_intersect( $post_terms, $country_ids ) ) {
            $filtered_ids[] = $post_id;
        }
    }

    return $filtered_ids;
}

if ( ! function_exists( 'dcf_load_template' ) ) {
    /**
     * Load a PHP template file and pass data to it.
     *
     * @param string $file  Absolute path to the template file.
     * @param array  $args  Optional. Variables to extract for use inside the template.
     * @param bool   $echo  Optional. Whether to directly print the output. Default true.
     *
     * @return string|null  Returns output if $echo = false, otherwise prints it.
     */
    function dcf_load_template( $file, $args = [], $echo = true ) {
        if ( ! file_exists( $file ) ) {
            return;
        }

        // Extract args as local variables inside template
        if ( is_array( $args ) && ! empty( $args ) ) {
            extract( $args, EXTR_SKIP );
        }

        // Capture template output
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

/**
 * Get all countries in the 'country_expert' taxonomy
 * Returns array in same format as get_cetagory_options()
 *
 * @return array
 */
function get_country_options() {

    $terms = get_terms([
        'taxonomy'   => 'country_expert',
        'hide_empty' => false,
    ]);

    $options = [];

    if ( is_wp_error( $terms ) || ! count( $terms ) ) {
        return $options;
    }

    foreach ( $terms as $term ) {
        $options[] = [
            'id'    => $term->term_id,
            'value' => $term->term_id,
            'label' => $term->name,
        ];
    }

    return $options;
}


/**
 * Get full list of countries.
 *
 * @return array List of country names.
 */
function get_all_countries_list() {
    return [
        'Afghanistan', 'Albania', 'Algeria', 'Andorra', 'Angola', 'Antigua and Barbuda',
        'Argentina', 'Armenia', 'Australia', 'Austria', 'Azerbaijan', 'Bahamas',
        'Bahrain', 'Bangladesh', 'Barbados', 'Belarus', 'Belgium', 'Belize', 'Benin',
        'Bhutan', 'Bolivia', 'Bosnia and Herzegovina', 'Botswana', 'Brazil', 'Brunei',
        'Bulgaria', 'Burkina Faso', 'Burundi', 'Cabo Verde', 'Cambodia', 'Cameroon',
        'Canada', 'Central African Republic', 'Chad', 'Chile', 'China', 'Colombia',
        'Comoros', 'Congo (Congo-Brazzaville)', 'Costa Rica', 'Croatia', 'Cuba',
        'Cyprus', 'Czech Republic', 'Democratic Republic of the Congo', 'Denmark',
        'Djibouti', 'Dominica', 'Dominican Republic', 'Ecuador', 'Egypt', 'El Salvador',
        'Equatorial Guinea', 'Eritrea', 'Estonia', 'Eswatini', 'Ethiopia', 'Fiji',
        'Finland', 'France', 'Gabon', 'Gambia', 'Georgia', 'Germany', 'Ghana', 'Greece',
        'Grenada', 'Guatemala', 'Guinea', 'Guinea-Bissau', 'Guyana', 'Haiti',
        'Honduras', 'Hungary', 'Iceland', 'India', 'Indonesia', 'Iran', 'Iraq', 'Ireland',
        'Israel', 'Italy', 'Jamaica', 'Japan', 'Jordan', 'Kazakhstan', 'Kenya', 'Kiribati',
        'Kuwait', 'Kyrgyzstan', 'Laos', 'Latvia', 'Lebanon', 'Lesotho', 'Liberia',
        'Libya', 'Liechtenstein', 'Lithuania', 'Luxembourg', 'Madagascar', 'Malawi',
        'Malaysia', 'Maldives', 'Mali', 'Malta', 'Marshall Islands', 'Mauritania',
        'Mauritius', 'Mexico', 'Micronesia', 'Moldova', 'Monaco', 'Mongolia',
        'Montenegro', 'Morocco', 'Mozambique', 'Myanmar (Burma)', 'Namibia', 'Nauru',
        'Nepal', 'Netherlands', 'New Zealand', 'Nicaragua', 'Niger', 'Nigeria',
        'North Korea', 'North Macedonia', 'Norway', 'Oman', 'Pakistan', 'Palau',
        'Palestine State', 'Panama', 'Papua New Guinea', 'Paraguay', 'Peru', 'Philippines',
        'Poland', 'Portugal', 'Qatar', 'Romania', 'Russia', 'Rwanda', 'Saint Kitts and Nevis',
        'Saint Lucia', 'Saint Vincent and the Grenadines', 'Samoa', 'San Marino',
        'Sao Tome and Principe', 'Saudi Arabia', 'Senegal', 'Serbia', 'Seychelles',
        'Sierra Leone', 'Singapore', 'Slovakia', 'Slovenia', 'Solomon Islands', 'Somalia',
        'South Africa', 'South Korea', 'South Sudan', 'Spain', 'Sri Lanka', 'Sudan',
        'Suriname', 'Sweden', 'Switzerland', 'Syria', 'Tajikistan', 'Tanzania', 'Thailand',
        'Timor-Leste', 'Togo', 'Tonga', 'Trinidad and Tobago', 'Tunisia', 'Turkey',
        'Turkmenistan', 'Tuvalu', 'Uganda', 'Ukraine', 'United Arab Emirates',
        'United Kingdom', 'United States of America', 'Uruguay', 'Uzbekistan', 'Vanuatu',
        'Vatican City', 'Venezuela', 'Vietnam', 'Yemen', 'Zambia', 'Zimbabwe'
    ];
}

/**
 * Add all countries to taxonomy 'country_expert' if not already added.
 */
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

/**
 * Debug helper
 */
if ( ! function_exists( 'dlf_log' ) ) {
    function dlf_log( $data ) {
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            error_log( print_r( $data, true ) );
        }
    }
}


