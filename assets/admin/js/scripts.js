$(document).ready(function() {
    $('#setMainRoomImg').on('click', function () {
        setMainRoomImg($(this).val(), + $(this).prop('checked'));
    });
    $('.add-convenience-btn').on('click', function () {
        addRoomConvenience();
    });
    $('.remove-convenience-btn').on('click', function () {
        removeRoomConvenience($(this).data('id'));
    });
    $('.summary-table tr td.room-title i').on('click', function () {
        showBookingDetails($(this).data('room'));
    });
});

function redactRoomImg(id, src, main)
{
    $('#setMainRoomPage').prop('checked', false);

    $('#redactImgModal .modal-body')
        .html('')
        .append('<img src="/public/img/thumb/' + src + '" data-id="' + id + '">')
    ;

    if (main == 1) {
        $('#setMainRoomPage').prop('checked', true);
    }

    $('#redactImgModal').modal('toggle');
}

function removeRoomImg()
{
    var id = $('#redactImgModal .modal-body img').data('id');

    $.ajax({url: '/ajax/remove_room_img', type: 'POST', data: {id: id}, async: true})
        .done(function () {
            $('#redactImgModal').modal('toggle');
            $('#roomImg' + id).remove();
        });
}

function removeRoom(id)
{
    $.ajax({url: '/ajax/remove_room', type: 'POST', data: {id: id}, async: true})
        .done(function () {
            $('#roomRow' + id).remove();
        });
}
function removeContact(id)
{
    $.ajax({url: '/ajax/remove_contact', type: 'POST', data: {id: id}, async: true})
        .done(function () {
            $('#contactRow' + id).remove();
        });
}
function removeConvenience(id)
{
    $.ajax({url: '/ajax/remove_convenience', type: 'POST', data: {id: id}, async: true})
        .done(function () {
            $('#convenienceRow' + id).remove();
        });
}
function setMainRoomImg(roomId, value) {
    var imgId = $('#redactImgModal .modal-body img').data('id');

    $.ajax({url: '/ajax/set_main_room_img', type: 'POST', data: {imgId: imgId, roomId: roomId, value: value}, async: true})
        .done(function () {
            $('#redactImgModal').modal('toggle');

            $('a.thumbnail img').removeClass('main-room-img');

            if (value == 1) {
                $('#roomImg' + imgId + ' img').addClass('main-room-img');
            }
        });
}
function addRoomConvenience() {$.ajax({url: '/ajax/add_room_convenience', type: 'POST', data: {roomId: $('#roomId').val(), value: $('#conveniencesList>option:selected').val()}, async: true}).done(function () {location.reload();});}
function removeRoomConvenience(convenienceId) {$.ajax({url: '/ajax/remove_room_convenience', type: 'POST', data: {roomId: $('#roomId').val(), convenienceId: convenienceId}, async: true}).done(function () {location.reload();});}
function removeNews(newsId) {$.ajax({url: '/ajax/remove_news', type: 'POST', data: {newsId: newsId}, async: true}).done(function () {location.reload();});}
function reserveRoom() {var dates = getDates('daterange');$.ajax({url: '/ajax/check_room_reserve', type: 'POST', data: {roomId: $('#inputRoom').val(), arrivalDate: dates[0], departureDate: dates[1]}, async: true}).done(function (response) {if(response === 'free') {$.ajax({url: '/ajax/reserve_room', type: 'POST', data: {roomId: $('#inputRoom').val(), phone: $('#inputPhone').val(), name: $('#inputName').val(), email: $('#inputEmail').val(), comment: $('#inputComment').val(),arrivalDate: dates[0], departureDate: dates[1], adult: $('#inputAdult').val(), childrenTo2: $('#inputChildrenTo2').val(), childrenTo6: $('#inputChildrenTo6').val(), childrenTo12: $('#inputChildrenTo12').val(), type: $('#inputType').val(), price: $('#inputPrice').val()}, async: true}).done(function () {alert('Номер успешно забронирован!');location.reload();});}else{alert('Бронирование номера не доступно!');}});}
function getDates(id){var dateRange = $('#' + id).val();return dates = dateRange.split(' - ');}
function changePassword(userId) {$.ajax({url: '/ajax/change_password', type: 'POST', data: {userId: userId, password: $('#userPassword' + userId).val()}, async: true}).done(function () {alert('Пароль успешно изменен.');});}
function setPrice() {var price = $('#inputSetPrice').val(); if(price === ''){alert('Не указана цена!');return;}var dates = getDates('daterangePrice');$.ajax({url: '/ajax/set_price', type: 'POST', data: {roomId: $('#inputRoomPrice').val(), firstDate: dates[0], lastDate: dates[1], price: price}, async: true}).done(function () {alert('Цены установлены.');location.reload();});}
function showBookingPeriod(id, status) {var colors = {'success' : '#82c44f' , 'canceled' : '#b80f35', 'active' : '#ecd20a'};var $elems = $('.booking-ceil-period-' + id);var background = rgbToHex($elems.css('background-color'));if(background === '#00d7ec') {$elems.css('background-color', colors[status]);} else {$elems.css('background-color', '#00d7ec');}}
function rgbToHex(color) {color = ""+ color;if (!color || color.indexOf("rgb") < 0) {return;}if (color.charAt(0) == "#") {return color;}var nums = /(.*?)rgb\((\d+),\s*(\d+),\s*(\d+)\)/i.exec(color),r = parseInt(nums[2], 10).toString(16),g = parseInt(nums[3], 10).toString(16),b = parseInt(nums[4], 10).toString(16);return "#"+ ((r.length == 1 ? "0"+ r : r) +(g.length == 1 ? "0"+ g : g) +(b.length == 1 ? "0"+ b : b));}
function showBookingDetails(roomId) {var $cells = (roomId == 'all' ? $('.booking-hidden') : $('.booking-hidden-' + roomId));var checkDisplayStyle = $cells.css('display');if(checkDisplayStyle === 'none'){$cells.css('display', 'table-cell');} else {$cells.css('display', 'none');}}
function canceledBooking(reservationId) {$.ajax({url: '/ajax/canceled_booking', type: 'POST', data: {reservationId: reservationId}, async: true}).done(function () {location.reload();});}
function showRedactBookingForm(bookingId) {$.ajax({url: '/ajax/show_redact_booking_rorm', type: 'POST', data: {bookingId: bookingId}}).done(function (form) {$('#redactBookingBody').html(form);$('#redactBooking').modal('toggle');})}
function changeBooking(bookingId, roomId) {var dates = getDates('inputChangePeriod');$.ajax({url: '/ajax/check_room_reserve', type: 'POST', data: {bookingId: bookingId, roomId: roomId, arrivalDate: dates[0], departureDate: dates[1]}, async: true}).done(function (response) {if(response === 'free') {$.ajax({url: '/ajax/change_booking', type: 'POST', data: {bookingId: bookingId, roomId: roomId, phone: $('#inputChangePhone').val(), name: $('#inputChangeName').val(), comment: $('#inputChangeComment').val(),arrivalDate: dates[0], departureDate: dates[1], adult: $('#inputChangeAdult').val(), childrenTo2: $('#inputChangeChildrenTo2').val(), childrenTo6: $('#inputChangeChildrenTo6').val(), childrenTo12: $('#inputChangeChildrenTo12').val(), price: $('#inputChangePrice').val(), type: $('#inputChangeType').val()}, async: true}).done(function () {alert('Изменение данных о бронировании успешно завершено!');location.reload();});}else{alert('Указанный период бронирования недоступен!');}});}
function changeRoomPrice(roomId) {var date = $('#newRoomPriceDate' + roomId).val();$.ajax({url: '/ajax/set_price', type: 'POST', data: {roomId: roomId, firstDate: date, lastDate: date, price: $('#newRoomPrice' + roomId).val()}, async: true}).done(function () {alert('Изменение стоимости бронировании успешно завершено!');location.reload();});}