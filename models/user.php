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

class NeoFrag_m_user extends Model2
{
	static public function __schema()
	{
		return [
			'id'                 => self::field()->primary(),
			'username'           => self::field()->text(100),
			'password'           => self::field()->text(34),
			'salt'               => self::field()->text(32),
			'email'              => self::field()->text(100),
			'registration_date'  => self::field()->datetime(),
			'last_activity_date' => self::field()->datetime()->null(),
			'admin'              => self::field()->bool(),
			'language'           => self::field()->text(5),
			'deleted'            => self::field()->bool()
		];
	}

	public function profile()
	{
		return $this->model2('user_profile', $this->id);
	}

	public function is_online()
	{
		if (!property_exists($this, 'online'))
		{
			if ($this->user->id == $this->id)
			{
				$this->online = TRUE;
			}
			else if ($this->id)
			{
				$this->online = $this->db	->select('MAX(last_activity) > DATE_SUB(NOW(), INTERVAL 5 MINUTE)')
											->from('nf_sessions')
											->where('user_id', $this->id)
											->row();
			}

			$this->online = FALSE;
		}

		return $this->online;
	}

	public function link($user_id = 0, $username = '', $prefix = '')
	{
		if (!$user_id)
		{
			$user_id  = $this->id;
			$username = $this->username;
		}

		if (!$username)
		{
			$username = $this->db->select('username')->from('nf_user')->where('id', $user_id)->row();
		}

		if (!$user_id || !$username)
		{
			return '';
		}

		return '<a class="user-profile" data-user-id="'.$user_id.'" data-username="'.url_title($username).'" href="'.url('user/'.$user_id.'/'.url_title($username)).'">'.$prefix.$username.'</a>';
	}

	public function avatar($avatar = 0, $sex = '', $user_id = NULL, $username = '')
	{
		if ($this->id && !func_num_args())
		{
			$avatar   = $this->profile()->avatar ? $this->profile()->avatar->path() : NULL;
			$sex      = $this->profile()->sex;
			$user_id  = $this->id;
			$username = $this->username;
		}
		else if ($avatar)
		{
			$avatar = path($avatar);
		}

		return $this->view('user/avatar', [
			'user_id'  => $user_id,
			'username' => $username,
			'avatar'   => $avatar ?: image($sex == 'female' ? 'default_avatar_female.jpg' : 'default_avatar_male.jpg')
		]);
	}
}

/*
NeoFrag Alpha 0.1.7
./models/user.php
*/