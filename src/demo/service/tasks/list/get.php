<?php

class DemoServiceTasksListGet extends JControllerBase
{
	public function execute()
	{
		$taskList = new DemoTaskList(new JRegistry, $this->app->getDatabase());
		$data = $taskList->getList();

		foreach ($data as &$datum)
		{
			// Set this to a boolean for JavaScript
			settype($datum->state, 'boolean');
		}

		$this->app->setBody(json_encode($data));
	}
}
