<div class="alert alert-danger text-center">
<!--    При бронировании без оплаты, Ваша бронь будет действительна в течении 24 часов.-->
    <strong>Бронирование без оплаты прекращается за 5 суток до предполагаемой даты заезда.</strong>
</div>
<div class="row">
    <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <button class="btn" onclick="notPayedReserveRoom();">Забронировать без оплаты</button>
    </div>
    <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <button class="btn btn-primary">Забронировать с оплатой</button>
    </div>
</div>
<div id="reserveRoomData">
    <input type="hidden" id="reserveRoomId" value="<?=$roomId;?>">
    <input type="hidden" id="arrivalDate" value="<?=$arrivalDate;?>">
    <input type="hidden" id="departureDate" value="<?=$departureDate;?>">
    <input type="hidden" id="customerPhone" value="<?=$phone;?>">
    <input type="hidden" id="customerName" value="<?=$name;?>">
    <input type="hidden" id="customerComment" value="<?=$comment;?>">
</div>