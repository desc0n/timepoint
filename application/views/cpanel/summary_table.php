<?php
/** @var Model_Booking $bookingModel */
$bookingModel = Model::factory('Booking');

/** @var Model_Room $roomModel */
$roomModel = Model::factory('Room');

$adminRole = Auth::instance()->logged_in('admin');
$today = new \DateTime();
$calendarToday = clone $today;
$calendarToday = $calendarToday->modify('- 1 month');
$tomorrow = clone $today;
$tomorrow->modify('+ 1 day');
$firstDate = new DateTime(date('Y-m-d', strtotime(Arr::get($get, 'first_date', $today->format('d.m.Y')))));
$lastDate = new DateTime(date('Y-m-d', strtotime(Arr::get($get, 'last_date', $today->format('d.m.Y')))));

$rooms = $roomModel->findAll(null, null);
$selectionRooms = [];

foreach ($rooms as $room) {
    $selectionRooms[$room['id']] = $room['title'];
}

$statusStyles = [1 => 'active', 2 => 'success', 3 => 'canceled', 4 => 'active', 5 => 'active', 6 => 'active', 7 => 'active'];
$resources = ['site' => 'С', 'office' => 'Т', 'booking' => 'Б'];
$weekDays = [0 => 'вс', 1 => 'пн', 2 => 'вт', 3 => 'ср', 4 => 'чт', 5 => 'пт', 6 => 'сб'];
$managerPricesDays = [];
$pricesDays = [];
$managerPricesRooms = [];
$pricesRooms = [];
?>
<div class="row">
    <div class="col-lg-12 form-group">
        <h3>Результирующая таблица</h3>
    </div>
    <div class="col-lg-12 form-group">
        <strong>Значение цвета ячейки</strong>
        <?foreach ($bookingModel->getStatuses(1) as $id=> $status) {?>
        <div class="col-lg-12 form-group">
            <div class="col-lg-1 color-legend <?=$statusStyles[$id];?>-color-legend"></div><div class="col-lg-11 text-left"> - <?=$status;?></div>
        </div>
        <?}?>
    </div>
    <div class="col-lg-12 form-group">
        <form id="summaryTableForm">
            <div class="col-lg-5">
                <label for="firstDate">Начальная дата</label>
                <div class='input-group date'>
                    <input id="firstDate" name="first_date" type="text" value="<?=$firstDate->format('d.m.Y');?>" class="form-control"/>
                    <span class="input-group-addon datepicker-toggler" data-target="firstDate">
                        <i class="fa fa-calendar"></i>
                    </span>
                </div>
            </div>
            <div class="col-lg-5">
                <label for="lastDate">Конечная дата</label>
                <div class='input-group date'>
                    <input id="lastDate" name="last_date" type="text" value="<?=$lastDate->format('d.m.Y');?>" class="form-control"/>
                    <span class="input-group-addon datepicker-toggler" data-target="lastDate">
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
                <td rowspan="3" class="room-title">
                    Номера
                    <i class="fa fa-chevron-circle-down pull-right show-details" data-room="all"></i>
                </td>
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
                <?if($adminRole){?>
                <td rowspan="3" class="booking-hidden"></td>
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
                    <td rowspan="4" class="room-title">
                        <?=$room['title'];?>
                        <i class="fa fa-chevron-circle-down pull-right show-details" data-room="<?=$room['id'];?>"></i>
                    </td>
                    <?foreach ($summaryTableData as $year => $yearItems) {?>
                        <?foreach ($yearItems as $month => $monthItems) {?>
                            <?foreach ($monthItems as $day => $dayItems) {?>
                                <td class="text-center alert-info booking-hidden booking-hidden-<?=$room['id'];?>"><?=$day;?>.<?=$month;?> (<strong><?=$weekDays[date('w', strtotime($year . '-' . $month . '-' . $day))];?></strong>)</td>
                            <?}?>
                        <?}?>
                    <?}?>
                    <td rowspan="2" class="booking-hidden booking-hidden-<?=$room['id'];?>"></td>
                </tr>
                <tr>
                    <?foreach ($summaryTableData as $year => $yearItems) {?>
                        <?foreach ($yearItems as $month => $monthItems) {?>
                            <?foreach ($monthItems as $day => $dayItems) {?>
                                <?if(!empty($dayItems[$room['id']])) {?>
                                    <?
                                    $canceledButton = !$dayItems[$room['id']]['payed'] ? "<button class='btn btn-danger btn-sm' onclick='canceledBooking(" . $dayItems[$room['id']]['id'] . ");'>Отменить <i class='fa fa-remove'></i></button>" : '';
                                    $popoverTitle = 'Информация о бронировании c ' . date('d.m', strtotime($dayItems[$room['id']]['arrival_at'])) . ' по ' . date('d.m', strtotime($dayItems[$room['id']]['departure_at']));
                                    $popoverContent = "<div class='booking-data-popover'>";
                                    $popoverContent .= '<div><strong>Статус брони: </strong><i>' . $dayItems[$room['id']]['status_name'] . '</i></div>';
                                    $popoverContent .= '<div><strong>Статус оплаченности: </strong><i>' . ($dayItems[$room['id']]['payed'] ? 'оплачено' : 'не оплачено') . '</i></div>';
                                    $popoverContent .= '<div><strong>Стоимость номера: </strong>' . $dayItems[$room['id']]['price'] . ' руб.</div>';
                                    $popoverContent .= '<div><strong>Стоимость бронирования: </strong>' . $dayItems[$room['id']]['booking_price'] . ' руб.</div>';
                                    $popoverContent .= '<div><strong>Клиент: </strong>имя: ' . $dayItems[$room['id']]['customer_name'] . ', тел.: ' . $dayItems[$room['id']]['customer_phone'] . '</div>';
                                    $popoverContent .= '<div><strong>Количество взрослых: </strong>' . $dayItems[$room['id']]['adult'] . '</div>';
                                    $popoverContent .= '<div><strong>Детей: </strong>' . $dayItems[$room['id']]['children_to_2'] . ' (до 2), ' . $dayItems[$room['id']]['children_to_6'] . ' (до 6), ' . $dayItems[$room['id']]['children_to_12'] . ' (до 12)</div>';
                                    $popoverContent .= '<div><strong>Комментарий: </strong>' . str_replace('"', '', $dayItems[$room['id']]['customer_comment']) . '</div>';
                                    $popoverContent .= (int)$dayItems[$room['id']]['status_id'] === 1 ? "<br /><div class='text-right'>" . $canceledButton . " <button class='btn btn-success btn-sm' onclick='showRedactBookingForm(" . $dayItems[$room['id']]['id'] . ");'>Редактировать <i class='fa fa-pencil'></i></button></div>" : '';
                                    $popoverContent .= '</div>';
                                    ?>
                                    <td class="text-center booking-ceil <?=$statusStyles[$dayItems[$room['id']]['status_id']];?>-booking-ceil booking-ceil-period-<?=$dayItems[$room['id']]['id'];?>" data-toggle="popover" data-html="true" data-content="<?=$popoverContent;?>" data-placement="bottom" data-original-title="<?=$popoverTitle;?>" onclick="showBookingPeriod(<?=$dayItems[$room['id']]['id'];?>, '<?=$statusStyles[$dayItems[$room['id']]['status_id']];?>')">
                                        <b><?=$resources[$dayItems[$room['id']]['type']];?></b>
                                    </td>
                                <?} else {?>
                                    <td class="text-right alert-success booking-price">
                                        <i class="fa fa-dollar" data-toggle="popover" data-html="true" data-trigger="hover" data-content="<strong><?=$bookingModel->findRoomPriceByIdAndDate($room['id'], new DateTime($year . '-' . $month . '-' . $day), new DateTime($year . '-' . $month . '-' . $day));?> руб.</strong>" data-placement="right" data-original-title="Информация о стоимости номера"></i>
                                    </td>
                                <?}?>
                            <?}?>
                        <?}?>
                    <?}?>
                </tr>
                <tr>
                    <?foreach ($summaryTableData as $year => $yearItems) {?>
                        <?foreach ($yearItems as $month => $monthItems) {?>
                            <?foreach ($monthItems as $day => $dayItems) {?>
                                <?$managerPrice = !empty($dayItems[$room['id']]) ? ((int)$dayItems[$room['id']]['manager_price'] ?: $dayItems[$room['id']]['price']) : $bookingModel->findRoomManagerPriceByIdAndDate($room['id'], new DateTime($year . '-' . $month . '-' . $day), new DateTime($year . '-' . $month . '-' . $day));?>
                                <?
                                $popoverTitle = 'Изменение стоимости номера';
                                $popoverContent = "<div class='booking-data-popover'>";
                                $popoverContent .= "<div><div class='input-group'>";
                                $popoverContent .= "<input id='newRoomManagerPrice" . $room['id'] . "' type='text' value='" . $managerPrice . "' class='form-control'>";
                                $popoverContent .= "<span class='input-group-btn'><button class='btn btn-success' onclick='changeRoomManagerPrice(" . $room['id'] . ");'><i class='glyphicon glyphicon-ok'></i></button></span>";
                                $popoverContent .= '</div></div>';
                                $popoverContent .= "<input type='hidden' id='newRoomManagerPriceDate" . $room['id'] . "' value='" . $year . "-" . $month . "-" . $day . "'>";
                                $popoverContent .= '</div>';
                                ?>
                                <td class="text-right manager-prices booking-hidden booking-hidden-<?=$room['id'];?>">
                                    <div id="bookingManagerPriceChange<?=$year;?>-<?=$month;?>-<?=$day;?>" class="booking-price-change" data-trigger="click" data-toggle="popover" data-html="true" data-content="<?=$popoverContent;?>" data-placement="bottom" data-original-title="<?=$popoverTitle;?>"><?=$managerPrice;?></div>
                                </td>
                                <?$managerPricesDays[$year . '-' . $month . '-' . $day] = isset($managerPricesDays[$year . '-' . $month . '-' . $day]) ? $managerPricesDays[$year . '-' . $month . '-' . $day] + $managerPrice : $managerPrice;?>
                                <?$managerPricesRooms[$room['id']] = isset($managerPricesRooms[$room['id']]) ? $managerPricesRooms[$room['id']] + $managerPrice : $managerPrice;?>
                            <?}?>
                        <?}?>
                    <?}?>
                    <?if($adminRole){?>
                    <td class="booking-hidden booking-hidden-<?=$room['id'];?>"><?=$managerPricesRooms[$room['id']];?></td>
                    <?}?>
                </tr>
                <tr>
                    <?foreach ($summaryTableData as $year => $yearItems) {?>
                        <?foreach ($yearItems as $month => $monthItems) {?>
                            <?foreach ($monthItems as $day => $dayItems) {?>
                                <?$price = !empty($dayItems[$room['id']]) ? $dayItems[$room['id']]['price'] : $bookingModel->findRoomPriceByIdAndDate($room['id'], new DateTime($year . '-' . $month . '-' . $day), new DateTime($year . '-' . $month . '-' . $day));?>
                                <?if($adminRole) {?>
                                <?
                                $popoverTitle = 'Изменение стоимости номера';
                                $popoverContent = "<div class='booking-data-popover'>";
                                $popoverContent .= "<div><div class='input-group'>";
                                $popoverContent .= "<input id='newRoomPrice" . $room['id'] . "' type='text' value='" . $price . "' class='form-control'>";
                                $popoverContent .= "<span class='input-group-btn'><button class='btn btn-success' onclick='changeRoomPrice(" . $room['id'] . ");'><i class='glyphicon glyphicon-ok'></i></button></span>";
                                $popoverContent .= '</div></div>';
                                $popoverContent .= "<input type='hidden' id='newRoomPriceDate" . $room['id'] . "' value='" . $year . "-" . $month . "-" . $day . "'>";
                                $popoverContent .= '</div>';
                                ?>
                                <td class="text-right alert-danger booking-hidden booking-hidden-<?=$room['id'];?>">
                                    <div id="bookingPriceChange<?=$year;?>-<?=$month;?>-<?=$day;?>" class="booking-price-change" data-trigger="click" data-toggle="popover" data-html="true" data-content="<?=$popoverContent;?>" data-placement="bottom" data-original-title="<?=$popoverTitle;?>"><?=$price;?></div>
                                </td>
                                <?} else {?>
                                    <td class="text-right alert-danger booking-hidden booking-hidden-<?=$room['id'];?>">
                                        <?=$price;?>
                                    </td>
                                <?}?>
                                <?$pricesDays[$year . '-' . $month . '-' . $day] = isset($pricesDays[$year . '-' . $month . '-' . $day]) ? $pricesDays[$year . '-' . $month . '-' . $day] + $price : $price;?>
                                <?$pricesRooms[$room['id']] = isset($pricesRooms[$room['id']]) ? $pricesRooms[$room['id']] + $price : $price;?>
                            <?}?>
                        <?}?>
                    <?}?>
                    <?if($adminRole){?>
                    <td class="booking-hidden booking-hidden-<?=$room['id'];?>"><?=$pricesRooms[$room['id']];?></td>
                    <?}?>
                </tr>
            <?}?>
            <?$amount = 0;?>
            <?$managerAmount = 0;?>
            <tr>
                <td class="booking-hidden" <?=($adminRole ? 'rowspan="2"' : null);?>>Итого</td>
                <?foreach ($summaryTableData as $year => $yearItems) {?>
                    <?foreach ($yearItems as $month => $monthItems) {?>
                        <?foreach ($monthItems as $day => $dayItems) {?>
                            <td class="booking-hidden manager-prices">
                                <div><?=$managerPricesDays[$year . '-' . $month . '-' . $day];?></div>
                                <?$managerAmount += $managerPricesDays[$year . '-' . $month . '-' . $day];?>
                            </td>
                        <?}?>
                    <?}?>
                <?}?>
                <td class="booking-hidden"><?=$managerAmount;?></td>
                <?if($adminRole){?>
            </tr>
            <tr>
                <?foreach ($summaryTableData as $year => $yearItems) {?>
                    <?foreach ($yearItems as $month => $monthItems) {?>
                        <?foreach ($monthItems as $day => $dayItems) {?>
                            <td class="booking-hidden">
                                <?=$pricesDays[$year . '-' . $month . '-' . $day];?>
                                <?$amount += $pricesDays[$year . '-' . $month . '-' . $day];?>
                            </td>
                        <?}?>
                    <?}?>
                <?}?>
                <td class="booking-hidden alert-danger"><?=$amount;?></td>
                <?}?>
            </tr>
        </table>
    </div>
