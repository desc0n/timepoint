<div class="col-sm-12">
    <h3>Добавление проекта</h3>
    <div class="row">
        <div class="col-md-12">
            <h3>Изображение</h3>
            <?foreach($portfolioItemImgs as $img){?>
                <div class="col-lg-3" id="portfolioItemImg<?=$img['id'];?>">
                    <a href="#" class="thumbnail" onclick="redactPortfolioItemImg(<?=$img['id'];?>, '<?=$img['src'];?>', <?=(int)$img['main'];?>);">
                        <img src="/public/img/thumb/<?=$img['src'];?>" class="<?=((bool)$img['main'] ? 'main-item-img' : '');?>">
                    </a>
                </div>
            <?}?>
            <div class="col-lg-3">
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
                        <div class="text-muted col-md-12">Категория:</div>
                        <div class="col-md-12">
                            <?=Form::select('category_id', $categories, Arr::get($portfolioItem, 'category_id'), ['class' => 'form-control']);?>
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-6">
                        <div class="text-muted col-md-12">Название проекта:</div>
                        <div class="col-md-12">
                            <input type="text" class="form-control"  name="title" placeholder="Название проекта" value="<?=Arr::get($portfolioItem, 'title');?>">
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-12">
                        <div class="text-muted col-sm-12">Описание:</div>
                        <div class="col-sm-12">
                            <textarea class="form-control ckeditor"  name="description" placeholder="Описание" rows="10"><?=Arr::get($portfolioItem, 'description');?></textarea>
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-6">
                        <button type="submit" class="btn btn-block btn-success" name="redactPortfolioItem">Сохранить</button>
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
                <span><input type="checkbox" id="setMainItemPage" value="<?=Arr::get($portfolioItem, 'id');?>"> Сделать главной</span>
                <button class="btn btn-danger" onclick="removePortfolioItemImg();">Удалить изображение</button>
            </div>
        </div>
    </div>
</div>