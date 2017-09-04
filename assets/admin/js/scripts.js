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
function reserveRoom() {var dates = getDates('daterange');$.ajax({url: '/ajax/check_room_reserve', type: 'POST', data: {roomId: $('#inputRoom').val(), arrivalDate: dates[0], departureDate: dates[1]}, async: true}).done(function (response) {if(response === 'free') {$.ajax({url: '/ajax/reserve_room', type: 'POST', data: {roomId: $('#inputRoom').val(), phone: $('#inputPhone').val(), name: $('#inputName').val(), comment: $('#inputComment').val(),arrivalDate: dates[0], departureDate: dates[1], adult: $('#inputAdult').val(), childrenTo2: $('#inputChildrenTo2').val(), childrenTo6: $('#inputChildrenTo6').val(), childrenTo12: $('#inputChildrenTo12').val(), type: $('#inputType').val(), price: $('#inputPrice').val()}, async: true}).done(function () {alert('Номер успешно забронирован!');location.reload();});}else{alert('Бронирование номера не доступно!');}});}
function getDates(id){var dateRange = $('#' + id).val();return dates = dateRange.split(' - ');}
function canceledBooking(reservationId) {$.ajax({url: '/ajax/canceled_booking', type: 'POST', data: {reservationId: reservationId}, async: true}).done(function () {location.reload();});}
function changePassword(userId) {$.ajax({url: '/ajax/change_password', type: 'POST', data: {userId: userId, password: $('#userPassword' + userId).val()}, async: true}).done(function () {alert('Пароль успешно изменен.');});}
function setPrice() {var price = $('#inputSetPrice').val(); if(price === ''){alert('Не указана цена!');return;}var dates = getDates('daterangePrice');$.ajax({url: '/ajax/set_price', type: 'POST', data: {roomId: $('#inputRoom').val(), firstDate: dates[0], lastDate: dates[1], price: price}, async: true}).done(function () {alert('Цены установлены.');location.reload();});}
function showBookingPeriod(id, status) {var colors = {'success' : '#82c44f' , 'canceled' : '#b80f35', 'active' : '#ecd20a'};var $elems = $('.booking-ceil-period-' + id);var background = rgbToHex($elems.css('background-color'));if(background === '#00d7ec') {$elems.css('background-color', colors[status]);} else {$elems.css('background-color', '#00d7ec');}}
function rgbToHex(color) {color = ""+ color;if (!color || color.indexOf("rgb") < 0) {return;}if (color.charAt(0) == "#") {return color;}var nums = /(.*?)rgb\((\d+),\s*(\d+),\s*(\d+)\)/i.exec(color),r = parseInt(nums[2], 10).toString(16),g = parseInt(nums[3], 10).toString(16),b = parseInt(nums[4], 10).toString(16);return "#"+ ((r.length == 1 ? "0"+ r : r) +(g.length == 1 ? "0"+ g : g) +(b.length == 1 ? "0"+ b : b));}