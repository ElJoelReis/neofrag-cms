<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\User\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Checker extends Controller_Module
{
	public function edit()
	{
		if (!$this->user->id)
		{
			$this->error->unconnected();
		}

		return [];
	}

	public function sessions($page = '')
	{
		if ($this->user->id)
		{
			return [$this->pagination->get_data($this->user->get_sessions_history(), $page)];
		}

		$this->error->unconnected();
	}

	public function _session_delete($session_id)
	{
		$this->ajax();

		if (!$this->user->id)
		{
			$this->error->unconnected();
		}
		else if ($this->db->select('1')->from('nf_sessions')->where('user_id', $this->user->id)->where('session_id', $session_id)->row())
		{
			return [$session_id];
		}
	}

	public function login($error = 0)
	{
		if ($this->user->id)
		{
			redirect('user');
		}

		return [$error];
	}

	public function _lost_password($key_id)
	{
		if ($this->user->id)
		{
			redirect('user');
		}

		if ($user_id = $this->model()->check_key($key_id))
		{
			return [$key_id, (int)$user_id];
		}
	}

	public function _auth($provider)
	{
		if ($this->user->id)
		{
			redirect('user');
		}

		$provider = str_replace('-', '_', strtolower($provider));

		if (	($settings = $this->db	->select('settings')
										->from('nf_settings_authenticators')
										->where('name', $provider)
										->where('is_enabled', TRUE)
										->row()) &&
				($authenticator = $this->authenticator($provider, TRUE, unserialize($settings))))
		{
			return [$authenticator];
		}
	}

	public function logout()
	{
		if (!$this->user->id)
		{
			redirect('user');
		}

		return [];
	}

	private function _messages($page, $box)
	{
		if ($this->user->id)
		{
			$this->css('inbox');

			return [$this->pagination->fix_items_per_page(10)->get_data($this->model('messages')->get_messages_inbox($box), $page)];
		}
		else
		{
			redirect('user');
		}
	}

	public function _messages_inbox($page = '')
	{
		return $this->_messages($page, 'inbox');
	}

	public function _messages_sent($page = '')
	{
		return $this->_messages($page, 'sent');
	}

	public function _messages_archives($page = '')
	{
		return $this->_messages($page, 'archives');
	}

	public function _messages_read($message_id, $title)
	{
		if (($message = $this->model('messages')->get_message($message_id, $title)) && $title == url_title($message['title']))
		{
			return array_merge($message, [$this->model('messages')->get_replies($message_id)]);
		}
	}

	public function _messages_compose($user_id = NULL, $username = NULL)
	{
		if (!$this->user->id)
		{
			redirect('user');
		}
		else
		{
			if ($user_id && $username)
			{
				if (($user = $this->db->select('username')->from('nf_user')->where('id', $user_id)->where('username <>', NULL)->where('deleted', FALSE)->row()) && $username == url_title($user))
				{
					$username = $user;
				}
				else
				{
					$this->error();
				}
			}

			return [$username];
		}
	}

	public function _messages_delete($message_id, $title)
	{
		$this->ajax();

		if (($message = $this->model('messages')->get_message($message_id, $title)) && $title == url_title($message['title']))
		{
			return $message;
		}
	}

	public function _member($user_id, $username)
	{
		if ($user = $this->model()->check_user($user_id, $username))
		{
			return $user;
		}
	}
}
