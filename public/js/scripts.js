$(document).ready(function () {
    $('.map-controls__list_sublist li').on('click', function () {
        $('.map-controls__list_sublist li').removeClass('map-controls__list_active');
        $(this).addClass('map-controls__list_active');
        changeMapSrc($(this).data('img'));
    });
    $('.map-controls__list_parent li').on('click', function () {
        $('.map-controls__list li').removeClass('map-controls__list_active');
        $('.map-controls__list_sublist li').removeClass('map-controls__list_active');
        $(this).addClass('map-controls__list_active');
        $('.map-path-visible')
            .removeClass('map-path-visible')
            .addClass('map-path-hidden')
        ;
        $('.map-controls .map-path-' + $(this).data('path'))
            .removeClass('map-path-hidden')
            .addClass('map-path-visible')
        ;
        $('.map-controls .map-path-' + $(this).data('path') + ' ul li:first')
            .addClass('map-controls__list_active');
        changeMapSrc($('.map-controls .map-path-' + $(this).data('path') + ' ul li:first').data('img'));
    });
});

function changeMapSrc(src) {
    $('#map').attr('src', '/public/images/' + src + '.jpg');
}

