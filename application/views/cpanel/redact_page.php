<div class="row">
    <div class="col-lg-12">
        <h2 class="page-header">Редактирование страницы "<?=Arr::get($pageData, 'title', '');?>"</h2>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <form class="form-horizontal row" method="post">
            <div class="form-group col-lg-12">
                <label for="redact_content_text">Текст страницы</label>
                <textarea id="redact_content_text" name="content" class="ckeditor"><?=Arr::get($pageData, 'content', '');?></textarea>
            </div>
            <div class="col-lg-3">
                <button type="submit" class="btn btn-primary" name="updateContent" value="1">Сохранить</button>
            </div>
        </form>
    </div>
</div>