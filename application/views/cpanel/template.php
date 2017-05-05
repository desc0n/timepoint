<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>CRM</title>
    <link href="/public/i/fav.png"  sizes="38x38" rel="shortcut icon" type="image/png" />
    <!-- Bootstrap Core CSS -->
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.css" />
    <link rel="stylesheet" href="/assets/admin/css/styles.css?v=2" />
    <link rel="stylesheet" href="/assets/bootstrap/css/font-awesome.css" />
    <!-- jQuery -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="/assets/bootstrap/js/bootstrap.js"></script>
    <script src="/assets/bootstrap/js/bootstrap3-typeahead.js"></script>
    <script src="/assets/admin/js/scripts.js?v=2"></script>
    <script src="/assets/admin/js/holder.js"></script>
    <script src="/assets/admin/js/ckeditor/ckeditor.js"></script>
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
                    <li>
                        <a href="/cpanel/portfolio_items_list"><i class="fa fa-list fa-fw"></i> Портфолио</a>
                    </li>
                    <li>
                        <a href="/cpanel/add_portfolio_item"><i class="fa fa-file fa-fw"></i> Добавить проект</a>
                    </li>
                    <li>
                        <a href="/cpanel/contacts"><i class="fa fa-phone fa-fw"></i> Редактировать контакты</a>
                    </li>
                    <li>
                        <a href="/cpanel/redact_page/activity"><i class="fa fa-pencil-square-o fa-fw"></i> Редактировать деятельность</a>
                    </li>
                    <li>
                        <a href="/cpanel/services"><i class="fa fa-cogs fa-fw"></i> Редактировать услуги</a>
                    </li>
                    <li>
                        <a href="/cpanel/social_networks"><i class="fa fa-share-alt fa-fw"></i> Редактировать соцсети</a>
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
