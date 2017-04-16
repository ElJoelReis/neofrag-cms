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

class a_authenticator_steam extends Authenticator
{
	protected function __info()
	{
		return [
			'title'   => 'Steam',
			'icon'    => 'fa-steam',
			'color'   => '#171a21',
			'help'    => 'http://steamcommunity.com/dev/apikey',
			'version' => '1.0',
			'depends' => [
				'addon/authenticator' => '1.0'
			]
		];
	}

	protected $_keys = ['key'];

	public function config()
	{
		return [
			'applicationId'     => $this->_settings['key'],
			'applicationSecret' => ''
		];
	}

	public function data(&$params = [])
	{
		if (!empty($_GET['openid_sig']))
		{
			$params = $_GET;

			return function($data){
				return [
					'id'            => $data->steamid,
					'username'      => $data->personaname,
					'email'         => '',
					'first_name'    => '',
					'last_name'     => '',
					'date_of_birth' => '',
					'sex'           => '',
					'language'      => strtolower($data->loccountrycode),
					'location'      => '',
					'signature'     => '',
					'website'       => '',
					'avatar'        => $data->avatarfull
				];
			};
		}
	}

	protected function _params()
	{
		return [
			'domain' => $_SERVER['HTTP_HOST']
		];
	}
}

/*
NeoFrag Alpha 0.1.6
./libraries/authenticators/steam.php
*/