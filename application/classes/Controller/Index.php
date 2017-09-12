<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Index extends Controller
{
	public function action_index()
	{
        /** @var $contentModel Model_Content */
        $contentModel = Model::factory('Content');

        /** @var Model_Reservation $reservationModel */
        $reservationModel = Model::factory('Reservation');

        View::set_global('title', 'Главная');
        View::set_global('rootPage', 'main');

        if ($this->request->query('booking') === 'success' && $this->request->query('orderId')) {
            $extendedStatus = $contentModel->getOrderStatusExtended($this->request->query('orderId'));

            if ((int)Arr::get($extendedStatus, 'errorCode') === 0 && (int)Arr::get($extendedStatus, 'actionCode') === 0) {
                $amount = $extendedStatus['amount'] / 100;
                $roomId = null;
                $arrivalDate = null;
                $departureDate = null;
                $phone = null;
                $name = null;
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
                    if ($value['name'] === 'comment') $comment = $value['value'];
                    if ($value['name'] === 'adult') $adult = $value['value'];
                    if ($value['name'] === 'childrenTo2') $childrenTo2 = $value['value'];
                    if ($value['name'] === 'childrenTo6') $childrenTo6 = $value['value'];
                    if ($value['name'] === 'childrenTo12') $childrenTo12 = $value['value'];
                }

                $reservationModel->addReservation(
                    $roomId,
                    $arrivalDate,
                    $departureDate,
                    $phone,
                    $name,
                    $comment,
                    $adult,
                    $childrenTo2,
                    $childrenTo6,
                    $childrenTo12,
                    'site',
                    $amount
                );

                View::set_global('payment', 'success');
            }
        }

		$template = $contentModel->getBaseTemplate('main', 'ru');
        
		$this->response->body($template);
	}

	public function action_news()
	{
        /** @var $contentModel Model_Content */
        $contentModel = Model::factory('Content');

        View::set_global('title', 'Новости');
        View::set_global('rootPage', 'news');

		$template = $contentModel->getBaseTemplate('news', 'ru');

		$this->response->body($template);
	}

	public function action_payment_form()
	{
        /** @var $contentModel Model_Content */
        $contentModel = Model::factory('Content');

        $formUrl = $contentModel->getPayedForm(
            (int)$this->request->post('roomId'),
            new DateTime($this->request->post('arrivalDate')),
            new DateTime($this->request->post('departureDate')),
            $this->request->post('phone'),
            $this->request->post('name'),
            preg_replace('/["\<\>]+/', '', $this->request->post('comment')),
            (int)$this->request->post('adult'),
            (int)$this->request->post('childrenTo2'),
            (int)$this->request->post('childrenTo6'),
            (int)$this->request->post('childrenTo12')
        );
        HTTP::redirect($formUrl ?: '/?booking=fail');
	}
}
