jQuery(document).ready(function($) {
    $('.directorist-btn-reset-ajax').on('click', function(e) {
        e.preventDefault();

        // Find all Directorist Select2 fields
        $('.directorist-search-select').each(function() {
            const $select = $(this);

            // Clear all values (works for single & multiple)
            $select.val(null).trigger('change.select2');

            // Force clear the rendered tags (visual reset)
            const container = $select.next('.select2');
            container.find('.select2-selection__choice').remove();
            container.find('.select2-selection__rendered').empty();
        });
    });
});
