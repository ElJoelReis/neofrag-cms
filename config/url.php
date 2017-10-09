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
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag.  If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

$url['segments'] = function($url){
	if (preg_match('/^(humans|robots)\.txt$/', $url['request'], $match))
	{
		$url['segments'] = explode('/', 'ajax/settings/'.$match[1]);
	}

	return $url['segments'];
};

//TODO 0.1.7
$url['domains'] = [
	'neofr.ag'         => function(){
	
	},
	'neofrag.download' => function(){
		
	},
	'neofrag'          => function(){
		return '';
	}
];

/*
NeoFrag Alpha 0.1.7
./config/url.php
*/