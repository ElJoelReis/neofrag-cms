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

class a_language_pt extends Language
{
	protected function __info()
	{
		return [
			'title'   => 'Português',
			'icon'    => '🇵🇹',
			'version' => '1.0',
			'depends' => [
				'neofrag' => 'Alpha 0.1.7'
			]
		];
	}

	public function locale()
	{
		return [
			'pt_PT.UTF8',
			'pt.UTF8',
			'pt_PT.UTF-8',
			'pt.UTF-8',
			'Portuguese_Portugal.1252'
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
			'unfound_translation' => '{0}Traduction introuvable : %s|{1}Erreur de pluralisation %s'
		];
	}
}

/*
NeoFrag Alpha 0.1.7
./addons/language_pt/language_pt.php
*/