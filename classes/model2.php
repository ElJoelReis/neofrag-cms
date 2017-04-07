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

abstract class Model2 extends NeoFrag
{
	static protected $_schemas = [];
	static protected $_objects = [];

	static protected function __schema()
	{
	}

	static protected function field()
	{
		return new Field;
	}

	protected $_id;
	protected $_load;
	protected $_model;
	protected $_data    = [];
	protected $_updates = [];
	protected $_values  = [];

	public function __construct($name, $load)
	{
		$this->_model = [];

		if (isset($load->caller->name))
		{
			$this->_model[] = $load->caller->name;
		}

		if (!$this->_model || $this->_model[0] != $name)
		{
			$this->_model[] = $name;
		}

		$this->_name = $name;
		$this->_load = $load;
	}

	public function __isset($name)
	{
		return (bool)$this->_schema($name);
	}

	public function __get($name)
	{
		if ($field = $this->_schema($name))
		{
			return $this->_value($field);
		}
		else if (in_array($name, ['load', 'name']))
		{
			return $this->{'_'.$name};
		}

		return NeoFrag()->$name;
	}

	public function __unset($name)
	{
		if ($field = $this->_schema($name))
		{
			unset($this->_updates[$field->i], $this->_values[$field->i]);
		}
	}

	public function __call($name, $args)
	{
		if (array_key_exists(0, $args) && $this->set($name, $args[0]))
		{
			return $this;
		}

		return call_user_func_array('parent::__call', func_get_args());
	}

	public function __debugInfo()
	{
		static $objects = [];

		$values = [];

		if (!isset($objects[$this->_table()][$this->_id]))
		{
			$objects[$this->_table()][$this->_id] = TRUE;

			$values['id']   = $this->_id;
			$values['data'] = $this->_data;

			if ($this->_updates)
			{
				$values['update'] = $this->_updates;
			}

			foreach ($this->_schema() as $name => $field)
			{
				$values['values'][$name] = $this->_value($field);
			}
		}

		return $values;
	}

	public function __toArray()
	{
		$values = [];

		foreach ($this->_schema() as $name => $field)
		{
			$values[$name] = $this->_value($field);
		}

		foreach (get_object_vars($this) as $name => $value)
		{
			if ($name[0] != '_')
			{
				$values[$name] = $value;
			}
		}

		return $values;
	}

	public function __clone()
	{
		$this->_data = $this->_updates = $this->_values = [];
	}

	public function set($name, $value)
	{
		if ($field = $this->_schema($name))
		{
			$value = $field->raw($value);

			if (!$this->_data || $value !== $this->_data[$field->i])
			{
				$this->_updates[$field->i] = $value;
				unset($this->_values[$field->i]);
			}

			return TRUE;
		}
	}

	public function read($id, $values = NULL, $inject = FALSE)
	{
		if ($id === NULL && $values)
		{
			$id = [];

			foreach ($this->_primaries() as $name => $field)
			{
				$id[] = $values[$field->key($name)];
			}
		}

		if ($id)
		{
			$primaries = (array)$id;
			$id        = is_array($id) ? (isset($id[1]) ? serialize($id) : $id[0]) : $id;

			if (!isset(self::$_objects[$this->_table()][$id]))
			{
				$model = !$inject ? clone $this : $this;

				$model->_data = $model->_updates = $model->_values = [];

				if (!$values && array_filter($primaries))
				{
					$values = $model->_get_by_primaries($primaries)->from($this->_table())->row();
				}

				if ($values)
				{
					$model->_id = $id;
					
					foreach ($this->_schema() as $name => $field)
					{
						if (array_key_exists($key = $field->key($name), $values))
						{
							$model->_data[$field->i] = $values[$key];
						}
					}
				}
				else
				{
					return $model;
				}

				self::$_objects[$this->_table()][$id] = $model;
			}

			return self::$_objects[$this->_table()][$id];
		}

		$this->_data = $this->_updates = $this->_values = [];

		return $this;
	}

	public function collection()
	{
		return NeoFrag()->collection($this);
	}

	public function db()
	{
		return $this->_db()->from($this->_table().' AS `_`');
	}

	public function form2()
	{
		return $this->_data ? $this->load->form2($this->_name, $this) : $this->load->form2($this->_name);
	}

