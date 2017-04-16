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

abstract class Theme extends Addon
{
	static public $core = [
		'admin'   => FALSE,
		'default' => TRUE
	];

	static public function __load($loader, $name, $type, $settings, &$class, &$path, &$construct)
	{
		$class     = 't_'.$name;
		$path      = $loader->paths2('themes', $name.'/'.$name.'.php');
		$construct = [$name, $type, $path, $settings];
	}

	static public function __label()
	{
		return ['Thèmes', 'Thème', 'fa-tint', 'success'];
	}

	abstract public function styles_row();
	abstract public function styles_widget();

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

	public function paths()
	{
		return [
			'assets' => [
				'overrides/themes/'.$this->name,
				'themes/'.$this->name
			],
			'controllers' => [
				'overrides/themes/'.$this->name.'/controllers',
				'themes/'.$this->name.'/controllers'
			],
			'forms' => [
				'overrides/themes/'.$this->name.'/forms',
				'themes/'.$this->name.'/forms'
			],
			'helpers' => [
				'overrides/themes/'.$this->name.'/helpers',
				'themes/'.$this->name.'/helpers'
			],
			'lang' => [
				'overrides/themes/'.$this->name.'/lang',
				'themes/'.$this->name.'/lang'
			],
			'libraries' => [
				'overrides/themes/'.$this->name.'/libraries',
				'themes/'.$this->name.'/libraries'
			],
			'models' => [
				'overrides/themes/'.$this->name.'/models',
				'themes/'.$this->name.'/models'
			],
			'views' => [
				'overrides/themes/'.$this->name.'/views',
				'themes/'.$this->name.'/overrides/views',
				'themes/'.$this->name.'/views'
			]
		];
	}
	
	public function __init()
	{

	}

	public function install($dispositions = [])
	{
		foreach ($dispositions as $page => $dispositions)
		{
			foreach ($dispositions as $zone => $disposition)
			{
				$this->db->insert('nf_dispositions', [
					'theme'       => $this->name,
					'page'        => $page,
					'zone'        => array_search($zone, $this->info()->zones),
					'disposition' => serialize($disposition)
				]);
			}
		}

		return parent::install();
	}
	
	public function uninstall($remove = TRUE)
	{
		if ($dispositions = $this->db->select('disposition')->from('nf_dispositions')->where('theme', $this->name)->get())
		{
			$this->module('live_editor')->model()->delete_widgets(array_map('unserialize', $dispositions));

			$this->db	->where('theme', $this->name)
						->delete('nf_dispositions');
		}

		return parent::uninstall($remove);
	}
}

/*
NeoFrag Alpha 0.1.6
./classes/theme.php
*/