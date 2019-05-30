<div class="row">
    <div class="col-lg-12">
        <h2 class="page-header">Редактирование страницы </h2>
    </div>
</div>
<div class="form-group row">
    <div class="col-lg-6">
        <form class="form-horizontal row" method="post">
            <h3>Реквизиты для оплаты</h3>
            <div class="form-group col-lg-12">
                <label for="requisites_name">Название компании</label>
                <input type="text" class="form-control" id="requisites_name" name="name" value="<?=str_replace('"', "'", $pageInfo['requisites']['name']);?>">
            </div>
            <div class="form-group col-lg-12">
                <label for="requisites_inn">ИНН</label>
                <input type="text" class="form-control" id="requisites_inn" name="inn" value="<?=$pageInfo['requisites']['inn'];?>">
            </div>
            <div class="form-group col-lg-12">
                <label for="requisites_kpp">КПП</label>
                <input type="text" class="form-control" id="requisites_kpp" name="kpp" value="<?=$pageInfo['requisites']['kpp'];?>">
            </div>
            <div class="form-group col-lg-12">
                <label for="requisites_ogrn">ОГРН</label>
                <input type="text" class="form-control" id="requisites_ogrn" name="ogrn" value="<?=$pageInfo['requisites']['ogrn'];?>">
            </div>
            <div class="form-group col-lg-12">
                <label for="requisites_okpo">ОКПО</label>
                <input type="text" class="form-control" id="requisites_okpo" name="okpo" value="<?=$pageInfo['requisites']['okpo'];?>">
            </div>
            <div class="form-group col-lg-12">
                <label for="requisites_checking_account">Расчетный счет</label>
                <input type="text" class="form-control" id="requisites_checking_account" name="checking_account" value="<?=$pageInfo['requisites']['checking_account'];?>">
            </div>
            <div class="form-group col-lg-12">
                <label for="requisites_bank_name">Банк</label>
                <input type="text" class="form-control" id="requisites_bank_name" name="bank_name" value="<?=str_replace('"', "'", $pageInfo['requisites']['bank_name']);?>">
            </div>
            <div class="form-group col-lg-12">
                <label for="requisites_bik">БИК</label>
                <input type="text" class="form-control" id="requisites_bik" name="bik" value="<?=$pageInfo['requisites']['bik'];?>">
            </div>
            <div class="form-group col-lg-12">
                <label for="requisites_corr_account">Корр. счет</label>
                <input type="text" class="form-control" id="requisites_corr_account" name="corr_account" value="<?=$pageInfo['requisites']['corr_account'];?>">
            </div>
            <div class="form-group col-lg-12">
                <button type="submit" class="btn btn-primary" name="updateRequisites" value="1">Сохранить</button>
            </div>
        </form>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <form class="form-horizontal row" method="post">
            <div class="form-group col-lg-12">
                <label for="redact_content_text">Текст страницы</label>
                <textarea id="redact_content_text" name="content" class="ckeditor"></textarea>
            </div>
            <div class="form-group col-lg-12">
                <button type="submit" class="btn btn-primary" name="updateContent" value="1">Сохранить</button>
            </div>
        </form>
    </div>
</div>