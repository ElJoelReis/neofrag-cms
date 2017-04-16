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

abstract class Language extends Addon
{
	static public function __load($loader, $name, $type, $settings, &$class, &$path, &$construct)
	{
		$class     = 'a_language_'.$name;
		$path      = $loader->paths2('addons', 'language_'.$name.'/language_'.$name.'.php');
		$construct = [$name, $type, $path, $settings];
	}

	static public function __label()
	{
		return ['Langues', 'Langue', 'fa-flag', 'danger'];
	}

	abstract public function locale();
	abstract public function date2sql(&$date);
	abstract public function time2sql(&$time);
	abstract public function datetime2sql(&$datetime);
	abstract public function i18n();

	protected $_i18n = [];

	public function __actions()
	{
		return [
			['enable',   'Activer',       'fa-check',   'success'],
			['disable',  'Désactiver',    'fa-times',   'muted'],
			['settings', 'Configuration', 'fa-wrench',  'warning'],
			NULL,
			['reset',    'Réinitialiser', 'fa-refresh', 'danger'],
			['delete',   'Désinstaller',  'fa-remove',  'danger']
		];
	}

	public function get($paths, $name)
	{
		$args  = func_get_args();
		$paths = array_shift($args);
		$name  = array_shift($args);

		$locale = '';

		foreach ($paths as $dir)
		{
			if (!check_file($path = $dir.'/'.$this->info()->name.'.php'))
			{
				continue;
			}

			if (isset($this->_i18n[$path]))
			{
				$lang = $this->_i18n[$path];
			}
			else
			{
				$lang = [];

				include $path;

				$this->_i18n[$path] = $lang;
			}

			if (isset($lang[$name]))
			{
				$locale = $lang[$name];
				break;
			}
		}

		if (!$locale && array_key_exists($name, $lang = $this->i18n()))
		{
			$locale = $lang[$name];
		}

		if ($locale)
		{
			if (!$args)
			{
				$translation = $locale;
			}
			else
			{
				if (in_string('|', $locale))
				{
					$n      = NULL;
					$locale = explode('|', $locale);
					$count  = count($locale);

					foreach ($locale as $i => &$l)
					{
						if (preg_match('/^\{(\d+?)\}|\[(\d+?),(\d+?|Inf)\]/', $l, $match))
						{
							$n = end($match);

							if ($n == 'Inf')
							{
								break;
							}
						}
						else if ($n === NULL)
						{
							$l = '[0,1]'.$l;
							$n = 1;
						}
						else if ($i == $count - 1)
						{
							$l = '['.++$n.',Inf]'.$l;
						}
						else
						{
							$l = '{'.++$n.'}'.$l;
						}

						unset($l);
					}

					foreach ($locale as $l)
					{
						if (preg_match('/^\{(\d+?)\}(.*)/', $l, $match) && $args[0] == $match[1])
						{
							$locale = $match[2];
							unset($args[0]);
							break;
						}
						else if (preg_match('/^\[(\d+?),(\d+?|Inf)\](.*)/', $l, $match) && $args[0] >= $match[1] && ($match[2] == 'Inf' || $args[0] <= $match[2]))
						{
							$locale = $match[3];
							unset($args[0]);
							break;
						}
					}
				}

				array_unshift($args, $locale);
				$translation = call_user_func_array('sprintf', $args);
			}

			if ($this->debug->i18n())
			{
				$translation = $this->info()->icon.'__'.$translation.'__'.$this->info()->icon;
			}

			return $translation;
		}

		return FALSE;
	}
}

/*
NeoFrag Alpha 0.1.7
./classes/addons/language.php
*/