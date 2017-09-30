<?php

/**
 * Class Model_Admin
 */
class Model_Admin extends Kohana_Model
{

	private $user_id;

    public function __construct()
    {
        date_default_timezone_set('Asia/Vladivostok');
		if (Auth::instance()->logged_in()) {
			$this->user_id = Auth::instance()->get_user()->id;
		} else {
			$this->user_id = 0;
		}
		DB::query(Database::UPDATE, "SET time_zone = '+10:00'")->execute();
	}

	public function setPage($params = [])
	{
		$id = Arr::get($params, 'redactpage', 0);

		DB::update('pages__pages')
			->set([
				'main_content' => Arr::get($params, 'main_content'),
				'secondary_content' => Arr::get($params, 'secondary_content')
			])
			->where('id', '=', $id)
			->execute()
		;
	}

    public function setContacts($data)
    {
        DB::update('contacts__contacts')
            ->set([
                'value' => $data['phone'],
            ])
            ->where('name', '=', 'phone')
            ->execute()
        ;

        DB::update('contacts__contacts')
            ->set([
                'value' => $data['email'],
            ])
            ->where('name', '=', 'email')
            ->execute()
        ;

        DB::update('contacts__contacts')
            ->set([
                'value' => $data['address'],
            ])
            ->where('name', '=', 'address')
            ->execute()
        ;

        DB::update('contacts__contacts')
            ->set([
                'value' => $data['about'],
            ])
            ->where('name', '=', 'about')
            ->execute()
        ;
    }

    public function changePassword($userId, $password)
    {
        $hashPassword = Auth::instance()->hash($password);
        DB::update('users')
            ->set(['password' => $hashPassword])
            ->where('id', '=', $userId)
            ->execute()
        ;
    }
}
?>