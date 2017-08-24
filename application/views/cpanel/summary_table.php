<div class="row">
    <div class="col-lg-12 form-group">
        <h3>Результирующая таблица</h3>
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
                <td class="text-center alert-danger" data-toggle="popover" data-trigger="hover" data-html="true" data-content="Стоимость номера на момент бронирования: <strong><?=$dayItems[$room['id']]['price'];?> руб.</strong>" data-placement="right" data-original-title="Информация о бронировании"></td>
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
<div id="summary-table"></div>
<script>
    $('.summary-table tr td').popover();
</script>
<!--<script src="/public/assets/js/summary_table.js"></script>-->