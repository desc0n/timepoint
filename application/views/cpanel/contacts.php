<?php
/** @var Model_Content $contentModel */
$contentModel = Model::factory('Content');
?>
<form method="post" class="form-horizontal">
    <div class="form-group">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th class="text-center">Тип</th>
                <th class="text-center">Значение</th>
                <th class="text-center">Действия</th>
            </tr>
            </thead>
            <tbody>
            <?foreach ($contacts as $contact) {?>
                <tr id="contactRow<?=$contact['id'];?>">
                    <td>
                        <?=Form::select('types[]', $contentModel->getContactTypes(), $contact['type'], ['class' => 'form-control']);?>
                    </td>
                    <td>
                        <?=Form::input('values[]', $contact['value'], ['class' => 'form-control']);?>
                    </td>
                    <td class="text-center">
                        <button class="btn btn-danger" type="button" onclick="removeContact(<?=$contact['id'];?>);">
                            <span class="fa fa-remove"></span>
                        </button>
                        <?=Form::hidden('ids[]', $contact['id']);?>
                    </td>
                </tr>
            <?}?>
            </tbody>
        </table>
    </div>
    <div class="form-group row">
        <div class="col-lg-12">
            <span class="input-group-btn">
                <button class="btn btn-primary" name="updateContacts" value="1">
                    <span class="fa fa-check-circle fa-fw"></span> Сохранить
                </button>
            </span>
        </div>
    </div>
</form>
<form method="post" class="form-horizontal">
    <h3>Добавить контакт</h3>
    <div class="form-group row">
        <div class="col-lg-9">
            <label for="inputType" class="col-lg-2 control-label">Тип</label>
            <div class="col-lg-10">
                <?=Form::select('type', $contentModel->getContactTypes(), null, ['id' => 'inputType', 'class' => 'form-control']);?>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-lg-9">
            <label for="inputValue" class="col-lg-2 control-label">Значение</label>
            <div class="col-lg-10">
                <input type="text" id="inputValue" class="form-control" name="value" placeholder="Значение" required>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-lg-12">
            <span class="input-group-btn">
                <button class="btn btn-success" name="addContact" value="1">
                    <span class="fa fa-plus-circle fa-fw"></span> Добавить
                </button>
            </span>
        </div>
    </div>
</form>