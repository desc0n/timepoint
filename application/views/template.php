<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/public/css/styles.css?v=2" >
</head>
<?php
/** @var Model_Content $contentModel */
$contentModel = Model::factory('Content');
?>
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
                <img src="/public/images/logo.svg" alt="VladPoint">
            </a>

            <div class="collapse navbar-collapse" id="navbar">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Гостиница</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Номера и цены</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Новости</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Контакты</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link__booking" href="#">Бронирование</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="dropdown1" data-toggle="dropdown" aria-haspopup="true"
                           aria-expanded="true">RU</a>
                        <div class="dropdown-menu" aria-labelledby="dropdown1">
                            <a class="dropdown-item" href="#">RU</a>
                            <a class="dropdown-item" href="#">EN</a>
                        </div>

                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="dropdown2" data-toggle="dropdown" aria-haspopup="true"
                           aria-expanded="true">RUB</a>
                        <div class="dropdown-menu" aria-labelledby="dropdown2">
                            <a class="dropdown-item" href="#">RUB</a>
                            <a class="dropdown-item" href="#">USD</a>
                        </div>

                    </li>
                    <li class="nav-item nav-item__contacts hidden-lg-down">
                        <img src="/public/images/point.png" alt="Адрес">
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
            <div class="col-12">
                <div class="header">
                    <h1>МИНИ ОТЕЛЬ В ДЕЛОВОМ ЦЕНТРЕ ВЛАДИВОСТОКА С ВИДОМ НА ГОРОД И БУХТУ «ЗОЛОТОЙ РОГ»</h1>
                    <img src="/public/images/logo.svg" alt="VladPoint">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.wrapper -->
<div class="container">
    <div class="booking">
        <div class="row">
            <div class="col-md-12 col-lg-3 booking__caption"><span>Бронирование</span></div>
            <div class="col-md-12 col-lg-6 booking__selects">
                <div class="row">
                    <div class="col-6">
                        <div class="row">
                            <div class="col-lg-12 col-xl-3"><label for="arrival">Заезд </label></div>
                            <div class="col-lg-12 col-xl-9"><input id="arrival" type="date" value="2017-05-10" class="form-control"/></div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row">
                            <div class="col-lg-12 col-xl-3"><label for="departureDate">Выезд </label></div>
                            <div class="col-lg-12 col-xl-9"><input id="departureDate" type="date" value="2017-05-10" class="form-control"/></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-3 booking__action">
                <button type="button" class="btn btn-primary rooms__kind-caption-action" data-toggle="modal" data-target=".modal-booking">Показать номера</button>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <?=$content;?>
</div>
<!-- /.rooms -->
<div class="contacts-block">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>Контакты</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="contacts-block__caption">
                    Телефон бронирования
                </div>
                <?foreach ($contentModel->getContacts(['phone']) as $contact){?>
                    <div class="contacts-block__content"><?=$contact['value'];?></div>
                <?}?>
            </div>
            <div class="col-6">
                <div class="contacts-block__caption">
                    Адрес
                </div>
                <?foreach ($contentModel->getContacts(['address']) as $contact){?>
                    <div class="contacts-block__content">
                        <img src="/public/images/point.png" alt="Адрес">
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
            <div class="col-12">
                <div class="map-controls">
                    <div class="row">
                        <div class="col-12">
                            <ul class="map-controls__list">
                                <li>из аэропорта</li>
                                <li class="map-controls__list_active">с ж/д вокзала</li>
                                <li>с автовокзала</li>
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <ul class="map-controls__list map-controls__list_sublist">
                                <li>на такси</li>
                                <li class="map-controls__list_active">пешком</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="map">
    </div>
</div>
<!-- /.map -->
<div class="footer">
    <div class="container">
        <div class="row">
            <div class="col-xl-3">
                <div class="footer__block-logo">
                    <a href="/"><img src="/public/images/logo.svg" alt="VladPoint"></a>
                </div>
            </div>
            <div class="col-xl-3">
                <div class="footer__block-contacts">
                    <div class="caption">Телефон для бронирования</div>
                    <div class="phone">8 800 235 35 72</div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="footer__block-navbar">
                    <nav class="navbar navbar-toggleable-md navbar-inverse navbar_footer">
                        <div class="navbar-collapse">
                            <ul class="navbar-nav mr-auto">
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Гостиница</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Номера и цены</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Новости</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Контакты</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link nav-link__booking" href="#">Бронирование</a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- modal -->
<div class="modal fade modal-booking" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-booking">
            <div class="modal-header">
                <h5 class="modal-title">Бронирование номера</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="carousel" class="carousel slide" data-ride="carousel">
                    <div class="row">
                        <div class="col-12">
                            <div class="carousel-inner" role="listbox">
                                <div class="carousel-item  active">
                                    <img class="d-block"
                                         alt=""
                                         src="/public/images/rooms/room1__1.jpg"
                                         style="height: 500px">
                                </div>
                                <div class="carousel-item">
                                    <img class="d-block"
                                         alt=""
                                         src="/public/images/rooms/room1__2.jpg"
                                         style="height: 500px">
                                </div>
                                <div class="carousel-item">
                                    <img class="d-block"
                                         alt=""
                                         src="/public/images/rooms/room1__3.jpg"
                                         style="height: 500px">
                                </div>
                            </div>
                            <a class="carousel-control-prev" href="#carousel" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carousel" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="modal-booking__desc col-12 col-md-6">
                            <h2>Удобства в номере</h2>
                            <ul>
                                <li>Двуспальная кровать</li>
                                <li>Удобное рабочее место</li>
                                <li>Кондиционер</li>
                                <li>Бесплатный Wi-Fi</li>
                                <li>Телевизор</li>
                                <li>Холодильник</li>
                                <li>Фен</li>
                                <li>Телевизор</li>
                            </ul>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="row">
                                <div class="form-group">
                                    <label for="Select">Количество гостей</label>
                                    <select class="form-control" id="Select">
                                        <option>1</option>
                                        <option>2</option>
                                        <option>3</option>
                                        <option>4</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label for="modalArrival">Заезд </label>
                                    <input id="modalArrival" type="date" value="2017-05-10" class="form-control"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label for="modalDepartureDate">Выезд </label>
                                    <input id="modalDepartureDate" type="date" value="2017-05-10" class="form-control"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary">Забронировать</button>
            </div>
        </div>
    </div>
</div>
<!-- modal -->
<!-- jQuery first, then Tether, then Bootstrap JS. -->
<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
</body>
</html>