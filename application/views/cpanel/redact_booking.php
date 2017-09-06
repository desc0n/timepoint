<?php
/** @var Model_Reservation $reservationModel */
$reservationModel = Model::factory('Reservation');


?>
<legend><?=$templateWords['main']['booking_request'];?></legend>
<div class="form-group">
    <label for="inputPhone<?=$roomId;?>"><?=$templateWords['main']['phone'];?> *</label>
    <input type="text" class="form-control" id="inputPhone<?=$roomId;?>" placeholder="<?=$templateWords['main']['specify_phone'];?> +79001234567">
</div>
<div class="form-group">
    <label for="inputName<?=$roomId;?>"><?=$templateWords['main']['name'];?> *</label>
    <input type="text" class="form-control" id="inputName<?=$roomId;?>" placeholder="<?=$templateWords['main']['name'];?>">
</div>
<div class="form-group">
    <label for="inputComment<?=$roomId;?>"><?=$templateWords['main']['comment'];?></label>
    <textarea id="inputComment<?=$roomId;?>" class="form-control" rows="3" placeholder="<?=$templateWords['main']['comment'];?>"></textarea>
</div>
<div class="form-group row">
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <label class="label-sm" for="inputAdult<?=$roomId;?>"><?=$templateWords['main']['adult'];?></label>
        <input type="text" class="form-control" id="inputAdult<?=$roomId;?>" value="0">
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <label class="label-sm" for="inputChildrenTo12<?=$roomId;?>"><?=$templateWords['main']['children_12'];?></label>
        <input type="text" class="form-control" id="inputChildrenTo12<?=$roomId;?>" value="0">
    </div>
</div>
<div class="form-group row">
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <label class="label-sm" for="inputChildrenTo2<?=$roomId;?>"><?=$templateWords['main']['children_2'];?></label>
        <input type="text" class="form-control" id="inputChildrenTo2<?=$roomId;?>" value="0">
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <label class="label-sm" for="inputChildrenTo6<?=$roomId;?>"><?=$templateWords['main']['children_6'];?></label>
        <input type="text" class="form-control" id="inputChildrenTo6<?=$roomId;?>" value="0">
    </div>
</div>
<div class="form-group text-right">
    <button type="button" class="btn btn-primary" data-id="<?=$roomId;?>"><?=$templateWords['main']['book_a_room'];?></button>
    <input type="hidden" id="notChecked<?=$roomId;?>" value="<?=(int)($queryArrivalDate !== null && $queryDepartureDate !== null);?>">
</div>
<legend><?=$templateWords['main']['booking_period'];?></legend>
<div class="form-group">
    <div class="form-group">
        <div class='input-group date'>
            <input id="daterange<?=$roomId;?>" type="text" value="<?=$arrivalDate->format('d.m.Y');?> - <?=$departureDate->format('d.m.Y');?>" class="form-control"/>
            <span class="input-group-addon datepicker-toggler" data-target="daterange<?=$roomId;?>">
                                        <i class="fa fa-calendar"></i>
                                    </span>
        </div>
    </div>
</div>
<!-- modal -->
<script>
    moment.locale('ru');
    $('#daterange<?=$roomId;?>').daterangepicker({
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
            writeNightCount((Math.round(dateDiff / 86400000) - 1), <?=$roomId;?>);
        });
    $('.datepicker-toggler').click(function() {
        $("#" + $(this).data('target')).focus();
    });
</script>

