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

class Core extends NeoFrag
{
	static protected $_callbacks = [];

	public function __get($name)
	{
		return NeoFrag()->$name;
	}

	protected function on($core, $event, $callback)
	{
		static::$_callbacks[$core][$event][] = $callback;
	}

	public function trigger($event, &...$args)
	{
		if (isset(static::$_callbacks[$this->name][$event]))
		{
			foreach (static::$_callbacks[$this->name][$event] as $callback)
			{
				call_user_func_array($callback, $args);	
			}

			unset(static::$_callbacks[$this->name][$event]);
		}
	}
}

/*
NeoFrag Alpha 0.1.6
./classes/core.php
*/