jQuery(document).ready(function($) {
    $('.notice-warning').on('click', '.notice-dismiss', function() {
        $.ajax({
            url: ajaxurl,
            data: {
                action: 'movies_plugin_dismiss_notice'
            }
        });
    });
});
