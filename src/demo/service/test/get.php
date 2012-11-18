<?php

class DemoServiceTestGet extends JControllerBase
{
	public function execute()
	{
		$this->app->setBody(json_encode(array('Hello' => 'World')));
	}
}
