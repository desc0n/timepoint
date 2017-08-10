<div class="row">
    <div class="col-lg-12">
        <h2 class="page-header">Редактирование новости</h2>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <form class="form-horizontal row" method="post">
            <div class="form-group row">
                <div class="col-lg-12">
                    <label for="inputTitle" class="control-label">Заголовок новости</label>
                    <?=Form::input('title', Arr::get($news, 'title', ''), ['id' => 'inputTitle', 'class' => 'form-control']);?>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-12">
                    <label for="inputDescription" class="control-label">Текст новости</label>
                    <textarea id="inputDescription" name="content" class="ckeditor">
                        <?=Arr::get($news, 'content', '');?>
                    </textarea>
                </div>
            </div>
            <div class="col-lg-3">
                <button type="submit" class="btn btn-primary" name="updateNews" value="1">Сохранить</button>
            </div>
        </form>
    </div>
</div>
