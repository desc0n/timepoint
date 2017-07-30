<?php

class Model_Reservation extends Kohana_Model
{
    /**
     * @param int $roomId
     * @param string $phone
     * @param string $name
     * @param string $comment
     * @param DateTime $arrivalAt
     * @param DateTime $departureAt
     *
     * @return string
     */
    public function addReservation($roomId, \DateTime $arrivalAt, \DateTime $departureAt, $phone, $name, $comment)
    {
        DB::insert('reservations__reservations', [
                'room_id',
                'customer_phone',
                'customer_name',
                'customer_comment',
                'arrival_at',
                'departure_at',
                'status_id',
                'created_at'
            ])
            ->values([
                $roomId,
                $phone,
                $name,
                $comment,
                $arrivalAt->format('Y-m-d H:i:s'),
                $departureAt->format('Y-m-d H:i:s'),
                1,
                DB::expr('NOW()')
            ])
            ->execute()
        ;

        return json_encode(['result' => 'success']);
    }
}