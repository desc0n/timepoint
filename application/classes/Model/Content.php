<?php

/**
 * Class Model_Content
 */
class Model_Content extends Kohana_Model
{
    private $contactTypes = ['address' => 'Адрес', 'phone' => 'Телефон', 'email' => 'E-mail'];

    /**
     * @return array
     */
    public function getContactTypes()
    {
        return $this->contactTypes;
    }

    public function getBaseTemplate($slug)
    {
        return View::factory('template')
            ->set('content', Arr::get($this->findPageBySlug($slug), 'content'))
            ->set('googlePlusNetwork', $this->getSocialNetworks('google+'))
            ->set('twitterNetwork', $this->getSocialNetworks('twitter'))
            ->set('facebookNetwork', $this->getSocialNetworks('facebook'))
        ;
    }

    /**
     * @param string $slug
     * 
     * @return array
     */
    public function findPageBySlug($slug = '')
    {
        /** @var $roomModel Model_Room */
        $roomModel = Model::factory('Room');

        switch ($slug) {
            case 'main':
                return [
                    'content' => View::factory('main')
                        ->set('rooms',
                            $roomModel->findRomsOnMainPage(
                                (int)Arr::get($_GET, 'guest_count'),
                                new DateTime(date('Y-m-d H:i:s', strtotime(Arr::get($_GET, 'arrival_date')))),
                                new DateTime(date('Y-m-d H:i:s', strtotime(Arr::get($_GET, 'departure_date'))))
                            )
                        )
                        ->set('conveniencesList', $roomModel->getConveniences())
                ];
            default:
                return DB::select()
                    ->from('content__page')
                    ->where('slug', '=', $slug)
                    ->limit(1)
                    ->execute()
                    ->current()
                ;
        }
    }

    /**
     * @param string $slug
     * @param string $content
     */
    public function updatePageContent($slug, $content)
    {
        DB::update('content__page')
            ->set(['content' => $content])
            ->where('slug', '=', $slug)
            ->execute()
        ;
    }

    /**
     * @param null|array $type
     * @return array
     */
    public function getContacts($type = null)
    {
        $query = DB::select()
            ->from('content__contacts')
            ->where('', '', 1)
        ;

        $query = $type !== null ? $query->and_where('type', 'IN', $type) : $query;

        return $query->execute()->as_array();
    }

    /**
     * @param string $type
     * @param string $value
     *
     * @return bool
     */
    public function addContact($type, $value)
    {
        if (!array_key_exists($type, $this->getContactTypes())) {
            return false;
        }

        DB::insert('content__contacts', ['type', 'value'])
            ->values([$type, $value])
            ->execute()
        ;

        return true;
    }

    /**
     * @param array $params
     */
    public function updateContacts($params)
    {
        $ids = Arr::get($params, 'ids', []);
        $types = Arr::get($params, 'types', []);
        $values = Arr::get($params, 'values', []);

        foreach ($ids as $key => $id) {
            DB::update('content__contacts')
                ->set(['type' => $types[$key], 'value' => $values[$key]])
                ->where('id', '=', $id)
                ->execute()
            ;
        }
    }

    /**
     * @param int $id
     */
    public function removeContact($id)
    {
        DB::delete('content__contacts')
            ->where('id', '=', $id)
            ->execute()
        ;
    }

    /**
     * @return array
     */
    public function findAllServices()
    {
        return DB::select()
            ->from('content__services')
            ->execute()
            ->as_array()
            ;
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function findServiceById($id)
    {
        return DB::select()
            ->from('content__services')
            ->where('id', '=', $id)
            ->limit(1)
            ->execute()
            ->current()
        ;
    }

    /**
     * @param string $title
     * @param string $description
     *
     * @return bool
     */
    public function addService($title, $description)
    {
        DB::insert('content__services', ['title', 'description'])
            ->values([$title, $description])
            ->execute()
        ;

        return true;
    }

    /**
     * @param int $id
     * @param string $title
     * @param string $description
     */
    public function updateService($id, $title, $description)
    {
        DB::update('content__services')
            ->set(['title' => $title, 'description' => $description])
            ->where('id', '=', $id)
            ->execute()
        ;
    }

    /**
     * @param int $id
     */
    public function removeService($id)
    {
        DB::delete('content__services')
            ->where('id', '=', $id)
            ->execute()
        ;
    }

    /**
     * @param array $files
     * @param int $id
     */
    public function loadServiceImg($id, $files)
    {
        $type = mb_strrchr ($files['imgname']['name'], '.', false);
        $fileName = 'public/img/services/' . $id . $type;

        if (copy($files['imgname']['tmp_name'], $fileName))	{
            $image = Image::factory($fileName);
            $image
                ->resize(500, NULL)
                ->save($fileName,100)
            ;
        }
    }

    /**
     * @param null|array $type
     * @return array
     */
    public function getSocialNetworks($type = null)
    {
        $query = DB::select()
            ->from('content__social_networks')
            ->where('', '', 1)
        ;

        $query = $type !== null ? $query->and_where('type', '=', $type) : $query;
        $query = $type !== null ? $query->limit(1) : $query;

        return $query = $type !== null ? $query->execute()->current() : $query->execute()->as_array();
    }

    /**
     * @param array $params
     */
    public function updateSocialNetworks($params)
    {
        $ids = Arr::get($params, 'ids', []);
        $values = Arr::get($params, 'values', []);

        foreach ($ids as $key => $id) {
            DB::update('content__social_networks')
                ->set(['value' => $values[$key]])
                ->where('id', '=', $id)
                ->execute()
            ;
        }
    }

}