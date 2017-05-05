<?php
/** @var Model_Content $contentModel */
$contentModel = Model::factory('Content');
?>
<form method="post" class="form-horizontal">
    <div class="form-group">
        <div class="col-lg-9">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th class="text-center">Название</th>
                    <th class="text-center">Действия</th>
                </tr>
                </thead>
                <tbody>
                <?foreach ($services as $service) {?>
                    <tr id="serviceRow<?=$service['id'];?>">
                        <td>
                            <?=$service['title'];?>
                        </td>
                        <td class="text-center">
                            <a class="btn btn-success" href="/cpanel/redact_service/<?=$service['id'];?>" target="_self">
                                <span class="fa fa-pencil"></span>
                            </a>
                            <button class="btn btn-danger" type="button" onclick="removeService(<?=$service['id'];?>);">
                                <span class="fa fa-remove"></span>
                            </button>
                        </td>
                    </tr>
                <?}?>
                </tbody>
            </table>
        </div>
    </div>
</form>
<form method="post" class="form-horizontal">
    <h3>Добавить услугу</h3>
    <div class="form-group row">
        <div class="col-lg-12">
            <label for="inputTitle" class="col-lg-2 control-label">Название</label>
            <div class="col-lg-10">
                <?=Form::input('title', null, ['id' => 'inputTitle', 'class' => 'form-control']);?>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-lg-12">
            <label for="inputDescription" class="col-lg-2 control-label">Описание</label>
            <div class="col-lg-10">
                <textarea id="inputDescription" class="form-control ckeditor" name="description" required></textarea>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-lg-12 text-right">
            <span class="input-group-btn">
                <button class="btn btn-success" name="addService" value="1">
                    <span class="fa fa-plus-circle fa-fw"></span> Добавить
                </button>
            </span>
        </div>
    </div>
</form>