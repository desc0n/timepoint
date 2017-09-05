<?php

/**
 * Class Model_Room
 */
class Model_Room extends Kohana_Model
{
    public $defaultLimit = 20;

    public $roomsGuests = [1 => 1, 2 => 2, 3 => 3, 4 => 4];

    /**
     * @param int $page
     * @param int $limit
     *
     * @return array
     */
    public function findAll($page = 1, $limit = 20)
    {
        $query = DB::select('*')
            ->from('rooms__rooms')
        ;

        $query = !empty($page) && !empty($limit) ? $query->offset((($page - 1) * $limit)) : $query;
        $query = !empty($limit) ? $query->limit($limit) : $query;

        return $query
            ->execute()
            ->as_array()
            ;
    }

    /**
     * @param int $id
     *
     * @return array|bool
     */
    public function findById($id)
    {
        return DB::select()
            ->from('rooms__rooms')
            ->where('id', '=', $id)
            ->execute()
            ->current()
        ;
    }

    /**
     * @param int $id
     * @param string $title
     * @param int $guestsCount
     * @param int $price
     */
    public function setRoomData($id, $title, $guestsCount, $price)
    {
        DB::update('rooms__rooms')
            ->set(['title' => $title, 'guests_count' => $guestsCount, 'price' => $price])
            ->where('id', '=', $id)
            ->execute()
        ;
    }

    /**
     * @param $id
     *
     * @return array
     */
    public function findImgsByRoomId($id)
    {
        return DB::select()
            ->from('rooms__imgs')
            ->where('room_id', '=', $id)
            ->execute()
            ->as_array()
            ;
    }

    /**
     * @param array $filesGlobal
     * @param int $roomId
     */
    public function loadRoomImg($filesGlobal, $roomId)
    {
        $filesData = [];

        foreach ($filesGlobal['imgname']['name'] as $key => $data) {
            $filesData[$key]['name'] = $filesGlobal['imgname']['name'][$key];
            $filesData[$key]['type'] = $filesGlobal['imgname']['type'][$key];
            $filesData[$key]['tmp_name'] = $filesGlobal['imgname']['tmp_name'][$key];
            $filesData[$key]['error'] = $filesGlobal['imgname']['error'][$key];
            $filesData[$key]['size'] = $filesGlobal['imgname']['size'][$key];
        }

        foreach ($filesData as $files) {
            $res = DB::insert('rooms__imgs', ['room_id'])
                ->values([$roomId])
                ->execute();

            $new_id = $res[0];

            $imageName = preg_replace("/[^0-9a-z.]+/i", "0", Arr::get($files,'name',''));
            $file_name = 'public/img/original/' . $new_id . '_' . $imageName;

            if (copy($files['tmp_name'], $file_name))	{
                $thumb_file_name = 'public/img/thumb/' . $new_id . '_' . $imageName;

                if (copy($files['tmp_name'], $thumb_file_name))	{
                    $thumb_image = Image::factory($thumb_file_name);
                    $thumb_image
                        ->resize(500, NULL)
                        ->save($thumb_file_name,100)
                    ;

                    DB::update('rooms__imgs')
                        ->set([
                            'src' => $new_id . '_' . $imageName
                        ])
                        ->where('id', '=', $new_id)
                        ->execute()
                    ;
                }
            }
        }
    }

    /**
     * @param int $id
     */
    public function removeImg($id)
    {
        DB::delete('rooms__imgs')->where('id', '=', $id)->execute();
    }

    /**
     * @param int $imgId
     * @param int $roomId
     * @param int $value
     */
    public function setMainRoomImg($imgId, $roomId, $value)
    {
        DB::update('rooms__imgs')
            ->set(['main' => 0])
            ->where('room_id', '=', $roomId)
            ->execute()
        ;

        DB::update('rooms__imgs')
            ->set(['main' => $value])
            ->where('id', '=', $imgId)
            ->execute()
        ;
    }

    /**
     * @param int $roomId
     * @return array
     */
    public function getMainImg($roomId)
    {
        return DB::select()
            ->from('rooms__imgs')
            ->where('main', '=', 1)
            ->and_where('room_id', '=', $roomId)
            ->limit(1)
            ->execute()
            ->current()
        ;
    }

