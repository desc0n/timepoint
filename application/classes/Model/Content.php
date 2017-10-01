<?php

/**
 * Class Model_Content
 */
class Model_Content extends Kohana_Model
{
    private $templateWords = [
        'ru' => [
            'currency' => [
                'rub' => 'руб.'
            ],
            'menu' => [
                'rooms_and_prices' => 'Номера и цены',
                'news' => 'Новости',
                'info' => 'Информация',
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
            ],
            'main' => [
                'detail' => 'Посмотреть',
                'booking_room' => 'Бронирование номера',
                'cost' => 'Стоимость',
                'rooms_comfort' => 'Удобства в номере',
                'booking_request' => 'Запрос на бронирование',
                'phone' => 'Ваш телефон',
                'specify_phone' => 'Укажите телефон в формате',
                'name' => 'Ваше имя',
                'email' => 'E-mail',
                'comment' => 'Комментарий',
                'adult' => 'Количество взрослых',
                'children_2' => 'Количество детей до 2 лет',
                'children_6' => 'Количество детей до 6 лет',
                'children_12' => 'Количество детей до 12 лет',
                'book_a_room' => 'Забронировать номер',
                'booking_period' => 'Период бронирования',
                'check' => 'Проверить',
                'price' => 'Цена',
            ]
        ],
        'en' => [
            'currency' => [
                'rub' => 'RUB'
            ],
            'menu' => [
                'rooms_and_prices' => 'Rooms and prices',
                'news' => 'News',
                'info' => 'Info',
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
            ],
            'main' => [
                'detail' => 'Detail',
                'booking_room' => 'Booking room',
                'cost' => 'Cost',
                'rooms_comfort' => 'Rooms comfort',
                'booking_request' => 'Booking request',
                'phone' => 'Your phone',
                'specify_phone' => 'Specify the phone in the format',
                'name' => 'Your name',
                'email' => 'E-mail',
                'comment' => 'Comment',
                'adult' => 'Number of adult',
                'children_2' => 'Number of children under 2 years',
                'children_6' => 'Number of children under 6 years',
                'children_12' => 'Number of children under 12 years',
                'book_a_room' => 'Book a room',
                'booking_period' => 'Booking period',
                'check' => 'Check',
                'price' => 'Price',
            ]
        ]
    ];
    private $contactTypes = ['address' => 'Адрес', 'phone' => 'Телефон', 'email' => 'E-mail'];

    /** @var array  */
    private $acquiringSettings;

    public function __construct()
    {
        date_default_timezone_set('Asia/Vladivostok');
        $this->acquiringSettings = Kohana::$config->load('acquiring')->as_array();
    }

    /**
     * @return array
     */
    public function getContactTypes()
    {
        return $this->contactTypes;
    }

    /**
     * @param string $language
     *
     * @return array
     */
    public function getTemplateWords($language)
    {
        return $this->templateWords[$language];
    }

