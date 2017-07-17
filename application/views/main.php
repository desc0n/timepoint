<div class="rooms">
    <div class="rooms__wrapper">
        <?foreach ($rooms as $room) {?>
        <div class="rooms__kind" style="background: url('/public/images/a.png');">
            <div class="rooms__kind-caption">
                <div class="rooms__kind-caption-price"><?=$room['room']['price'];?> руб.</div>
                <button type="button" class="btn btn-primary rooms__kind-caption-action" data-toggle="modal" data-target=".modal-booking">Посмотреть</button>
            </div>
        </div>
        <?}?>
    </div>
</div>