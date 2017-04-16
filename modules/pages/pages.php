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

class m_pages extends Module
{
	protected function __info()
	{
		return [
			'title'       => $this->lang('pages'),
			'description' => '',
			'icon'        => 'fa-file-o',
			'link'        => 'https://neofr.ag',
			'author'      => 'Michaël BILCOT & Jérémy VALENTIN <contact@neofrag.com>',
			'license'     => 'LGPLv3 <https://neofr.ag/license>',
			'admin'       => TRUE,
			'routes'      => [
				//Index
				'{url_title}'             => '_index',
				
				//Admin
				'admin{pages}'            => 'index',
				'admin/{id}/{url_title*}' => '_edit'
			]
		];
	}
	
	public function get_title($new_title = NULL)
	{
		if (!empty($this->load->data['module_title']))
		{
			return $this->load->data['module_title'];
		}

		/* TODO
			Bug dans la liste des modules quand un module est désactivé et que le module Page est activé
			return parent::get_title($new_title);
		*/

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
}

/*
NeoFrag Alpha 0.1.6
./modules/pages/pages.php
*/