    /**
     * @param string $slug
     * @param string $language
     * @return View
     */
    public function getBaseTemplate($slug, $language)
    {
        return View::factory('template')
            ->set('content', Arr::get($this->findPageBySlug($slug, $language), 'content'))
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
    public function findPageBySlug($slug = '', $language)
    {
        /** @var $roomModel Model_Room */
        $roomModel = Model::factory('Room');

        $today = new \DateTime();
        $queryArrivalDate = Arr::get($_GET, 'arrival_date');
        $arrivalDate = $queryArrivalDate === null ? null : new DateTime(date('Y-m-d H:i:s', strtotime($queryArrivalDate)));
        $queryDepartureDate = Arr::get($_GET, 'departure_date');
        $departureDate = $queryDepartureDate === null ? null : new DateTime(date('Y-m-d H:i:s', strtotime($queryDepartureDate)));
        $startDate = $arrivalDate < $today ? $today : $arrivalDate;
        $endDate = $departureDate < $today ? $today : $departureDate;

        switch ($slug) {
            case 'main':
                return [
                    'content' => View::factory('main')
                        ->set('rooms',
                            $roomModel->findRoomsOnMainPage(
                                (int)Arr::get($_GET, 'guest_count'),
                                $startDate,
                                $endDate
                            )
                        )
                        ->set('conveniencesList', $roomModel->getConveniences())
                        ->set('queryArrivalDate', $startDate->format('d.m.Y'))
                        ->set('queryDepartureDate', $endDate->format('d.m.Y'))
                        ->set('course', $this->getCurrencyCourse())
                        ->set('currency', mb_strtoupper(Arr::get($_GET, 'currency', 'rub')))
                        ->set('templateWords', $this->templateWords[$language])
                ];
            case 'news':
                return [
                    'content' => View::factory('news')
                        ->set('newsList', $this->getNewsList())
                ];
            case 'info':
                return [
                    'content' => View::factory('info')
                        ->set('templateWords', $this->templateWords[$language])
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

        if ($content) {
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
        }

        return $course;
    }

    /**
     * @param int $roomId
     * @param DateTime $arrivalAt
     * @param DateTime $departureAt
     * @param string $phone
     * @param string $name
     * @param string $comment
     * @param string $adult
     * @param string $childrenTo2
     * @param string $childrenTo6
     * @param string $childrenTo12
     *
     * @return string
     */
    public function getPayedForm(
        $roomId,
        DateTime $arrivalAt,
        DateTime $departureAt,
        $phone,
        $name,
        $comment,
        $adult,
        $childrenTo2,
        $childrenTo6,
        $childrenTo12
        )
    {
        /** @var Model_Reservation $reservationModel */
        $reservationModel = Model::factory('Reservation');

        $link = 'https://securepayments.sberbank.ru/payment/merchants/rbs/payment_ru.html?mdOrder=';
        $acquiringData = $reservationModel->getAcquiringOrderData($reservationModel->getBookingOrder($roomId, $phone, $arrivalAt, $departureAt));

        if($acquiringData) {
            return $link . $acquiringData['acquiring_order_id'];
        }

        $apiContent = $this->registerOrder($roomId, $arrivalAt, $departureAt, $phone, $name, $comment, $adult, $childrenTo2, $childrenTo6, $childrenTo12);

        if (!$apiContent || !empty($apiContent['errorCode'])) {
            return null;
        }

        $reservationModel->setAcquiringOrderData($reservationModel->getBookingOrder($roomId, $phone, $arrivalAt, $departureAt), $apiContent['orderId'], (int)($reservationModel->findAllPeriodPrice($roomId, $arrivalAt, $departureAt) * 100));
        return $link . $apiContent['orderId'];
    }

    public function registerOrder($roomId, DateTime $arrivalAt, DateTime $departureAt, $phone, $name, $comment, $adult, $childrenTo2, $childrenTo6, $childrenTo12)
    {
        /** @var Model_Reservation $reservationModel */
        $reservationModel = Model::factory('Reservation');

        $variables = [
            'amount' => $reservationModel->findAllPeriodPrice($roomId, $arrivalAt, $departureAt) * 100,
            'currency' => '',
            'language' => 'ru',
            'description' => '# ' . $roomId,
            'orderNumber' => $reservationModel->getBookingOrder($roomId, $phone, $arrivalAt, $departureAt),
            'jsonParams' => json_encode(['roomId' => $roomId, 'arrivalAt' => $arrivalAt->format('Y-m-d H:i:s'), 'departureAt' => $departureAt->format('Y-m-d H:i:s'), 'phone' => $phone, 'name' => $name, 'comment' => $comment, 'adult' => $adult, 'childrenTo2' => $childrenTo2, 'childrenTo6' => $childrenTo6, 'childrenTo12' => $childrenTo12])
        ];
        $response = $this->getSberbankRequest('https://securepayments.sberbank.ru/payment/rest/register.do', $variables);

        return $response === null ? null : json_decode($response, true);
    }

    /**
     * @param string $orderId
     * @return mixed|null
     */
    public function getOrderStatusExtended($orderId)
    {
        $variables = [
            'orderId' => $orderId,
        ];
        $response = $this->getSberbankRequest('https://securepayments.sberbank.ru/payment/rest/getOrderStatusExtended.do', $variables);

        return $response === null ? null : json_decode($response, true);
    }

    /**
     * @param string $orderId
     * @param int $amount
     * @return mixed|null
     */
    public function refundOrder($orderId, $amount)
    {
        $variables = [
            'orderId' => $orderId,
            'amount' => $amount,
        ];
        $response = $this->getSberbankRequest('https://securepayments.sberbank.ru/payment/rest/refund.do', $variables);

        return $response === null ? null : json_decode($response, true);
    }

    public function getSberbankRequest($url, array $variables)
    {
        $arguments = [
            'returnUrl' => 'http://' . $_SERVER['HTTP_HOST'] . '/?booking=success',
            'userName' => $this->acquiringSettings['userName'],
            'password' => $this->acquiringSettings['password'],
            'token' => $this->acquiringSettings['token']
        ];

        $params = $arguments + $variables;

        return $this->getCurlContent('GET', $url, $params);
    }

    public function getCurlContent($method, $url, $params)
    {
        $query = http_build_query($params);

        if ($method === 'POST') {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            $content = curl_exec ($ch);
            curl_close($ch);

            return $content;
        }

        $url .= '?' . $query;

        return file_get_contents($url);
    }
}