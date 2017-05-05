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
            </tr>
            </thead>
            <tbody>
            <?foreach ($networks as $network) {?>
                <tr id="contactRow<?=$network['id'];?>">
                    <td>
                        <?=$network['type'];?>
                    </td>
                    <td>
                        <?=Form::input('values[]', $network['value'], ['class' => 'form-control']);?>
                        <?=Form::hidden('ids[]', $network['id']);?>
                    </td>
                </tr>
            <?}?>
            </tbody>
        </table>
    </div>
    <div class="form-group row">
        <div class="col-lg-12">
            <span class="input-group-btn">
                <button class="btn btn-primary" name="updateNetworks" value="1">
                    <span class="fa fa-check-circle fa-fw"></span> Сохранить
                </button>
            </span>
        </div>
    </div>
</form>