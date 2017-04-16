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

class Model extends NeoFrag implements Loadable
{
	static public function __load($loader, $name, $type, $settings, &$class, &$path, &$construct)
	{
		$class     = preg_replace('/^o_/', '', get_class($loader->caller)).'_m_'.$name;
		$path      = $loader->paths2('models', $name.'.php');
		$construct = [$name, $loader];

		if (in_string('modules/', $path))
		{
			$class = preg_replace('/^w_/', 'm_', $class);
		}
	}

	public $load;
	public $name;

	public function __construct($name, $loader)
	{
		$this->name = $name;
		$this->load = $loader;
	}
}

/*
NeoFrag Alpha 0.1.6
./classes/model.php
*/