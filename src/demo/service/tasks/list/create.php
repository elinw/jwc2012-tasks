<?php

class DemoServiceTasksListCreate extends JControllerBase
{
	public function execute()
	{
		$db = $this->app->getDatabase();
		$task = new DemoTask($db);

		$data = $this->app->input->getArray(
			array(
				'name' => 'string',
				'content' => 'string',
				'date_created' => 'string',
				'date_modified' => 'string',
				'state' => 'integer',
				'author' => 'string'
			)
		);
		$task->save($data);
		$this->app->setBody(json_encode($task));
	}
}
