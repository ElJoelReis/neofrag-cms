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

class NeoFrag_m_user_profile extends Model2
{
	static public function __schema()
	{
		return [
			'user'          => self::field()->primary()->depends('user'),
			'first_name'    => self::field()->text(100),
			'last_name'     => self::field()->text(100),
			'avatar'        => self::field()->file(),
			'signature'     => self::field()->text(),
			'date_of_birth' => self::field()->datetime()->null(),
			'sex'           => self::field()->enum('female', 'male')->null(),
			'location'      => self::field()->text(100),
			'quote'         => self::field()->text(100),
			'website'       => self::field()->text(100)
		];
	}
}

/*
NeoFrag Alpha 0.1.7
./models/user_profile.php
*/