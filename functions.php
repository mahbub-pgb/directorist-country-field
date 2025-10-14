<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! function_exists( 'dlf_pri' ) ) {
    function dlf_pri( $arg ) {
        echo '<pre style="background:#111;color:#0f0;padding:10px;border-radius:6px;">';
        print_r( $arg );
        echo '</pre>';
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
 * Return ISO 639-1 language codes (master list)
 *
 * @return array
 */

if ( ! function_exists( 'dlf_get_iso_639_1_languages' ) ) {
    function dlf_get_iso_639_1_languages() {
        $languages = [
            "aa"=>"Afar",
            "ab"=>"Abkhaz",
            "ae"=>"Avestan",
            "af"=>"Afrikaans",
            "ak"=>"Akan",
            "am"=>"Amharic",
            "an"=>"Aragonese",
            "ar"=>"Arabic",
            "as"=>"Assamese",
            "av"=>"Avaric",
            "ay"=>"Aymara",
            "az"=>"Azerbaijani",
            "ba"=>"Bashkir",
            "be"=>"Belarusian",
            "bg"=>"Bulgarian",
            "bh"=>"Bihari languages",
            "bi"=>"Bislama",
            "bm"=>"Bambara",
            "bn"=>"Bengali",
            "bo"=>"Tibetan",
            "br"=>"Breton",
            "bs"=>"Bosnian",
            "ca"=>"Catalan",
            "ce"=>"Chechen",
            "ch"=>"Chamorro",
            "co"=>"Corsican",
            "cr"=>"Cree",
            "cs"=>"Czech",
            "cu"=>"Church Slavic",
            "cv"=>"Chuvash",
            "cy"=>"Welsh",
            "da"=>"Danish",
            "de"=>"German",
            "dv"=>"Divehi",
            "dz"=>"Dzongkha",
            "ee"=>"Ewe",
            "el"=>"Greek",
            "en"=>"English",
            "eo"=>"Esperanto",
            "es"=>"Spanish",
            "et"=>"Estonian",
            "eu"=>"Basque",
            "fa"=>"Persian",
            "ff"=>"Fulah",
            "fi"=>"Finnish",
            "fj"=>"Fijian",
            "fo"=>"Faroese",
            "fr"=>"French",
            "fy"=>"Western Frisian",
            "ga"=>"Irish",
            "gd"=>"Scottish Gaelic",
            "gl"=>"Galician",
            "gn"=>"Guarani",
            "gu"=>"Gujarati",
            "gv"=>"Manx",
            "ha"=>"Hausa",
            "he"=>"Hebrew",
            "hi"=>"Hindi",
            "ho"=>"Hiri Motu",
            "hr"=>"Croatian",
            "ht"=>"Haitian Creole",
            "hu"=>"Hungarian",
            "hy"=>"Armenian",
            "hz"=>"Herero",
            "ia"=>"Interlingua",
            "id"=>"Indonesian",
            "ie"=>"Interlingue",
            "ig"=>"Igbo",
            "ii"=>"Sichuan Yi",
            "ik"=>"Inupiaq",
            "io"=>"Ido",
            "is"=>"Icelandic",
            "it"=>"Italian",
            "iu"=>"Inuktitut",
            "ja"=>"Japanese",
            "jv"=>"Javanese",
            "ka"=>"Georgian",
            "kg"=>"Kongo",
            "ki"=>"Kikuyu",
            "kj"=>"Kuanyama",
            "kk"=>"Kazakh",
            "kl"=>"Kalaallisut",
            "km"=>"Khmer",
            "kn"=>"Kannada",
            "ko"=>"Korean",
            "kr"=>"Kanuri",
            "ks"=>"Kashmiri",
            "ku"=>"Kurdish",
            "kv"=>"Komi",
            "kw"=>"Cornish",
            "ky"=>"Kyrgyz",
            "la"=>"Latin",
            "lb"=>"Luxembourgish",
            "lg"=>"Ganda",
            "li"=>"Limburgish",
            "ln"=>"Lingala",
            "lo"=>"Lao",
            "lt"=>"Lithuanian",
            "lu"=>"Luba-Katanga",
            "lv"=>"Latvian",
            "mg"=>"Malagasy",
            "mh"=>"Marshallese",
            "mi"=>"Māori",
            "mk"=>"Macedonian",
            "ml"=>"Malayalam",
            "mn"=>"Mongolian",
            "mr"=>"Marathi",
            "ms"=>"Malay",
            "mt"=>"Maltese",
            "my"=>"Burmese",
            "na"=>"Nauru",
            "nb"=>"Norwegian Bokmål",
            "nd"=>"North Ndebele",
            "ne"=>"Nepali",
            "ng"=>"Ndonga",
            "nl"=>"Dutch",
            "nn"=>"Norwegian Nynorsk",
            "no"=>"Norwegian",
            "nr"=>"South Ndebele",
            "nv"=>"Navajo",
            "ny"=>"Chichewa",
            "oc"=>"Occitan",
            "oj"=>"Ojibwa",
            "om"=>"Oromo",
            "or"=>"Odia",
            "os"=>"Ossetian",
            "pa"=>"Punjabi",
            "pi"=>"Pali",
            "pl"=>"Polish",
            "ps"=>"Pashto",
            "pt"=>"Portuguese",
            "qu"=>"Quechua",
            "rm"=>"Romansh",
            "rn"=>"Rundi",
            "ro"=>"Romanian",
            "ru"=>"Russian",
            "rw"=>"Kinyarwanda",
            "sa"=>"Sanskrit",
            "sc"=>"Sardinian",
            "sd"=>"Sindhi",
            "se"=>"Northern Sami",
            "sg"=>"Sango",
            "si"=>"Sinhala",
            "sk"=>"Slovak",
            "sl"=>"Slovenian",
            "sm"=>"Samoan",
            "sn"=>"Shona",
            "so"=>"Somali",
            "sq"=>"Albanian",
            "sr"=>"Serbian",
            "ss"=>"Swati",
            "st"=>"Southern Sotho",
            "su"=>"Sundanese",
            "sv"=>"Swedish",
            "sw"=>"Swahili",
            "ta"=>"Tamil",
            "te"=>"Telugu",
            "tg"=>"Tajik",
            "th"=>"Thai",
            "ti"=>"Tigrinya",
            "tk"=>"Turkmen",
            "tl"=>"Tagalog",
            "tn"=>"Tswana",
            "to"=>"Tongan",
            "tr"=>"Turkish",
            "ts"=>"Tsonga",
            "tt"=>"Tatar",
            "tw"=>"Twi",
            "ty"=>"Tahitian",
            "ug"=>"Uyghur",
            "uk"=>"Ukrainian",
            "ur"=>"Urdu",
            "uz"=>"Uzbek",
            "ve"=>"Venda",
            "vi"=>"Vietnamese",
            "vo"=>"Volapük",
            "wa"=>"Walloon",
            "wo"=>"Wolof",
            "xh"=>"Xhosa",
            "yi"=>"Yiddish",
            "yo"=>"Yoruba",
            "za"=>"Zhuang",
            "zh"=>"Chinese",
            "zu"=>"Zulu"
        ];
        return $languages;
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


