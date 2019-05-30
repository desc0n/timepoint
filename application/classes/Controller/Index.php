<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Index extends Controller
{
    /** @var Model_Content */
    private $contentModel;

    /** @var  Model_Booking */
    private $bookingModel;

    /** @var  Model_Room */
    private $roomModel;

    /** @var  Model_Admin */
    private $adminModel;

    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
        $this->contentModel = Model::factory('Content');
        $this->bookingModel = Model::factory('Booking');
        $this->roomModel = Model::factory('Room');
        $this->adminModel = Model::factory('Admin');
    }

	public function action_index()
	{
        View::set_global('title', 'Главная');
        View::set_global('rootPage', 'main');

        if($this->request->query('paymentReturn')) {
            View::set_global('paymentReturn', $this->request->query('paymentReturn'));
        }

        if ($this->request->query('booking') === 'success' && $this->request->query('orderId')) {
            $extendedStatus = $this->contentModel->getOrderStatusExtended($this->request->query('orderId'));

            if ((int)Arr::get($extendedStatus, 'errorCode') === 0 && (int)Arr::get($extendedStatus, 'actionCode') === 0) {
                $amount = $extendedStatus['amount'] / 100;
                $roomId = null;
                $arrivalDate = null;
                $departureDate = null;
                $phone = null;
                $name = null;
                $email = '';
                $comment = '';
                $adult = 0;
                $childrenTo2 = 0;
                $childrenTo6 = 0;
                $childrenTo12 = 0;

                foreach ($extendedStatus['merchantOrderParams'] as $value) {
                    if ($value['name'] === 'roomId') $roomId = $value['value'];
                    if ($value['name'] === 'arrivalAt') $arrivalDate = new DateTime($value['value']);
                    if ($value['name'] === 'departureAt') $departureDate = new DateTime($value['value']);
                    if ($value['name'] === 'phone') $phone = $value['value'];
                    if ($value['name'] === 'name') $name = $value['value'];
                    if ($value['name'] === 'email') $email = $value['value'];
                    if ($value['name'] === 'comment') $comment = $value['value'];
                    if ($value['name'] === 'adult') $adult = $value['value'];
                    if ($value['name'] === 'childrenTo2') $childrenTo2 = $value['value'];
                    if ($value['name'] === 'childrenTo6') $childrenTo6 = $value['value'];
                    if ($value['name'] === 'childrenTo12') $childrenTo12 = $value['value'];
                }

                $this->bookingModel->addReservation(
                    $roomId,
                    $arrivalDate,
                    $departureDate,
                    $phone,
                    $name,
                    $email,
                    $comment,
                    $adult,
                    $childrenTo2,
                    $childrenTo6,
                    $childrenTo12,
                    'site',
                    $amount,
                    true
                );

                View::set_global('payment', 'success');
            }
        }

		$template = $this->contentModel->getBaseTemplate('main', 'ru');
        
		$this->response->body($template);
	}

	public function action_news()
	{
        /** @var $contentModel Model_Content */
        $contentModel = Model::factory('Content');

        View::set_global('title', 'Новости');
        View::set_global('rootPage', 'news');

		$template = $this->contentModel->getBaseTemplate('news', 'ru');

		$this->response->body($template);
	}

	public function action_info()
	{
        /** @var $contentModel Model_Content */
        $contentModel = Model::factory('Content');

        View::set_global('title', 'Информация');
        View::set_global('rootPage', 'info');

		$template = $this->contentModel->getBaseTemplate('info', 'ru');
        $template->content->set('pageInfo', $contentModel->getPageInfo());
		$this->response->body($template);
	}

	public function action_payment_form()
	{
        $formUrl = $this->contentModel->getPayedForm(
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
            (int)$this->request->post('childrenTo12')
        );
        HTTP::redirect($formUrl ?: '/?booking=fail');
	}

	public function action_canceled_booking()
	{
        $orderId = $this->request->param('orderId');
        $bookingData = $this->bookingModel->findBookingByOrderId($orderId);
        $link = '/';

        if ($bookingData) {
            $this->bookingModel->canceledBooking((int)$bookingData['id']);
            $acquiringOrderData = $this->bookingModel->getAcquiringOrderData($orderId);

            if ($acquiringOrderData && $acquiringOrderData['status'] === 'completed') {
                $link = '/?paymentReturn=' . $this->bookingModel->returnPayment($orderId);
            }
        }
        HTTP::redirect($link);
	}
}
