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
    $('.reserve-room-btn').on('click', function () {
        reserveRoom($(this).data('id'));
    });
    $('.check-room-reserve').on('click', function () {
        checkRoomReserve($(this).data('id'));
    });
    moment.locale('ru');
    $('#daterange').daterangepicker({
        autoApply: true,
        locale: {
            format: 'DD.MM.YYYY'
        },
        minDate: getMinDate(),
        startDate: getStartDate(),
        endDate: getEndDate()
    })
    .on('apply.daterangepicker', function(ev, picker) {
        var dateDiff = picker.endDate - picker.startDate;
        writeNightCount((Math.round(dateDiff / 86400000) - 1), '');
    });
    $('.datepicker-toggler').click(function() {
        $("#" + $(this).data('target')).focus();
    });
});

function changeMapSrc(src) {
    $('#map').attr('src', '/public/images/' + src + '.jpg?v=1');
    if(src.indexOf('taxi') !== -1) {
        $('.taxi-link').css('display', 'block');
    } else {
        $('.taxi-link').css('display', 'none');
    }
}
function reserveRoom(roomId) {
    if (parseInt($('#notChecked' + roomId).val()) === 0) {
        showNotificationModal('Проверьте возможность забронировать в нужный период', 'danger');
        return;
    }
    var validFormResult = formIsValid(roomId);
    if(validFormResult === 'errorName') {
        showNotificationModal('Заполните поле Имя!', 'danger');
        return;
    } else if(validFormResult === 'errorPhone') {
        showNotificationModal('Некорректно указан номер телефона!', 'danger');
        return;
    } else if(validFormResult === 'errorEmail') {
        showNotificationModal('Некорректно указан email!', 'danger');
        return;
    }
    var dates = getDates(roomId);
    $.ajax({url: '/ajax/show_reserve_modal', type: 'POST', data: {roomId: roomId, phone: $('#inputPhone' + roomId).val(), name: $('#inputName' + roomId).val(), email: $('#inputEmail' + roomId).val(), comment: $('#inputComment' + roomId).val(), arrivalDate: dates[0], departureDate: dates[1], adult: $('#inputAdult' + roomId).val(), childrenTo2: $('#inputChildrenTo2' + roomId).val(), childrenTo6: $('#inputChildrenTo6' + roomId).val(), childrenTo12: $('#inputChildrenTo12' + roomId).val()}, async: true}).done(function (html) {$('#reservationModal .modal-body').html(html);$('#reservationModal').modal('toggle');});
}
function formIsValid(roomId) {
    if(checkPhone($('#inputPhone' + roomId).val()) === 0) {return 'errorPhone';}
    if(checkEmail($('#inputEmail' + roomId).val()) === 0) {return 'errorEmail';}
    if($('#inputName' + roomId).val() === '') {return 'errorName';}
    return 'valid';
}
function checkPhone(phone) {return parseInt($.ajax({type: 'POST', url: '/ajax/check_phone', async: false, data : {phone:phone}}).responseText);}
function checkEmail(email) {return parseInt($.ajax({type: 'POST', url: '/ajax/check_email', async: false, data : {email:email}}).responseText);}
function showNotificationModal(text, style) {
    var html = '<div class="alert alert-' + style + '">' + text + '</div>';
    $('#notificationModal .modal-body').html(html);
    $('#notificationModal').modal('toggle');
}
function checkRoomReserve(roomId){var dates = getDates(roomId);if(dates[0]===dates[1] || new Date(dates[0]) > new Date(dates[1])){showNotificationModal('Некорректно выбран период.', 'danger');$('#notChecked' + roomId).val(0);return;}$.ajax({url: '/ajax/check_room_reserve', type: 'POST', data: {roomId: roomId, arrivalDate: dates[0], departureDate: dates[1]}, async: true}).done(function (response) {if(response === 'free') {showNotificationModal('Бронирование номера доступно.', 'success');$('#notChecked' + roomId).val(1);}else{showNotificationModal('Бронирование номера не доступно.', 'danger');$('#notChecked' + roomId).val(0);}});}
function notPayedReserveRoom() {
    var roomId = $('#reserveRoomData > #reserveRoomId').val();
    $.ajax({url: '/ajax/reserve_room', type: 'POST', data: {roomId: roomId, phone: $('#reserveRoomData > #customerPhone').val(), name: $('#reserveRoomData > #customerName').val(), email: $('#reserveRoomData > #customerEmail').val(), comment: $('#reserveRoomData > #customerComment').val(),arrivalDate: $('#reserveRoomData > #arrivalDate').val(), departureDate: $('#reserveRoomData > #departureDate').val(), adult: $('#reserveRoomData > #adult').val(), childrenTo2: $('#reserveRoomData > #childrenTo2').val(), childrenTo6: $('#reserveRoomData > #childrenTo6').val(), childrenTo12: $('#reserveRoomData > #childrenTo12').val(), type: 'site'}, async: true}).done(function () {$('#reservationModal').modal('toggle');showNotificationModal('Номер успешно забронирован!', 'success');$('#notChecked' + roomId).val(0);});
}
function getDates(roomId){var dateRange = $('#daterange' + roomId).val();return dates = dateRange.split(' - ');}
function filterRooms() {var dates = getDates('');$('input[name="arrival_date"]').val(dates[0]);$('input[name="departure_date"]').val(dates[1]);$('#filter_form').submit();}
function declOfNum(number, titles) {cases = [2, 0, 1, 1, 1, 2];return titles[ (number%100>4 && number%100<20)? 2 : cases[(number%10<5)?number%10:5] ];}
function writeNightCount(count, roomId) {var titles = ['ночь', 'ночи', 'ночей'];$('#nightCount' + roomId).html(count + ' ' + declOfNum(count, titles));}