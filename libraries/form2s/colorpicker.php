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

class Form2_ColorPicker extends Form2_Text
{
	public function __invoke($name)
	{
		parent::__invoke($name);

		$this->_check[] = function($post, &$data){
			if (isset($post[$this->_name]) && $post[$this->_name] !== '' && !isset(get_colors()[$post[$this->_name]]) && !preg_match('/^#([a-f0-9]{3}){1,2}/i', $post[$this->_name]))
			{
				$this->_errors[] = 'Couleur invalide';
			}
		};

		$this->_template[] = function(&$input){
			$this	->css('bootstrap-colorpicker.min')
					->js('bootstrap-colorpicker.min')
					->js('colorpicker');

			$input->append_attr('class', 'color');
		};

		return $this->addon('fa-eyedropper', 'right')
					->addon($this->label()->title('<i></i>'))
					->size('col-md-3');
	}
}

/*
NeoFrag Alpha 0.1.7
./libraries/form2/colorpicker.php
*/