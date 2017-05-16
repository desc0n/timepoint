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

    /**
     * @param array $filesGlobal
     * @param int $itemId
     */
    public function loadRoomImg($filesGlobal, $itemId)
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
                ->values([$itemId])
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
}