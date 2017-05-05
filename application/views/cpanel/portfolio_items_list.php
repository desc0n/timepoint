<?php
/** @var Model_Portfolio $portfolio */
$portfolio = Model::factory('Portfolio');
?>
<div class="row items-page">
    <div class="col-lg-12 form-group">
        <h3>Список проектов</h3>
    </div>
    <div class="col-lg-12 form-group">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Категория</th>
                <th>Название</th>
                <th class="text-center">Действия</th>
            </tr>
            </thead>
            <tbody>
            <?foreach ($itemsList as $item){?>
                <tr id="portfolioItemRow<?=$item['id'];?>">
                    <td><?=$item['category_name'];?></td>
                    <td><?=$item['title'];?></td>
                    <td class="text-center">
                        <a class="btn btn-success" href="/cpanel/redact_portfolio_item/<?=$item['id'];?>" target="_self">
                            <span class="fa fa-pencil"></span>
                        </a>
                        <button class="btn btn-danger" onclick="removePortfolioItem(<?=$item['id'];?>);">
                            <span class="fa fa-remove"></span>
                        </button>
                    </td>
                </tr>
            <?}?>
        </table>
    </div>
    <div class="col-lg-12 form-group">
        <ul class="pagination">
            <?for ($i = 1; $i <= ceil($itemsListCount / $portfolio->defaultLimit); $i++){?>
                <li <?=($i === (int)$page ? 'class="active"' : null);?>><a href="/cpanel/portfolio_items_list/?page=<?=$i;?>"><?=$i;?></a></li>
            <?}?>
        </ul>
    </div>
</div>