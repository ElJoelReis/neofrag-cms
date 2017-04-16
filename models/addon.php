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

class NeoFrag_m_addon extends Model2
{
	static public function __schema()
	{
		return [
			'id'   => self::field()->primary(),
			'type' => self::field()->depends('addon_type')->null(),
			'name' => self::field()->text(100),
			'data' => self::field()->serialized()
		];
	}

	public function addon()
	{
		return $this->load($this->name, $this->type ? $this->type->name : 'addon', $this->data);
	}

	public function get($type, $name = NULL, $load = TRUE)
	{
		static $types = [];

		if (!$types)
		{
			$types = $this	->db()
							->select('name', 'id')
							->from('nf_addon_type')
							->index();
		}

		$finder = $this->collection()->where('type_id', $types[$type]);

		if ($name)
		{
			return $finder	->where('name', $name)
							->row()
							->addon_if($load);
		}
		else
		{
			return array_filter(array_map(function($a) use ($load){
				return $a->addon_if($load);
			}, $finder->get()));
		}
	}
}

/*
NeoFrag Alpha 0.1.7
./models/addon.php
*/