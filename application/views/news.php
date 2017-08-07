<?
$month = [
    1 => 'ЯНВ',
    2 => 'ФЕВ',
    3 => 'МАР',
    4 => 'АПР',
    5 => 'МАЙ',
    6 => 'ЮИН',
    7 => 'ИЮЛ',
    8 => 'АВГ',
    9 => 'СЕН',
    10 => 'ОКТ',
    11 => 'НОЯ',
    12 => 'ДЕК',
];
?>
<div class="row">
    <div class="col-md-12 blog-wrapper clearfix">
        <h2 class="bordered light blog-wrapper-title"><span>Новости</span></h2>
        <?foreach ($newsList as $news) {if ((boolean)$news['published']) {?>
            <?$createdAt = new DateTime($news['created_at']);?>
        <div class="blog-item blog-full-width">
            <div class="blog-thumbnail"></div>
            <div class="blog-full-width-date">
                <p class="day"><?=$createdAt->format('d');?></p><p class="monthyear"><?=$month[$createdAt->format('n')];?> <?=$createdAt->format('Y');?></p>
                <a href="#."></a>
            </div>
            <div class="blog-content">
                <h4 class="blog-title"><?=$news['title'];?></h4>
                <div class="blog-body"><?=$news['content'];?></div>
            </div>
        </div>
        <?}}?>
    </div>
</div>