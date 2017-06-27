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

class Form2_Captcha extends Form2_Labelable
{
	protected $_color;
	protected $_compact;
	protected $_session;

	public function __invoke($name = '')
	{
		$this->_check[] = function($post){
			if (!($this->_session = $this->session('captcha', $this->id)))
			{
				if (!empty($post[$this->_name]))
				{
					$result = $this	->network('https://www.google.com/recaptcha/api/siteverify')
									->get([
										'secret'   => $this->config->nf_captcha_private_key,
										'response' => $post[$this->_name],
										'remoteip' => $_SERVER['REMOTE_ADDR']
									]);

					if ($result === FALSE)
					{
						$this->_errors[] = 'Erreur serveur';
					}
					else if (!empty($result->success))
					{
						$this->_session = $this->session->set('captcha', $this->id, TRUE);
						return FALSE;
					}
				}

				$this->_errors[] = 'Veuiller valider ce CAPTCHA';
			}

			return FALSE;
		};

		$this->_template[] = function(&$input){
			if (!$this->_session && $this->config->nf_captcha_public_key && $this->config->nf_captcha_private_key && !$this->user->id)
			{
				$this	->js('https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit&hl='.$this->config->lang.'&_=')
						->js('captcha');

				$input = parent	::html()
								->attr('class', 'g-recaptcha')
								->attr_if($this->_color,   'data-theme', $this->_color)
								->attr_if($this->_compact, 'data-size', 'compact');
			}

			return FALSE;
		};

		return parent::__invoke('g-recaptcha-response');
	}

	public function dark()
	{
		$this->_color = 'dark';
		return $this;
	}

	public function compact()
	{
		$this->_compact = TRUE;
		return $this;
	}
}

/*
NeoFrag Alpha 0.1.7
./libraries/form2/captcha.php
*/