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

class Date extends Library
{
	protected $_datetime;

	public function __invoke($datetime = NULL)
	{
		if ($datetime)
		{
			$datetime = date_create_from_format('U',           $datetime) ?:
				        date_create_from_format('Y-m-d H:i:s', $datetime) ?:
					    date_create_from_format('Y-m-d',       $datetime) ?:
						date_create_from_format('H:i:s',       $datetime) ?:
						date_create($datetime);
		}

		$this->_datetime = $datetime ?: date_create();

		return $this->reset();
	}

	public function __toString()
	{
		return '<time datetime="'.$this->_datetime->format('Y-m-d\TH:i:s').'">'.time_span($this->_datetime->getTimestamp()).'</time>';
	}

	public function modify($modify)
	{
		 $this->_datetime->modify($modify);
		 return $this;
	}

	public function sub($interval)
	{
		if (!is_a($interval, 'DateInterval'))
		{
			$interval = DateInterval::createFromDateString($interval);
		}
	
		$this->_datetime->sub($interval);
		return $this;
	}

	public function sql()
	{
		return $this->_datetime->format('Y-m-d H:i:s');
	}

	public function __debugInfo()
	{
		return [
			'datetime' => $this->__toString()
		];
	}
}

/*
NeoFrag Alpha 0.1.7
./libraries/date.php
*/