// ------------ Sekcja Sidebara ------------ //
$(document).ready(function(){
    $('.nav-link[data-toggle="collapse"]').each(function() {
        var collapseTargetId = $(this).attr('data-target');
        var toggle = $(this).find('.plus-minus-toggle');
        if ($(collapseTargetId).hasClass('show')) {
            toggle.html('&minus;');
        } else {
            toggle.html('&plus;');
        }
    });

    $('.nav-link[data-toggle="collapse"]').click(function(event) {
        var collapseTargetId = $(this).attr('data-target');
        var toggle = $(this).find('.plus-minus-toggle');
        $(collapseTargetId).on('show.bs.collapse', function() {
            toggle.html('&minus;');
        });
        $(collapseTargetId).on('hide.bs.collapse', function() {
            toggle.html('&plus;');
        });
    });
});

// przycisk sidebaru (burger)
    $('#mobileSidebar .close').click(function() {
        $('#mobileSidebar').collapse('hide');
});

