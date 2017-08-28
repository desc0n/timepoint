<?php

class Model_Reservation extends Kohana_Model
{
    const ROW_LIMIT = 20;
    public $types = [
        'booking' => 'Booking',
        'office' => 'Офис',
        'site' => 'Сайт',
    ];

    /**
     * @param int $roomId
     * @param string $phone
     * @param string $name
     * @param string $comment
     * @param DateTime $arrivalAt
     * @param DateTime $departureAt
     * @param int $adult
     * @param int $childrenTo2
     * @param int $childrenTo6
     * @param int $childrenTo12
     * @param string $type
     * @param int $price
     *
     * @return string
     */
    public function addReservation($roomId, \DateTime $arrivalAt, \DateTime $departureAt, $phone, $name, $comment, $adult, $childrenTo2, $childrenTo6, $childrenTo12, $type, $price = null)
    {
        /** @var Model_Mail $mailModel */
        $mailModel = Model::factory('Mail');

        /** @var Model_Room $roomModel */
        $roomModel = Model::factory('Room');

        $roomData = $roomModel->findById($roomId);

        DB::insert('reservations__reservations', [
                'type',
                'room_id',
                'customer_phone',
                'customer_name',
                'customer_comment',
                'price',
                'arrival_at',
                'departure_at',
                'status_id',
                'adult',
                'children_to_2',
                'children_to_6',
                'children_to_12',
                'created_at'
            ])
            ->values([
                $type,
                $roomId,
                $phone,
                $name,
                $comment,
                $price ?: $roomData['price'],
                $arrivalAt->format('Y-m-d H:i:s'),
                $departureAt->format('Y-m-d H:i:s'),
                1,
                $adult,
                $childrenTo2,
                $childrenTo6,
                $childrenTo12,
                DB::expr('NOW()')
            ])
            ->execute()
        ;

        if($type === 'site') {
            $message = '<div><strong>Номер: </strong>' . $roomData['title'] . '</div>';
            $message .= '<div><strong>Период бронирования: </strong>' . $arrivalAt->format('d.m.Y') . ' - ' . $departureAt->format('d.m.Y') . '</div>';
            $message .= '<div><strong>Клиент: </strong>' . $name . '</div>';
            $message .= '<div><strong>Номер телефона: </strong>' . $phone . '</div>';
            $message .= '<div><strong>Взрослых: </strong>' . $adult . '</div>';
            $message .= '<div><strong>Детей до 2 лет: </strong>' . $childrenTo2 . '</div>';
            $message .= '<div><strong>Детей до 6 лет: </strong>' . $childrenTo6 . '</div>';
            $message .= '<div><strong>Детей до 12 лет: </strong>' . $childrenTo12 . '</div>';
            $message .= '<div><strong>Комментарий: </strong>' . $comment . '</div>';
            $mailModel->send('site@vladpointhotel.ru', 'descon@bk.ru', 'Запрос на бронирование', $message);
            $mailModel->send('site@vladpointhotel.ru', 'vladpointhotel@mail.ru', 'Запрос на бронирование', $message);
            $mailModel->send('site@vladpointhotel.ru', 'pvr2569@mail.ru', 'Запрос на бронирование', $message);
        }

        return json_encode(['result' => 'success']);
    }

    /**
     * @param int $page
     * @return array
     */
    public function getList($page = 1)
    {
        return DB::select('rr.*', 'r.title', ['rs.name', 'status_name'])
            ->from(['reservations__reservations', 'rr'])
            ->join(['rooms__rooms', 'r'])
            ->on('r.id', '=', 'rr.room_id')
            ->join(['reservations__statuses', 'rs'])
            ->on('rs.id', '=', 'rr.status_id')
            ->limit(self::ROW_LIMIT)
            ->offset(($page - 1) * self::ROW_LIMIT)
            ->execute()
            ->as_array()
        ;
    }

    /**
     * @param string $date
     * @param string $format
     * @return false|string
     */
    public function formatDate($date, $format = 'd.m.Y')
    {
        return date($format, strtotime($date));
    }

    /**
     * @param DateTime $firstDate
     * @param int $limit
     * @return array
     */
    public function getSummaryTableData($firstDate = null, $limit = 30)
    {
        /** @var Model_Room $roomModel */
        $roomModel = Model::factory('Room');

        $this->checkSuccess();

        $data = [];
        $firstDate = $firstDate ?: new DateTime();

        for ($i = 0; $i < $limit; $i++) {
            foreach ($roomModel->findAll() as $room) {
                $info = DB::select('rr.*', 'r.title', ['rs.name', 'status_name'])
                    ->from(['reservations__reservations', 'rr'])
                    ->join(['rooms__rooms', 'r'])
                    ->on('r.id', '=', 'rr.room_id')
                    ->join(['reservations__statuses', 'rs'])
                    ->on('rs.id', '=', 'rr.status_id')
                    ->where('r.id', '=', $room['id'])
                    ->and_where_open()
                        ->where('rr.arrival_at', '=', $firstDate->format('Y') . '-' . $firstDate->format('m') . '-' . $firstDate->format('d'))
                        ->or_where('rr.departure_at', '=', $firstDate->format('Y') . '-' . $firstDate->format('m') . '-' . $firstDate->format('d'))
                        ->or_where_open()
                            ->where('rr.arrival_at', '<', $firstDate->format('Y') . '-' . $firstDate->format('m') . '-' . $firstDate->format('d'))
                            ->and_where('rr.departure_at', '>', $firstDate->format('Y') . '-' . $firstDate->format('m') . '-' . $firstDate->format('d'))
                        ->or_where_close()
                    ->and_where_close()
                    ->limit(1)
                    ->execute()
                    ->current()
                ;

                $data[$firstDate->format('Y')][$firstDate->format('m')][$firstDate->format('d')][$room['id']] = $info;
            }

            $firstDate->modify('+ 1 day');
        }

        return $data;
    }

    /**
     * @param int $id
     */
    public function canceledBooking($id)
    {
        DB::update('reservations__reservations')
            ->set(['status_id' => 3])
            ->where('id', '=', $id)
            ->execute()
        ;
    }

    /**
     * @return array
     */
    public function getStatuses()
    {
        return DB::select()
            ->from('reservations__statuses')
            ->execute()
            ->as_array('id', 'name')
        ;
    }

    private function checkSuccess()
    {
        DB::update('reservations__reservations')
            ->set(['status_id' => 2])
            ->where('status_id', '=', 1)
            ->and_where('departure_at', '<', DB::expr('NOW()'))
            ->execute()
        ;
    }
}