<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Выбор страниц сайта</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">
    <div class="col-lg-12">
        <form action="/crm/redact_page">
            <div class="text-muted col-sm-12">Страница:</div>
            <div class="col-sm-6">
                <select class="form-control" name="id">
                    <option value="0">не выбрано</option>
                    <?foreach($pages as $page) {
                        if(!$page['redact']) {
                            continue;
                        }?>
                        <option value="<?=$page['id'];?>"><?=$page['title'];?></option>
                    <?}?>
                </select>
            </div>
            <button class="btn btn-default" type="submit">Выбрать</button>
        </form>
    </div>
</div>