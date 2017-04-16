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

class m_games extends Module
{
	protected function __info()
	{
		return [
			'title'       => $this->lang('games_maps'),
			'description' => '',
			'icon'        => 'fa-gamepad',
			'link'        => 'https://neofr.ag',
			'author'      => 'Michaël BILCOT & Jérémy VALENTIN <contact@neofrag.com>',
			'license'     => 'LGPLv3 <https://neofr.ag/license>',
			'admin'       => 'gaming',
			'version'     => '1.0',
			'depends'     => [
				'neofrag' => 'Alpha 0.1.7'
			],
			'routes'      => [
				//Admin
				'admin{pages}'                  => 'index',
				'admin/{id}/{url_title}{pages}' => '_edit',
				
				//Maps
				'admin/maps/add(?:/{id}/{url_title})?' => '_maps_add',
				'admin/maps/edit/{id}/{url_title}'     => '_maps_edit',
				'admin/maps/delete/{id}/{url_title}'   => '_maps_delete',
				
				//Modes
				'admin/modes/add/{id}/{url_title}'    => '_modes_add',
				'admin/modes/edit/{id}/{url_title}'   => '_modes_edit',
				'admin/modes/delete/{id}/{url_title}' => '_modes_delete'
			]
		];
	}
}

/*
NeoFrag Alpha 0.1.6
./modules/games/games.php
*/