</div>
<div class="row">
    <div class="well col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <legend>Добавление бронирования</legend>
        <div class="form-group">
            <label for="inputType">Источник обращения</label>
            <?=Form::select('', $bookingModel->types, null, ['id' => 'inputType', 'class' => 'form-control']);?>
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
            <label for="inputPhone">Телефон клиента</label>
            <input type="text" class="form-control" id="inputPhone" placeholder="Телефон клиента в формате +79001234567">
        </div>
        <div class="form-group">
            <label for="inputName">Имя клиента</label>
            <input type="text" class="form-control" id="inputName" placeholder="Имя клиента">
        </div>
        <div class="form-group">
            <label for="inputEmail">Email</label>
            <input type="text" class="form-control" id="inputEmail" placeholder="Email">
        </div>
        <div class="form-group">
            <label for="inputComment">Комментарий к бронированию</label>
            <textarea id="inputComment" class="form-control" rows="3" placeholder="Комментарий к бронированию"></textarea>
        </div>
        <div class="form-group">
            <label for="inputPrice">Стоимость бронирования</label>
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
    <?if($adminRole) {?>
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
<div class="modal fade" id="redactBooking" tabindex="-1" role="dialog" aria-labelledby="redactBookingLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="redactBookingLabel">Редактирование бронирования</h4>
            </div>
            <div class="modal-body" id="redactBookingBody">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>
<div id="summary-table"></div>
<script>
    function getMinDate() {
        return new Date(<?=$calendarToday->format('Y');?>, <?=$calendarToday->format('m');?>, <?=$calendarToday->format('d');?>);
    }
    $('.summary-table tr td.booking-ceil').popover();
    $('.summary-table tr td i').popover();
    $('.summary-table tr td .booking-price-change').popover();
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
        $( "#lastDate").datepicker({
            dateFormat: 'dd.mm.yy'
        });
    });
</script>
<!--<script src="/public/assets/js/summary_table.js"></script>-->