    /**
     * @param int $roomId
     * @param int $convenienceId
     */
    public function addRoomConvenience($roomId, $convenienceId)
    {
         DB::insert('rooms__conveniences', ['room_id', 'convenience_id'])
            ->values([$roomId, $convenienceId])
            ->execute()
        ;
    }

    /**
     * @param int $roomId
     * @param int $convenienceId
     */
    public function removeRoomConvenience($roomId, $convenienceId)
    {
         DB::delete('rooms__conveniences')
            ->where('room_id', '=', $roomId)
            ->and_where('convenience_id', '=', $convenienceId)
            ->execute()
        ;
    }

    /**
     * @param int $id
     * @return array
     */
    public function findRoomConveniencesById($id)
    {
        return DB::select()
            ->from('rooms__conveniences')
            ->where('room_id', '=', $id)
            ->execute()
            ->as_array()
        ;
    }

    /**
     * @return array
     */
    public function getConveniences()
    {
        return DB::select()
            ->from('conveniences')
            ->execute()
            ->as_array('id', 'value')
        ;
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    public function addConvenience($value)
    {
        DB::insert('conveniences', ['value'])
            ->values([$value])
            ->execute()
        ;

        return true;
    }

    /**
     * @param array $params
     */
    public function updateConveniences($params)
    {
        $ids = Arr::get($params, 'ids', []);
        $values = Arr::get($params, 'values', []);

        foreach ($ids as $key => $id) {
            DB::update('conveniences')
                ->set(['value' => $values[$key]])
                ->where('id', '=', $id)
                ->execute()
            ;
        }
    }

    /**
     * @param int $id
     */
    public function removeConvenience($id)
    {
        DB::delete('conveniences')
            ->where('id', '=', $id)
            ->execute()
        ;
    }

    /**
     * @param int $roomId
     * @return array
     */
    public function getRoomData($roomId)
    {
        return [
            'room' => $this->findById($roomId),
            'room_main_img' => $this->getMainImg($roomId),
            'room_imgs' => $this->findImgsByRoomId($roomId),
            'room_conveniences' => $this->findRoomConveniencesById($roomId),
        ];
    }

    /**
     * @param DateTime|null $firstTime
     * @param DateTime|null $lastTime
     * @return array
     */
    public function findNotReservationRoomsByPeriod(\DateTime $firstTime = null, \DateTime $lastTime = null)
    {
        $rooms = [];
        $allRooms = $this->findAll();

        foreach ($allRooms as $room) {
            $firstDate = clone $firstTime;
            $lastDate = clone $lastTime;
            $reservationRoom =
                $firstDate === null || $lastDate === null
                ? false
                : $this->checkRoomReservationStatusByPeriod($room['id'], $firstDate, $lastDate)
            ;

            if (!$reservationRoom) {
                $rooms[] = $this->getRoomData((int)$room['id']);
            }
        }

        return $rooms;
    }

    /**
     * @param $roomId
     * @param DateTime $firstDate
     * @param DateTime $lastDate
     * @return mixed
     */
    public function checkRoomReservationStatusByPeriod($roomId, DateTime $firstDate, DateTime $lastDate)
    {
        while ($firstDate <= $lastDate) {
            $check = DB::select()
                ->from('reservations__reservations')
                ->where('room_id', '=', $roomId)
                ->and_where('status_id', '=', 1)
                ->where('arrival_at', '<=', $firstDate->format('Y-m-d H:i:s'))
                ->and_where('departure_at', '>', $firstDate->format('Y-m-d H:i:s'))
                ->limit(1)
                ->execute()
                ->current()
            ;

            if ($check) {
                return true;
            }

            $firstDate->modify('+ 1 day');
        }

        return false;
    }

    /**
     * @param int $guestsCount
     * @param DateTime|null $arrivalDate
     * @param DateTime|null $departureDate
     * @return array
     */
    public function findRoomsOnMainPage($guestsCount, \DateTime $arrivalDate = null, \DateTime $departureDate = null)
    {
        $notReservationRooms = $this->findNotReservationRoomsByPeriod($arrivalDate, $departureDate);

        foreach ($notReservationRooms as $key => $value) {
            if ((int)$value['room']['guests_count'] < $guestsCount) {
                unset($notReservationRooms[$key]);
            }
        }

        return $notReservationRooms;
    }
}