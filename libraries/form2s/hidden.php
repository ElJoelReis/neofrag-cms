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

class Form2_Hidden extends Library
{
	protected $_name;
	protected $_value;

	public function __invoke($name, $value)
	{
		$this->_name  = $name;
		$this->_value = $value;
		return $this->reset();
	}

	public function __toString()
	{
		return $this->html('input', TRUE)
					->attr('type',  'hidden')
					->attr('name',  $this->_name)
					->attr('value', $this->_value)
					->__toString();
	}
}

/*
NeoFrag Alpha 0.1.7
./libraries/form2/hidden.php
*/