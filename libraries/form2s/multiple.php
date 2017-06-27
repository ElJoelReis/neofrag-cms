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

abstract class Form2_Multiple extends Form2_Labelable
{
	protected $_data = [];
	protected $_multiple;

	public function __invoke($name)
	{
		parent::__invoke($name);

		$this->_check[1] = function($post, &$data){
			if ($this->_multiple)
			{
				$data[$this->_name] = [];

				if (isset($post[$this->_name]))
				{
					foreach ($post[$this->_name] as $value)
					{
						if (isset($this->_data[$value]))
						{
							$data[$this->_name][] = $value;
						}
					}
				}

				if ($this->_required && empty($data[$this->_name]))
				{
					$this->_errors[] = $this->lang('required_input');
				}

				$this->_value = $data[$this->_name];
			}
			else
			{
				$this->_value = $data[$this->_name] = '';

				if (isset($post[$this->_name]) && isset($this->_data[$post[$this->_name]]))
				{
					$this->_value = $data[$this->_name] = $post[$this->_name];
				}
				else if ($this->_required)
				{
					$this->_errors[] = $this->lang('required_input');
				}
			}
		};

		return $this;
	}

	public function data($data)
	{
		if (is_a($data, 'Collection'))
		{
			$this->_data = [];

			foreach ($data->get() as $row)
			{
				$row = $row->__toArray();
				$id  = $row['id'];
				unset($row['id']);

				$this->_data[$id] = array_values($row);
			}
		}
		else
		{
			$this->_data = $data;
		}

		return $this;
	}
}

/*
NeoFrag Alpha 0.1.7
./libraries/form2/miltiple.php
*/