<?php defined('SYSPATH') or die('No direct script access.');

class Controller_En extends Controller
{
	public function action_index()
	{
        /** @var $contentModel Model_Content */
        $contentModel = Model::factory('Content');

        View::set_global('title', 'Main');
        View::set_global('rootPage', 'main');

		$template = $contentModel->getBaseTemplate('main', 'en');
        
		$this->response->body($template);
	}

	public function action_news()
	{
        /** @var $contentModel Model_Content */
        $contentModel = Model::factory('Content');

        View::set_global('title', 'Новости');
        View::set_global('rootPage', 'news');

		$template = $contentModel->getBaseTemplate('news');

		$this->response->body($template);
	}
}