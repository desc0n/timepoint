<form method="post" class="form-horizontal">
    <div class="form-group">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th class="text-center">Значение</th>
                <th class="text-center">Действия</th>
            </tr>
            </thead>
            <tbody>
            <?foreach ($conveniences as $convenience) {?>
                <tr id="convenienceRow<?=$convenience['id'];?>">
                    <td>
                        <?=Form::input('values[]', $convenience['value'], ['class' => 'form-control']);?>
                    </td>
                    <td class="text-center">
                        <button class="btn btn-danger" type="button" onclick="removeConvenience(<?=$convenience['id'];?>);">
                            <span class="fa fa-remove"></span>
                        </button>
                        <?=Form::hidden('ids[]', $convenience['id']);?>
                    </td>
                </tr>
            <?}?>
            </tbody>
        </table>
    </div>
    <div class="form-group row">
        <div class="col-lg-12">
            <span class="input-group-btn">
                <button class="btn btn-primary" name="updateConveniences" value="1">
                    <span class="fa fa-check-circle fa-fw"></span> Сохранить
                </button>
            </span>
        </div>
    </div>
</form>
<form method="post" class="form-horizontal">
    <h3>Добавить удобство</h3>
    <div class="form-group row">
        <div class="col-lg-9">
            <label for="inputValue" class="col-lg-2 control-label">Название</label>
            <div class="col-lg-10">
                <input type="text" id="inputValue" class="form-control" name="value" placeholder="Название" required>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-lg-12">
            <span class="input-group-btn">
                <button class="btn btn-success" name="addConvenience" value="1">
                    <span class="fa fa-plus-circle fa-fw"></span> Добавить
                </button>
            </span>
        </div>
    </div>
</form>