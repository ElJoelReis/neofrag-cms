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

class Form2 extends Library
{
	const ID = NULL;

	const FORM_INLINE  = 1;
	const FORM_COMPACT = 2;

	protected $_buttons = [];
	protected $_rules   = [];
	protected $_values  = [];
	protected $_deleting;
	protected $_success;
	protected $_display;
	protected $_template;
	protected $_token;

	static private function _token($id)
	{
		static $tokens;

		if ($tokens === NULL)
		{
			$tokens = NeoFrag()->session('form2') ?: [];
		}

		if (empty($tokens[$id]))
		{
			NeoFrag()->session->set('form2', $id, $tokens[$id] = unique_id(array_merge([$id], $tokens)));
		}

		return $tokens[$id];
	}

	public function __invoke($form = '', $values = [])
	{
		if ($form)
		{
			foreach ($paths = $this->load->paths('forms') as $dir)
			{
				if (!check_file($path = $dir.'/'.$form.'.php'))
				{
					continue;
				}

				$found = TRUE;

				if ($this->debug->is_enabled())
				{
					$this->load->forms[$dir] = [$path, $form.'.php'];
				}

				if (is_array($rules = include $path))
				{
					$this->_rules = $rules;
				}

				$this->_values = $values;

				if (func_num_args() == 1 && ($model = $this->model2($form)))
				{
					$this->_values = $model;
				}
			
				if (is_a($this->_values, 'Model2'))
				{
					foreach ($this->_rules as $rule)
					{
						if (method_exists($rule, 'value'))
						{
							if (is_a($value = $this->_values->{$rule->name()}, 'Model2'))
							{
								$value = $value->id;
							}

							$rule->value($value);
						}
					}
				}

				break;
			}

			if (empty($found))
			{
				trigger_error('Unfound form: '.$form.' in paths ['.implode(', ', $paths).']', E_USER_WARNING);
			}
		}

		return $this->reset();
	}

	public function __toString()
	{
		$check = $this->_check();

		$has_upload = FALSE;

		$rules = $this->_rules;

		$rules[] = $this->form2_hidden('_', $this->token());

		foreach ($rules as $rule)
		{
			if (!$has_upload && is_a($rule, 'Form2_File'))
			{
				$has_upload = TRUE;
			}

			if (method_exists($rule, 'form2'))
			{
				$rule->form2($this);
			}
		}

		if ($has_upload)
		{
			$this->js('file');
		}

		if ($check && $this->url->ajax())
		{
			$this->output->json([
				'form' => implode($rules)
			]);
		}

		if (!$this->_template)
		{
			$this->_template = function($fields){
				return implode($fields).$this->_buttons();
			};
		}

		return $this->html('form')
					->attr_if(!($this->_display & self::FORM_COMPACT), 'class', 'form-horizontal')
					->attr_if($this->_display & self::FORM_INLINE,     'class', 'form-inline')
					->attr('action', url($this->url->request))
					->attr('method', 'post')
					->attr_if($has_upload, 'enctype', 'multipart/form-data')
					->content(call_user_func($this->_template, $rules))
					->__toString();
	}

	protected function _check()
	{
		$post = post();

		if ($this->_success && isset($post['_']) && $post['_'] == $this->token())
		{
			$success = TRUE;
			$data    = [];

			foreach ($post as $key => &$value)
			{
				if (is_array($value))
				{
					array_walk_recursive($value, function(&$v){
						$v = utf8_htmlentities(trim($v));
					});
				}
				else if ($value !== NULL)
				{
					$value = utf8_htmlentities(trim($value));
				}

				unset($value);
			}

			foreach ($this->_rules as $rule)
			{
				if (method_exists($rule, 'check'))
				{
					$success = $rule->check($post, $data) && $success;
				}
			}

			if ($success)
			{
				if (is_a($this->_values, 'Model2'))
				{
					foreach ($data as $key => $value)
					{
						$this->_values->set($key, $value);
					}

					$data = $this->_values;
				}

				call_user_func_array($this->_success, [$data]);
			}

			return TRUE;
		}
	}

	public function token($id = NULL)
	{
		if ($id === NULL)
		{
			$id = $this->id;
		}

		return self::_token($id);
	}

	public function rule($rule, $title = '', $value = '', $type = 'text')
	{
		if (is_string($rule))
		{
			$rule = $this	->{'form2_'.$type}($rule)
							->title($title)
							->value($value);
		}

		$this->_rules[] = $rule;

		return $this;
	}

	public function legend($legend, $icon = '')
	{
		$this->_rules[] = is_a($legend, 'Form2_Legend') ? $legend : $this->form2_legend($legend, $icon);
		return $this;
	}

	public function captcha()
	{
		$this->_rules[] = $this->form2_captcha();
		return $this;
	}

	public function submit($title = '', $color = 'primary')
	{
		$this->_buttons[] = $this->button_submit($title, $color);
		return $this;
	}

	public function back($url = '')
	{
		$this->_buttons[] = $this->button_back($url);
		return $this;
	}

	public function compact()
	{
		$this->_display |= self::FORM_COMPACT;
		return $this;
	}

	public function inline()
	{
		$this->_display |= self::FORM_INLINE;
		return $this;
	}

	public function display()
	{
		return $this->_display;
	}

	public function success($success)
	{
		$this->_success = $success;
		return $this;
	}

	public function delete($deleting, $callback)
	{
		$this->_deleting = $deleting;
		$this->_success  = $callback;

		return $this->submit($this->lang('remove'), 'danger');
	}

	public function panel()
	{
		$this->_template = function($fields){
			return 	$this	->html()
							->attr('class', 'panel-body')
							->content($fields).
					$this	->html()
							->attr('class', 'panel-footer')
							->content($this->_buttons());
		};

		return parent	::panel()
						->heading()
						->body($this, FALSE);
	}

	public function modal($title, $icon = '')
	{
		$this->_template = function($fields){
			return $this->html()
						->attr('class', 'modal-body')
						->content($fields)
						->append_content_if($this->_deleting, $this->_deleting);
		};

		$modal = parent	::modal($title, $icon)
						->body($this, FALSE)
						->cancel();

		foreach ($this->_buttons as $button)
		{
			$modal->button($button);
		}

		if (!$this->_has_submit())
		{
			$modal->submit();
		}

		return $modal;
	}

	protected function _buttons()
	{
		$buttons = $this->_buttons;

		if (!$this->_has_submit())
		{
			$buttons[] = $this->button_submit();
		}

		usort($buttons, function($a, $b){
			return strcmp(get_class($a), get_class($b));
		});

		return $this->html()
					->attr('class', 'form-group')
					->content(
						$this	->html()
								->attr('class', 'col-md-offset-3 col-md-9')
								->content(implode('&nbsp;', $buttons))
					);
	}

	protected function _has_submit()
	{
		foreach ($this->_buttons as $button)
		{
			if (is_a($button, 'Button_submit'))
			{
				return TRUE;
			}
		}

		return FALSE;
	}
}

/*
NeoFrag Alpha 0.1.7
./libraries/form2.php
*/