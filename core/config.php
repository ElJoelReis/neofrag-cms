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

class Config extends Core
{
	private $_settings = [];
	private $_configs  = [];

	public function __construct()
	{
		foreach ($this->db->select('site', 'lang', 'name', 'value', 'type')->from('nf_settings')->get() as $setting)
		{
			if ($setting['type'] == 'array')
			{
				$value = unserialize(utf8_html_entity_decode($setting['value']));
			}
			else if ($setting['type'] == 'list')
			{
				$value = explode('|', $setting['value']);
			}
			else if ($setting['type'] == 'bool')
			{
				$value = (bool)$setting['value'];
			}
			else if ($setting['type'] == 'int')
			{
				$value = (int)$setting['value'];
			}
			else
			{
				$value = $setting['value'];
			}

			$this->_settings[$setting['site']][$setting['lang']][$setting['name']] = $value;
		}

		$load = function($site = '', $lang = ''){
			$this->_configs['lang'] = $lang;
			$this->_configs['site'] = $site;
	
			if (!empty($this->_settings[$site][$lang]))
			{
				foreach ($this->_settings[$site][$lang] as $name => $value)
				{
					$this->_configs[$name] = $value;
				}
			}
		};

		$load();

		$this->on('session', 'init', function() use ($load){
			$n = 0;
			$langs = [];

			foreach ($this->model2('addon')->get('language') as $lang)
			{
				if (1)// || $lang->settings()->enabled;//TODO
				{
					$n++;
					$langs[$lang->name] = $lang;
				}
			}

			$main_lang = NULL;

			if ($n > 1)
			{
				uasort($langs, function($a, $b){
					return strnatcmp($a->settings()->order, $b->settings()->order);
				});

				$this->trigger('langs_listed', $langs, $main_lang);

				if (!$main_lang)
				{
					if (($this->user() && ($addon = $this->user->language->addon()) && isset($langs[$name = $addon->name])) || isset($langs[$name = $this->session('language')]))
					{
						$main_lang = $langs[$name];
					}
					else if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE']) && preg_match_all('/([a-zA-Z-]+)(?:;q=([0-9.]+))?,?/', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $matches, PREG_SET_ORDER))
					{
						$accepted = [];

						foreach ($matches as $match)
						{
							$accepted[$match[1]] = isset($match[2]) ? (float)$match[2] : 1;
						}

						arsort($accepted);

						foreach ($accepted as $name => $q)
						{
							if (isset($langs[$name]))
							{
								$main_lang = $langs[$name];
								break;
							}
						}
					}
				}
			}

			if (!$main_lang)
			{
				$main_lang = $langs[0];
			}

			$this->trigger('lang_selected', $main_lang);

			$this->_configs['lang']  = $main_lang->name;
			$this->_configs['langs'] = array_values($langs);
			
			setlocale(LC_ALL, $main_lang->locale());
		});
	}

	public function __get($name)
	{
		if (isset($this->_configs[$name]))
		{
			return $this->_configs[$name];
		}

		return parent::__get($name);
	}

	public function __set($name, $value)
	{
		$this->_configs[$name] = $value;
	}

	public function __isset($name)
	{
		return isset($this->_configs[$name]);
	}

	public function __invoke($name, $value, $type = NULL)
	{
		if (isset($this->_configs[$name]))
		{
			NeoFrag()->db	->where('name', $name)
									->update('nf_settings', [
										'value' => $value
									]);

			if ($type)
			{
				NeoFrag()->db	->where('name', $name)
										->update('nf_settings', [
											'type' => $type
										]);
			}
		}
		else
		{
			NeoFrag()->db->insert('nf_settings', [
				'name'  => $name,
				'value' => $value,
				'type'  => $type ?: 'string'
			]);
		}

		$this->_configs[$name] = $value;

		return $this;
	}

	public function debugbar()
	{
		return $this->debug->table($this->_configs);
	}
}

/*
NeoFrag Alpha 0.1.6
./core/config.php
*/