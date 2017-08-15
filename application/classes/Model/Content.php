<?php

/**
 * Class Model_Content
 */
class Model_Content extends Kohana_Model
{
    private $templateWords = [
        'ru' => [
            'menu' => [
                'rooms_and_prices' => 'Номера и цены',
                'news' => 'Новости',
            ],
            'filter' => [
                'booking' => 'Бронирование',
                'quests' => 'Гости',
                'period' => 'Период',
                'show_free_rooms' => 'Показать свободные номера',
            ],
            'contacts' => [
                'contacts' => 'Контакты',
                'booking_phone' => 'Телефон для бронирования',
                'address' => 'Адрес',
            ]
        ],
        'en' => [
            'menu' => [
                'rooms_and_prices' => 'Rooms and prices',
                'news' => 'News',
            ],
            'filter' => [
                'booking' => 'Booking',
                'quests' => 'Quests',
                'period' => 'Period',
                'show_free_rooms' => 'Show free rooms',
            ],
            'contacts' => [
                'contacts' => 'Contacts',
                'booking_phone' => 'Booking phone',
                'address' => 'Address',
            ]
        ]
    ];
    private $contactTypes = ['address' => 'Адрес', 'phone' => 'Телефон', 'email' => 'E-mail'];

    /**
     * @return array
     */
    public function getContactTypes()
    {
        return $this->contactTypes;
    }

    /**
     * @return array
     */
    public function getTemplateWords()
    {
        return $this->templateWords;
    }

    /**
     * @param string $slug
     * @param string $language
     * @return View
     */
    public function getBaseTemplate($slug, $language)
    {
        return View::factory('template')
            ->set('content', Arr::get($this->findPageBySlug($slug), 'content'))
            ->set('googlePlusNetwork', $this->getSocialNetworks('google+'))
            ->set('twitterNetwork', $this->getSocialNetworks('twitter'))
            ->set('facebookNetwork', $this->getSocialNetworks('facebook'))
            ->set('templateWords', $this->templateWords[$language])
            ->set('get', $_GET)
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

        $queryArrivalDate = Arr::get($_GET, 'arrival_date');
        $queryDepartureDate = Arr::get($_GET, 'departure_date');

        switch ($slug) {
            case 'main':
                return [
                    'content' => View::factory('main')
                        ->set('rooms',
                            $roomModel->findRomsOnMainPage(
                                (int)Arr::get($_GET, 'guest_count'),
                                $queryArrivalDate === null ? null : new DateTime(date('Y-m-d H:i:s', strtotime($queryArrivalDate))),
                                $queryDepartureDate === null ? null : new DateTime(date('Y-m-d H:i:s', strtotime($queryDepartureDate)))
                            )
                        )
                        ->set('conveniencesList', $roomModel->getConveniences())
                        ->set('queryArrivalDate', $queryArrivalDate)
                        ->set('queryDepartureDate', $queryDepartureDate)
                        ->set('course', $this->getCurrencyCourse())
                        ->set('currency', mb_strtoupper(Arr::get($_GET, 'currency', 'rub')))
                ];
            case 'news':
                return [
                    'content' => View::factory('news')
                        ->set('newsList', $this->getNewsList())
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

    /**
     * @return  int
     */
    public function addNews()
    {
        $res = DB::insert('content__news', ['created_at'])
            ->values([DB::expr('NOW()')])
            ->execute()
        ;

        return $res[0];
    }

    /**
     * @param int $newsId
     */
    public function removeNews($newsId)
    {
        DB::delete('content__news')
            ->where('id', '=', $newsId)
            ->execute()
        ;
    }

    /**
     * @return  array
     */
    public function getNewsList()
    {
        return DB::select()
            ->from('content__news')
            ->execute()
            ->as_array()
        ;
    }

    /**
     * @return  array
     */
    public function findNewsById($id)
    {
        return DB::select()
            ->from('content__news')
            ->where('id', '=', $id)
            ->limit(1)
            ->execute()
            ->current()
        ;
    }

    /**
     * @param int $id
     * @param string $title
     * @param string $content
     */
    public function updateNews($id, $title, $content)
    {
        DB::update('content__news')
            ->set(['title' => $title, 'content' => $content, 'updated_at' => DB::expr('NOW()')])
            ->where('id', '=', $id)
            ->execute()
        ;
    }

    /**
     * @param array $get
     * @param bool $first
     * @param array $ignoreKeys
     * @return string
     */
    public function createQueryString(array $get, $first = true, $ignoreKeys = [])
    {
        $queryString = $first ? '?' : '&';
        $count = 0;

        foreach ($get as $key => $value) {
            if (in_array($key, $ignoreKeys)) {
                continue;
            }

            $queryString .= $key . '=' . $value . ($value === end($get) ? null : '&');
            $count++;
        }

        return $count ? $queryString : '';
    }

    public function getCurrencyCourse()
    {
        $course = 0;
        $content = file_get_contents('http://www.cbr.ru/scripts/XML_daily.asp');
        $xml = new SimpleXMLElement($content);
        $json_string = json_encode($xml);
        $currencyData = json_decode($json_string, TRUE);

        if (!Arr::get($currencyData, 'Valute')) {
            return $course;
        }

        foreach ($currencyData['Valute'] as $data) {
            if ($data['CharCode'] === 'USD') {
                $course = $data['Value'];
            }
        }

        return $course;
    }
}