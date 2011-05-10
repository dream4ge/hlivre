<?php

class Controller_User extends Controller_Template {

	protected $user_id = null;
	//TODO
	//protected $user_group = null;
	
    public function before()
    {
        parent::before();

        if (Auth::check())
        {
            $user = Auth::instance()->get_user_id();

            $this->user_id = $user[1];
			//TODO
			//$this->user_group = ...
        }
		else
		{
			 Response::redirect('/home/login');
		}
    }

}