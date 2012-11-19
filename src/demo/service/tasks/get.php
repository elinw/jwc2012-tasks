<?php

class DemoServiceTasksGet extends JControllerBase
{
	public function execute()
	{
		$db = $this->app->getDatabase();
		$task = new DemoTask($db);
		if (!$task->load($this->app->input->get->getInt('task_id')))
		{
			throw new RuntimeException('Invalid task');
		}

		$this->app->setBody(json_encode($task));
	}
}
