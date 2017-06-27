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

class Form2_Textarea extends Form2_Labelable
{
	protected $_rows = 15;

	public function __invoke($name)
	{
		$this->_template[] = function(&$input){
			$input = parent	::html('textarea')
							->attr('class', 'form-control')
							->attr('rows', $this->_rows)
							->attr_if($this->_disabled,  'disabled')
							->attr_if($this->_read_only, 'readonly')
							->content($this->_value);

			$this->_placeholder($input);
		};

		return parent::__invoke($name);
	}

	public function rows($rows)
	{
		$this->_rows = $rows;
		return $this;
	}
}

/*
NeoFrag Alpha 0.1.7
./libraries/form2/textarea.php
*/