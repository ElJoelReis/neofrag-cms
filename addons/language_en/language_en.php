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

class a_language_en extends Language
{
	protected function __info()
	{
		return [
			'title'   => 'English',
			'icon'    => '🇬🇧',
			'version' => '1.0',
			'depends' => [
				'neofrag' => 'Alpha 0.1.7'
			]
		];
	}

	public function locale()
	{
		return [
			'en_GB.UTF8',
			'en_US.UTF8',
			'en.UTF8',
			'en_GB.UTF-8',
			'en_US.UTF-8',
			'en.UTF-8',
			'English_Australia.1252'
		];
	}

	public function date2sql(&$date)
	{
		if (preg_match('#^(\d{2})/(\d{2})/(\d{4})$#', $date, $match))
		{
			$date = $match[3].'-'.$match[2].'-'.$match[1];
		}
	}

	public function time2sql(&$time)
	{
		if (preg_match('#^(\d{2}):(\d{2})$#', $time, $match))
		{
			$time = $match[1].':'.$match[2].':00';
		}
	}

	public function datetime2sql(&$datetime)
	{
		if (preg_match('#^(\d{2})/(\d{2})/(\d{4}) (\d{2}):(\d{2})$#', $datetime, $match))
		{
			$datetime = $match[3].'-'.$match[2].'-'.$match[1].' '.$match[4].':'.$match[5].':00';
		}
	}

