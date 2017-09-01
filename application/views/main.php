<?php
/** @var Model_Reservation $reservationModel */
$reservationModel = Model::factory('Reservation');

$today = new \DateTime();
$tomorrow = clone $today;
$tomorrow->modify('+ 1 day');
$arrivalDate = $queryArrivalDate === null ? $today : new DateTime(date('Y-m-d', strtotime($queryArrivalDate)));
$departureDate = $queryDepartureDate === null ? $tomorrow : new DateTime(date('Y-m-d', strtotime($queryDepartureDate)));
$calendarArrivalDate = clone $arrivalDate;
$calendarArrivalDate->modify('- 1 month');
$calendarDepartureDate = clone $departureDate;
$calendarDepartureDate->modify('- 1 month');
$nightCount = (round(($departureDate->getTimestamp() - $arrivalDate->getTimestamp()) / 86400));
?>
<div class="rooms">
    <div class="rooms__wrapper">
        <?foreach ($rooms as $room) {?>
            <?$price = $reservationModel->findRoomPriceByIdAndDate($room['room']['id'], $arrivalDate);?>
            <?$mainImg = !empty($room['room_imgs']) ? $room['room_imgs'][0]['src'] : null;?>
            <?$mainImg = !empty($room['room_main_img']) ? $room['room_main_img']['src'] : $mainImg;?>
        <div class="rooms__kind" style="background: url('/public/img/original/<?=$mainImg;?>');">
            <div class="rooms__kind-caption">
                <div class="rooms__kind-caption-price"><?=($course && $currency === 'USD' ? round($price / $course) . ' USD': $price . ' ' . $templateWords['currency']['rub'])?></div>
                <button type="button" class="btn btn-primary rooms__kind-caption-action" data-toggle="modal" data-target="#roomModal<?=$room['room']['id'];?>"><?=$templateWords['main']['detail'];?></button>
            </div>
        </div>
        <?}?>
    </div>
</div>
<?foreach ($rooms as $room) {?>
    <?$price = $reservationModel->findRoomPriceByIdAndDate($room['room']['id'], $arrivalDate);?>
    <!-- modal -->
    <div id="roomModal<?=$room['room']['id'];?>" class="modal fade modal-booking" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content modal-booking">
                <div class="modal-header">
                    <h5 class="modal-title"><?=$templateWords['main']['booking_room'];?></h5>
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
                                <legend><?=$templateWords['main']['cost'];?></legend>
                                <h2 class="rooms__kind-caption-price"><?=($course && $currency === 'USD' ? round($price / $course) . ' USD': $price . ' ' . $templateWords['currency']['rub'])?></h2>
                                <legend><?=$templateWords['main']['rooms_comfort'];?></legend>
                                <ul>
                                    <?foreach ($room['room_conveniences'] as $roomConvenience) {?>
                                    <li><?=$conveniencesList[$roomConvenience['convenience_id']];?></li>
                                    <?}?>
                                </ul>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <legend><?=$templateWords['main']['booking_request'];?></legend>
                                <div class="form-group">
                                    <label for="inputPhone<?=$room['room']['id'];?>"><?=$templateWords['main']['phone'];?> *</label>
                                    <input type="text" class="form-control" id="inputPhone<?=$room['room']['id'];?>" placeholder="<?=$templateWords['main']['specify_phone'];?> +79001234567">
                                </div>
                                <div class="form-group">
                                    <label for="inputName<?=$room['room']['id'];?>"><?=$templateWords['main']['name'];?> *</label>
                                    <input type="text" class="form-control" id="inputName<?=$room['room']['id'];?>" placeholder="<?=$templateWords['main']['name'];?>">
                                </div>
                                <div class="form-group">
                                    <label for="inputComment<?=$room['room']['id'];?>"><?=$templateWords['main']['comment'];?></label>
                                    <textarea id="inputComment<?=$room['room']['id'];?>" class="form-control" rows="3" placeholder="<?=$templateWords['main']['comment'];?>"></textarea>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label class="label-sm" for="inputAdult<?=$room['room']['id'];?>"><?=$templateWords['main']['adult'];?></label>
                                        <input type="text" class="form-control" id="inputAdult<?=$room['room']['id'];?>" value="0">
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label class="label-sm" for="inputChildrenTo12<?=$room['room']['id'];?>"><?=$templateWords['main']['children_12'];?></label>
                                        <input type="text" class="form-control" id="inputChildrenTo12<?=$room['room']['id'];?>" value="0">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label class="label-sm" for="inputChildrenTo2<?=$room['room']['id'];?>"><?=$templateWords['main']['children_2'];?></label>
                                        <input type="text" class="form-control" id="inputChildrenTo2<?=$room['room']['id'];?>" value="0">
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label class="label-sm" for="inputChildrenTo6<?=$room['room']['id'];?>"><?=$templateWords['main']['children_6'];?></label>
                                        <input type="text" class="form-control" id="inputChildrenTo6<?=$room['room']['id'];?>" value="0">
                                    </div>
                                </div>
                                <div class="form-group text-right">
                                    <button type="button" class="btn btn-primary reserve-room-btn" data-id="<?=$room['room']['id'];?>"><?=$templateWords['main']['book_a_room'];?></button>
                                    <input type="hidden" id="notChecked<?=$room['room']['id'];?>" value="<?=(int)($queryArrivalDate !== null && $queryDepartureDate !== null);?>">
                                </div>
                                <legend><?=$templateWords['main']['booking_period'];?></legend>
                                <div class="form-group">
                                    <div class="form-group">
                                        <div class='input-group date'>
                                            <input id="daterange<?=$room['room']['id'];?>" type="text" value="<?=$arrivalDate->format('d.m.Y');?> - <?=$departureDate->format('d.m.Y');?>" class="form-control"/>
                                            <span class="input-group-addon datepicker-toggler" data-target="daterange<?=$room['room']['id'];?>">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group text-right">
                                    <button type="button" class="btn btn-info check-room-reserve" data-id="<?=$room['room']['id'];?>"><?=$templateWords['main']['check'];?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- modal -->
    <script>
        moment.locale('ru');
        $('#daterange<?=$room['room']['id'];?>').daterangepicker({
            autoApply: true,
            opens: "center",
            drops: "up",
            locale: {
                format: 'DD.MM.YYYY'
            },
            minDate: getMinDate(),
            startDate: getStartDate(),
            endDate: getEndDate()
        })
            .on('apply.daterangepicker', function(ev, picker) {
                var dateDiff = picker.endDate - picker.startDate;
                writeNightCount((Math.round(dateDiff / 86400000) - 1), <?=$room['room']['id'];?>);
            });
        $('.datepicker-toggler').click(function() {
            $("#" + $(this).data('target')).focus();
        });
    </script>
<?}?>
