<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

class i_0_2 extends Install
{
	public function up()
	{
		//Comment
		$this->db	->execute('ALTER TABLE `nf_comments` CHANGE `comment_id` `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT')
					->execute('RENAME TABLE `nf_comments` TO `nf_comment`');
	}
}
