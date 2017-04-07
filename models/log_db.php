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

class NeoFrag_m_log_db extends Model2
{
	const DB  = 'logs';
	const LOG = NULL;

	static public function __schema()
	{
		return [
			'id'        => self::field()->primary(),
			'date'      => self::field()->datetime(),
			'action'    => self::field()->enum(0, 1, 2),//create - update - delete
			'model'     => self::field()->text(100),
			'primaries' => self::field()->text(100)->null(),
			'data'      => self::field()->serialized()
		];
	}
}

/*
NeoFrag Alpha 0.1.7
./models/log_db.php
*/