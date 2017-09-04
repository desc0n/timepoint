<?php
/** @var Model_Reservation $reservationModel */
$reservationModel = Model::factory('Reservation');

/** @var Model_Room $roomModel */
$roomModel = Model::factory('Room');

$today = new \DateTime();
$calendarToday = clone $today;
$calendarToday = $calendarToday->modify('- 1 month');
$tomorrow = clone $today;
$tomorrow->modify('+ 1 day');
$firstDate = new DateTime(date('Y-m-d', strtotime(Arr::get($get, 'first_date', $today->format('d.m.Y')))));

$rooms = $roomModel->findAll(null, null);
$selectionRooms = [];

foreach ($rooms as $room) {
    $selectionRooms[$room['id']] = $room['title'];
}

$statusStyles = [1 => 'active', 2 => 'success', 3 => 'canceled'];
$resources = ['site' => 'С', 'office' => 'З', 'booking' => 'Б'];
?>
<div class="row">
    <div class="col-lg-12 form-group">
        <h3>Результирующая таблица</h3>
    </div>
    <div class="col-lg-12 form-group">
        <strong>Значение цвета ячейки</strong>
        <?foreach ($reservationModel->getStatuses() as $id=> $status) {?>
            <?if($id === 2) continue;?>
        <div class="col-lg-12 form-group">
            <div class="col-lg-1 color-legend <?=$statusStyles[$id];?>-color-legend"></div><div class="col-lg-11 text-left"> - <?=$status;?></div>
        </div>
        <?}?>
    </div>
    <div class="col-lg-12 form-group">
        <form id="summaryTableForm">
            <div class="col-lg-5">
                <label for="inputDaysCount">Количество дней</label>
                <?=Form::select('days_count', [30 => 30, 60 => 60, 90 => 90], Arr::get($get, 'days_count', 30), ['id' => 'inputDaysCount', 'class' => 'form-control']);?>
            </div>
            <div class="col-lg-5">
                <label for="firstDate">Начальная дата</label>
                <div class='input-group date'>
                    <input id="firstDate" name="first_date" type="text" value="<?=$firstDate->format('d.m.Y');?>" class="form-control"/>
                    <span class="input-group-addon datepicker-toggler" data-target="firstDate">
                        <i class="fa fa-calendar"></i>
                    </span>
                </div>
            </div>
            <div class="col-lg-2">
                <button class="btn btn-primary">Фильтровать</button>
            </div>
        </form>
    </div>
    <div class="col-lg-12 form-group">
        <table class="table table-bordered summary-table">
            <tr>
                <td rowspan="3">Номера</td>
                <?
                $yearColspan = 0;

                foreach ($summaryTableData as $year => $yearItems) {
                    foreach ($yearItems as $month => $monthItems) {
                        $yearColspan += count($monthItems);
                    }
                }

                foreach ($summaryTableData as $year => $yearItems) {?>
                    <td class="text-center" colspan="<?=$yearColspan;?>"><?=$year;?></td>
                <?}?>
            </tr>
            <tr>
                <?foreach ($summaryTableData as $year => $yearItems) {?>
                    <?foreach ($yearItems as $month => $monthItems) {?>
                        <td class="text-center" colspan="<?=count($monthItems);?>"><?=$month;?></td>
                    <?}?>
                <?}?>
            </tr>
            <tr>
                <?foreach ($summaryTableData as $year => $yearItems) {?>
                    <?foreach ($yearItems as $month => $monthItems) {?>
                        <?foreach ($monthItems as $day => $dayItems) {?>
                            <td class="text-center"><?=$day;?></td>
                        <?}?>
                    <?}?>
                <?}?>
            </tr>
            <?foreach ($rooms as $room) {?>
                <tr>
                    <td><?=$room['title'];?></td>
                    <?foreach ($summaryTableData as $year => $yearItems) {?>
                        <?foreach ($yearItems as $month => $monthItems) {?>
                            <?foreach ($monthItems as $day => $dayItems) {?>
                                <?if($dayItems[$room['id']]) {?>
                                    <?
                                    $popoverContent = '<div><strong>Статус брони: </strong><i>' . $dayItems[$room['id']]['status_name'] . '</i></div>';
                                    $popoverContent .= '<div><strong>Стоимость номера: </strong>' . $dayItems[$room['id']]['price'] . ' руб.</div>';
                                    $popoverContent .= '<div><strong>Клиент: </strong>имя: ' . $dayItems[$room['id']]['customer_name'] . ', тел.: ' . $dayItems[$room['id']]['customer_phone'] . '</div>';
                                    $popoverContent .= '<div><strong>Количество взрослых: </strong>' . $dayItems[$room['id']]['adult'] . '</div>';
                                    $popoverContent .= '<div><strong>Детей: </strong>' . $dayItems[$room['id']]['children_to_2'] . ' (до 2), ' . $dayItems[$room['id']]['children_to_6'] . ' (до 6), ' . $dayItems[$room['id']]['children_to_12'] . ' (до 12)</div>';
                                    $popoverContent .= (int)$dayItems[$room['id']]['status_id'] === 1 ? "<br /><div><button class='btn btn-danger btn-sm' onclick='canceledBooking(" . $dayItems[$room['id']]['id'] . ");'>Отменить <i class='fa fa-remove'></i></button> <button class='btn btn-success btn-sm' onclick='redactBooking(" . $dayItems[$room['id']]['id'] . ");'>Редактировать <i class='fa fa-pencil'></i></button></div>" : '';
                                    ?>
                                    <td class="text-center booking-ceil <?=$statusStyles[$dayItems[$room['id']]['status_id']];?>-booking-ceil booking-ceil-period-<?=$dayItems[$room['id']]['id'];?>" data-toggle="popover" data-html="true" data-content="<?=$popoverContent;?>" data-placement="bottom" data-original-title="Информация о бронировании" onclick="showBookingPeriod(<?=$dayItems[$room['id']]['id'];?>, '<?=$statusStyles[$dayItems[$room['id']]['status_id']];?>')">
                                        <b><?=$resources[$dayItems[$room['id']]['type']];?></b>
                                    </td>
                                <?} else {?>
                                    <td class="text-right alert-success booking-price">
                                        <i class="fa fa-dollar" data-toggle="popover" data-html="true" data-trigger="hover" data-content="<strong><?=$reservationModel->findRoomPriceByIdAndDate($room['id'], new DateTime($year . '-' . $month . '-' . $day), new DateTime($year . '-' . $month . '-' . $day));?> руб.</strong>" data-placement="right" data-original-title="Информация о стоимости номера"></i>
                                    </td>
                                <?}?>
                            <?}?>
                        <?}?>
                    <?}?>
                </tr>
            <?}?>
        </table>
    </div>