	public function create()
	{
		if ($this->_updates && !$this->_data)
		{
			$values = $this->_updates();

			foreach ($this->_schema() as $name => $field)
			{
				if (!array_key_exists($key = $field->key($name), $values) && $key != 'id')
				{
					$values[$key] = $field->raw();
				}
			}

			if (($id = $this->_db()->insert($this->_table(), $values)) !== NULL)
			{
				$this->_updates = [];

				if ($id && ($primaries = $this->_primaries()) && count($primaries) == 1 && isset($primaries['id']))
				{
					$values['id'] = $id;
				}

				unset(self::$_objects[$this->_table()][$this->_id]);
				$this->read(NULL, $values, TRUE);

				$this->_log('create', $this->_data);

				return $this;
			}
		}
	}

	public function update()
	{
		if ($this->_updates && $this->_data && $this->_get_by_primaries($primaries)->update($this->_table(), $this->_updates()) !== NULL)
		{
			$this->_data = $this->_updates + $this->_data;

			$this->_log('update', $this->_updates, $primaries);

			$this->_updates = [];

			return $this;
		}
	}

	public function commit()
	{
		if (($result = $this->update()) || ($result = $this->create()))
		{
			return $result;
		}
	}

	public function delete()
	{
		if ($this->_data)
		{
			$this->_get_by_primaries($primaries)->delete($this->_table());

			$this->_log('delete', $this->_data, $primaries);

			$this->_data = $this->_updates = $this->_values = [];
		}

		return $this;
	}

	protected function _schema($name = NULL)
	{
		if (!isset(self::$_schemas[$this->_table()]))
		{
			self::$_schemas[$this->_table()] = $this::__schema();

			$i = 0;

			foreach (self::$_schemas[$this->_table()] as $field)
			{
				$field->i = $i++;
			}
		}

		return $name ? (isset(self::$_schemas[$this->_table()][$name]) ? self::$_schemas[$this->_table()][$name] : NULL) : self::$_schemas[$this->_table()];
	}

	protected function _db()
	{
		return NeoFrag()->db(defined('static::DB') ? static::DB : 'default');
	}

	protected function _get_by_primaries(&$primaries = [])
	{
		$output = [];

		$db = $this->_db();

		foreach ($this->_primaries() as $name => $field)
		{
			$db->where($key = $field->key($name), $value = array_key_exists($i = $field->i, $this->_data) ? $this->_data[$i] : array_shift($primaries));
			$output[$key] = $value;
		}

		$primaries = $output;

		return $db;
	}

	protected function _primaries()
	{
		return array_filter($this->_schema(), function($a){
			return $a->is_primary();
		});
	}

	protected function _updates()
	{
		$updates = [];

		foreach ($this->_schema() as $name => $field)
		{
			if (array_key_exists($field->i, $this->_updates))
			{
				$updates[$field->key($name)] = $this->_updates[$field->i];
			}
		}

		return $updates;
	}

	protected function _value($field)
	{
		if (!array_key_exists($key = $field->i, $this->_values))
		{
			if (array_key_exists($key, $this->_updates))
			{
				$this->_values[$key] = $field->value($this->_updates[$key]);
			}
			else if (array_key_exists($key, $this->_data))
			{
				$this->_values[$key] = $field->value($this->_data[$key]);
			}
			else
			{
				$this->_values[$key] = $field->value();
			}
		}

		return $this->_values[$key];
	}

	protected function _table()
	{
		return 'nf_'.implode('_', $this->_model);
	}

	protected function _log($action, $data, $primaries = NULL)
	{
		if (!defined('static::LOG') || static::LOG)
		{
			$actions = [
				'create' => 0,
				'update' => 1,
				'delete' => 2,
			];

			if (!$primaries)
			{
				$this->_get_by_primaries($primaries);
			}

			/*$this	->model2('log_db')
					->action($actions[$action])
					->model(implode('/', $this->_model))
					->primaries(count($primaries) == 1 && isset($primaries['id']) ? $primaries['id'] : serialize($primaries))
					->data($data)
					->create();*/
		}
	}
}

/*
NeoFrag Alpha 0.1.7
./classes/model2.php
*/