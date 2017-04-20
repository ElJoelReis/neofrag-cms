<?php if (!defined('NEOFRAG_CMS')) exit;
/**************************************************************************
Copyright © 2015 Michaël BILCOT & Jérémy VALENTIN

This file is part of NeoFrag.

NeoFrag is free software: you can redistribute it and/or modify
it under the terms of the GNU Lesser General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

NeoFrag is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

class Collection extends Library
{
	public $pagination;

	protected $_db;
	protected $_model;
	protected $_aggregates = [];

	public function __invoke($model = NULL)
	{
		if ($model)
		{
			if (!is_a($model, 'Model2'))
			{
				$model = parent::model2($model);
			}

			$this->_model = $model;
			$this->_db    = $model->db();
		}

		return $this->reset();
	}

	public function __call($name, $args)
	{
		$result = call_user_func_array([$this->_db ?: $this->load, $name], $args);
		return $this->_db && is_a($result, 'NeoFrag') ? $this : $result;
	}

	public function get()
	{
		$results = [];

		if ($this->pagination)
		{
			$this->pagination->limit();
		}

		foreach ($this->_db()->get() as $result)
		{
			$results[] = $this->_aggregate($result);
		}

		return $results;
	}

	public function row()
	{
		return $this->_aggregate($this->_db()->row());
	}

	public function aggregate($name, $value)
	{
		$this->_aggregates[$name] = $value;
		return $this;
	}

	public function paginate($page, $limit = 20)
	{
		$this->pagination = parent::__call('pagination', [$this->_db, $page, $limit]);
		return $this;
	}

	public function view($name)
	{
		return implode(array_map(function($a) use ($name){
			return parent::__call('view', [$name, [
				$this->_model->name => $a
			]]);
		}, $this->get()));
	}

	protected function _db()
	{
		$select = $this->_db->select() ?: ['_.*'];

		if ($this->_aggregates)
		{
			foreach ($this->_aggregates as $name => $value)
			{
				$select[] = $value.' AS `'.$name.'`';
			}

			$this->_db->group_by('_.id');
		}

		return call_user_func_array([$this->_db, 'select'], $select)->__invoke();
	}

	protected function _aggregate($data)
	{
		$object = $this->_model->read(NULL, $data);

		foreach ($this->_aggregates as $name => $value)
		{
			$object->$name = $data[$name];
		}

		return $object;
	}
}

/*
NeoFrag Alpha 0.1.7
./libraries/collection.php
*/