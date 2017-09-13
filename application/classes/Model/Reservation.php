<?php

class Model_Reservation extends Kohana_Model
{
    const ROW_LIMIT = 20;
    public $types = [
        'booking' => 'Booking',
        'office' => 'Телефон',
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
                'order_id',
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
                $this->getBookingOrder($roomId, $arrivalAt, $departureAt),
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
     * @param DateTime $firstTime
     * @param int $limit
     * @return array
     */
    public function getSummaryTableData($firstTime = null, $limit = 30)
    {
        /** @var Model_Room $roomModel */
        $roomModel = Model::factory('Room');

        $this->checkSuccess();

        $data = [];
        $firstTime = $firstTime ?: new DateTime();
        $firstDate = clone $firstTime;

        for ($i = 0; $i < $limit; $i++) {
            foreach ($roomModel->findAll() as $room) {
                $sqlDate = $firstDate->format('Y') . '-' . $firstDate->format('m') . '-' . $firstDate->format('d');
                $info = DB::select(
                        'rr.*',
                        'r.title',
                        ['rs.name', 'status_name'],
                        ['rr.price', 'booking_price'],
                        [
                            DB::expr(
                                'IFNULL((' .
                                DB::select('price')
                                    ->from('reservations__reservation_prices')
                                    ->where('room_id', '=', DB::expr('r.id'))
                                    ->and_where('date_at', '=', $sqlDate)
                                    ->limit(1) .
                                '), r.price)'),
                            'price'
                        ]
                    )
                    ->from(['reservations__reservations', 'rr'])
                    ->join(['rooms__rooms', 'r'])
                    ->on('r.id', '=', 'rr.room_id')
                    ->join(['reservations__statuses', 'rs'])
                    ->on('rs.id', '=', 'rr.status_id')
                    ->where('r.id', '=', $room['id'])
                    ->and_where_open()
                        ->where('rr.arrival_at', '=', $sqlDate)
                        ->or_where_open()
                            ->where('rr.arrival_at', '<', $sqlDate)
                            ->and_where('rr.departure_at', '>', $sqlDate)
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

    /**
     * @param int $id
     * @param DateTime $firstDate
     * @param DateTime $lastDate
     * @return int
     */
    public function findRoomPriceByIdAndDate($id, DateTime $firstDate, DateTime $lastDate)
    {
        /** @var Model_Room $roomModel */
        $roomModel = Model::factory('Room');

        $roomData = $roomModel->findById($id);
        $price = (int)$roomData['price'];
        $reservationPrice = 0;
        $firstTime = clone $firstDate;
        $lastTime = clone $lastDate;

        while ($firstTime <= $lastTime) {
            $reservationPriceData = DB::select()
                ->from('reservations__reservation_prices')
                ->where('room_id', '=', $id)
                ->and_where('date_at', '=', $firstTime->format('Y-m-d'))
                ->limit(1)
                ->execute()
                ->current()
            ;

            if ($reservationPriceData && (!$reservationPrice || (int)$reservationPriceData['price'] < $reservationPrice)) {
                $reservationPrice = (int)$reservationPriceData['price'];
            }

            $firstTime->modify('+ 1 day');
        }

        return $reservationPrice ?: $price;
    }

    /**
     * @param int $roomId
     * @param DateTime $firstDate
     * @param DateTime $lastDate
     * @return int
     */
    public function findAllPeriodPrice($roomId, DateTime $firstDate, DateTime $lastDate)
    {
        /** @var Model_Room $roomModel */
        $roomModel = Model::factory('Room');

        $firstTime = clone $firstDate;
        $lastTime = clone $lastDate;

        if ($firstTime->getTimestamp() === $lastTime->getTimestamp()) {
            return $this->findRoomPriceByIdAndDate($roomId, $firstDate, $lastDate);
        }

        $roomData = $roomModel->findById($roomId);
        $price = (int)$roomData['price'];
        $amount = 0;

        while ($firstTime < $lastTime) {
            $reservationPriceData = DB::select()
                ->from('reservations__reservation_prices')
                ->where('room_id', '=', $roomId)
                ->and_where('date_at', '=', $firstTime->format('Y-m-d'))
                ->limit(1)
                ->execute()
                ->current()
            ;

            if ($reservationPriceData) {
                $amount += (int)$reservationPriceData['price'];
            } else {
                $amount += $price;
            }

            $firstTime->modify('+ 1 day');
        }

        return $amount;
    }

    /**
     * @param int $roomId
     * @param DateTime $firstDate
     * @param DateTime $lastDate
     * @param int $price
     */
    public function setPrice($roomId, DateTime $firstDate, DateTime $lastDate, $price)
    {
        $firstTime = clone $firstDate;
        $lastTime = clone $lastDate;

        while ($firstTime <= $lastTime) {
            DB::query(Database::INSERT,
                'INSERT INTO reservations__reservation_prices (`room_id`, `price`, `date_at`) 
                VALUES (:roomId, :price, :date)
                ON DUPLICATE KEY UPDATE `price` = :price
            ')
                ->parameters([
                    ':roomId' => $roomId,
                    ':price' => $price,
                    ':date' => $firstTime->format('Y-m-d')
                ])
                ->execute()
            ;

            $firstTime->modify('+ 1 day');
        }
    }

    /**
     * @param int $id
     * @return array|false
     */
    public function findById($id)
    {
        return DB::select()
            ->from('reservations__reservations')
            ->where('id', '=', $id)
            ->limit(1)
            ->execute()
            ->current()
        ;
    }

    /**
     * @param int $bookingId
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
     * @param int $price
     *
     * @return void
     */
    public function changeBooking($bookingId, $roomId, \DateTime $arrivalAt, \DateTime $departureAt, $phone, $name, $comment, $adult, $childrenTo2, $childrenTo6, $childrenTo12, $price)
    {
        DB::update('reservations__reservations')
            ->set([
                'order_id' => $this->getBookingOrder($roomId, $arrivalAt, $departureAt),
                'customer_phone' => $phone,
                'customer_name' => $name,
                'customer_comment' => $comment,
                'price' => $price,
                'arrival_at' => $arrivalAt->format('Y-m-d 00:00:00'),
                'departure_at' => $departureAt->format('Y-m-d 00:00:00'),
                'adult' => $adult,
                'children_to_2' => $childrenTo2,
                'children_to_6' => $childrenTo6,
                'children_to_12' => $childrenTo12
            ])
            ->where('id', '=', $bookingId)
            ->execute()
        ;

        //$this->setPrice($roomId, $arrivalAt, $departureAt, $price);
    }

    public function getBookingAmount($bookingId)
    {
        $bookingData = $this->findById($bookingId);
        return $this->findAllPeriodPrice((int)$bookingData['room_id'], new DateTime($bookingData['arrival_at']), new DateTime($bookingData['departure_at']));
    }

    /**
     * @param int $roomId
     * @param DateTime $arrivalAt
     * @param DateTime $departureAt
     * @return string
     */
    public function getBookingOrder($roomId, DateTime $arrivalAt, DateTime $departureAt)
    {
        return md5($roomId . $arrivalAt->format('Y-m-d H:i:s') . $departureAt->format('Y-m-d H:i:s'));
    }

    /**
     * @param string $orderId
     *
     * @return array|false
     */
    public function getAcquiringOrderData($orderId) {
        return DB::select()
            ->from('reservations__acquiring_data')
            ->where('order_id', '=', $orderId)
            ->limit(1)
            ->execute()
            ->current()
        ;
    }

    /**
     * @param string $orderId
     * @param string $acquiringOrderId
     *
     */
    public function setAcquiringOrderData($orderId, $acquiringOrderId) {
        DB::query(Database::INSERT,
            '
                  INSERT INTO reservations__acquiring_data (`order_id`, `acquiring_order_id`)
                  VALUES(:orderId, :acquiringOrderId)
                  ON DUPLICATE KEY UPDATE `acquiring_order_id` = :acquiringOrderId
                '
        )
            ->parameters([
                ':orderId' => $orderId,
                ':acquiringOrderId' => $acquiringOrderId
            ])
            ->execute()
        ;
    }
}