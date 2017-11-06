<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\Comments\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Ajax_Checker extends Controller_Module
{
	public function delete($comment_id)
	{
		$comment = $this->db->select('user_id', 'module_id', 'module')
							->from('nf_comment')
							->where('id', (int)$comment_id)
							->row();

		if ($comment && ($this->user->admin || ($this->user->id && $comment['user_id'] == $this->user->id)))
		{
			return [$comment_id, $comment['module_id'], $comment['module']];
		}
	}
}
