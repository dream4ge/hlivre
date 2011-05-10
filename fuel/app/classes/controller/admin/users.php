<?php

class Controller_Admin_Users extends Controller_Admin
{

	public function before()
	{
		parent::before();
	}

	public function action_index($filter = 'all', $offset = 0)
	{
		$group = ($filter === 'all') ? 'all' : Auth::group()->get_group($filter);
		$this->data['total_users'] = Model_User::count_users($group);

		Pagination::$current_page = $offset;
		Pagination::set_config(array(
			'pagination_url' => 'admin/users/index/'.$group.'/',
			'per_page' => 10,
			'total_items' => $this->data['total_users'],
			'num_links' => 3,
			'uri_segment' => 5
		));

		$this->title = 'Admin Users Index';

		$this->data['filter'] = $filter;
		$this->data['users'] =
				Model_User::get_users_by_group(
						$group, Pagination::$offset, Pagination::$per_page);
	}

	public function action_edit($id)
	{
		if (empty($id) || !$user = Model_User::find($id))
		{
			Response::redirect('admin/users');
		}

		$form = Model_User_Validation::edit($user);
        if ($form->validation()->run())
        {
			$user->username = $form->validated('username');
			$user->email = $form->validated('email');
			$user->group = $form->validated('group');

            if ($user->save())
			{
				Session::set_flash('success', 'User successfully updated.');
				Response::redirect('admin/users');
			}
			else
			{
				Session::set_flash('error', 'Something went wrong, please try again!');
			}

			Response::redirect('admin/users/edit/'.$user->id);
		}

		$this->title = 'Edit User - '.$user->username;
		$this->data['user'] = $user;
		$this->data['form'] = $form;


		/*
		  $this->template->title = 'Edit article';
		  $this->data['article'] = $article;
		  $this->data['form'] = $form;
		  $this->data['parts'] = Model_Part::find('all', array(
		  'where' => array(array('article_id', '=', $article->id)),
		  'order' => array('sort_order', 'asc')
		  ));
		 */

		/////////////////
		//$val = Validation::factory('edit_user');
		//$val->add('username')->add_rule('required');
		//$val->add('email')->add_rule('required');
		//$val->add('group')->add_rule('required');
		//if ($val->run()) {
//            $user->username = $val->validated('username');
//            $user->email = $val->validated('email');
//            $user->group = $val->validated('group');
	}

	/*
	 * public function action_edit($id)

	  {
	  if ($id === null) {
	  Response::redirect('admin/users');
	  }

	  if (!Input::post('edit')) {
	  $user = Model_User::find_by_id($id);
	  }

	  $val = Validation::factory('edit_user');
	  $val->add_model('Model_User');

	  $val->add('username_input', 'Username')
	  ->add_rule('required')
	  ->add_rule('min_length', 3)
	  ->add_rule('max_length', 20)
	  ->add_rule('trim')
	  ->add_rule('valid_string', array('alpha', 'numeric', 'dashes', 'dots'))
	  ->add_rule('unique', 'username');

	  $val->add('email_input', 'Email Address')
	  ->add_rule('required')
	  ->add_rule('min_length', 3)
	  ->add_rule('max_length', 80)
	  ->add_rule('trim')
	  ->add_rule('valid_email')
	  ->add_rule('unique', 'email');

	  $val->add('group_input', 'Group')
	  ->add_rule('required')
	  ->add_rule('trim');

	  if ($val->run())
	  {
	  $user->username = $val->validated('username_input');
	  $user->email 	= $val->validated('email_input');
	  $user->group 	= $val->validated('group_input');

	  if (Input::post('edit') && $user->save())
	  {
	  Session::set_flash('success', 'User successfully updated.');
	  Response::redirect('admin/users');
	  }
	  else
	  {
	  Session::set_flash('error', 'Something went wrong, please try again!');
	  }

	  Response::redirect('admin/users/edit/'.$user->id);
	  }else {
	  Session::set_flash('error', $val->show_errors());
	  }

	  $template = View::factory('template');
	  $template->title = 'Edit User - '.$user->username;
	  $template->content = View::factory('admin/users/edit')
	  ->set('user', $user, false)
	  ->set('val', Validation::instance('edit_user'), false);
	  $this->response->body($template);

	  }
	 *
	 */

	publiC function action_delete($id = null)
	{
		$user = Model_User::find($id);

		if ($user and $user->delete())
		{
			Session::set_flash('notice', 'User '.$user->username.
					' (#'.$id.') was successfully removed.');
		}
		else
		{
			Session::set_flash('error', 'Something went wrong, please try again!');
		}

		//TODO keep the same filter by checking the uri segment
		Response::redirect('admin/users');
	}

}

/* End of file users.php */