<?php

class DemoServiceTasksListGet extends JControllerBase
{
	public function execute()
	{
		$taskList = new DemoTaskList(new JRegistry, $this->app->getDatabase());
		$data = $taskList->getList();

		$this->app->setBody(json_encode($data));
	}
}
