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

abstract class Addon extends NeoFrag implements Loadable
{
	abstract protected function __info();
	//abstract public function paths();

	static public function __load($loader, $name, $type, $settings, &$class, &$path, &$construct)
	{
		$class     = 'a_'.$name;
		$path      = $loader->paths2('addons', $name.'/'.$name.'.php');
		$construct = [$name, $type, $path, $settings];
	}

	static public function __label()
	{
		return ['Addons', 'Addon', 'fa-puzzle-piece', 'info'];
	}

	public function __actions()
	{
		return [];
	}

	protected $__info     = [];
	protected $__settings = [];

	public $name;
	public $type;

	public function __construct($name, $type, $path = '', $settings = [])
	{
		$this->name = $name;
		$this->type = $type;

		$this->__info = [
			'name' => $name,
			'type' => $type,
			'path' => $path
		];

		$this->__settings = (object)$settings;
	}

	public function info()
	{
		return (object)array_merge($this->__info(), $this->__info);
	}

	public function settings()
	{
		return $this->__settings;
	}

	public function title($new_title = NULL)
	{
		static $title;

		if ($new_title !== NULL)
		{
			$title = $new_title;
		}
		else if ($title === NULL)
		{
			$title = $this->lang($this->info()->title, NULL);
		}

		return $title;
	}

	public function is_enabled()
	{
		return !$this->is_removable() || isset($this->settings()->enabled);
	}

	public function __get($name)
	{
		return $name != 'load' ? parent::__get($name) : $this->load = load('loader', $this, $this->paths());
	}

	public function is_deactivatable()
	{
		return !empty(static::$core[$this->name]) || $this->is_removable();
	}

	public function is_removable()
	{
		return !isset(static::$core) || !isset(static::$core[$this->name]);
	}

	public function get_title($new_title = NULL)
	{
		static $title;

		if ($new_title !== NULL)
		{
			$title = $new_title;
		}
		else if ($title === NULL)
		{
			$title = $this->lang($this->info()->title, NULL);
		}

		return $title;
	}

	public function install()
	{
		$this->db->insert('nf_settings_addons', [
			'name'       => $this->name,
			'type'       => $this->type,
			'is_enabled' => TRUE
		]);

		return $this;
	}

	public function uninstall($remove = TRUE)
	{
		$this->db	->where('name', $this->name)
					->where('type', $this->type)
					->delete('nf_settings_addons');

		if ($remove)
		{
			dir_remove($this->type.'s/'.$this->name);
		}

		return $this;
	}

	public function reset()
	{
		$this->uninstall(FALSE);
		//$this->config->reset();
		$this->install();

		return $this;
	}
}

/*
NeoFrag Alpha 0.1.7
./classes/addon.php
*/