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

class i_0_1_5 extends Install
{
	public function up()
	{
		$this->db	->execute('ALTER TABLE `nf_comments` CHANGE `content` `content` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL')
					->execute('ALTER TABLE `nf_comments` CHANGE `date` `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP')
					->execute('ALTER TABLE `nf_files` CHANGE `date` `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP')
					->execute('ALTER TABLE `nf_forum` CHANGE `order` `order` SMALLINT(6) UNSIGNED NOT NULL DEFAULT \'0\'')
					->execute('ALTER TABLE `nf_forum_categories` CHANGE `order` `order` SMALLINT(6) UNSIGNED NOT NULL DEFAULT \'0\'')
					->execute('ALTER TABLE `nf_forum_messages` CHANGE `message` `message` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL')
					->execute('ALTER TABLE `nf_forum_url` CHANGE `redirects` `redirects` INT(11) UNSIGNED NOT NULL DEFAULT \'0\'')
					->execute('ALTER TABLE `nf_forum_topics` CHANGE `message_id` `message_id` INT(11) UNSIGNED NULL DEFAULT NULL')
					->execute('ALTER TABLE `nf_forum_topics` CHANGE `views` `views` INT(11) UNSIGNED NOT NULL DEFAULT \'0\'')
					->execute('ALTER TABLE `nf_forum_topics` CHANGE `count_messages` `count_messages` INT(11) UNSIGNED NOT NULL DEFAULT \'0\'')
					->execute('ALTER TABLE `nf_gallery_images` CHANGE `description` `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL')
					->execute('ALTER TABLE `nf_gallery_images` CHANGE `views` `views` INT(11) UNSIGNED NOT NULL DEFAULT \'0\'')
					->execute('ALTER TABLE `nf_news` CHANGE `views` `views` INT(11) UNSIGNED NOT NULL DEFAULT \'0\'')
					->execute('ALTER TABLE `nf_partners` CHANGE `count` `count` INT(11) UNSIGNED NOT NULL DEFAULT \'0\'')
					->execute('ALTER TABLE `nf_partners` CHANGE `order` `order` TINYINT(6) UNSIGNED NOT NULL DEFAULT \'0\'')
					->execute('ALTER TABLE `nf_settings_languages` CHANGE `order` `order` SMALLINT(6) UNSIGNED NOT NULL DEFAULT \'0\'')
					->execute('ALTER TABLE `nf_talks_messages` CHANGE `message` `message` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL')
					->execute('ALTER TABLE `nf_teams` CHANGE `order` `order` SMALLINT(6) UNSIGNED NOT NULL DEFAULT \'0\'')
					->execute('ALTER TABLE `nf_teams_roles` CHANGE `order` `order` SMALLINT(6) UNSIGNED NOT NULL DEFAULT \'0\'')
					->execute('ALTER TABLE `nf_users` CHANGE `last_activity_date` `last_activity_date` TIMESTAMP NULL DEFAULT NULL')
					->execute('ALTER TABLE `nf_users_messages` CHANGE `reply_id` `reply_id` INT(11) UNSIGNED NULL DEFAULT NULL')
					->execute('INSERT IGNORE INTO `nf_settings` VALUES(\'nf_version_css\', \'\', \'\', \'0\', \'int\')')
					->execute('INSERT IGNORE INTO `nf_settings_addons` VALUES(\'statistics\', \'module\', \'1\')')
					->execute('INSERT IGNORE INTO `nf_settings_addons` VALUES(\'monitoring\', \'module\', \'1\')')
					->execute('INSERT IGNORE INTO `nf_settings` VALUES(\'nf_monitoring_last_check\', \'\', \'\', \'0\', \'int\')');
	}
}

/*
NeoFrag Alpha 0.1.5
./install/alpha.0.1.5.php
*/