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