jQuery(document).ready(function($){
    $('#country_expert_select').select2({
        placeholder: $(this).data('placeholder'),
        allowClear: true,
        width: '100%'
    });

    // fully reset select on clear button click
    $('#country_expert_select').on('select2:clear', function(){
        $(this).val(null).trigger('change');
    });
});
