$(function () {

    // fix .panel height
    (function() {
        var max = 0;
        var projects = $('#projects');

        projects.find('.col-lg-4 .panel .panel-content').each(function() {
            max = Math.max($(this).height(), max);
        });

        projects.find('.col-lg-4 .panel .panel-content').height(max + 10);
    })();

});