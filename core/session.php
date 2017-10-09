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

class Session extends Core
{
	protected $_session;
	protected $_data = [];

	public function __construct()
	{
		/*
			TODO 0.1.7
			 - is_crawler
			 - history_back
			 - session_hostory
			 - asset / ajax
		*/

		$expiration_date = $this->date()->sub($this->config->nf_cookie_expire);

		$this->db	->where('remember', FALSE)
					->where('last_activity <', $expiration_date->sql())
					->delete('nf_session');

		$this->_session = $this->model2('session', isset($_COOKIE[$this->config->nf_cookie_name]) ? $_COOKIE[$this->config->nf_cookie_name] : NULL);

		$this->_data = $this->_session->data ?: [];

		$this->load->libraries['user'] = $this->_session->user;

		$this->trigger('init');

		$set_cookie = function(){
			do
			{
				$this->_session->id(unique_id());
			}
			while (!$this->_session->commit());

			$domain = '';
	
			if (preg_match('/(neofr\.ag|neofrag\.download|neofrag)$/', $_SERVER['HTTP_HOST'], $match))
			{
				$domain = $match[1];
			}
	
			setcookie($this->config->nf_cookie_name, $this->_session->id, strtotime('+1 year'), url(), $domain, !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off', TRUE);
		};
	
		if ($this->_session->id)
		{
			if ($this->_session->last_activity < $expiration_date)
			{
				$set_cookie();
			}
			
			$this->_session->last_activity($this->date());
		}
		else if (!is_asset())
		{
			$set_cookie();

			$this->set('session', [
				'date'       => time(),
				'ip_address' => isset($_SERVER['HTTP_X_REAL_IP'])  ? $_SERVER['HTTP_X_REAL_IP']                     : $_SERVER['REMOTE_ADDR'],
				'referer'    => isset($_SERVER['HTTP_REFERER'])    ? utf8_htmlentities($_SERVER['HTTP_REFERER'])    : '',
				'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? utf8_htmlentities($_SERVER['HTTP_USER_AGENT']) : '',
			]);
		}
		
		statistics('nf_sessions_max_simultaneous', $this->db->select('COUNT(DISTINCT IFNULL(user_id, id))')->from('nf_session')->where('last_activity > DATE_SUB(NOW(), INTERVAL 5 MINUTE)')->row(), function($a, $b){ return $a > $b; });

		register_shutdown_function(function(){
			$this->_session->data($this->_data)->update();
		});
	}

	public function __invoke()
	{
		$value = NULL;

		$this->_browse(func_get_args(), function(&$node, $name) use (&$value){
			if (array_key_exists($name, $node))
			{
				$value = $node[$name];
			}
		});

		return $value;
	}

	public function __get($name)
	{
		return $this->_session ? $this->_session->$name : call_user_func_array('parent::__get', [$name]);
	}

	public function set()
	{
		$args  = func_get_args();
		$value = array_pop($args);

		$this->_browse($args, function(&$node, $name) use ($value){
			$node[$name] = $value;
		});

		return $this;
	}

	public function append()
	{
		$args  = func_get_args();
		$value = array_pop($args);

		$this->_browse($args, function(&$node, $name) use ($value){
			if (!array_key_exists($name, $node))
			{
				$node[$name] = [];
			}

			array_push($node[$name], $value);
		});

		return $this;
	}

	public function prepend()
	{
		$args  = func_get_args();
		$value = array_pop($args);

		$this->_browse($args, function(&$node, $name) use ($value){
			if (!array_key_exists($name, $node))
			{
				$node[$name] = [];
			}

			array_unshift($node[$name], $value);
		});

		return $this;
	}

	public function destroy()
	{
		$this->_browse(func_get_args(), function(&$node, $name){
			unset($node[$name]);
		});

		return $this;
	}

	private function _browse($args, $callback)
	{
		if ($args)
		{
			$node = &$this->_data;

			while (($name = array_shift($args)) && $args)
			{
				if (!array_key_exists($name, $node))
				{
					$node[$name] = [];
				}

				$node = &$node[$name];
			}
			
			$callback($node, $name);
		}

		return NULL;

	}
	
	public function debugbar()
	{
		return $this->debug->table($this->_data);
	}
}

/*
NeoFrag Alpha 0.1.6
./core/session.php
*/