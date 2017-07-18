<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Ajax extends Controller
{
    /** @var Model_Content */
    private $contentModel;

    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
        $this->contentModel = Model::factory('Content');
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
}
