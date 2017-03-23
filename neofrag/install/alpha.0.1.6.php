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

class i_0_1_6 extends Install
{
	public function up()
	{
		foreach ($this->db->from('nf_dispositions')->get() as $disposition)
		{
			$rows = unserialize(preg_replace('/O:\d+:"(Row|Col|Widget_View)"/', 'O:8:"stdClass"', preg_replace_callback('/s:\d+:"(.(?:Row|Col|Widget_View).+?)";/', function($a){
				return 's:'.strlen($a = preg_replace('/.*_(.+?)$/', '\1', $a[1])).':"'.$a.'";';
			}, $disposition['disposition'])));

			$new_disposition = [];

			foreach ($rows as $row)
			{
				$cols = [];

				if (!empty($row->cols))
				{
					foreach ($row->cols as $col)
					{
						$widgets = [];

						if (!empty($col->widgets))
						{
							foreach ($col->widgets as $widget)
							{
								$new_widget = $this->panel_widget($widget->widget_id);

								if (!empty($widget->style))
								{
									$new_widget->color(str_replace('panel-', '', $widget->style));
								}

								$widgets[] = $new_widget;
							}
						}

						$new_col = call_user_func_array([$this, 'col'], $widgets);

						if (!empty($col->size))
						{
							$new_col->size($col->size);
						}

						$cols[] = $new_col;
					}
				}

				$new_row = call_user_func_array([$this, 'row'], $cols);

				if (!empty($row->style))
				{
					$new_row->style($row->style);
				}

				$new_disposition[] = $new_row;
			}

			$this->db	->where('disposition_id', $disposition['disposition_id'])
						->update('nf_dispositions', [
							'disposition' => serialize($new_disposition)
						]);
		}

		$default_settings = [
			'default_background'                 => [0, 'int'],
			'nf_team_logo '                      => [0, 'int'],
			'nf_http_authentication'             => [FALSE, 'bool'],
			'nf_http_authentication_name'        => ['', 'string'],
			'nf_maintenance'                     => [FALSE, 'bool'],
			'nf_maintenance_opening'             => ['', 'string'],
			'nf_maintenance_title'               => ['', 'string'],
			'nf_maintenance_content'             => ['', 'string'],
			'nf_maintenance_logo'                => [0, 'int'],
			'nf_maintenance_background'          => [0, 'int'],
			'nf_maintenance_background_repeat'   => ['', 'string'],
			'nf_maintenance_background_position' => ['', 'string'],
			'nf_maintenance_background_color'    => ['', 'string'],
			'nf_maintenance_text_color'          => ['', 'string'],
			'nf_maintenance_facebook'            => ['', 'string'],
			'nf_maintenance_twitter'             => ['', 'string'],
			'nf_maintenance_google-plus'         => ['', 'string'],
			'nf_maintenance_steam'               => ['', 'string'],
			'nf_maintenance_twitch'              => ['', 'string'],
			'recruits_alert'                     => [TRUE, 'bool'],
			'recruits_hide_unavailable'          => [TRUE, 'bool'],
			'recruits_per_page'                  => [5, 'int'],
			'recruits_send_mail'                 => [TRUE, 'bool'],
			'recruits_send_mp'                   => [TRUE, 'bool']
		];

		foreach ($default_settings as $name => $setting)
		{
			list($value, $type) = $setting;

			if (!isset($this->config->$name))
			{
				$this->config($name, $value, $type);
			}
		}

		$this->db	->execute('ALTER TABLE `nf_users_profiles` CHANGE `date_of_birth` `date_of_birth` DATE NULL DEFAULT NULL')
					->execute('ALTER TABLE `nf_groups` ADD `order` SMALLINT UNSIGNED NOT NULL DEFAULT \'0\' AFTER `auto`')
					->execute('ALTER TABLE `nf_groups` ADD `hidden` ENUM(\'0\',\'1\') NOT NULL DEFAULT \'0\' AFTER `icon`')
					->execute('ALTER TABLE `nf_users` CHANGE `email` `email` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL')
					->execute('ALTER TABLE `nf_sessions_history` ADD `authenticator` VARCHAR(100) NOT NULL AFTER `host_name`')
					->execute('CREATE TABLE `nf_settings_authenticators` (
						`name` varchar(100) NOT NULL,
						`settings` text NOT NULL,
						`is_enabled` enum(\'0\',\'1\') NOT NULL DEFAULT \'0\',
						`order` smallint(5) unsigned NOT NULL DEFAULT \'0\',
						PRIMARY KEY (`name`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;')
					->execute('CREATE TABLE `nf_users_auth` (
						`user_id` int(11) unsigned NOT NULL,
						`authenticator` varchar(100) NOT NULL,
						`id` varchar(250) NOT NULL,
						PRIMARY KEY (`authenticator`,`id`),
						KEY `user_id` (`user_id`),
						CONSTRAINT `nf_users_auth_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `nf_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
						CONSTRAINT `nf_users_auth_ibfk_2` FOREIGN KEY (`authenticator`) REFERENCES `nf_settings_authenticators` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;')
					->execute('CREATE TABLE `nf_recruits` (
					  `recruit_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
					  `title` varchar(100) NOT NULL,
					  `introduction` text NOT NULL,
					  `description` text NOT NULL,
					  `requierments` text NOT NULL,
					  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
					  `user_id` int(11) unsigned NOT NULL,
					  `size` int(11) NOT NULL,
					  `role` varchar(60) NOT NULL,
					  `icon` varchar(60) NOT NULL,
					  `date_end` date DEFAULT NULL,
					  `closed` enum(\'0\',\'1\') NOT NULL DEFAULT \'0\',
					  `team_id` int(11) unsigned DEFAULT NULL,
					  `image_id` int(11) unsigned DEFAULT NULL,
					  PRIMARY KEY (`recruit_id`),
					  KEY `image_id` (`image_id`),
					  KEY `user_id` (`user_id`),
					  KEY `team_id` (`team_id`),
					  CONSTRAINT `nf_recruits_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `nf_teams` (`team_id`) ON DELETE SET NULL ON UPDATE SET NULL,
					  CONSTRAINT `nf_recruits_ibfk_2` FOREIGN KEY (`image_id`) REFERENCES `nf_files` (`file_id`) ON DELETE SET NULL ON UPDATE SET NULL,
					  CONSTRAINT `nf_recruits_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `nf_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;')
					->execute('CREATE TABLE `nf_recruits_candidacies` (
					  `candidacy_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
					  `recruit_id` int(11) unsigned NOT NULL,
					  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
					  `user_id` int(11) unsigned DEFAULT NULL,
					  `pseudo` varchar(60) NOT NULL,
					  `email` varchar(100) NOT NULL,
					  `date_of_birth` date DEFAULT NULL,
					  `presentation` text NOT NULL,
					  `motivations` text NOT NULL,
					  `experiences` text NOT NULL,
					  `status` enum(\'1\',\'2\',\'3\') NOT NULL DEFAULT \'1\',
					  `reply` text,
					  PRIMARY KEY (`candidacy_id`),
					  KEY `recruit_id` (`recruit_id`),
					  KEY `user_id` (`user_id`),
					  CONSTRAINT `nf_recruits_candidacies_ibfk_1` FOREIGN KEY (`recruit_id`) REFERENCES `nf_recruits` (`recruit_id`) ON DELETE CASCADE ON UPDATE CASCADE,
					  CONSTRAINT `nf_recruits_candidacies_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `nf_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;')
					->execute('CREATE TABLE `nf_recruits_candidacies_votes` (
					  `vote_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
					  `candidacy_id` int(11) unsigned NOT NULL,
					  `user_id` int(11) unsigned NOT NULL,
					  `vote` enum(\'0\',\'1\') NOT NULL DEFAULT \'0\',
					  `comment` text NOT NULL,
					  PRIMARY KEY (`vote_id`),
					  KEY `candidacy_id` (`candidacy_id`),
					  KEY `user_id` (`user_id`),
					  CONSTRAINT `nf_recruits_candidacies_votes_ibfk_1` FOREIGN KEY (`candidacy_id`) REFERENCES `nf_recruits_candidacies` (`candidacy_id`) ON DELETE CASCADE ON UPDATE CASCADE,
					  CONSTRAINT `nf_recruits_candidacies_votes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `nf_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;')
					->execute('INSERT INTO `nf_settings_addons` VALUES(\'recruits\', \'module\', \'1\')')
					->execute('INSERT INTO `nf_settings_addons` VALUES(\'recruits\', \'widget\', \'1\')');

		foreach (['facebook', 'twitter', 'google', 'battle_net', 'steam', 'twitch', 'github', 'linkedin'] as $i => $name)
		{
			$this->db->insert('nf_settings_authenticators', [
				'name'     => $name,
				'settings' => serialize([]),
				'order'    => $i
			]);
		}

		$this->config('nf_version_css', time());
	}
}

/*
NeoFrag Alpha 0.1.6
./neofrag/install/alpha.0.1.6.php
*/