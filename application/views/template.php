<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/public/css/styles.css" >
</head>
<body>
<div class="container">
    <nav class="navbar navbar-toggleable-md navbar-inverse">
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse"
                data-target="#navbar" aria-controls="navbar" aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="/"><img src="/public/images/logo.svg" alt="VladPoint"></a>

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
                    <a class="nav-link dropdown-toggle" id="dropdown1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">RU</a>
                    <div class="dropdown-menu" aria-labelledby="dropdown1">
                        <a class="dropdown-item" href="#">RU</a>
                        <a class="dropdown-item" href="#">EN</a>
                    </div>

                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="dropdown2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">RUB</a>
                    <div class="dropdown-menu" aria-labelledby="dropdown2">
                        <a class="dropdown-item" href="#">RUB</a>
                        <a class="dropdown-item" href="#">USD</a>
                    </div>

                </li>
                <li class="nav-item nav-item__contacts hidden-lg-down">
                    <img src="/public/images/point.png" alt="Адрес">
                    <div class="nav-link" >
                        <div class="nav-link__address">г. Владивосток, ул. Посьетская, 14
                        </div>
                        <div class="nav-link__phone">8 800 235 35 72</div>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</div>
<!-- /.navbar -->
<div id="carousel" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <li data-target="#carousel" data-slide-to="0" class="active"><img class="d-block img-fluid" src="/public/images/c1.png"></li>
        <li data-target="#carousel" data-slide-to="1" class=""><img class="d-block img-fluid" src="/public/images/c1.png"></li>
        <li data-target="#carousel" data-slide-to="2" class=""><img class="d-block img-fluid" src="/public/images/c1.png"></li>
        <li data-target="#carousel" data-slide-to="3" class=""><img class="d-block img-fluid" src="/public/images/c1.png"></li>
    </ol>
    <div class="carousel-inner" role="listbox">
        <div class="carousel-item  active">
            <img class="d-block img-fluid" src="/public/images/c1.png">
            <div class="carousel-caption d-none d-md-block">
                <h1>МИНИ ОТЕЛЬ В ДЕЛОВОМ ЦЕНТРЕ ВЛАДИВОСТОКА<br/> С ВИДОМ НА БУХТУ «ЗОЛОТОЙ РОГ»</h1>
                <img src="/public/images/logo.svg" alt="VladPoint">
            </div>
        </div>
        <div class="carousel-item">
            <img class="d-block img-fluid" src="/public/images/c1.png">
            <div class="carousel-caption d-none d-md-block">
                <h1>МИНИ ОТЕЛЬ В ДЕЛОВОМ ЦЕНТРЕ ВЛАДИВОСТОКА<br/> С ВИДОМ НА БУХТУ «ЗОЛОТОЙ РОГ»</h1>
                <img src="/public/images/logo.svg" alt="VladPoint">
            </div>
        </div>
        <div class="carousel-item">
            <img class="d-block img-fluid" src="/public/images/c1.png">
            <div class="carousel-caption d-none d-md-block">
                <h1>МИНИ ОТЕЛЬ В ДЕЛОВОМ ЦЕНТРЕ ВЛАДИВОСТОКА<br/> С ВИДОМ НА БУХТУ «ЗОЛОТОЙ РОГ»</h1>
                <img src="/public/images/logo.svg" alt="VladPoint">

            </div>
        </div>
        <div class="carousel-item">
            <img class="d-block img-fluid" src="/public/images/c1.png">
            <div class="carousel-caption d-none d-md-block">
                <h1>МИНИ ОТЕЛЬ В ДЕЛОВОМ ЦЕНТРЕ ВЛАДИВОСТОКА<br/> С ВИДОМ НА БУХТУ «ЗОЛОТОЙ РОГ»</h1>
                <img src="/public/images/logo.svg" alt="VladPoint">
            </div>
        </div>
    </div>

</div>
<!-- /.carousel -->
<div class="container">
    <div class="booking">
        <div class="row">
            <div class="col-12 col-md-3 booking__caption">Бронирование</div>
            <div class="col-12 col-md-6 booking__selects"></div>
            <div class="col-12 col-md-3 booking__action">
                <button type="button" class="btn btn-primary rooms__kind-caption-action">Показать номера</button>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="rooms">
        <div class="rooms__kind rooms__kind_a">
            <div class="rooms__kind-caption">
                <div class="rooms__kind-caption-price">от 2000 руб.</div>
                <button type="button" class="btn btn-primary rooms__kind-caption-action">Забронировать</button>
            </div>
        </div>
        <div class="rooms__kind rooms__kind_b">
            <div class="rooms__kind-caption">
                <div class="rooms__kind-caption-price">от 3000 руб.</div>
                <button type="button" class="btn btn-primary rooms__kind-caption-action">Забронировать</button>
            </div>
        </div>
        <div class="rooms__kind rooms__kind_c">
            <div class="rooms__kind-caption">
                <div class="rooms__kind-caption-price">от 4000 руб.</div>
                <button type="button" class="btn btn-primary rooms__kind-caption-action">Забронировать</button>
            </div>
        </div>
        <div class="rooms__kind rooms__kind_d">
            <div class="rooms__kind-caption">
                <div class="rooms__kind-caption-price">от 5000 руб.</div>
                <button type="button" class="btn btn-primary rooms__kind-caption-action">Забронировать</button>
            </div>
        </div>
    </div>
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
            <div class="col-4">
                <div class="contacts-block__caption">
                    Телефон бронирования
                </div>
                <div class="contacts-block__content">
                    8 800 235 35 72
                </div>
            </div>
            <div class="col-4">
                <div class="contacts-block__caption">
                    Стойка администратора
                </div>
                <div class="contacts-block__content">
                    8 423 73 62 44
                </div>
            </div>
            <div class="col-4">
                <div class="contacts-block__caption">
                    Директор
                </div>
                <div class="contacts-block__content">
                    8 423 235 82 31
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="contacts-block__address">
                    <img src="/public/images/point.png" alt="Адрес">
                    г. Владивосток, ул. Посьетская, 14
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.contacts -->
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
<!-- jQuery first, then Tether, then Bootstrap JS. -->
<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
</body>
</html>