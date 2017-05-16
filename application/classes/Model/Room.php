<?php

/**
 * Class Model_Room
 */
class Model_Room extends Kohana_Model
{
    public $defaultLimit = 20;

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
}