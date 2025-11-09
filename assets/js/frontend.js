jQuery(document).ready(function($){
    var $select = $('#country_expert_select');

    // Initialize Select2 only if available
    if ( $.fn.select2 ) {
        $select.select2({
            placeholder: $select.data('placeholder'),
            allowClear: true,
            width: '100%'
        });
    }

    // Clear button behavior (click + keyboard)
    var $clearBtn = $('.directorist-search-field__btn--clear');
    $clearBtn.on('click keypress', function(e){
        if (e.type === 'click' || (e.type === 'keypress' && (e.key === 'Enter' || e.key === ' '))) {
            $select.val(null).trigger('change');
        }
    });
});

jQuery(document).ready(function ($) {

    function setCookie(name, value, days) {
        var expires = "";
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days*24*60*60*1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "")  + expires + "; path=/";
    }

    function getCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for(var i=0;i < ca.length;i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1,c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
        }
        return null;
    }

    $('.directorist-btn-reset-ajax').on('click', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        var urlFromCookie = getCookie('listings_url');

        if (urlFromCookie) {
            // If cookie exists, use it
            window.location.href = urlFromCookie;
        } else if (typeof DLF_JS !== 'undefined' && DLF_JS.listings_url) {
            // If cookie not found, set it and redirect
            setCookie('listings_url', DLF_JS.listings_url, 1);
            window.location.href = DLF_JS.listings_url;
        } else {
            console.warn('Listings URL not available.');
        }
    });

});

jQuery(document).ready(function($) {
    // target your select field and clear button
    var $select = $('#atbdp_language_select');
    var $clearBtn = $select.closest('.directorist-search-field').find('.directorist-search-field__btn--clear');

    function toggleClearButton() {
        var selected = $select.val(); // get selected values
        if (selected && selected.length > 0) {
            $clearBtn.show();  // show icon when something selected
        } else {
            $clearBtn.hide();  // hide when nothing selected
        }
    }

    // Run once on load
    toggleClearButton();

    // When selection changes (Select2 compatible)
    $select.on('change', function() {
        toggleClearButton();
    });

    // When clear icon is clicked, also hide it
    $clearBtn.on('click', function() {
        setTimeout(toggleClearButton, 100); // wait for clear action
    });
});



