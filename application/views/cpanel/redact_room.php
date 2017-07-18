<?php
/** @var Model_Room $roomModel */
$roomModel = Model::factory('Room');
?>
<div class="col-md-12">
    <h3>Редактирование номера</h3>
    <input type="hidden" id="roomId" value="<?=$roomId;?>">
    <div class="row">
        <div class="col-md-12">
            <h3>Изображение</h3>
            <?foreach($roomImgs as $img){?>
                <div class="col-md-3" id="roomImg<?=$img['id'];?>">
                    <a href="#" class="thumbnail" onclick="redactRoomImg(<?=$img['id'];?>, '<?=$img['src'];?>', <?=(int)$img['main'];?>);">
                        <img src="/public/img/thumb/<?=$img['src'];?>" class="<?=((bool)$img['main'] ? 'main-room-img' : '');?>">
                    </a>
                </div>
            <?}?>
            <div class="col-md-3">
                <a  data-toggle="modal" href="#loadImgModal" class="thumbnail">
                    <img data-src="holder.js/100%x190" alt="">
                </a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h3>Описание</h3>
            <form method="post">
                <div class="row form-group">
                    <div class="col-md-6">
                        <div class="text-muted col-md-12">Название номера:</div>
                        <div class="col-md-12">
                            <input type="text" class="form-control"  name="title" placeholder="Название номера" value="<?=Arr::get($room, 'title');?>">
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-6">
                        <div class="text-muted col-md-12">Количество гостей:</div>
                        <div class="col-md-12">
                            <?=Form::select('guests_count', $roomModel->roomsGuests, $room['guests_count'], ['class' => 'form-control'])?>
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-6">
                        <div class="text-muted col-md-12">Стоимость проживания:</div>
                        <div class="col-md-12">
                            <input type="text" class="form-control"  name="price" placeholder="Стоимость проживания" value="<?=Arr::get($room, 'price');?>">
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-12">
                        <div class="text-muted col-md-12">Список удобств:</div>
                        <div class="col-md-12">
                            <ul>
                            <?$conveniencesListForSelect = $conveniencesList;?>
                            <?foreach ($roomConveniences as $roomConvenience) {?>
                                <li><?=$conveniencesList[$roomConvenience['convenience_id']];?> <i class="fa fa-remove remove-convenience-btn" data-id="<?=$roomConvenience['convenience_id'];?>"></i></li>
                                <?unset($conveniencesListForSelect[$roomConvenience['convenience_id']]);?>
                            <?}?>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <?=Form::select('', $conveniencesListForSelect, null, ['class' => 'form-control', 'id' => 'conveniencesList'])?>
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-block btn-warning add-convenience-btn">
                                        <i class="fa fa-plus-circle"></i>
                                    </button>
                                </span>
                            </div><!-- /input-group -->
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-block btn-success" name="redactRoom" value="1">Сохранить</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="loadImgModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Загрузка изображения</h4>
            </div>
            <div class="modal-body">
                <form role="form" method="post" enctype='multipart/form-data'>
                    <div class="form-group">
                        <label for="exampleInputFile">Выбор файла</label>
                        <input type="file" name="imgname[]" id="exampleInputFile" multiple>
                    </div>
                    <button type="submit" class="btn btn-default">Загрузить</button>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="redactImgModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Просмотр изображения</h4>
            </div>
            <div class="modal-body text-center">

            </div>
            <div class="modal-footer">
                <span><input type="checkbox" id="setMainRoomImg" value="<?=Arr::get($room, 'id');?>"> Сделать главной</span>
                <button class="btn btn-danger" onclick="removeRoomImg();">Удалить изображение</button>
            </div>
        </div>
    </div>
</div>