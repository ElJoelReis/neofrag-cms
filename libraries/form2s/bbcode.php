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

class Form2_BBCode extends Form2_Textarea
{
	protected $_html;

	public function __invoke($name)
	{
		parent::__invoke($name);

		$this->_check[] = function($post, &$data){
			if ($this->_html)
			{
				$check = $this->_html[0]->required_if($this->_required)->check($post);

				if (isset($post[$this->_name.'_type']) && $post[$this->_name.'_type'] == 'html')
				{
					$this->_html[1] = FALSE;

					if (!$check)
					{
						$this->_errors = $this->_html[0]->errors();
					}
					else
					{
						$this->_errors      = [];
						$data[$this->_name] = $post[$this->_name.'_html'];
					}
				}
			}
		};

		$this->_template[] = function(&$input){
			$this	->css('wbbtheme')
					->js('jquery.wysibb.min')
					->js('jquery.wysibb.fr')
					->js_load('$(\'textarea.editor\').wysibb({lang: "'.$this->config->lang.'"});');

			$input->append_attr('class', 'editor');

			if ($this->_html)
			{
				$this->js('bbcode');

				$input = parent	::html()
								->attr('class', 'editor-bbcode-html')
								->content([
									parent	::form2_hidden($this->_name.'_type', $this->_html[1] ? 'bbcode' : 'html'),
									parent	::html()
											->attr('class', !$this->_html[1] ? 'hidden' : '')
											->content($input),
									parent	::html()
											->attr('class', 'textarea')
											->append_attr_if($this->_html[1], 'class', 'hidden')
											->content($this->_html[0]	->disabled_if($this->_disabled)
																		->read_only_if($this->_read_only)
																		->placeholder($this->_placeholder)
																		->rows($this->_rows)),
									parent	::html()
											->attr('class', 'editor-buttons')
											->content([
												'<br />',
												parent	::button()
														->title('BBCode')
														->icon('fa-bold')
														->color($this->_html[1] ? 'primary' : 'default')
														->data('type', 'bbcode'),
												'&nbsp;',
												parent	::button()
														->title('Code HTML')
														->icon('fa-code')
														->color(!$this->_html[1] ? 'primary' : 'default')
														->data('type', 'html')
											])
								]);
			}
		};

		return $this;
	}

	public function html($value = NULL)
	{
		$this->_html = [parent::form2_textarea($this->_name.'_html')->value_if($value !== NULL, $value), $value === NULL];
		return $this;
	}
}

/*
NeoFrag Alpha 0.1.7
./libraries/form2/bbcode.php
*/