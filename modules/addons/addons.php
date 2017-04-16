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

class m_addons extends Module
{
	protected function __info()
	{
		return [
			'title'       => 'Composants',
			'description' => '',
			'icon'        => 'fa-puzzle-piece',
			'link'        => 'https://neofr.ag',
			'author'      => 'Michaël BILCOT & Jérémy VALENTIN <contact@neofrag.com>',
			'license'     => 'LGPLv3 <https://neofr.ag/license>',
			'admin'       => FALSE,
			'routes'      => [
				//Modules
				'admin/module/{url_title}'        => '_module_settings',
				'admin/delete/module/{url_title}' => '_module_delete',
				
				//Thèmes
				'admin/theme/{url_title}'         => '_theme_settings',
				'admin/delete/theme/{url_title}'  => '_theme_delete',
				'admin/ajax/theme/active'         => '_theme_activation',
				'admin/ajax/theme/reset'          => '_theme_reset',
				'admin/ajax/theme/{url_title}'    => '_theme_settings',

				//Languages
				'admin/ajax/language/sort'        => '_language_sort',

				//Authenticators
				'admin/ajax/authenticator/sort'   => '_authenticator_sort',
				'admin/ajax/authenticator/admin'  => '_authenticator_admin',
				'admin/ajax/authenticator/update' => '_authenticator_update'
			]
		];
	}
}

/*
NeoFrag Alpha 0.1.6
./modules/addons/addons.php
*/