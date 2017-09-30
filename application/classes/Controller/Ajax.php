<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Ajax extends Controller
{
    /** @var Model_Content */
    private $contentModel;

    /** @var  Model_Reservation */
    private $reservationModel;

    /** @var  Model_Room */
    private $roomModel;

    /** @var  Model_Admin */
    private $adminModel;

    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
        $this->contentModel = Model::factory('Content');
        $this->reservationModel = Model::factory('Reservation');
        $this->roomModel = Model::factory('Room');
        $this->adminModel = Model::factory('Admin');
    }

    public function action_remove_contact()
    {
        $this->contentModel->removeContact((int)$this->request->post('id'));

        $this->response->body(json_encode(['result' =>'success']));
    }

    public function action_remove_convenience()
    {
        /** @var $roomModel Model_Room */
        $roomModel = Model::factory('Room');

        $roomModel->removeConvenience((int)$this->request->post('id'));

        $this->response->body(json_encode(['result' =>'success']));
    }

    public function action_remove_room_img()
    {
        /** @var $roomModel Model_Room */
        $roomModel = Model::factory('Room');

        $roomModel->removeImg((int)$this->request->post('id'));

        $this->response->body(json_encode(['result' =>'success']));
    }

    public function action_set_main_room_img()
    {
        /** @var $roomModel Model_Room */
        $roomModel = Model::factory('Room');

        $roomModel->setMainRoomImg(
            (int)$this->request->post('imgId'),
            (int)$this->request->post('roomId'),
            (int)$this->request->post('value')
        );

        $this->response->body(json_encode(['result' =>'success']));
    }

    public function action_add_room_convenience()
    {
        /** @var $roomModel Model_Room */
        $roomModel = Model::factory('Room');

        $roomModel->addRoomConvenience(
            (int)$this->request->post('roomId'),
            (int)$this->request->post('value')
        );

        $this->response->body(json_encode(['result' =>'success']));
    }

    public function action_remove_room_convenience()
    {
        /** @var $roomModel Model_Room */
        $roomModel = Model::factory('Room');

        $roomModel->removeRoomConvenience(
            (int)$this->request->post('roomId'),
            (int)$this->request->post('convenienceId')
        );

        $this->response->body(json_encode(['result' =>'success']));
    }

    public function action_remove_news()
    {
        $this->contentModel->removeNews((int)$this->request->post('newsId'));

        $this->response->body(json_encode(['result' =>'success']));
    }

    public function action_show_reserve_modal()
    {
        $body = View::factory('reservation_modal')
            ->set('roomId', $this->request->post('roomId'))
            ->set('arrivalDate', date('Y-m-d', strtotime($this->request->post('arrivalDate'))))
            ->set('departureDate', date('Y-m-d', strtotime($this->request->post('departureDate'))))
            ->set('phone', $this->request->post('phone'))
            ->set('name', $this->request->post('name'))
            ->set('email', $this->request->post('email'))
            ->set('comment', preg_replace('/["\<\>]+/', '', $this->request->post('comment')))
            ->set('adult', $this->request->post('adult'))
            ->set('childrenTo2', $this->request->post('childrenTo2'))
            ->set('childrenTo6', $this->request->post('childrenTo6'))
            ->set('childrenTo12', $this->request->post('childrenTo12'))
        ;

        $this->response->body($body);
    }

    public function action_show_redact_booking_rorm()
    {
        $body = View::factory('cpanel/redact_booking')
            ->set('bookingId', $this->request->post('bookingId'))
        ;

        $this->response->body($body);
    }

    public function action_reserve_room()
    {
        $body = $this->reservationModel->addReservation(
            (int)$this->request->post('roomId'),
            new DateTime($this->request->post('arrivalDate')),
            new DateTime($this->request->post('departureDate')),
            $this->request->post('phone'),
            $this->request->post('name'),
            $this->request->post('email'),
            preg_replace('/["\<\>]+/', '', $this->request->post('comment')),
            (int)$this->request->post('adult'),
            (int)$this->request->post('childrenTo2'),
            (int)$this->request->post('childrenTo6'),
            (int)$this->request->post('childrenTo12'),
            $this->request->post('type'),
            $this->request->post('price')
        );

        $this->response->body($body);
    }

    public function action_check_room_reserve()
    {
        $check = $this->roomModel->checkRoomReservationStatusByPeriod(
            (int)$this->request->post('roomId'),
            new DateTime($this->request->post('arrivalDate')),
            new DateTime($this->request->post('departureDate')),
            (int)$this->request->post('bookingId')
        );

        $this->response->body(!$check ? 'free' : 'busy');
    }

    public function action_get_summary_table_data()
    {
        $data = $this->reservationModel->getSummaryTableData();

        $this->response->body(json_encode($data));
    }

    public function action_canceled_booking()
    {
        $this->reservationModel->canceledBooking((int)$this->request->post('reservationId'));

        $this->response->body(json_encode(['result' => 'success']));
    }

    public function action_change_password()
    {
        $this->adminModel->changePassword((int)$this->request->post('userId'), $this->request->post('password'));
        $this->response->body(json_encode(['result' => 'success']));
    }

    public function action_set_price()
    {
        $this->reservationModel->setPrice(
            (int)$this->request->post('roomId'),
            new DateTime($this->request->post('firstDate')),
            new DateTime($this->request->post('lastDate')),
            (int)$this->request->post('price')
        );
        $this->response->body(json_encode(['result' => 'success']));
    }

    public function action_set_manager_price()
    {
        $this->reservationModel->setManagerPrice(
            (int)$this->request->post('roomId'),
            new DateTime($this->request->post('firstDate')),
            new DateTime($this->request->post('lastDate')),
            (int)$this->request->post('price')
        );
        $this->response->body(json_encode(['result' => 'success']));
    }

    public function action_change_booking()
    {
        $this->reservationModel->changeBooking(
            (int)$this->request->post('bookingId'),
            (int)$this->request->post('roomId'),
            new DateTime($this->request->post('arrivalDate')),
            new DateTime($this->request->post('departureDate')),
            $this->request->post('phone'),
            $this->request->post('name'),
            preg_replace('/["\<\>]+/', '', $this->request->post('comment')),
            (int)$this->request->post('adult'),
            (int)$this->request->post('childrenTo2'),
            (int)$this->request->post('childrenTo6'),
            (int)$this->request->post('childrenTo12'),
            (int)$this->request->post('price'),
            $this->request->post('type')
        );

        $this->response->body(json_encode(['result' => 'success']));
    }

    public function action_check_phone()
    {
        $this->response->body((int)preg_match('/^\+[0-9]{11}+$/', $this->request->post('phone')));
    }

    public function action_check_email()
    {
        $this->response->body((int)preg_match('/^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/', $this->request->post('email')));
    }
}
