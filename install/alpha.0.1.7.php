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

class i_0_1_7 extends Install
{
	public function up()
	{
		dir_remove('neofrag');

		$this->db->where('name', 'nf_debug')->delete('nf_settings');

		$this->db	->execute('ALTER TABLE `nf_users` CHANGE `user_id` `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT')
					->execute('RENAME TABLE `nf_users` TO `nf_user`')
					->execute('RENAME TABLE `nf_users_profiles` TO `nf_user_profile`')
					->execute('ALTER TABLE `nf_files` CHANGE `file_id` `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT')
					->execute('RENAME TABLE `nf_files` TO `nf_file`')
					->execute('ALTER TABLE `nf_comments` CHANGE `comment_id` `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT')
					->execute('RENAME TABLE `nf_comments` TO `nf_comment`')
					->execute('ALTER TABLE nf_talks CONVERT TO CHARACTER SET utf8')
					->execute('ALTER TABLE nf_talks_messages CONVERT TO CHARACTER SET utf8')
					->execute('CREATE TABLE `nf_addon_type` (
					  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
					  `name` varchar(100) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8');
					->execute('CREATE TABLE `nf_addon` (
					  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
					  `type_id` int(11) unsigned DEFAULT NULL,
					  `name` varchar(100) NOT NULL,
					  `data` text,
					  PRIMARY KEY (`id`),
					  UNIQUE KEY `name` (`name`,`type_id`),
					  KEY `type_id` (`type_id`),
					  CONSTRAINT `nf_addon_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `nf_addon_type` (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8');

		foreach ($this->db->from('nf_settings_addons')->get() as $addon)
		{
			
		}
	}
}

/*
NeoFrag Alpha 0.1.7
./install/alpha.0.1.7.php
*/