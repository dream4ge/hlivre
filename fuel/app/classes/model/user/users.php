<?php

class View_Admin_Users extends ViewModel
{

	public $group = null;
	public $show = 'all';
	public $form;

	public function before()
	{
		$this->_template = View::factory('template');
	}

	public function show_users()
	{
		$this->_template->title = 'Users';
		$this->_template->content = View::factory('admin/users');
		$this->_template->content->show = $this->show;

		$total_users = Model_User::count_users($this->group);
		$this->_template->content->total_users = $total_users;

		Pagination::set_config(array(
			'pagination_url' => 'admin/users/index/'.$this->group.'/',
			'per_page' => 5,
			'total_items' => $total_users,
			'num_links' => 3,
			'uri_segment' => 5,
		));

		$this->_template->content->users = $this->get_users_by_group();
		$this->_template->content->form = $this->form;
	}


	/**
	 * Orm call to get all users from this group
	 * @return type array users from this group
	 */
	private function get_users_by_group()
	{
		if ($this->group === 'all')
		{
			return Model_User::find('all', array(
				'offset' => Pagination::$offset,
				'limit' => Pagination::$per_page
			));
		}
		else
		{
			return Model_User::find('all', array(
				'offset' => Pagination::$offset,
				'limit' => Pagination::$per_page,
				'where' => array(
					array('group', '=', $this->group)
				)
			));
		}
	}

	public static function set_edit_form(Fieldset $form, $user = null)
	{
		//$form->validation()->add_callable('Validation');
		$user_id = !empty($user) ? $user->id : '';

		$form->add('id', 'Id',
				array(	'type' => 'hidden',
						'value' => $user_id));

		$form->add('username', 'Username',
				array(	'type' => 'text',
						'value' => !empty($user) ? $user->username : ''),
				Model_User::_validation_rules('username', $user_id));

		$form->add('email', 'Email Address',
				array(	'type' => 'text',
						'value' => !empty($user) ? $user->email : ''),
				Model_User::_validation_rules('email', $user_id));

		Config::load('simpleauth', true);
		$group_select = Config::get('simpleauth.groups', array());
		foreach ($group_select as $key => $group)
		{
			$group_select[$key] = Inflector::singularize($group['name']);
		}

		$form->add('group', 'Group',
				array(	'type' => 'select',
						'options' => $group_select,
						'value' => !empty($user) ? $user->group : null),
				Model_User::_validation_rules('group', $user_id));

		$form->add('submit', null,
				array(	'type' => 'submit',
						'value' => 'Done'));

	}

	public static function process_form($form, $user)
	{
		$user->username = $form->validated('username');
		$user->email = $form->validated('email');
		$user->group = $form->validated('group');

		return $user->save();
	}

}