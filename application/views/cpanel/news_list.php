<div class="row news-page">
    <div class="col-lg-12 form-group">
        <h3>Список новостей</h3>
    </div>
    <div class="col-lg-12 form-group">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Название</th>
                <th class="text-center">Действия</th>
            </tr>
            </thead>
            <tbody>
            <?foreach ($newsList as $news){?>
                <tr id="portfolioItemRow<?=$news['id'];?>">
                    <td><?=$news['title'];?></td>
                    <td class="text-center">
                        <a class="btn btn-success" href="/cpanel/redact_news/<?=$news['id'];?>" target="_self">
                            <span class="fa fa-pencil"></span>
                        </a>
                        <button class="btn btn-danger" onclick="">
                            <span class="fa fa-remove"></span>
                        </button>
                    </td>
                </tr>
            <?}?>
        </table>
    </div>
</div>