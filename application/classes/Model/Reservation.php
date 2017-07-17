<?php

class Model_Reservation extends Kohana_Model
{
    /**
     * @param int $roomId
     * @param DateTime $arrivalAt
     * @param DateTime $departureAt
     */
    public function addReservation($roomId, \DateTime $arrivalAt, \DateTime $departureAt)
    {
        DB::insert('reservations__reservations', ['room_id', 'arrival_at', 'departure_at', 'status_id', 'created_at'])
            ->values([$roomId, $arrivalAt->format('Y-m-d H:i:s'), $departureAt->format('Y-m-d H:i:s'), 1, DB::expr('NOW()')])
            ->execute()
        ;

    }
}