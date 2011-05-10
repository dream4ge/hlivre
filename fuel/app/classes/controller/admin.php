<?php

class Controller_Admin extends Controller_Template
{
	protected $user_id = null;
	//TODO
	//protected $user_group = null;

	public function before()
	{
		parent::before();

		$user_groups = Auth::get_groups();
		if ($user_groups &&
				Auth::acl()->has_access(
						array(	'website'  =>	array('create', 'read', 'update', 'delete'),
								'admin'    =>	array('create', 'read', 'update', 'delete')),
						$user_groups[0]))
		{
			$user = Auth::instance()->get_user_id();

            $this->user_id = $user[1];
			//TODO
			//$this->user_group = ...
		}
		else
		{
			Response::redirect('/');
		}

	}

}