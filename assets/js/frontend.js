
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

