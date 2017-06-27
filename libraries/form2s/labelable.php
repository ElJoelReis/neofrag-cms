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

abstract class Form2_Labelable extends Library
{
	protected $_title;
	protected $_icon;
	protected $_placeholder;
	protected $_info;
	protected $_size;
	protected $_form;
	protected $_name;
	protected $_value;
	protected $_disabled;
	protected $_read_only;
	protected $_required;
	protected $_template = [];
	protected $_check  = [];
	protected $_errors = [];

	public function __invoke($name)
	{
		$this->_name = $name;

		$this->_template[] = function(&$input){
			$input	->attr_if($this->_form, 'id', function(){
						return $this->_form->token().'_'.$this->_name;
					})
					->attr('name', $this->_name);
		};

		$this->_check[] = function($post, &$data){
			if ($this->_disabled || $this->_read_only)
			{
				return FALSE;
			}
		};

		$this->_check[] = function($post, &$data){
			if ($this->_required && (!isset($post[$this->_name]) || $post[$this->_name] === ''))
			{
				$this->_errors[] = $this->lang('required_input');
			}

			if (isset($post[$this->_name]))
			{
				$this->_value = $data[$this->_name] = $post[$this->_name];
			}
		};

		return $this->reset();
	}

	public function __toString()
	{
		$input = NULL;

		foreach ($this->_template as $template)
		{
			if (call_user_func_array($template, [&$input]) === FALSE)
			{
				break;
			}
		}

		if (!($input = (string)$input) || !$this->_form)
		{
			return $input;
		}

		$display = $this->_form->display();

		$label = '';

		if (!($display & Form2::FORM_COMPACT))
		{
			$label = parent	::html('label')
							->attr('class', 'control-label col-md-3')
							->attr_if($label = (string)$this->_label(), 'for', $this->_form->token().'_'.$this->_name)
							->content($label);

			if ($display & Form2::FORM_INLINE)
			{
				$label = $label->attr('class', 'control-label').'&nbsp;';
			}
		}

		if (!($display & Form2::FORM_INLINE))
		{
			if ($display & Form2::FORM_COMPACT)
			{
				return $label.$input;
			}
			else
			{
				$input = parent	::html()
								->attr('class', $this->_size ?: 'col-md-9')
								->content($input);
			}
		}

		return parent	::html()
						->attr('class', 'form-group')
						->append_attr_if($this->_errors, 'class', 'has-error')
						->content([$label, $input])
						->__toString();
	}

	public function check($post, &$data = [])
	{
		if (is_a($post, 'closure'))
		{
			$this->_check[] = $data;
			return $this;
		}
		else
		{
			foreach ($this->_check as $check)
			{
				if ($check($post, $data) === FALSE)
				{
					break;
				}
			}

			return empty($this->_errors);
		}
	}

	public function name()
	{
		return $this->_name;
	}

	public function title($title, $icon = NULL)
	{
		$this->_title = $title;
		$this->_icon  = $icon;
		return $this;
	}

	public function placeholder($placeholder)
	{
		$this->_placeholder = $placeholder;
		return $this;
	}

	public function info($info)
	{
		$this->_info = $info;
		return $this;
	}

	public function size($size)
	{
		$this->_size = $size;
		return $this;
	}

	public function form2($form)
	{
		$this->_form = $form;
		return $this;
	}

	public function value($value)
	{
		$this->_value = $value;
		return $this;
	}

	public function disabled()
	{
		if ($this->_read_only || $this->_required)
		{
			trigger_error(get_class().' can only have one state among disabled, readonly and required at a time', E_USER_ERROR);
		}

		$this->_disabled = TRUE;
		return $this;
	}

	public function read_only()
	{
		if ($this->_disabled || $this->_required)
		{
			trigger_error(get_class().' can only have one state among disabled, readonly and required at a time', E_USER_ERROR);
		}

		$this->_read_only = TRUE;
		return $this;
	}

	public function required()
	{
		if ($this->_read_only || $this->_disabled)
		{
			trigger_error(get_class().' can only have one state among disabled, readonly and required at a time', E_USER_ERROR);
		}

		$this->_required = TRUE;
		return $this;
	}

	public function errors()
	{
		return $this->_errors;
	}

	protected function _label()
	{
		$label = $this->label($this->_title, $this->_errors ? 'fa-exclamation-triangle' : $this->_icon);

		if ($this->_info || $this->_errors)
		{
			$label	->icon_if(!$this->_errors, $icon = 'fa-info-circle text-info')
					->attr('data-toggle',    'popover')
					->attr('data-trigger',   'hover')
					->attr('data-placement', 'auto')
					->attr('data-html',      'true')
					->attr('data-content',   implode('<br /><br />', array_filter([
						$this->_info   ? $this->label($this->_info, $icon) : '',
						$this->_errors ? $this->label(implode('<br />', $this->_errors), 'fa-exclamation-triangle')->attr('class', 'text-danger') : '',
					])));
		}

		if ($this->_required)
		{
			$label .= '<em>*</em>';
		}

		return $label;
	}

	protected function _placeholder(&$input, $placeholder = 'placeholder')
	{
		$input->attr_if($this->_placeholder, $placeholder, $this->_placeholder);

		if ($this->_form && ($this->_form->display() & Form2::FORM_COMPACT))
		{
			$input->attr($placeholder, (string)parent::label($this->_title) ?: $this->_placeholder);
		}
	}
}

/*
NeoFrag Alpha 0.1.7
./libraries/form2/labelable.php
*/