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

class Field
{
	protected $_fields = [];

	public function __call($name, $args)
	{
		if (preg_match('/^is_(.+)/', $name, $match))
		{
			return isset($this->_fields[$match[1]]);
		}

		require_once 'classes/fields/'.$name.'.php';
		$r = new ReflectionClass('Field_'.$name);

		if (method_exists($field = $this->_fields[$name] = $r->newInstanceArgs($args), 'init'))
		{
			$field->init($this);
		}

		return $this;
	}

	public function key($key)
	{
		foreach ($this->_fields as $field)
		{
			if (method_exists($field, 'key'))
			{
				$key = $field->key($key);
			}
		}

		return $key;
	}

	public function raw($value = NULL)
	{
		if (!func_num_args())
		{
			foreach ($this->_fields as $field)
			{
				if (method_exists($field, 'default_'))
				{
					$value = $field->default_($value);
				}
			}
		}

		foreach ($this->_fields as $field)
		{
			if (method_exists($field, 'raw'))
			{
				$value = $field->raw($value);
			}
		}

		return $value;
	}

	public function value($value = NULL)
	{
		if (!func_num_args())
		{
			foreach ($this->_fields as $field)
			{
				if (method_exists($field, 'default_'))
				{
					$value = $field->default_($value);
				}
			}
		}

		foreach ($this->_fields as $field)
		{
			if (method_exists($field, 'value'))
			{
				$value = $field->value($value);
			}
		}

		return $value;
	}
}

/*
NeoFrag Alpha 0.1.7
./libraries/field.php
*/