</div>
<div class="row">
    <div class="well col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <legend>Добавление бронирования</legend>
        <div class="form-group">
            <label for="inputType">Источник обращения</label>
            <?=Form::select('', $reservationModel->types, null, ['id' => 'inputType', 'class' => 'form-control']);?>
        </div>
        <legend>Выбор номера</legend>
        <div class="form-group">
            <label for="inputRoom">Список номеров</label>
            <?=Form::select('', $selectionRooms, null, ['id' => 'inputRoom', 'class' => 'form-control']);?>
        </div>
        <legend>Период бронирования</legend>
        <div class="form-group">
            <div class="form-group">
                <div class='input-group date'>
                    <input id="daterange" type="text" value="<?=$today->format('d.m.Y');?> - <?=$tomorrow->format('d.m.Y');?>" class="form-control"/>
                    <span class="input-group-addon datepicker-toggler" data-target="daterange"><i class="fa fa-calendar"></i></span>
                </div>
            </div>
        </div>
        <legend>Данные о бронировании</legend>
        <div class="form-group">
            <label for="inputPhone">Телефон клиента *</label>
            <input type="text" class="form-control" id="inputPhone" placeholder="Телефон клиента в формате +79001234567">
        </div>
        <div class="form-group">
            <label for="inputName">Имя клиента *</label>
            <input type="text" class="form-control" id="inputName" placeholder="Имя клиента">
        </div>
        <div class="form-group">
            <label for="inputComment">Комментарий к бронированию</label>
            <textarea id="inputComment" class="form-control" rows="3" placeholder="Комментарий к бронированию"></textarea>
        </div>
        <div class="form-group">
            <label for="inputPrice">Стоимость номера</label>
            <input type="text" class="form-control" id="inputPrice" placeholder="Стоимость номера">
        </div>
        <div class="form-group row">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <label class="label-sm" for="inputAdult">Взрослых</label>
                <input type="text" class="form-control" id="inputAdult" value="0">
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <label class="label-sm" for="inputChildrenTo12">Детей до 12</label>
                <input type="text" class="form-control" id="inputChildrenTo12" value="0">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <label class="label-sm" for="inputChildrenTo2">Детей до 2</label>
                <input type="text" class="form-control" id="inputChildrenTo2" value="0">
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <label class="label-sm" for="inputChildrenTo6">Детей до 6</label>
                <input type="text" class="form-control" id="inputChildrenTo6" value="0">
            </div>
        </div>
        <div class="form-group text-right">
            <button type="button" class="btn btn-primary" onclick="reserveRoom();">Забронировать</button>
        </div>
    </div>
    <?if(Auth::instance()->logged_in('admin')) {?>
    <div class="well col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <legend>Назначение цен</legend>
        <legend>Выбор номера</legend>
        <div class="form-group">
            <label for="inputRoomPrice">Список номеров</label>
            <?=Form::select('', $selectionRooms, null, ['id' => 'inputRoomPrice', 'class' => 'form-control']);?>
        </div>
        <legend>Период цен</legend>
        <div class="form-group">
            <div class="form-group">
                <div class='input-group date'>
                    <input id="daterangePrice" type="text" value="<?=$today->format('d.m.Y');?> - <?=$tomorrow->format('d.m.Y');?>" class="form-control"/>
                    <span class="input-group-addon datepicker-toggler" data-target="daterangePrice"><i class="fa fa-calendar"></i></span>
                </div>
            </div>
        </div>
        <legend>Значение цены</legend>
        <div class="form-group">
            <label for="inputSetPrice">Стоимость номера</label>
            <input type="text" class="form-control" id="inputSetPrice" placeholder="Стоимость номера">
        </div>
        <div class="form-group text-right">
            <button type="button" class="btn btn-primary" onclick="setPrice();">Установить цену</button>
        </div>
    </div>
    <?}?>
</div>
<div id="summary-table"></div>
<script>
    function getMinDate() {
        return new Date(<?=$calendarToday->format('Y');?>, <?=$calendarToday->format('m');?>, <?=$calendarToday->format('d');?>);
    }
    $('.summary-table tr td.booking-ceil').popover();
    $('.summary-table tr td i').popover();
    $('#daterange').daterangepicker({
        autoApply: true,
        opens: "center",
        drops: "up",
        locale: {
            format: 'DD.MM.YYYY'
        },
        minDate: getMinDate()
    });
    $('#daterangePrice').daterangepicker({
        autoApply: true,
        opens: "center",
        drops: "up",
        locale: {
            format: 'DD.MM.YYYY'
        },
        minDate: getMinDate()
    });
    $('.datepicker-toggler').click(function() {
        $("#" + $(this).data('target')).focus();
    });
    $( function() {
        $( "#firstDate").datepicker({
            dateFormat: 'dd.mm.yy'
        });
    });
</script>
<!--<script src="/public/assets/js/summary_table.js"></script>-->