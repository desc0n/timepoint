<!DOCTYPE html>
<html lang="en">
<head>
    <title>Мини-отель VLADPOINT. В деловом центре Владивостока.</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no" />
    <link rel="icon" href="/public/images/fav.png" sizes="38x38" type="image/png">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/public/css/daterangepicker.css" >
    <link rel="stylesheet" href="/public/css/styles.css?v=10" >
    <link rel="stylesheet" href="/assets/bootstrap/css/font-awesome.css" >
    <!-- jQuery first, then Tether, then Bootstrap JS. -->
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <script src="/public/js/moment.js"></script>
    <script src="/public/js/moment-with-locales.js"></script>
    <script src="/public/js/daterangepicker.js?v=1"></script>
</head>
<?php
/** @var Model_Content $contentModel */
$contentModel = Model::factory('Content');

/** @var $roomModel Model_Room */
$roomModel = Model::factory('Room');
$today = new \DateTime();
$calendarToday = clone $today;
$calendarToday = $calendarToday->modify('- 1 month');
$tomorrow = clone $today;
$tomorrow->modify('+ 1 day');
$arrivalDate = new DateTime(date('Y-m-d', strtotime(Arr::get($get, 'arrival_date', $today->format('d.m.Y')))));
$departureDate = new DateTime(date('Y-m-d', strtotime(Arr::get($get, 'departure_date', $tomorrow->format('d.m.Y')))));
$calendarArrivalDate = clone $arrivalDate;
$calendarArrivalDate->modify('- 1 month');
$calendarDepartureDate = clone $departureDate;
$calendarDepartureDate->modify('- 1 month');
$startDate = $arrivalDate < $today ? $today : $arrivalDate;
$nightCount = (round(($departureDate->getTimestamp() - $startDate->getTimestamp()) / 86400) + 1);
?>
<script>
    function getMinDate() {
        return new Date(<?=$calendarToday->format('Y');?>, <?=$calendarToday->format('m');?>, <?=$calendarToday->format('d');?>);
    }
    function getStartDate() {
        return new Date(<?=$calendarArrivalDate->format('Y');?>, <?=$calendarArrivalDate->format('m');?>, <?=$calendarArrivalDate->format('d');?>);
    }
    function getEndDate() {
        return new Date(<?=$calendarDepartureDate->format('Y');?>, <?=$calendarDepartureDate->format('m');?>, <?=$calendarDepartureDate->format('d');?>);
    }
</script>
<body>
<div class="wrapper">
    <div class="container">
        <nav class="navbar navbar-toggleable-md navbar-inverse">
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse"
                    data-target="#navbar" aria-controls="navbar" aria-expanded="false"
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="/">
                <img class="hidden-xs" src="/public/images/logo.svg" alt="VladPoint">
            </a>

            <div class="collapse navbar-collapse" id="navbar">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link nav-link__booking" href="/"><?=$templateWords['menu']['rooms_and_prices'];?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/news"><?=$templateWords['menu']['news'];?></a>
                    </li>
<!--                    <li class="nav-item">-->
<!--                        <a class="nav-link" href="#">Контакты</a>-->
<!--                    </li>-->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="dropdown1" data-toggle="dropdown" aria-haspopup="true"
                           aria-expanded="true">RU</a>
                        <div class="dropdown-menu" aria-labelledby="dropdown1">
                            <a class="dropdown-item" href="/">RU</a>
                            <a class="dropdown-item" href="/en">EN</a>
                        </div>

                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="dropdown2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            <?=mb_strtoupper(Arr::get($get, 'currency', 'RUB'));?>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdown2">
                            <a class="dropdown-item" href="/?currency=rub<?=$contentModel->createQueryString($get, false, ['currency']);?>">RUB</a>
                            <a class="dropdown-item" href="/?currency=usd<?=$contentModel->createQueryString($get, false, ['currency']);?>">USD</a>
                        </div>
                    </li>
                    <li class="nav-item nav-item__contacts hidden-lg-down">
                        <div class="nav-link">
                            <?foreach ($contentModel->getContacts(['address']) as $contact){?>
                                <div class="nav-link__address"><?=$contact['value'];?></div>
                            <?}?>
                            <?foreach ($contentModel->getContacts(['phone']) as $contact){?>
                                <div class="nav-link__phone"><?=$contact['value'];?></div>
                            <?}?>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
    <!-- /.navbar -->
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="header">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.wrapper -->
<div class="container">
    <form action="/" class="booking" id="filter_form">
        <div class="row">
            <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12 booking__caption"><span><?=$templateWords['filter']['booking'];?></span></div>
            <div class="col-lg-8 col-md-12  col-sm-12  col-xs-12 booking__selects">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 booking__selects-form">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label for="guests"><?=$templateWords['filter']['quests'];?> </label></div>
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                <?=Form::select('guest_count', $roomModel->roomsGuests, Arr::get($get, 'guest_count'), ['class' => 'form-control', 'id' => 'guests']);?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 booking__selects-form">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12"><label for="arrival"><?=$templateWords['filter']['period'];?> </label></div>
                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 booking-calendar">
                                <div class="form-group">
                                    <div class='input-group date'>
                                        <input value="<?=$arrivalDate->format('d.m.Y');?> - <?=$departureDate->format('d.m.Y');?>" type="text" id="daterange" class="form-control"/>
                                        <span class="input-group-addon datepicker-toggler" data-target="daterange">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12" id="nightCount">
                        <?=$nightCount;?>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 booking__action">
                <button type="button" class="btn btn-primary rooms__kind-caption-action" onclick="filterRooms();"><?=$templateWords['filter']['show_free_rooms'];?></button>
            </div>
        </div>
        <input value="<?=$arrivalDate->format('d.m.Y');?>" type="hidden" name="arrival_date">
        <input value="<?=$departureDate->format('d.m.Y');?>" type="hidden" name="departure_date">
        <input value="<?=Arr::get($get, 'currency', 'rub');?>" type="hidden" name="currency">
    </form>
