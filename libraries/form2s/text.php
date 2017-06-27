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

class Form2_Text extends Form2_Labelable
{
	protected $_type   = 'text';
	protected $_addons = [];
	protected $_iconpicker;

	public function __invoke($name)
	{
		$this->_template[] = function(&$input){
			$input = parent	::html('input', TRUE)
							->attr('class', 'form-control')
							->attr('type',  $this->_type)
							->attr_if($this->_value,     'value', $this->_value)
							->attr_if($this->_disabled,  'disabled')
							->attr_if($this->_read_only, 'readonly');

			$this->_placeholder($input);
		};

		parent::__invoke($name);

		$this->_check[] = function($post, &$data){
			if ($this->_iconpicker)
			{
				if (!$this->_iconpicker[0]->check($post, $data))
				{
					$this->_errors = array_merge($this->_errors, $this->_iconpicker[0]->errors());
				}
			}
		};

		$this->_template[] = function(&$input){
			$left = $right = '';

			$add_group = function($addon, $align, $type) use (&$left, &$right){
				if (!in_array($align, ['left', 'right']))
				{
					$align = 'left';
				}

				if (!$$align)
				{
					$$align = '<div class="input-group-'.$type.'">'.$addon.'</div>';
				}
			};

			if ($this->_iconpicker)
			{
				list($iconpicker, $align) = $this->_iconpicker;

				$iconpicker->disabled_if($this->_disabled || $this->_read_only);

				$add_group($iconpicker, $align, 'btn');
			}

			foreach ($this->_addons as $align => $addon)
			{
				$add_group($addon, $align, 'addon');
			}

			if ($left || $right)
			{
				$input = parent	::html()
								->attr('class', 'input-group')
								->content($left.$input.$right);
			}
		};

		return $this;
	}

	public function addon($label, $align = 'left')
	{
		if (!is_a($label, 'Label'))
		{
			$label = $this	->label()
							->icon($label)
							->align($align);
		}

		$this->_addons[$label->align()] = $label;
		return $this;
	}

	public function iconpicker($name, $value = '', $required = FALSE, $align = 'left')
	{
		$this->_iconpicker = [parent::form2_iconpicker($name)->value($value)->required_if($required), $align];
		return $this;
	}
}

/*
NeoFrag Alpha 0.1.7
./libraries/form2/text.php
*/