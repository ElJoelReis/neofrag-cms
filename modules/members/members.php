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

class m_members extends Module
{
	protected function __info()
	{
		return [
			'title'       => $this->lang('members_list'),
			'description' => '',
			'icon'        => 'fa-users',
			'link'        => 'https://neofr.ag',
			'author'      => 'Michaël BILCOT & Jérémy VALENTIN <contact@neofrag.com>',
			'license'     => 'LGPLv3 <https://neofr.ag/license>',
			'version'     => '1.0',
			'depends'     => [
				'neofrag' => 'Alpha 0.1.7'
			],
			'routes'      => [
				'{pages}'                                   => 'index',
				'group/(admins|members){pages}'             => '_group',
				'group/{url_title}-{id}/{url_title}{pages}' => '_group',
				'group/{id}/{url_title}{pages}'             => '_group'
			]
		];
	}
}

/*
NeoFrag Alpha 0.1.5
./modules/members/members.php
*/