</div>
<div class="container">
    <?=$content;?>
</div>
<!-- /.rooms -->
<div class="contacts-block">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h1><?=$templateWords['contacts']['contacts'];?></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="contacts-block__caption">
                    <?=$templateWords['contacts']['booking_phone'];?>
                </div>
                <?foreach ($contentModel->getContacts(['phone']) as $contact){?>
                    <div class="contacts-block__content"><?=$contact['value'];?></div>
                <?}?>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 ">
                <div class="contacts-block__caption">
                    <?=$templateWords['contacts']['address'];?>
                </div>
                <?foreach ($contentModel->getContacts(['address']) as $contact){?>
                    <div class="contacts-block__content">
                        <?=$contact['value'];?>
                    </div>
                <?}?>
            </div>
        </div>
    </div>
</div>
<!-- /.contacts -->
<div class="map-block">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h1 class="map-block-header">Как к нам добраться</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="map-controls">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <ul class="map-controls__list map-controls__list_parent">
                                <li class="map-controls__list_active" data-path="1"><span>с ж/д вокзала</span></li>
                                <li data-path="2"><span>из аэропорта</span></li>
                                <li data-path="3"><span>с автовокзала</span></li>
                            </ul>
                        </div>
                    </div>
                    <div class="row map-path-visible map-path-1">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <ul class="map-controls__list map-controls__list_sublist">
                                <li class="map-controls__list_active" data-img="path1_pedestrian"><span>пешком</span></li>
                                <li data-img="path1_taxi"><span>на такси</span> <i class="fa fa-question-circle" aria-hidden="true" onclick="$('#taxiInfo1').modal('toggle');"></i></li>
                            </ul>
                        </div>
                    </div>
                    <div class="row map-path-hidden map-path-2">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <ul class="map-controls__list map-controls__list_sublist">
                                <li class="map-controls__list_active" data-img="path2_express"><span>аэроэкспрессом</span> <i class="fa fa-question-circle" aria-hidden="true" onclick="$('#expressInfo').modal('toggle');"></i></li>
                                <li data-img="path2_taxi"><span>на такси</span> <i class="fa fa-question-circle" aria-hidden="true" onclick="$('#taxiInfo1').modal('toggle');"></i></li>
                            </ul>
                        </div>
                    </div>
                    <div class="row map-path-hidden map-path-3">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <ul class="map-controls__list map-controls__list_sublist">
                                <li class="map-controls__list_active" data-img="path3_bus"><span>на автобусе</span></li>
                                <li data-img="path3_taxi"><span>на такси</span> <i class="fa fa-question-circle" aria-hidden="true" onclick="$('#taxiInfo1').modal('toggle');"></i></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="map">
        <img id="map" src="/public/images/path1_pedestrian.jpg">
    </div>
</div>
<!-- /.map -->
<div class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="footer__block-logo">
                    <a href="/"><img src="/public/images/logo.svg" alt="VladPoint"></a>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="footer__block-contacts">
                    <div class="caption"><?=$templateWords['contacts']['booking_phone'];?></div>
                    <?foreach ($contentModel->getContacts(['phone']) as $contact){?>
                        <div class="phone"><?=$contact['value'];?></div>
                    <?}?>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="footer__block-navbar">
                    <nav class="navbar navbar-toggleable-md navbar-inverse navbar_footer">
                        <div class="navbar-collapse">
                            <ul class="navbar-nav mr-auto">
                                <li class="nav-item">
                                    <a class="nav-link" href="/"><?=$templateWords['menu']['rooms_and_prices'];?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/news"><?=$templateWords['menu']['news'];?></a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="taxiInfo1" class="modal fade modal-booking" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-booking">
            <div class="modal-header">
                <h5 class="modal-title">Список такси</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h4>Такси Максим</h4>
                <div>тел. (423) 2-888-888, (423) 2-511-115</div>
                <div>стоимость от 140 руб.</div>
                <div><a href="https://taximaxim.ru/?city=Владивосток" target="_blank">https://taximaxim.ru/?city=Владивосток</a></div>
                <h4>Такси Сатурн</h4>
                <div>тел. (423) 2-500-500</div>
                <div>стоимость от 70 руб.</div>
                <div><a href="http://vladivostok.taxisaturn.ru/" target="_blank">http://vladivostok.taxisaturn.ru/</a></div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
<div id="expressInfo" class="modal fade modal-booking" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-booking">
            <div class="modal-header">
                <h5 class="modal-title">Расписание аэроэкспресса</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h4>Владивосток - Кневичи</h4>
                <strong>стоимость 230 руб.</strong>
                <div>07:08 (отправление) - 08:03 (прибытие)</div>
                <div>09:01 (отправление) - 09:56 (прибытие)</div>
                <div>12:00 (отправление) - 12:56 (прибытие)</div>
                <div>16:00 (отправление) - 16:55 (прибытие)</div>
                <div>18:00 (отправление) - 18:55 (прибытие)</div>
                <div><a href="http://www.vl.ru/transport/aeroexpress" target="_blank">http://www.vl.ru/transport/aeroexpress</a></div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
<div id="notificationModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>
<div id="reservationModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>
<script src="/public/js/scripts.js?v=6"></script>
<script>
    $(document).ready(function () {
        writeNightCount(<?=$nightCount;?>, '');
    });
</script>
</body>
</html>