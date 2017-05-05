<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Index extends Controller
{
	public function action_index()
	{
        /** @var $contentModel Model_Content */
        $contentModel = Model::factory('Content');

        View::set_global('title', 'Главная');
        View::set_global('rootPage', 'main');

		$template = $contentModel->getBaseTemplate();
        
		$this->response->body($template);
	}
}