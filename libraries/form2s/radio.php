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

class Form2_Radio extends Form2_Multiple
{
	protected $_type = 'radio';
	protected $_inline;

	public function __invoke($name)
	{
		parent::__invoke($name);

		$this->_template[0] = function(&$input){
			$output = [];

			foreach ($this->_data as $value => $label)
			{
				$input = $this	->html('input', TRUE)
								->attr('type',  $this->_type)
								->attr('name',  $this->_name)
								->attr('value', $value)
								->attr_if($this->_disabled, 'disabled')
								->attr_if($this->_read_only, 'readonly');

				$this->_value($input, $value);

				$output[] = '<div class="'.$this->_type.($this->_inline || ($this->_form->display() & Form2::FORM_INLINE) ? '-inline' : '').'">
								<label>
									'.$input.'&nbsp;'.$label.'
								</label>
							</div>';
			}

			$input = implode($output);
		};

		return $this;
	}

	public function inline()
	{
		$this->_inline = TRUE;
		return $this;
	}

	protected function _value(&$input, $value)
	{
		$input->attr_if($this->_value == $value, 'checked');
	}
}

/*
NeoFrag Alpha 0.1.7
./libraries/form2/radio.php
*/