<?php
$today = new \DateTime();
$tomorrow = clone $today;
$tomorrow->modify('+ 1 day');
?>
<div class="rooms">
    <div class="rooms__wrapper">
        <?foreach ($rooms as $room) {?>
            <?$mainImg = !empty($room['room_main_img']) ? $room['room_main_img']['src'] : null;?>
            <?$mainImg = !empty($room['room_imgs']) ? $room['room_imgs'][0]['src'] : $mainImg;?>
        <div class="rooms__kind" style="background: url('/public/img/original/<?=$mainImg;?>');">
            <div class="rooms__kind-caption">
                <div class="rooms__kind-caption-price"><?=$room['room']['price'];?> руб.</div>
                <button type="button" class="btn btn-primary rooms__kind-caption-action" data-toggle="modal" data-target="#roomModal<?=$room['room']['id'];?>">Посмотреть</button>
            </div>
        </div>
        <?}?>
    </div>
</div>
<?foreach ($rooms as $room) {?>
    <!-- modal -->
    <div id="roomModal<?=$room['room']['id'];?>" class="modal fade modal-booking" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content modal-booking">
                <div class="modal-header">
                    <h5 class="modal-title">Бронирование номера</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="carousel<?=$room['room']['id'];?>" class="carousel slide" data-ride="carousel">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="carousel-inner" role="listbox">
                                    <?$active = null;?>
                                    <?foreach ($room['room_imgs'] as $img) {?>
                                    <?$active = $active === null ? 'active' : '';?>
                                    <div class="carousel-item  <?=$active;?>">
                                        <img class="d-block" src="/public/img/original/<?=$img['src'];?>">
                                    </div>
                                    <?}?>
                                </div>
                                <a class="carousel-control-prev" href="#carousel<?=$room['room']['id'];?>" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#carousel<?=$room['room']['id'];?>" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="modal-booking__desc col-lg-6 col-sm-12 col-xs-12 col-md-6">
                                <legend>Стоимость</legend>
                                <h2 class="rooms__kind-caption-price"><?=$room['room']['price'];?> руб.</h2>
                                <legend>Удобства в номере</legend>
                                <ul>
                                    <?foreach ($room['room_conveniences'] as $roomConvenience) {?>
                                    <li><?=$conveniencesList[$roomConvenience['convenience_id']];?></li>
                                    <?}?>
                                </ul>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <legend>Период бронирования</legend>
                                <div class="form-group">
                                    <label for="modalArrival<?=$room['room']['id'];?>">Заезд </label>
                                    <div class="form-group">
                                        <div class='input-group date'>
                                            <input id="modalArrival<?=$room['room']['id'];?>" type="text" value="<?=($queryArrivalDate === null ? $today->format('d.m.Y') : $queryArrivalDate);?>" class="form-control"/>
                                            <span class="input-group-addon datepicker-toggler" data-target="modalArrival<?=$room['room']['id'];?>">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="modalDeparture<?=$room['room']['id'];?>">Выезд </label>
                                    <div class="form-group">
                                        <div class='input-group date'>
                                            <input id="modalDeparture<?=$room['room']['id'];?>" type="text" value="<?=($queryDepartureDate === null ? $tomorrow->format('d.m.Y') : $queryDepartureDate);?>" class="form-control"/>
                                            <span class="input-group-addon datepicker-toggler" data-target="modalDeparture<?=$room['room']['id'];?>">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group text-right">
                                    <button type="button" class="btn btn-info">Проверить</button>
                                </div>
                                <legend>Запрос на бронирование</legend>
                                <div class="form-group">
                                    <label for="inputPhone<?=$room['room']['id'];?>">Телефон *</label>
                                    <input type="text" class="form-control" id="inputPhone<?=$room['room']['id'];?>" placeholder="+79001234567">
                                </div>
                                <div class="form-group">
                                    <label for="inputName<?=$room['room']['id'];?>">Имя *</label>
                                    <input type="text" class="form-control" id="inputName<?=$room['room']['id'];?>" placeholder="Имя">
                                </div>
                                <div class="form-group">
                                    <label for="inputComment<?=$room['room']['id'];?>">Комментарий</label>
                                    <textarea id="inputComment<?=$room['room']['id'];?>" class="form-control" rows="3" placeholder="Комментарий"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary reserve-room-btn" data-id="<?=$room['room']['id'];?>">Забронировать</button>
                    <input type="hidden" id="notChecked<?=$room['room']['id'];?>" value="<?=(int)($queryArrivalDate !== null && $queryDepartureDate !== null);?>">
                </div>
            </div>
        </div>
    </div>
    <!-- modal -->
    <script>
        $( function() {
            $( "#modalArrival<?=$room['room']['id'];?>" ).datepicker({
                dateFormat: 'dd.mm.yy'
            });
            $( "#modalDeparture<?=$room['room']['id'];?>" ).datepicker({
                dateFormat: 'dd.mm.yy'
            });
        } );
    </script>
<?}?>
