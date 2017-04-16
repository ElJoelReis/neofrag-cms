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

class Loader extends NeoFrag
{
	public $libraries   = [];
	public $helpers     = [];
	public $controllers = [];
	public $models      = [];
	public $views       = [];
	public $forms       = [];
	public $langs       = [];
	public $data        = [];
	public $caller;

	protected $_paths;
	protected $_objects = [];

	public function __construct()
	{
		$args = func_get_args();

		$this->_paths = array_pop($args);

		if ($args)
		{
			$this->caller = array_pop($args);
		}

		$this->load = $this;
	}

	public function paths($type = NULL)
	{
		$paths = is_a($this->_paths, 'closure') ? call_user_func_array($this->_paths, []) : $this->_paths;

		if ($type)
		{
			if (!isset($paths[$type]))
			{
				if (NeoFrag() == $this)
				{
					return [];
				}
				else
				{
					return NeoFrag()->paths($type);
				}
			}

			if (NeoFrag() != $this)
			{
				$paths[$type] = array_merge_recursive($paths[$type], NeoFrag()->paths($type));
			}

			return $paths[$type];
		}
		else
		{
			if (NeoFrag() != $this)
			{
				$paths = array_merge_recursive($paths, NeoFrag()->paths());
			}

			return $paths;
		}
	}

	public function paths2($type, $file)
	{
		foreach ($this->paths($type) as $dir)
		{
			if (check_file($path = $dir.'/'.$file))
			{
				return $path;
			}
		}

		return FALSE;
	}

	public function __invoke($name, $type = 'addon', $settings = [])
	{
		$name = strtolower($name);
		$type = strtolower($type);

		if (isset($this->_objects[$type]) && array_key_exists($name, $this->_objects[$type]))
		{
			return $this->_objects[$type][$name];
		}

		$object = NULL;

		if (is_a($type, 'Loadable', TRUE))
		{
			$class     = '';
			$path      = '';
			$construct = [];

			forward_static_call_array([$type, '__load'], [$this->load, $name, $type, $settings, &$class, &$path, &$construct]);

			if ($path)
			{
				if (in_string('overrides/', $path) && check_file($o_path = preg_replace('_.*overrides/_', '', $path)))
				{
					include_once $o_path;
					$class = 'o_'.$class;
				}

				include_once $path;

				$object = call_user_func_array('load', array_merge([$class], $construct));
			}
		}

		return $this->_objects[$type][$name] = $object;
	}

	public function debugbar($title = 'Loader')
	{
		$output = '<span class="label label-info">'.$title.(property_exists($this, 'override') && $this->override ? ' '.icon('fa-code-fork') : '').'</span>';

		$this->debug->timeline($output, $this->__debug->time[0], $this->__debug->time[1]);

		$output = '	<ul>
						<li>
							'.$output;

		foreach ([
			[isset($this->_objects['module']) ? $this->_objects['module'] : [], 'Modules',     'default', function($a){ return $a->debug('default'); }],
			[isset($this->_objects['theme'])  ? $this->_objects['theme']  : [], 'Themes',      'primary', function($a){ return $a->debug('primary'); }],
			[isset($this->_objects['widget']) ? $this->_objects['widget'] : [], 'Widgets',     'success', function($a){ return $a->debug('success'); }],
			[$this->libraries,                                                  'Libraries',   'info',    function($a){ return $a->debug('info'); }],
			[$this->helpers,                                                    'Helpers',     'warning', function($a){ return '<span class="label label-warning">'.$a[1].'</span>'; }],
			[$this->controllers,                                                'Controllers', 'danger',  function($a){ return $a->debug('danger'); }],
			[$this->models,                                                     'Models',      'default', function($a){ return $a->debug('default'); }],
			[$this->views,                                                      'Views',       'primary', function($a){ return '<span class="label label-primary">'.$a[1].'</span>'; }],
			[$this->forms,                                                      'Forms',       'success', function($a){ return '<span class="label label-success">'.$a[1].'</span>'; }],
			[$this->langs,                                                      'Locales',     'info',    function($a, $b){ return '<span class="label label-info">'.$b.'</span>'; }]
		] as $vars)
		{
			list($objects, $name, $class, $callback) = $vars;

			if ($objects = array_filter($objects))
			{
				$output .= '	<ul>
									<li>
										<span class="label label-'.$class.'">'.$name.'</span>
										<ul>';

				foreach ($objects as $key => $object)
				{
					$output .= '			<li>'.$callback($object, $key).(is_object($object) && property_exists($object, 'load') && $object->load != $this ? $object->load->debugbar() : '').'</li>';
				}

				$output .= '			</ul>
									</li>
								</ul>';
			}
		}

		return $output.'</li>
					</ul>';
	}
}

/*
NeoFrag Alpha 0.1.6
./classes/loader.php
*/