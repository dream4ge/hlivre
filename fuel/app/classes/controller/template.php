<?php

abstract class Controller_Template extends Fuel\Core\Controller_Template {

	protected $title = '';
	protected $page_id = '';
	protected $content = '';
	protected $data = array();
	

	public function router($method = 'index', $args)
	{
		$full_method = 'action_'.$method;
		if ( ! method_exists($this, $full_method))
		{
			return $this->action_404();
		}

		$class_array = explode('_', get_class($this));
		unset($class_array[0]);
		$class_array = array_map("strtolower", $class_array);
		
		$this->page_id = implode('_', $class_array);
		$this->content = implode(DS, $class_array).DS.$method;
		
		return call_user_func_array(array($this, $full_method), $args);
	}

	public function after()
	{
		if ( ! empty($this->content))
		{
			$this->template->content = View::factory($this->content, $this->data)
				->set('title', $this->title, false)
				->set('page_id', $this->page_id, false);
		}

		parent::after();
	}

	public function action_404()
	{
		$messages = array('Aw, no! Damn thing', 'Bloody Hell!', 'Uh Oh!', 'Nope, not here.', 'Huh?');
		$this->title = $messages[array_rand($messages)];

        // Set a HTTP 404 output header
        $this->response->status = 404;
        $this->content = '404';
	}
}

/* End of file base.php */