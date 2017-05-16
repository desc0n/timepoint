$(document).ready(function() {
    $('#setMainRoomImg').on('click', function () {
        setMainRoomImg($(this).val(), + $(this).prop('checked'));
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