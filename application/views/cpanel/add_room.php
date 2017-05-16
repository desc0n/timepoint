<div class="col-sm-12">
    <h3>Добавление проекта</h3>
    <form method="post">
        <div class="row form-group">
            <div class="col-md-6">
                <div class="text-muted col-md-12">Категория:</div>
                <div class="col-md-12">
                    <?=Form::select('category_id', $categories, null, ['class' => 'form-control']);?>
                </div>
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-6">
                <div class="text-muted col-md-12">Название проекта:</div>
                <div class="col-md-12">
                    <input type="text" class="form-control"  name="title" placeholder="Название проекта" value="">
                </div>
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-12">
                <div class="text-muted col-sm-12">Описание:</div>
                <div class="col-sm-12">
                    <textarea class="form-control ckeditor"  name="description" placeholder="Описание" rows="10"></textarea>
                </div>
            </div>
        </div>
        <div class="row form-group">
            <div class="col-sm-6">
                <button type="submit" class="btn btn-block btn-success" name="addPortfolioItem">Сохранить</button>
            </div>
        </div>
    </form>
</div>