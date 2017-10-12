<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>CRM</title>
    <link href="/public/images/fav.png"  sizes="38x38" rel="shortcut icon" type="image/png" />
    <!-- Bootstrap Core CSS -->
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.css" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/public/css/daterangepicker.css" >
    <link rel="stylesheet" href="/assets/admin/css/styles.css?v=121020172258" />
    <link rel="stylesheet" href="/assets/bootstrap/css/font-awesome.css" />
    <!-- jQuery -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="/assets/bootstrap/js/bootstrap.js"></script>
    <script src="/assets/bootstrap/js/bootstrap3-typeahead.js"></script>
    <script src="/assets/admin/js/scripts.js?v=051020172228"></script>
    <script src="/assets/admin/js/holder.js"></script>
    <script src="/assets/admin/js/ckeditor/ckeditor.js"></script>
    <script src="/public/js/moment.js"></script>
    <script src="/public/js/moment-with-locales.js"></script>
    <script src="/public/js/daterangepicker.js?v=1"></script>
    <script src="/public/js/datepicker-ru.js"></script>
</head>
<body>
<div id="wrapper">

    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <!-- /.navbar-header -->
        <h1>
            Администраторская панель
            <div class="pull-right">
                <form method="post" action="/cpanel/logout">
                    <button class="btn btn-default" name="logout"><i class="fa fa-sign-out fa-fw"></i></button>
                </form>
            </div>
        </h1>
        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse">
                <ul class="nav" id="side-menu">
                    <?if(Auth::instance()->logged_in('admin')) {?>
                    <li>
                        <a href="/cpanel/rooms_list"><i class="fa fa-list fa-fw"></i> Список номеров</a>
                    </li>
                    <li>
                        <a href="/cpanel/contacts"><i class="fa fa-phone fa-fw"></i> Редактировать контакты</a>
                    </li>
                    <li>
                        <a href="/cpanel/conveniences_list"><i class="fa fa-star fa-fw"></i> Список удобств</a>
                    </li>
                    <li>
                        <a href="/cpanel/news_list"><i class="fa fa-newspaper-o fa-fw"></i> Список новостей</a>
                    </li>
                    <li>
                        <a href="/cpanel/registration"><i class="fa fa-user-plus fa-fw"></i> Добавление пользователя</a>
                    </li>
                    <?}?>
                    <li>
                        <a href="/cpanel/summary_table"><i class="fa fa-table fa-fw"></i> Результирующая таблица</a>
                    </li>
                </ul>
            </div>
            <!-- /.sidebar-collapse -->
        </div>
        <!-- /.navbar-static-side -->
    </nav>

    <div id="page-wrapper">
        <?=$content;?>
    </div>
    <!-- /#page-wrapper -->

</div>
<!-- /#wrapper -->
<div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="errorModalLabel">Ошибка</h4>
            </div>
            <div class="modal-body" id="errorModalBody">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>
</body>

</html>
