jQuery(document).ready(function($){
    $('#country_expert_select').select2({
        placeholder: $('#country_expert_select').data('placeholder'),
        allowClear: true,
        width: '100%'
    });

    // Clear button functionality
    $('.directorist-search-field__btn--clear').on('click', function(){
        var $select = $(this).closest('.directorist-search-field').find('select');
        $select.val(null).trigger('change');
    });
});