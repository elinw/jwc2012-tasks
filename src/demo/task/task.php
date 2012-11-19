<?php


class DemoTask extends JTable
{
	public function __construct(&$db)
	{
		parent::__construct('#__tasks', 'task_id', $db);
	}

	public function bind($src, $ignore = array())
	{
		$result = parent::bind($src, $ignore);

		if ($result)
		{
			// Convert state to a boolean for JavaScript
			$this->state = isset($this->state) ? (bool) $this->state : false;
		}

		return $result;
	}

	public function check()
	{
		$now = new JDate;
		if (!isset($this->date_created) || empty($this->date_created))
		{
			$this->date_created = $now->toSql();
		}
		$this->date_modified = $now->toSql();

		return parent::check();
	}
}