	public function i18n()
	{
		return [
			'about'                          => 'About',
			'access_path_already_used'       => 'Path already used',
			'activate'                       => 'Activate',
			'add'                            => 'Add',
			'addons'                         => 'Addons management',
			'administration'                 => 'Administration',
			'age'                            => '(%d year)|(%d years)',
			'ambiguities'                    => 'Ambiguities',
			'authorized_members'             => 'Authorized members',
			'avatar'                         => 'Avatar',
			'avatar_must_be_square'          => 'The avatar must be a square',
			'avatar_size_error'              => 'The avatar must be at least %dpx',
			'back'                           => 'Return',
			'background'                     => 'Background image',
			'background_color'               => 'Background color',
			'background_repeat'              => 'Repeat image',
			'birth_date'                     => 'Date of birth',
			'both'                           => 'Both',
			'bottom'                         => 'Bottom',
			'cancel'                         => 'Cancel',
			'center'                         => 'Centered',
			'close'                          => 'Close',
			'col'                            => 'Col',
			'col_delete_message'             => 'Are you sure you want to delete this <b>column</b>? < br / > all the <b>widgets</b> content will also be deleted.',
			'color'                          => 'Color',
			'comeback_common_layout'         => 'Return to the common layout',
			'comeback_common_layout_message' => 'Are you sure you want to revert to the common layout? < br / > all <b>columns</b> and <b>widgets</b> related to this area will be lost.',
			'coming_soon'                    => 'Coming soon',
			'comment_unlogged'               => 'You must be logged in to post a comment.',
			'comments'                       => '%d Comment| %d Comments',
			'common_layout'                  => 'Common layout',
			'configuration'                  => 'Configuration',
			'configure'                      => 'Configure',
			'content'                        => 'Content',
			'continue'                       => 'Continue',
			'copyright_all_rights_reserved'  => 'Copyright © '.date('Y').' - '.$this->config->nf_name.' all rights reserved',
			'create_account'                 => 'Create an account',
			'custom_layout'                  => 'Page-specific layout',
			'dashboard'                      => 'Dashboard',
			'database'                       => 'Database',
			'date_long'                      => '%A %e %B %Y',
			'date_short'                     => '%d/%m/%Y',
			'date_time_long'                 => '%A %e %B %Y, %H:%M',
			'date_time_short'                => '%d/%m/%Y %H:%M',
			'day_at'                         => '%s at %s',
			'default_theme'                  => 'Basic theme',
			'delete_confirmation'            => 'Delete confirmation',
			'design'                         => 'Appearance',
			'edit'                           => 'Edit',
			'email_unavailable'              => 'Email address already in use',
			'error'                          => 'Error',
			'error_theme_install'            => 'The theme could not be installed, check whether it is actually a theme',
			'female'                         => 'Female',
			'file_icon'                      => 'image (square format min. %dpx max. %d MB)',
			'file_picture'                   => 'image (max. %d MB)',
			'file_transfer_error'            => 'Transfer error',
			'file_transfer_error_1'          => 'The size of the uploaded file exceeds the configured value of upload_max_filesize in php.ini',
			'file_transfer_error_2'          => 'The size of the uploaded file exceeds the MAX_FILE_SIZE value that was specified in the HTML form',
			'file_transfer_error_3'          => 'The file was only partially downloaded',
			'file_transfer_error_4'          => 'The file has not been downloaded',
			'file_transfer_error_6'          => 'The temporary folder is missing',
			'file_transfer_error_7'          => 'Error writing the file to disk',
			'file_transfer_error_8'          => 'A PHP extension stopped the file upload',
			'first_name'                     => 'First name',
			'forbidden_guests'               => 'Excluded visitors',
			'forbidden_members'              => 'Excluded members',
			'forum'                          => 'Forum',
			'gender'                         => 'Sex',
			'group_admins'                   => 'Administrators',
			'group_members'                  => 'Members',
			'group_visitors'                 => 'Visitors',
			'groups'                         => 'Groups',
			'guest'                          => 'Visitor',
			'help'                           => 'Help',
			'hide'                           => 'Hide',
			'home'                           => 'Home',
			'horizontally'                   => 'Horizontally',
			'hours_ago'                      => 'About an hour ago| About %d hours ago',
			'icon'                           => 'Icon',
			'icon_must_be_square'            => 'The icon should be square',
			'icon_size_error'                => 'The icon should be at least %dpx',
			'icons'                          => '{2} icons',
			'increase'                       => 'Increase',
			'install'                        => 'Install',
			'install_in_progress'            => 'Installing theme...',
			'install_theme'                  => 'Installation / update theme',
			'invalid_birth_date'             => 'Really? 2.1 Gigowatt!',
			'invalid_values'                 => 'The selected value is not valid| The selected values are not valid',
			'invalide_filetype'              => 'Unmanaged resource type',
			'ip_address'                     => 'IP address',
			'last_activity'                  => 'Last activity',
			'last_name'                      => 'Name',
			'left'                           => 'Left',
			'loading'                        => 'Loading...',
			'location'                       => 'Location',
			'login'                          => 'Connection',
			'login_title'                    => 'Login',
			'maintenance'                    => 'Maintenance',
			'male'                           => 'Male',
			'manage_my_account'              => 'Manage my account',
			'member'                         => 'Member',
			'members'                        => 'Members',
			'message_needed'                 => 'Please fill in a message',
			'middle'                         => 'Medium',
			'minutes_ago'                    => 'About a minute ago| About %d minutes ago',
			'moderation'                     => 'Moderation',
			'module'                         => 'Module',
			'my_account'                     => 'My space',
			'my_comment'                     => 'My comment',
			'name'                           => 'Name',
			'navigation'                     => 'Navigation',
			'new_col'                        => 'New col',
			'new_row'                        => 'New Row',
			'new_widget'                     => 'New Widget',
			'no'                             => 'Non',
			'no_data'                        => 'There is nothing here at the moment',
			'no_result'                      => 'No results match the search',
			'notifications'                  => 'Notifications',
			'now'                            => 'A moment ago',
			'online'                         => 'Online',
			'open_website'                   => 'Open the website',
			'our_website_is_unavailable'     => 'Our website is temporarily unavailable,<br />please come back in a moment...',
			'pages'                          => '{0} on {1} pages',
			'password'                       => 'Password',
			'permissions'                    => 'Permissions',
			'permissions_reset_comfirmation' => 'Confirmation of permissions reset',
			'permissions_reset_message'      => 'Are you sure you want to reset permissions?',
			'position'                       => 'Position',
			'quote'                          => 'Quote',
			'reduce'                         => 'Reduce',
			'reinstall'                      => 'Reinstall',
			'reinstall_to_default'           => 'Reinstall default',
			'remove'                         => 'Delete',
			'remove_file'                    => 'Delete the file?',
			'removed_message'                => 'Deleted message',
			'reply'                          => 'Reply',
			'required_fields'                => '* All fields marked with a star are required',
			'required_input'                 => 'Please fill this field',
			'reset'                          => 'Reset',
			'reset_automatic'                => 'Automatic reset',
			'results'                        => '%d result| %d results',
			'results_total'                  => '%d total',
			'right'                          => 'Right',
			'row'                            => 'Row',
			'row_delete_message'             => 'Are you sure you want to delete this <b>line</b>? < br / > all <b>columns</b> and <b>Widget</b> content will also be deleted.',
			'row_design'                     => 'Appearance of the row',
			'save'                           => 'Save',
			'search'                         => 'Search',
			'search...'                      => 'Search...',
			'seconds_ago'                    => 'A second ago| About %d seconds ago',
			'select_image_file'              => 'Please choose an image file',
			'send'                           => 'Send',
			'server'                         => 'Server',
			'sessions'                       => 'Sessions',
			'settings'                       => 'Settings',
			'show_all'                       => 'Show all',
			'signature'                      => 'Signature',
			'sort'                           => 'Order',
			'theme_activation'               => 'Theme activation',
			'theme_activation_message'       => 'Are you sure you want to activate the theme <b>\'+$(this).data(\'title\')+\'</b>?',
			'theme_deletion_message'         => 'Are you sure you want to permanently delete the theme <b>\'+$(this).parents(\'.thumbnail:first\').data(\'title\')+\'</b>?',
			'theme_reinstallation_message'   => 'Are you sure you want to reinstall the theme <b>\'+$(this).parents(\'.thumbnail:first\').data(\'title\')+\'</b>? < br / > all layouts and widgets configurations will be lost.',
			'themes'                         => 'Themes',
			'time_long'                      => '%H:%M:%S',
			'time_short'                     => '%H:%M',
			'title'                          => 'Title',
			'top'                            => 'Top',
			'unavailable_feature'            => 'This feature is not available at the moment.',
			'unfound_translation'            => '{0} Translation not found: %s| {1} Pluralization error %s',
			'unknown_method'                 => 'Non-existent method %s::%s',
			'unknown_property'               => 'Non-existent property %s::%s',
			'upload_file'                    => 'Upload file',
			'username'                       => 'Username',
			'username_unavailable'           => 'Username already in use',
			'users'                          => 'Users',
			'vertically'                     => 'Vertically',
			'view_my_profile'                => 'See my profile',
			'website'                        => 'Web site',
			'website_down_for_maintenance'   => 'The website is down for maintenance',
			'website_under_maintenance'      => 'Website under maintenance',
			'widget_delete_message'          => 'Are you sure you want to delete this <b>widget</b>?',
			'widget_design'                  => 'Appearance of the Widget',
			'widget_settings'                => 'Widget configuration',
			'wrong_email'                    => 'Please enter a valid email address',
			'wrong_url'                      => 'Please enter a valid URL',
			'yesterday_at'                   => 'Yesterday at %s',
			'your_response'                  => 'Your reply',
			'zone'                           => 'Zone #%d'
		];
	}
}

/*
NeoFrag Alpha 0.1.7
./addons/language_en/language_en.php
*/