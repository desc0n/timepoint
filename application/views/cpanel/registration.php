<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>CRM</title>
  <!-- Bootstrap Core CSS -->
  <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.css" />
    <!-- jQuery -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
</head>

<body>

<div class="container">
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <?=$error;?>
      <h3>Регистрация</h3>
      <form method="post" id="regForm">
        <p>
        <div class="row">
          <div class="text-muted">Логин*:</div>
          <div class="col-sm-12">
            <input type="text" class="form-control"  name="username" placeholder="Логин" value="<?=Arr::get($post, 'username');?>">
          </div>
        </div>
        </p>
        <p>
        <div class="row">
          <div class="text-muted">Пароль*:</div>
          <div class="col-sm-6">
            <input type="password" class="form-control"  name="password" placeholder="Пароль">
          </div>
          <div class="col-sm-6">
            <input type="password" class="form-control"  name="password2"  placeholder="Еще раз">
          </div>
        </div>
        </p>
        <p>
        <div class="row">
          <div class="text-muted">Эл. почта*:</div>
          <div class="col-sm-12">
            <input type="text" class="form-control" name="email" placeholder="E-mail" value="<?=Arr::get($post, 'email');?>">
          </div>
        </div>
        </p>
        <p>
        <div class="row" style="margin-top:25px;">
          <div class="col-sm-12">
            <button type="button" class="btn btn-block btn-default" name="reg" onclick="$('#regForm').submit();">Зарегистрироваться</button>
          </div>
        </div>
        </p>
      </form>
    </div>
  </div>
</div>
</body>
</html>
