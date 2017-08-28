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
?>
<div class="row">
    <div class="col-lg-12 form-group">
        <h3>Результирующая таблица</h3>
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
                                    $popoverContent = '<div><strong>Стоимость номера: </strong>' . $dayItems[$room['id']]['price'] . ' руб.</div>';
                                    $popoverContent .= '<div><strong>Клиент: </strong>имя: ' . $dayItems[$room['id']]['customer_name'] . ', тел.: ' . $dayItems[$room['id']]['customer_phone'] . '</div>';
                                    $popoverContent .= '<div><strong>Количество взрослых: </strong>' . $dayItems[$room['id']]['adult'] . '</div>';
                                    $popoverContent .= '<div><strong>Детей: </strong>' . $dayItems[$room['id']]['children_to_2'] . ' (до 2), ' . $dayItems[$room['id']]['children_to_6'] . ' (до 6), ' . $dayItems[$room['id']]['children_to_12'] . ' (до 12)</div>';
                                    ?>
                                    <td class="text-center alert-danger" data-toggle="popover" data-html="true" data-content="<?=$popoverContent;?>" data-placement="right" data-original-title="Информация о бронировании"></td>
                                <?} else {?>
                                    <td class="text-center alert-success"></td>
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
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
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
</div>
<div id="summary-table"></div>
<script>
    function getMinDate() {
        return new Date(<?=$calendarToday->format('Y');?>, <?=$calendarToday->format('m');?>, <?=$calendarToday->format('d');?>);
    }
    $('.summary-table tr td').popover();
    $('#daterange').daterangepicker({
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