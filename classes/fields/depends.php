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

class Field_Depends
{
	protected $_model;
	protected $_suffix;

	public function __construct($model, $suffix = '_id')
	{
		$this->_model  = explode('/', $model);
		$this->_suffix = $suffix;
	}

	public function key($key)
	{
		return $key.$this->_suffix;
	}

	public function raw($value)
	{
		return is_a($value, 'Model2') ? ($value->id ?: NULL) : $value;
	}

	public function value($value)
	{
		if (isset($this->_model[1]))
		{
			$value = NeoFrag()->module($this->_model[0])->model2($this->_model[1], $value);
		}
		else
		{
			$value = NeoFrag()->model2($this->_model[0], $value);
		}

		return $value;
	}
}

/*
NeoFrag Alpha 0.1.7
./classes/fields/depends.php
*/