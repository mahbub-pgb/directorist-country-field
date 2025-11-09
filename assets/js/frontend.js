jQuery(document).ready(function($) {

    /************ 1. Listing Title Label Toggle ************/
    var $listingInput = $('#listing_title');
    var $listingLabel = $listingInput.siblings('label');

    function toggleListingLabel() {
        if ($listingInput.val().trim() !== '') {
            $listingLabel.css('visibility', 'hidden');
        } else {
            $listingLabel.css('visibility', 'visible');
        }
    }

    toggleListingLabel(); // initial check
    $listingInput.on('input change keyup', toggleListingLabel);

    /************ 2. Initialize Select2 for Country Expert ************/
    var $countrySelect = $('#country_expert_select');

    if ($.fn.select2) {
        $countrySelect.select2({
            placeholder: $countrySelect.data('placeholder'),
            allowClear: true,
            width: '100%'
        });
    }

    /************ 3. Clear Button Behavior for Select2 ************/
    function toggleClearButton($select) {
        var $clearBtn = $select.closest('.directorist-search-field').find('.directorist-search-field__btn--clear');
        var selected = $select.val();
        if (selected && selected.length > 0) {
            $clearBtn.show();
        } else {
            $clearBtn.hide();
        }
    }

    $('.directorist-search-select').each(function() {
        var $select = $(this);

        toggleClearButton($select); // initial check

        // On change
        $select.on('change', function() {
            toggleClearButton($select);
        });

        // On clear button click
        $select.closest('.directorist-search-field')
               .find('.directorist-search-field__btn--clear')
               .on('click', function() {
                   setTimeout(function() { toggleClearButton($select); }, 100);
               });
    });

    /************ 4. Cookie Handling for Reset Button ************/
    function setCookie(name, value, days) {
        var expires = "";
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days*24*60*60*1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/";
    }

    function getCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for(var i=0; i < ca.length; i++) {
            var c = ca[i].trim();
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }

    $('.directorist-btn-reset-ajax').on('click', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        var urlFromCookie = getCookie('listings_url');

        if (urlFromCookie) {
            window.location.href = urlFromCookie;
        } else if (typeof DLF_JS !== 'undefined' && DLF_JS.listings_url) {
            setCookie('listings_url', DLF_JS.listings_url, 1);
            window.location.href = DLF_JS.listings_url;
        } else {
            console.warn('Listings URL not available.');
        }
    });

});
