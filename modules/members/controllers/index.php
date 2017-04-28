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

class m_members_c_index extends Controller_Module
{
	public function index($members)
	{
		return $this->table2($members, $this->lang('no_members'))
					->col('', 'avatar')
					->col(function($data){
						return '<div>'.$data->link().'</div><small>'.icon('fa-circle '.($data->is_online() ? 'text-green' : 'text-gray')).' '.$this->lang($data->admin ? 'admin' : 'member').' '.$this->lang($data->is_online() ? 'online' : 'offline').'</small>';
					})
					->col(function($data){
						return $this->user->id && $this->user->id != $data->id ? $this->button()->icon('fa-envelope-o')->url('user/messages/compose/'.$data->id.'/'.url_title($data->username))->compact()->outline() : '';
					})
					->panel();
	}

	public function _group($title, $members)
	{
		return [
			$this->panel()->body('<h2 class="no-margin">'.$this->lang('group').' <small>'.$title.'</small>'.$this->button()->tooltip($this->lang('show_all_members'))->icon('fa-close')->url('members')->color('danger pull-right')->compact()->outline().'</h2>'),
			$this->index($members)
		];
	}
}

/*
NeoFrag Alpha 0.1.6
./modules/members/controllers/index.php
*/