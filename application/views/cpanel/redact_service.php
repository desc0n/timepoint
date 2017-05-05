<div class="row">
    <div class="col-lg-12">
        <h2 class="page-header">Редактирование услуги "<?=Arr::get($serviceData, 'title', '');?>"</h2>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <h3>Изображение</h3>
        <div class="col-lg-3">
            <a class="thumbnail" data-toggle="modal" href="#loadImgModal">
                <?if (is_file('public/img/services/' . Arr::get($serviceData, 'id') . '.jpg')) {?>
                    <img src="/public/img/services/<?=Arr::get($serviceData, 'id');?>.jpg?v=<?=time();?>" alt="">
                <?} else {?>
                    <img data-src="holder.js/100%x190" alt="">
                <?}?>
            </a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <form class="form-horizontal row" method="post">
            <div class="form-group row">
                <div class="col-lg-12">
                    <label for="inputTitle" class="control-label">Название</label>
                    <?=Form::input('title', Arr::get($serviceData, 'title', ''), ['id' => 'inputTitle', 'class' => 'form-control']);?>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-12">
                    <label for="inputDescription" class="control-label">Описание услуги</label>
                    <textarea id="inputDescription" name="description" class="ckeditor"><?=Arr::get($serviceData, 'description', '');?></textarea>
                </div>
            </div>
            <div class="col-lg-3">
                <button type="submit" class="btn btn-primary" name="updateService" value="1">Сохранить</button>
            </div>
        </form>
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
                        <input type="file" name="imgname" id="exampleInputFile">
                    </div>
                    <button type="submit" class="btn btn-default">Загрузить</button>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>