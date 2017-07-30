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
});

function changeMapSrc(src) {
    $('#map').attr('src', '/public/images/' + src + '.jpg');
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
    if(formIsValid(roomId) === 'errorName') {
        showNotificationModal('Заполните поле Имя!', 'danger');
        return;
    } else if(formIsValid(roomId) === 'errorPhone') {
        showNotificationModal('Некорректно указан номер телефона!', 'danger');
        return;
    }
    $.ajax({url: '/ajax/show_reserve_modal', type: 'POST', data: {roomId: roomId, phone: $('#inputPhone' + roomId).val(), name: $('#inputName' + roomId).val(), comment: $('#inputComment' + roomId).val(), arrivalDate: $('#modalArrival' + roomId).val(), departureDate: $('#modalDeparture' + roomId).val()}, async: true}).done(function (html) {$('#reservationModal .modal-body').html(html);$('#reservationModal').modal('toggle');});
}
function formIsValid(roomId) {
    var phoneReg = /\+7[\d]{10}/;
    var phone = $('#inputPhone' + roomId).val();
    if(!phoneReg.test(phone)) {
        return 'errorPhone';
    }
    if($('#inputName' + roomId).val() === '') {
        return 'errorName';
    }
    return 'valid';
}
function showNotificationModal(text, style) {
    var html = '<div class="alert alert-' + style + '">' + text + '</div>';
    $('#notificationModal .modal-body').html(html);
    $('#notificationModal').modal('toggle');
}
function checkRoomReserve(roomId) {
    $.ajax({url: '/ajax/check_room_reserve', type: 'POST', data: {roomId: roomId, arrivalDate: $('#modalArrival' + roomId).val(), departureDate: $('#modalDeparture' + roomId).val()}, async: true}).done(function (response) {if(response === 'free') {showNotificationModal('Бронирование номера доступно.', 'success');$('#notChecked' + roomId).val(1);}else{showNotificationModal('Бронирование номера не доступно.', 'danger');$('#notChecked' + roomId).val(0);}});
}
function notPayedReserveRoom() {
    var roomId = $('#reserveRoomData > #reserveRoomId').val();
    $.ajax({url: '/ajax/reserve_room', type: 'POST', data: {roomId: roomId, phone: $('#reserveRoomData > #customerPhone').val(), name: $('#reserveRoomData > #customerName').val(), comment: $('#reserveRoomData > #customerComment').val(),arrivalDate: $('#reserveRoomData > #arrivalDate').val(), departureDate: $('#reserveRoomData > #departureDate').val()}, async: true}).done(function () {$('#reservationModal').modal('toggle');showNotificationModal('Номер успешно забронирован!', 'success');$('#notChecked' + roomId).val(0);});
}