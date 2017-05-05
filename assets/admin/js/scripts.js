$(document).ready(function() {
    $('#setMainItemPage').on('click', function () {
        setMainItemImg($(this).val(), + $(this).prop('checked'));
    });
});

function redactPortfolioItemImg(id, src, main)
{
    $('#setMainItemPage').prop('checked', false);

    $('#redactImgModal .modal-body')
        .html('')
        .append('<img src="/public/img/thumb/' + src + '" data-id="' + id + '">')
    ;

    if (main == 1) {
        $('#setMainItemPage').prop('checked', true);
    }

    $('#redactImgModal').modal('toggle');
}

function removePortfolioItemImg()
{
    var id = $('#redactImgModal .modal-body img').data('id');

    $.ajax({url: '/ajax/remove_portfolio_item_img', type: 'POST', data: {id: id}, async: true})
        .done(function () {
            $('#redactImgModal').modal('toggle');
            $('#portfolioItemImg' + id).remove();
        });
}

function removePortfolioItem(id)
{
    $.ajax({url: '/ajax/remove_portfolio_item', type: 'POST', data: {id: id}, async: true})
        .done(function () {
            $('#portfolioItemRow' + id).remove();
        });
}
function removeContact(id)
{
    $.ajax({url: '/ajax/remove_contact', type: 'POST', data: {id: id}, async: true})
        .done(function () {
            $('#contactRow' + id).remove();
        });
}
function setMainItemImg(itemId, value) {
    var imgId = $('#redactImgModal .modal-body img').data('id');

    $.ajax({url: '/ajax/set_main_item_img', type: 'POST', data: {imgId: imgId, itemId: itemId, value: value}, async: true})
        .done(function () {
            $('#redactImgModal').modal('toggle');

            $('a.thumbnail img').removeClass('main-item-img')

            if (value == 1) {
                $('#portfolioItemImg' + imgId + ' img').addClass('main-item-img');
            }
        });
}