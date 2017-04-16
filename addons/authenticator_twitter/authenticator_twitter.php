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

class a_authenticator_twitter extends Authenticator
{
	protected function __info()
	{
		return [
			'title'   => 'Twitter',
			'icon'    => 'fa-twitter',
			'color'   => '#1da1f2',
			'help'    => 'https://apps.twitter.com/app/new',
			'version' => '1.0',
			'depends' => [
				'addon/authenticator' => '1.0'
			]
		];
	}

	public function config()
	{
		return [
			'applicationId'     => $this->_settings['id'],
			'applicationSecret' => $this->_settings['secret'],
			'scope'             => ['email']
		];
	}

	public function data(&$params = [])
	{
		if (!empty($_GET['oauth_token']))
		{
			$params = $_GET;

			return function($data){
				return [
					'id'            => $data->id,
					'username'      => $data->screen_name,
					'email'         => $data->email,
					'first_name'    => '',
					'last_name'     => '',
					'date_of_birth' => '',
					'sex'           => '',
					'language'      => $data->lang,
					'location'      => $data->location,
					'signature'     => $data->description,
					'website'       => $entities->url->urls[0]->display_url,
					'avatar'        => $data->profile_image_url_https
				];
			};
		}
	}
}

/*
NeoFrag Alpha 0.1.6
./libraries/authenticators/twitter.php
*/