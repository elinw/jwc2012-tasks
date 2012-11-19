<?php

class DemoServiceTasksDelete extends JControllerBase
{
	public function execute()
	{
		$db = $this->app->getDatabase();
		$task = new DemoTask($db);
		if (!$task->load($this->app->input->get->getInt('task_id')))
		{
			throw new RuntimeException('Invalid task');
		}

		if (!$task->delete())
		{
			throw new RuntimeException('Failed to delete account: ' . $task->getError());
		}

		$this->app->setBody(json_encode($task));
	}
}
