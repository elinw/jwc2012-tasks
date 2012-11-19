<?php

class DemoTaskList extends JModelDatabase
{
	public function getList()
	{
		$query = $this->db->getQuery(1);
		$query->select(
			array('task_id', 'name', 'date_created', 'date_modified', 'state', 'author')
		)->from('#__tasks');
		$this->db->setQuery($query);

		return $this->db->loadObjectList();
	}
}
