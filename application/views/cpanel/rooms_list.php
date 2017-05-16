<?php
/** @var $roomModel Model_Room */
$roomModel = Model::factory('Room');
?>
<div class="row rooms-page">
    <div class="col-lg-12 form-group">
        <h3>Список номеров</h3>
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
            <?foreach ($roomsList as $room){?>
                <tr id="portfolioItemRow<?=$room['id'];?>">
                    <td><?=$room['title'];?></td>
                    <td class="text-center">
                        <a class="btn btn-success" href="/cpanel/redact_room/<?=$room['id'];?>" target="_self">
                            <span class="fa fa-pencil"></span>
                        </a>
                        <button class="btn btn-danger" onclick="removePortfolioItem(<?=$room['id'];?>);">
                            <span class="fa fa-remove"></span>
                        </button>
                    </td>
                </tr>
            <?}?>
        </table>
    </div>
    <div class="col-lg-12 form-group">
        <ul class="pagination">
            <?for ($i = 1; $i <= ceil($roomsListCount / $roomModel->defaultLimit); $i++){?>
                <li <?=($i === (int)$page ? 'class="active"' : null);?>><a href="/cpanel/rooms_list/?page=<?=$i;?>"><?=$i;?></a></li>
            <?}?>
        </ul>
    </div>
</div>