<?php

/**
 * The Home Controller.
 *
 * A basic controller example.  Has examples of how to set the
 * response body and status.
 * 
 * @package  app
 * @extends  Controller
 */
class Controller_Home extends Controller_Template {

	/**
	 * The index action.
	 * 
	 * @access  public
	 * @return  void
	 */
	public function action_index()
	{
		$this->title = 'Welcome';
	}

	/**
	 * The 404 action for the application.
	 * 
	 * @access  public
	 * @return  void
	 */
	public function action_404()
	{
		$messages = array('Aw, no! Damn thing', 'Bloody Hell!', 'Uh Oh!', 'Nope, not here.', 'Huh?');
		$data['title'] = $messages[array_rand($messages)];

        // Set a HTTP 404 output header
        $this->title = 'Error 404 - '.$data['title'];
        $this->content = '404';
        $this->response->status = 404;        
	}
	
	//move this to an other controller??
	public function action_signup()
	{
        if (Auth::check())
        {
            Response::redirect('/');
        }
		
        $val = Validation::factory('signup_user');
		$val->add_model('Model_User');
		
		$val->add('username_input', 'Username')
			->add_rule('required')
			->add_rule('min_length', 3)
			->add_rule('max_length', 20)
			->add_rule('trim')
			->add_rule('valid_string', array('alpha', 'numeric', 'dashes', 'dots'))
			->add_rule('unique', 'user.username');
					
		$val->add('password_input', 'Password')
			->add_rule('required')
			->add_rule('min_length', 3)
			->add_rule('max_length', 20)
			->add_rule('trim')
			->add_rule('valid_string',
				array('alpha', 'numeric', 'spaces', 'punctuation', 'dashes'));

		$val->add('email_input', 'Email Address')
			->add_rule('required')
			->add_rule('min_length', 3)
			->add_rule('max_length', 80)
			->add_rule('trim')
			->add_rule('valid_email')
			->add_rule('unique', 'user.email');

        if ($val->run())
        {
            if (Auth::instance()->create_user(	$val->validated('username_input'),
												$val->validated('password_input'), 
												$val->validated('email_input'),
												1))
            {
                Session::set_flash('success', 'Thanks for registering!');

                Response::redirect('/');
            }
            else
            {
                throw new Exception('An unexpected error occurred. Please try again.');
            }
        }
		
		$this->template->title = 'Sign up';
		$this->template->content = View::factory('home/signup')
			->set('val', Validation::instance('signup_user'), false);
	}
	
    public function action_login()
	{
		if (Auth::check())
        {
            Response::redirect('/');
        }
		
		$val = Validation::factory('login_user');
        $val->add('username', 'Username')
			->add_rule('required')
			->add_rule('min_length', 3)
			->add_rule('max_length', 20)
			->add_rule('trim')
			->add_rule('valid_string', array('alpha', 'numeric', 'dashes', 'dots'));
			
		
		$val->add('password', 'Password')
			->add_rule('required')
			->add_rule('min_length', 3)
			->add_rule('max_length', 20)
			->add_rule('trim')
			->add_rule('valid_string',
				array('utf8', 'alpha', 'numeric', 'spaces', 'punctuation', 'dashes'));
			
        if ($val->run())
        {
            if (Auth::instance()->login($val->validated('username'), $val->validated('password')))
            {
                Response::redirect('home');
            }
			else
			{
				Session::set_flash('error', 'Incorrect username or password.');
				
				Response::redirect('home/login');
			}
        }

        $this->template->title = 'Login';
		$this->template->content = View::factory('home/login')
			->set('val', Validation::instance('login_user'), false);
	}

	public function action_logout()
	{
		Auth::instance()->logout();

        Response::redirect('home');
	}
}

/* End of file welcome.php */

	/**
	 * The 404 action for the application.
	 * 
	 * @access  public
	 * @return  void
	 */
/*	public function action_404()
	{
		$messages = array('Aw, crap!', 'Bloody Hell!', 'Uh Oh!', 'Nope, not here.', 'Huh?');
		$data['title'] = $messages[array_rand($messages)];

		// Set a HTTP 404 output header
		$this->response->status = 404;
		$this->response->body = View::factory('welcome/404', $data);
	}
*/