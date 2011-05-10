<?php

class Controller_Admin_Users extends Controller
{

	public function before() {
		parent::before();
	}

	public function action_index($show = 'all', $offset = 0) {
		$view = ViewModel::factory('Admin_Users', 'show_users');
		Pagination::$current_page = $offset;

		if ($show === 'admin' || $show == 100)
		{
			$view->group = 100;
			$view->show = 'admin';
		}
		else if ($show === 'regular' || $show == 1)
		{
			$view->group = 1;
			$view->show = 'regular';
		}
		else
		{
			$view->group = 'all';
			$view->show = 'all';
		}
	}

	public function action_edit($id) {

		if (empty($id) || !$user = Model_User::find($id))
		{
			Response::redirect('admin/users');
		}

		$form = Fieldset::factory('edit_user')
				->add_model('View_Admin_Users', $user, 'set_edit_form')
				->repopulate();

		if ($form->validation()->run())
		{
			if (View_Admin_Users::process_form($form, $user))
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

		$template = View::factory('template');
		$template->title = 'Edit User - '.$user->username;
		$template->content = View::factory('admin/users/edit')
				->set('user', $user, false)
				->set('form', $form, false)
				->set('val', Validation::instance('edit_user'), false);
		$this->response->body($template);

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

	publiC function action_delete($id = null) {
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

		Response::redirect('admin/users/index');
	}

}

/* End of file users.php */