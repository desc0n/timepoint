<?php
/** @var Model_Booking $bookingModel */
$bookingModel = Model::factory('Booking');

/** @var Model_Content $contentModel */
$contentModel = Model::factory('Content');

$bookingData = $bookingModel->findById($bookingId);
$templateWords = $contentModel->getTemplateWords('ru');
?>
<div class="form-group">
    <label for="inputType">Источник обращения</label>
    <?=Form::select('', $bookingModel->types, $bookingData['type'], ['id' => 'inputChangeType', 'class' => 'form-control']);?>
</div>
<div class="form-group">
    <label for="inputChangePhone">Телефон клиента</label>
    <input type="text" class="form-control" id="inputChangePhone" value="<?=$bookingData['customer_phone'];?>">
</div>
<div class="form-group">
    <label for="inputChangeName">Имя клиента</label>
    <input type="text" class="form-control" id="inputChangeName" value="<?=$bookingData['customer_name'];?>">
</div>
<div class="form-group">
    <label for="inputChangeComment"><?=$templateWords['main']['comment'];?></label>
    <textarea id="inputChangeComment" class="form-control" rows="3"><?=$bookingData['customer_comment'];?></textarea>
</div>
<div class="form-group">
    <label for="inputChangePrice"><?=$templateWords['main']['price'];?></label>
    <input type="text" class="form-control" id="inputChangePrice" value="<?=$bookingData['price'];?>" readonly>
</div>
<div class="form-group row">
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <label class="label-sm" for="inputChangeAdult"><?=$templateWords['main']['adult'];?></label>
        <input type="text" class="form-control" id="inputChangeAdult" value="<?=$bookingData['adult'];?>">
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <label class="label-sm" for="inputChangeChildrenTo12"><?=$templateWords['main']['children_12'];?></label>
        <input type="text" class="form-control" id="inputChangeChildrenTo12" value="<?=$bookingData['children_to_12'];?>">
    </div>
</div>
<div class="form-group row">
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <label class="label-sm" for="inputChangeChildrenTo2"><?=$templateWords['main']['children_2'];?></label>
        <input type="text" class="form-control" id="inputChangeChildrenTo2" value="<?=$bookingData['children_to_2'];?>">
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <label class="label-sm" for="inputChangeChildrenTo6"><?=$templateWords['main']['children_6'];?></label>
        <input type="text" class="form-control" id="inputChangeChildrenTo6" value="<?=$bookingData['children_to_6'];?>">
    </div>
</div>
<div class="form-group">
    <div class="form-group">
        <div class='input-group date'>
            <input id="inputChangePeriod" type="text" value="<?=date('d.m.Y', strtotime($bookingData['arrival_at']));?> - <?=date('d.m.Y', strtotime($bookingData['departure_at']));?>" class="form-control"/>
            <span class="input-group-addon datepicker-toggler" data-target="inputChangePeriod"><i class="fa fa-calendar"></i></span>
        </div>
    </div>
</div>
<div class="form-group text-right">
    <button type="button" class="btn btn-primary" onclick="changeBooking(<?=$bookingId;?>, <?=$bookingData['room_id'];?>)">Сохранить</button>
</div>
<!-- modal -->
<script>
    moment.locale('ru');
    $('#inputChangePeriod').daterangepicker({
        autoApply: true,
        opens: "center",
        drops: "up",
        locale: {
            format: 'DD.MM.YYYY'
        }
    });
    $('.datepicker-toggler').click(function() {
        $("#" + $(this).data('target')).focus();
    });
</script>

