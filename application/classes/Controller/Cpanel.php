<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Cpanel extends Controller
{
    public function getBaseTemplate()
    {
        if (!Auth::instance()->logged_in('admin') && !Auth::instance()->logged_in('manager')) {
            HTTP::redirect('/cpanel/login');
        }

        return View::factory('cpanel/template')
            ->set('get', $_GET)
            ->set('post', $_POST)
            ;
    }

    public function action_index()
    {
        if (!Auth::instance()->logged_in('admin') && !Auth::instance()->logged_in('manager')) {
            HTTP::redirect('/cpanel/login');
        }

        if (Auth::instance()->logged_in() && isset($_POST['logout'])) {
            Auth::instance()->logout();
            HTTP::redirect('/');
        }

        $template = $this->getBaseTemplate();

        $template->content = View::factory('cpanel/index')
        ;

        $this->response->body($template);
    }

    public function action_login()
    {
        if (!Auth::instance()->logged_in() && isset($_POST['login'])) {
            Auth::instance()->login($this->request->post('username'), $this->request->post('password'),true);
            HTTP::redirect('/cpanel/summary_table');
        }

        $template = View::factory('cpanel/login')
            ->set('post', $this->request->post())
        ;

        $this->response->body($template);
    }

    public function action_logout()
    {
        if (Auth::instance()->logged_in() && isset($_POST['logout'])) {
            Auth::instance()->logout();

            HTTP::redirect('/');
        }
    }

    public function action_registration()
    {
        if (!Auth::instance()->logged_in('admin')) {
            HTTP::redirect('/cpanel');
        }

        $template = $this->getBaseTemplate();
        $template->content = View::factory('cpanel/registration')
            ->set('post', $this->request->post())
            ->set('error', '')
        ;

        if (count($this->request->post())) {
            if (empty(Arr::get($_POST,'username'))) {
                $template->set('error', '<div class="alert alert-danger"><strong>Не указан логин!</strong> Укажите Ваш логин.</div>');
            } elseif (empty(Arr::get($_POST,'email'))) {
                $template->set('error', '<div class="alert alert-danger"><strong>Не указана почта!</strong> Укажите Вашу почту.</div>');
            } elseif (Arr::get($_POST,'password','')=="") {
                $template->set('error', '<div class="alert alert-danger"><strong>Не указан пароль!</strong> Укажите Ваш пароль.</div>');
            } else if (Arr::get($_POST,'password') != Arr::get($_POST,'password2')) {
                $template->set('error', '<div class="alert alert-danger"><strong>Пароли не совпадают!</strong> Проверьте правильность подтверждения пароля.</div>');
            } else {
                $user = ORM::factory('User');
                $user->values(array(
                    'username' => $_POST['username'],
                    'email' => $_POST['email'],
                    'password' => $_POST['password'],
                    'password_confirm' => $_POST['password2'],
                ));
                $some_error = false;

                try {
                    $user->save();
                    $user->add("roles",ORM::factory("Role",1));
                    $user->add("roles",ORM::factory("Role",3));
                }
                catch (ORM_Validation_Exception $e) {
                    $some_error = $e->errors('models');
                }

                if ($some_error) {
                    $template->set('error', '<div class="alert alert-danger"><strong>Ошибка регистрационных данных!</strong> Проверьте правильность ввода данных.</div>');

                    if (isset($some_error['username'])) {
                        if ($some_error['username'] == "models/user.username.unique") {
                            $template->set('error', '<div class="alert alert-danger"><strong>Такой логин уже есть в базе!</strong> Придумайте новый логин.</div>');
                        }
                    }
                    else if (isset($some_error['email'])) {
                        if ($some_error['email']=="email address must be an email address") {
                            $template->set('error', '<div class="alert alert-danger"><strong>Некорректный формат почты!</strong> Проверьте правильность написания почты.</div>');
                        }
                        if ($some_error['email']=="models/user.email.unique") {
                            $template->set('error', '<div class="alert alert-danger"><strong>Такая почта есть в базе!</strong> Укажите другую почту.</div>');
                        }
                    }
                } else {
                    HTTP::redirect($this->request->referrer());
                }
            }
        }

        $this->response->body($template);
    }

    public function action_rooms_list()
    {
        /** @var $roomModel Model_Room */
        $roomModel = Model::factory('Room');

        $template = $this->getBaseTemplate();

        $template->content = View::factory('cpanel/rooms_list')
            ->set('roomsList', $roomModel->findAll(
                Arr::get($this->request->query(), 'page', 1),
                Arr::get($this->request->query(), 'limit', 20)
            ))
            ->set('roomsListCount', count($roomModel->findAll(0,0)))
            ->set('page', Arr::get($this->request->query(), 'page', 1))
        ;

        $this->response->body($template);
    }

	public function action_add_room()
	{
        /** @var $roomModel Model_Room */
        $roomModel = Model::factory('Room');

        $template = $this->getBaseTemplate();

        $template->content = View::factory('cpanel/add_room')
            ->set('get', $this->request->query())
        ;

        if ((int)$this->request->post('addRoom') === 1) {
            $id = $roomModel->setItem(
                null,
                (int)$this->request->post('category_id'),
                $this->request->post('title'),
                $this->request->post('description')
            );

            HTTP::redirect('/cpanel/redact_room/' . $id);
        }

		$this->response->body($template);
	}

	public function action_redact_room()
	{
        /** @var $roomModel Model_Room */
        $roomModel = Model::factory('Room');

        $template = $this->getBaseTemplate();
        
        $roomId = $this->request->param('id');
        $filename = Arr::get($_FILES, 'imgname', []);

        if ($roomId != '' && !empty($filename)) {
            $roomModel->loadRoomImg($_FILES, $roomId);

            HTTP::redirect($this->request->referrer());
        }

        if ((int)$this->request->post('redactRoom') === 1) {
            $roomModel->setRoomData(
                $roomId,
                $this->request->post('title'),
                (int)$this->request->post('guests_count'),
                (int)$this->request->post('price')
            );

            HTTP::redirect($this->request->referrer());
        }

        $template->content = View::factory('cpanel/redact_room')
            ->set('room', $roomModel->findById($roomId))
            ->set('roomId', $roomId)
            ->set('roomImgs', $roomModel->findImgsByRoomId($roomId))
            ->set('roomConveniences', $roomModel->findRoomConveniencesById($roomId))
            ->set('conveniencesList', $roomModel->getConveniences())
        ;

		$this->response->body($template);
	}

    public function action_contacts()
    {
        /** @var $contentModel Model_Content */
        $contentModel = Model::factory('Content');

        $template = $this->getBaseTemplate();

        if (!empty($this->request->post('addContact'))) {
            $contentModel->addContact($this->request->post('type'), $this->request->post('value'));
            HTTP::redirect($this->request->referrer());
        }

        if (!empty($this->request->post('updateContacts'))) {
            $contentModel->updateContacts($this->request->post());
            HTTP::redirect($this->request->referrer());
        }

        $template->content = View::factory('cpanel/contacts')
            ->set('contacts', $contentModel->getContacts())
        ;
        $this->response->body($template);
    }

    public function action_reservations_list()
    {
        /** @var $bookingModel Model_Booking */
        $bookingModel = Model::factory('Booking');

        $template = $this->getBaseTemplate();

        $template->content = View::factory('cpanel/reservations_list')
            ->set('listData', $bookingModel->getList(Arr::get($this->request->query(), 'page', 1)))
        ;

        $this->response->body($template);
    }

    public function action_news_list()
    {
        /** @var $contentModel Model_Content */
        $contentModel = Model::factory('Content');

        if ((int)$this->request->post('addNews') === 1) {
            $newsId = $contentModel->addNews();
            HTTP::redirect('cpanel/redact_news/' . $newsId);
        }

        $template = $this->getBaseTemplate();

        $template->content = View::factory('cpanel/news_list')
            ->set('newsList', $contentModel->getNewsList())
        ;

        $this->response->body($template);
    }

    public function action_redact_news()
    {
        /** @var $contentModel Model_Content */
        $contentModel = Model::factory('Content');

        $template = $this->getBaseTemplate();
        $id = (int)$this->request->param('id');

        if ((int)$this->request->post('updateNews') === 1) {
            $contentModel->updateNews($id, $this->request->post('title'), $this->request->post('content'));
            HTTP::redirect($this->request->referrer());
        }

        $template->content = View::factory('cpanel/redact_news')
            ->set('news', $contentModel->findNewsById($id))
        ;

        $this->response->body($template);
    }

    public function action_redact_page()
    {
        /** @var $contentModel Model_Content */
        $contentModel = Model::factory('Content');

        $template = $this->getBaseTemplate();
        $slug = $this->request->param('id');

        if (!empty($this->request->post('updateContent'))) {
            $contentModel->updatePageContent($slug, $this->request->post('content'));
            HTTP::redirect($this->request->referrer());
        }

        $template->content = View::factory('cpanel/redact_page')
            ->set('pageData', $contentModel->findPageBySlug($slug, 'ru'))
        ;
        $this->response->body($template);
    }

    public function action_social_networks()
    {
        /** @var $contentModel Model_Content */
        $contentModel = Model::factory('Content');

        $template = $this->getBaseTemplate();

        if (!empty($this->request->post('updateNetworks'))) {
            $contentModel->updateSocialNetworks($this->request->post());
            HTTP::redirect($this->request->referrer());
        }

        $template->content = View::factory('cpanel/social_networks')
            ->set('networks', $contentModel->getSocialNetworks())
        ;
        $this->response->body($template);
    }

    public function action_conveniences_list()
    {
        /** @var $roomModel Model_Room */
        $roomModel = Model::factory('Room');

        $template = $this->getBaseTemplate();

        if ((int)$this->request->post('addConvenience') === 1) {
            $roomModel->addConvenience($this->request->post('value'));
            HTTP::redirect($this->request->referrer());
        }

        if ((int)$this->request->post('updateConveniences') === 1) {
            $roomModel->updateConveniences($this->request->post());
            HTTP::redirect($this->request->referrer());
        }

        $template->content = View::factory('cpanel/convenience')
            ->set('conveniences', $roomModel->getConveniences())
        ;
        $this->response->body($template);
    }

    public function action_summary_table()
    {
        /** @var $roomModel Model_Room */
        $roomModel = Model::factory('Room');

        /** @var $bookingModel Model_Booking */
        $bookingModel = Model::factory('Booking');

        $template = $this->getBaseTemplate();

        $today = new \DateTime();
        $firstDate = new DateTime(date('Y-m-d', strtotime(Arr::get($this->request->query(), 'first_date', $today->format('d.m.Y')))));
        $lastDate = new DateTime(date('Y-m-d', strtotime(Arr::get($this->request->query(), 'last_date', $today->format('d.m.Y')))));
        $daysCount = round(($lastDate->getTimestamp() - $firstDate->getTimestamp()) / 86400) + 1;

        $template->content = View::factory('cpanel/summary_table')
            ->set('summaryTableData', $bookingModel->getSummaryTableData($firstDate, $daysCount))
            ->set('rooms', $roomModel->findAll())
            ->set('get', $this->request->query())
        ;
        $this->response->body($template);
    }
}