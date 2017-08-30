<?php
$userFactory = ORM::factory('User');
?>
<div class="row">
    <div class="col-lg-12">
        <h2 class="page-header">Пользователи</h2>
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="form-group">
    <div class="col-lg-6">
        <table class="table table-bordered">
            <thead>
            <tr><th>Логин</th><th>Действие</th></tr>
            </thead>
        <?foreach ($userFactory->find_all() as $user) {?>
            <?if ($user->username === 'admin') continue;?>
            <tr>
                <td><?=$user->username;?></td>
                <td>
                    <div class="input-group">
                        <input id="userPassword<?=$user->id;?>" type="password" class="form-control" placeholder="Изменить пароль">
                        <span class="input-group-btn">
                            <button class="btn btn-primary" onclick="changePassword(<?=$user->id;?>);"><i class="fa fa-check"></i></button>
                        </span>
                    </div>
                </td>
            </tr>
        <?}?>
        </table>
    </div>
</div>
<div class="form-group">
    <div class="col-lg-12">
        <h3>Регистрация</h3>
        <form class="col-lg-6" method="post" id="regForm">
            <div class="row">
                <div class="text-muted">Логин*:</div>
                <div class="col-lg-12">
                    <input type="text" class="form-control"  name="username" placeholder="Логин" value="<?=Arr::get($post, 'username');?>">
                </div>
            </div>
            <div class="row">
                <div class="text-muted">Пароль*:</div>
                <div class="col-lg-6">
                    <input type="password" class="form-control"  name="password" placeholder="Пароль">
                </div>
                <div class="col-lg-6">
                    <input type="password" class="form-control"  name="password2"  placeholder="Еще раз">
                </div>
            </div>
            <div class="row">
                <div class="text-muted">Эл. почта*:</div>
                <div class="col-lg-12">
                    <input type="text" class="form-control" name="email" placeholder="E-mail" value="<?=Arr::get($post, 'email');?>">
                </div>
            </div>
            <div class="row" style="margin-top:25px;">
                <div class="col-lg-12">
                    <button type="button" class="btn btn-block btn-default" name="reg" onclick="$('#regForm').submit();">Зарегистрироваться</button>
                </div>
            </div>
        </form>
    </div>
</div>
