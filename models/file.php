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

class NeoFrag_m_file extends Model2
{
	static public function __schema()
	{
		return [
			'id'   => self::field()->primary(),
			'user' => self::field()->depends('user')->null()->default(NeoFrag()->user),
			'name' => self::field()->text(100),
			'path' => self::field()->text(100),
			'date' => self::field()->datetime()
		];
	}

	static public function filename($dir, $extension)
	{
		dir_create($dir = 'upload/'.($dir ?: 'unknow'));

		do
		{
			$file = unique_id().'.'.$extension;
		}
		while (check_file($filename = $dir.'/'.$file));

		return $filename;
	}

	static public function add($path, $name)
	{
		return NeoFrag()->model2('file')
						->name($name)
						->path($path)
						->create();
	}

	static public function uploaded_file($files, $dir = NULL, $file_id = NULL, $var = NULL)
	{
		$filename = static::filename($dir, extension(basename($var ? $files['name'][$var] : $files['name'])));

		if (move_uploaded_file($var ? $files['tmp_name'][$var] : $files['tmp_name'], $filename))
		{
			if (($file = NeoFrag()->model2('file', $file_id)) && $file->id)
			{
				@unlink($file->path);

				return $file->user(NeoFrag()->user)
							->name($var ? $files['name'][$var] : $files['name'])
							->path($filename)
							->update();
			}
			else
			{
				return static::add($filename, $var ? $files['name'][$var] : $files['name']);
			}
		}

		return FALSE;
	}

	public function path()
	{
		if ($args = func_get_args())
		{
			return call_user_func_array('parent::path', $args);
		}

		if ($this->path)
		{
			return url($this->path);
		}
	}

	public function delete()
	{
		@unlink($this->path);
		return parent::delete();
	}
}

/*
NeoFrag Alpha 0.1.7
./models/file.php
*/