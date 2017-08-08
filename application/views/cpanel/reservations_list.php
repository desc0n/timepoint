<?php
/** @var $reservationModel Model_Reservation */
$reservationModel = Model::factory('Reservation');
?>
<div class="row">
    <div class="col-lg-12">
        <h2 class="page-header">Список броней</h2>
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="form-group">
    <div class="col-lg-12">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th class="text-center">Номер</th>
                <th class="text-center">Период бронирования</th>
                <th class="text-center">Оплачен</th>
                <th class="text-center">Имя клиента</th>
                <th class="text-center">Телефон клиента</th>
                <th class="text-center">Комментарий клиента</th>
                <th class="text-center">Кол-во детей до 2 лет</th>
                <th class="text-center">Кол-во детей до 6 лет</th>
                <th class="text-center">Кол-во детей до 12 лет</th>
                <th class="text-center">Статус</th>
                <th class="text-center">Действия</th>
            </tr>
            </thead>
            <tbody>
            <?foreach ($listData as $data) {?>
                <tr>
                    <td><?=$data['title'];?></td>
                    <td><?=$reservationModel->formatDate($data['arrival_at']);?> - <?=$reservationModel->formatDate($data['departure_at']);?></td>
                    <td><?=((boolean)$data['payed'] ? 'оплачено' : 'не оплачено');?></td>
                    <td><?=$data['customer_name'];?></td>
                    <td><?=$data['customer_phone'];?></td>
                    <td><?=$data['customer_comment'];?></td>
                    <td class="text-center"><?=$data['children_to_2'];?></td>
                    <td class="text-center"><?=$data['children_to_6'];?></td>
                    <td class="text-center"><?=$data['children_to_12'];?></td>
                    <td><?=$data['status_name'];?></td>
                    <td>

                    </td>
                </tr>
            <?}?>
            </tbody>
        </table>
    </div>
</div>