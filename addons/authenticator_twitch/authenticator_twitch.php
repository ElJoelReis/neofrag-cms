<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class A_Authenticator_Twitch extends Authenticator
{
	protected function __info()
	{
		return [
			'title'   => 'Twitch',
			'icon'    => 'fa-twitch',
			'color'   => '#6441a4',
			'help'    => 'https://www.twitch.tv/kraken/oauth2/clients/new',
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
			'scope'             => ['user_read']
		];
	}

	public function data(&$params = [])
	{
		if (!empty($_GET['code']) && !empty($_GET['state']))
		{
			$params = $_GET;

			return function($data){
				return [
					'id'            => $data->_id,
					'username'      => $data->display_name,
					'email'         => $data->email,
					'first_name'    => '',
					'last_name'     => '',
					'date_of_birth' => '',
					'sex'           => '',
					'language'      => '',
					'location'      => '',
					'signature'     => $data->bio,
					'website'       => '',
					'avatar'        => $data->logo
				];
			};
		}
	}
}
