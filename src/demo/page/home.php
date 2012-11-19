<?php

class DemoPageHome extends JControllerBase
{
	public function execute()
	{
		$this->app->setBody(file_get_contents(dirname(dirname(__DIR__)) . '/theme/index.phtml'));
	}
}
