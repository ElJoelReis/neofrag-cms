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

class m_events_m_participants extends Model
{
	private $_status = [
		['En attente', 'info',    'fa-question'],
		['Présent',    'success', 'fa-check'],
		['Absent',     'danger',  'fa-times'],
		['Peut-être',  'warning', 'fa-ellipsis-h']
	];

	public function count_participants($event_id)
	{
		return implode(' / ', array_unique($this->db->select('COALESCE(SUM(IF(status = 1, 1, 0)), 0) as participants', 'COUNT(*) as total')->from('nf_events_participants')->where('event_id', $event_id)->row()));
	}

	public function get_participants($event_id)
	{
		return $this->db->select('u.id as user_id', 'u.username', 'u.admin', 'up.avatar', 'up.sex', 'MAX(s.last_activity) > DATE_SUB(NOW(), INTERVAL 5 MINUTE) as online', 'ep.status')
						->from('nf_events_participants ep')
						->join('nf_user                u',  'ep.user_id = u.id AND u.deleted = "0"', 'INNER')
						->join('nf_user_profile      up', 'u.id       = up.user_id')
						->join('nf_session            s',  'u.id       = s.user_id')
						->where('ep.event_id', $event_id)
						->group_by('u.username')
						->order_by('ep.status', 'u.username')
						->get();
	}

	public function status()
	{
		return $this->_status;
	}

	public function label_status($status)
	{
		list($title, $color, $icon) = $this->_status[$status];
		return $this->label($title, $icon, $color);
	}

	public function buttons_status($event_id, $event_title, $current_status)
	{
		$dropdown = [];

		foreach ($this->_status as $i => $status)
		{
			if (in_array($i, [0, $current_status]))
			{
				continue;
			}

			list($title, , $icon) = $status;

			$dropdown[] = $this	->label()
								->title($title)
								->icon($icon)
								->url('events/participant/'.$event_id.'/'.url_title($event_title).'/'.$i);
		}

		list($title, $color, $icon) = $this->_status[$current_status];

		return $this->button_dropdown()
					->title($title)
					->icon($icon)
					->color($color)
					->compact()
					->dropdown($dropdown);
	}

	public function invite($event_id, $title, $users)
	{
		foreach ($users as $user_id)
		{
			$this->db->insert('nf_events_participants', [
				'event_id' => $event_id,
				'user_id'  => $user_id,
				'status'   => 0
			]);
		}

		if ($this->config->events_alert_mp && ($users = array_diff($users, [$this->user->id])))
		{
			$message_id = $this->db	->ignore_foreign_keys()
									->insert('nf_users_messages', [
										'title' => 'Nouvelle invitation :: '.$title
									]);

			$reply_id = $this->db	->insert('nf_users_messages_replies', [
										'message_id' => $message_id,
										'user_id'    => $this->user->id,
										'message'    => '<div class="alert alert-info no-margin"><b>Message automatique.</b><br />Vous êtes invité à participer à l\'événement <b>'.$title.'</b>.<br /><br />Pour indiquer votre disponibilité, <a href="'.url('events/'.$event_id.'/'.url_title($title)).'">cliquer ici</a>.</div>'
									]);

			$this->db	->where('message_id', $message_id)
						->update('nf_users_messages', [
							'reply_id'      => $reply_id,
							'last_reply_id' => $reply_id
						]);

			foreach ($users as $user_id)
			{
				$this->db->insert('nf_users_messages_recipients', [
					'user_id'    => $user_id,
					'message_id' => $message_id,
					'date'       => NULL
				]);
			}
		}
	}
}

/*
NeoFrag Alpha 0.1.6
./modules/events/models/participants.php
*/