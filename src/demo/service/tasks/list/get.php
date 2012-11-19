<?php

class DemoServiceTasksListGet extends JControllerBase
{
	public function execute()
	{
		$db = $this->app->getDatabase();
		$taskList = new DemoTaskList(new JRegistry, $db);
		$data = $taskList->getList();
		
		foreach ($data as &$datum)
		{
			// Set this to a boolean for JavaScript
			settype($datum->state, 'boolean');
		}		

		$this->app->setBody(json_encode($data));
	}
}
