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

abstract class NeoFrag
{
	const UNFOUND      = 0;
	const UNAUTHORIZED = 1;
	const UNCONNECTED  = 2;

	const LIVE_EDITOR  = 1;
	const ZONES        = 2;
	const ROWS         = 4;
	const COLS         = 8;
	const WIDGETS      = 16;

	static public $route_patterns = [
		'id'         => '([0-9]+?)',
		'key_id'     => '([a-z0-9]+?)',
		'url_title'  => '([a-z0-9-]+?)',
		'url_title*' => '([a-z0-9-/]+?)',
		'page'       => '((?:/?page/[0-9]+?)?)',
		'pages'      => '((?:/?(?:all|page/[0-9]+?(?:/(?:10|25|50|100))?))?)'
	];

	static public function live_editor()
	{
		if (($live_editor = post('live_editor')) && NeoFrag()->user->admin)
		{
			NeoFrag()->session->set('live_editor', $live_editor);
			return $live_editor;
		}
		
		return FALSE;
	}

	public function __isset($name)
	{
		return isset($this->load->libraries[$name]) || isset(NeoFrag()->libraries[$name]);
	}

	public function __get($name)
	{
		if (property_exists($this, 'load') && isset($this->load->libraries[$name]))
		{
			return $this->load->libraries[$name];
		}
		else if (isset(NeoFrag()->libraries[$name]))
		{
			return NeoFrag()->libraries[$name];
		}
		else
		{
			$type = 'libraries';
			
			if (preg_match('/^core_(.+)/', $name, $match))
			{
				$name = $match[1];
				$type = 'core';
			}
			
			foreach ($this->load->paths($type) as $dir)
			{
				if (!check_file($path = $dir.'/'.$name.'.php') && (!preg_match('/^(.+?)_(.+)/', $name, $match) || !check_file($path = $dir.'/'.$match[1].'s/'.$match[2].'.php')))
				{
					continue;
				}

				require_once $path;

				foreach ($this->load->paths('config') as $dir)
				{
					if (check_file($path = $dir.'/'.$name.'.php'))
					{
						include $path;
					}
				}

				if (isset($$name))
				{
					$library = load($name, $$name);
				}
				else
				{
					$library = load($name);
				}

				if (!isset($library->load))
				{
					$library->load = $this->load;
				}

				return $this->load->libraries[$library->name = $name] = is_a($library, 'Library') ? $library->set_id() : $library;
			}
		}

		trigger_error('Undefined property: '.get_class($this).'::$'.$name, E_USER_WARNING);
	}

	public function __call($name, $args)
	{
		$callback = NULL;

		if (preg_match('/^(?:static_)?(.+?)_if$/', $name, $match))
		{
			if (!$args[0])
			{
				return $this;
			}

			$args = array_slice($args, 1);

			array_walk($args, function(&$a){
				if (is_a($a, 'closure'))
				{
					$a = $a();
				}
			});

			$callback = [$this, $match[1]];
		}

		if (preg_match('/^static_(.+)/', $name, $match))
		{
			return forward_static_call_array($callback ?: [$this, $match[1]], $args);
		}

		if (!$callback && is_callable($library = $this->$name ?: NeoFrag()->$name))
		{
			$callback = $library;
		}

		if ($callback)
		{
			return call_user_func_array($callback, $args);
		}

		trigger_error('Call to undefined method '.get_class($this).'::'.$name.'()', E_USER_WARNING);
	}

	public function ajax()
	{
		$this->url->ajax_allowed = TRUE;
		return $this;
	}

	public function extension($extension)
	{
		if (in_array($extension, ['json', 'xml', 'txt']))
		{
			if ($this->url->extension != $extension)
			{
				throw new Exception(NeoFrag::UNFOUND);
			}

			$this->url->extension     = $extension;
			$this->url->extension_allowed = TRUE;

			$this->ajax();
		}

		return $this;
	}

	public function add_data($data, $content)
	{
		$this->load->data[$data] = $content;
		return $this;
	}

	public function css($file, $media = 'screen')
	{
		NeoFrag()->css[] = [$file, $media, $this->load];
		return $this;
	}

	public function js($file)
	{
		NeoFrag()->js[] = [$file, $this->load];
		return $this;
	}

	public function js_load($function)
	{
		NeoFrag()->js_load[] = $function;
		return $this;
	}

	public function module($name)
	{
		return NeoFrag()->model2('addon')->get('module', $name);
	}

	public function theme($name)
	{
		return NeoFrag()->model2('addon')->get('theme', $name);
	}

	public function widget($name)
	{
		return NeoFrag()->model2('addon')->get('widget', $name);
	}

	public function controller($name)
	{
		return $this->load($name, 'controller');
	}

	public function model($name = NULL)
	{
		if ($name === NULL)
		{
			$name = $this->load->caller->name;
		}

		return $this->load($name, 'model');
	}

	public function model2($name = '', $id = 0)
	{
		if (is_integer($name))
		{
			$id   = $name;
			$name = '';
		}

		if ($name === '')
		{
			$name = $this->load->caller->name;
		}

		if ($model = $this->load($name, 'model2'))
		{
			$model = $model->read($id);
		}

		return $model;
	}

	public function helper($name)
	{
		foreach ($this->load->paths('helpers') as $dir)
		{
			if (!check_file($path = $dir.'/'.$name.'.php'))
			{
				continue;
			}

			$this->load->helpers[] = [$path, $name];

			include_once $path;

			break;
		}

		return $this;
	}

	public function form($form)
	{
		foreach ($paths = $this->load->paths('forms') as $dir)
		{
			if (!check_file($path = $dir.'/'.$form.'.php'))
			{
				continue;
			}
			
			if ($this->debug->is_enabled())
			{
				$this->load->forms[$dir] = [$path, $form.'.php'];
			}

			include $path;

			if (!empty($rules))
			{
				return $rules;
			}
			else
			{
				break;
			}
		}

		trigger_error('Unfound form: '.$form.' in paths ['.implode(', ', $paths).']', E_USER_WARNING);
	}

	public function lang($name)
	{
		$args = func_get_args();
		$name = array_shift($args);

		if (count($args) == 1 && $args[0] === NULL)
		{
			return preg_replace_callback('/\{lang (.+?)\}/', function($a){
				return $this->lang($a[1]);
			}, $name);
		}

		$paths = $this->load != NeoFrag() ? $this->load->paths('lang') : [];

		foreach ($this->config->langs as $lang)
		{
			if (($result = call_user_func_array([$lang, 'get'], array_merge([$paths], func_get_args()))) !== FALSE)
			{
				return $result;
			}
		}

		trigger_error('Unfound lang: '.$name.' in paths ['.implode(', ', $paths).']', E_USER_WARNING);

		return $name;
	}

	public function debug($color, $title = NULL)
	{
		$output = NeoFrag()	->label($title ?: (isset($this->name) ? $this->name : get_class($this)))
							->icon_if(property_exists($this, 'override') && $this->override, 'fa-code-fork')
							->color($color)
							->tooltip(icon('fa-clock-o').' '.round(($this->__debug->time[1] - $this->__debug->time[0]) * 1000, 2).' ms&nbsp;&nbsp;&nbsp;'.icon('fa-cogs').' '.ceil(($this->__debug->memory[1] - $this->__debug->memory[0]) / 1024).' kB');

		NeoFrag()->debug->timeline($output, $this->__debug->time[0], $this->__debug->time[1]);

		return $output;
	}
}

/*
NeoFrag Alpha 0.1.6
./classes/neofrag.php
*/