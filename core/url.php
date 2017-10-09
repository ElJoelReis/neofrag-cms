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

class Url extends Core
{
	protected $_const      = [];
	protected $_external   = FALSE;
	protected $_langs      = FALSE;
	protected $_production = FALSE;

	public function __construct($config = [])
	{
		if (preg_match('_/{2,}_', $_SERVER['REQUEST_URI']))
		{
			header('Location: '.preg_replace('_/+_', '/', $_SERVER['REQUEST_URI']));
			exit;
		}

		$this->_const['query']        = !empty($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : '';
		$this->_const['location']     = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].$this->query;

		$url = parse_url($this->location);

		$this->_const['https']        = !empty($_SERVER['HTTPS']);
		$this->_const['host']         = $url['host'];
		$this->_const['base']         = substr($_SERVER['SCRIPT_NAME'], 0, -9);//-strlen('index.php')
		$this->_const['ajax_header']  = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
		$this->_const['ajax_allowed'] = FALSE;

		$request = function($request) use ($config){
			$this->_const['request']           = $request;
			$this->_const['extension']         = extension($this->request);
			$this->_const['extension_allowed'] = $this->extension == '';
			$this->_const['segments']          = explode('/', $this->extension ? substr($this->request, 0, - strlen($this->extension) - 1) : $this->request ?: 'index');

			if (isset($config['segments']) && is_a($config['segments'], 'closure'))
			{
				$this->_const['segments']      = call_user_func_array($config['segments'], [$this->_const]);
			}

			$this->_const['admin']             = $this->segments[0] == 'admin';
			$this->_const['ajax']              = isset($this->segments[(int)$this->admin]) && $this->segments[(int)$this->admin] == 'ajax';
		};

		$request(substr($url['path'], strlen($this->base)));

		$this->on('config', 'langs_listed', function($langs, &$lang) use ($request){
			if (array_key_exists($name = $this->segments[0], $langs))
			{
				$this->_langs = TRUE;
				$lang = $langs[$name];
				$request(preg_replace('_^'.$name.'/?_', '', $this->request));
			}
			else
			{
				$this->on('config', 'lang_selected', function($lang){
					redirect($lang->name.'/'.$this->request.$this->query);
				});
			}
		});
	}

	public function __get($name)
	{
		if (isset($this->_const[$name]))
		{
			if ($name == 'base' && $this->_external)
			{
				return $this->_const['host'].$this->_const['base'];
			}

			return $this->_const[$name];
		}

		return parent::__get($name);
	}

	public function __isset($name)
	{
		return isset($this->_const[$name]);
	}

	public function __invoke($url = '')
	{
		if (substr($url, 0, 1) == '#')
		{
			return $url;
		}
		else if (substr($url, 0, 2) == '//')
		{
			$url = explode('/', substr($url, 2));

			array_unshift($url, implode('.', array_filter([array_shift($url), $this->_domain()])));

			return '//'.implode('/', $url);
		}
		else if (preg_match('_^[a-z]+://_', $url))
		{
			return $url;
		}

		$domain = '';//preg_match('/^.+\.neo/', $_SERVER['HTTP_HOST']) ? '//'.$this->_domain() : '';

		if (preg_match('/^addons\./', $_SERVER['HTTP_HOST']))
		{
			$url = preg_replace('_^shop/?_', '', $url);
		}
		else if (preg_match('/^my\./', $_SERVER['HTTP_HOST']))
		{
			$url = preg_replace('_^panel/?_', '', $url);
		}

		if ($this->_langs)
		{
			$url = $this->config->lang.'/'.$url;
		}

		return $domain.$this->base.rtrim($url, '/');
	}

	private function _domain()
	{
		return preg_match('_neofr\.ag_', $_SERVER['HTTP_HOST']) ? 'neofr.ag' : 'neofrag';
	}

	public function ajax()
	{
		return 	$this->ajax ||
				($this->ajax_header && $this->ajax_allowed) ||
				($this->extension_allowed && $this->extension != '');
	}

	public function external($external)
	{
		$this->_external = $external;
		return $this;
	}

	//TODO 0.1.7
	public function production()
	{
		return $this->_production;
	}
}

/*
NeoFrag Alpha 0.1.6
./core/url.php
*/