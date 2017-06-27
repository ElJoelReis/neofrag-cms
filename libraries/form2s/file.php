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

class Form2_File extends Form2_Labelable
{
	protected $_type = [];
	protected $_upload_dir;
	protected $_uploaded;

	public function __invoke($name, $upload_dir = '')
	{
		$this->_upload_dir = $upload_dir;

		$this->_template[] = function(&$input){
			$input = parent	::html('input', TRUE)
							->attr('type', 'file')
							->attr_if($this->_disabled, 'disabled');
		};

		return parent::__invoke($name);
	}

	public function type($type)
	{
		$this->_type = func_get_args();
		return $this;
	}

	public function uploaded($uploaded)
	{
		$this->_uploaded = $uploaded;
		return $this;
	}
}

/*
NeoFrag Alpha 0.1.7
./libraries/form2/file.php
*/