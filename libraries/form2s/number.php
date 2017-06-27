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

class Form2_Number extends Form2_Text
{
	protected $_type = 'number';

	public function __invoke($name)
	{
		parent::__invoke($name);

		$this->_check[] = function($post, &$data){
			if (isset($post[$this->_name]) && $post[$this->_name] !== '' && ($post[$this->_name] != (int)$post[$this->_name] || $post[$this->_name] != (float)$post[$this->_name]))
			{
				$this->_errors[] = 'Nombre invalide';
			}
		};

		return $this->size('col-md-2');
	}

	public function value($value)
	{
		$this->_value = str_replace(',', '.', $value);
		return $this;
	}
}

/*
NeoFrag Alpha 0.1.7
./libraries/form2/number.php
*/