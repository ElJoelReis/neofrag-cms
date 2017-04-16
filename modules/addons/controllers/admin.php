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

class m_addons_c_admin extends Controller_Module
{
	public function index()
	{
		$addons = array_filter($this->collection('addon')->get(), function($a){
			return $a->addon()->__actions();
		});

		$types = array_count_values(array_map(function($a){
			return $a->type->id;
		}, $addons));

		usort($addons, function($a, $b) use ($types){
			if ($types[$a->type->id] > $types[$b->type->id])
			{
				return 1;
			}
			else if ($types[$a->type->id] < $types[$b->type->id])
			{
				return -1;
			}
			else
			{
				return str_nat($a, $b, function($a){
					return $a->type->name.$a->addon()->title();
				});
			}
		});

		return $this->js('mixitup.min')
					->css('addons')
					//->js_load('mixitup($("#addons")[0]);')
					->view('admin', [
						'addons' => $addons
					]);
	}

	public function _module_settings($module)
	{
		$this	->title($module->get_title())
				->subtitle('Configuration')
				->icon('fa-wrench');

		return $module->settings();
	}

	public function _module_delete($module)
	{
		$this	->title('Confirmation de suppression')
				->subtitle($module->get_title())
				->form
				->confirm_deletion($this->lang('delete_confirmation'), 'Êtes-vous sûr(e) de vouloir supprimer le module <b>'.$module->get_title().'</b> ?');

		if ($this->form->is_valid())
		{
			$module->uninstall();
			return 'OK';
		}

		echo $this->form->display();
	}

	public function _theme_settings($theme, $controller)
	{
		$this	->title($theme->get_title())
				->subtitle($this->lang('theme_customize'))
				->icon('fa-paint-brush');

		return $controller->index($theme);
	}

	public function _theme_delete($theme)
	{
		$this	->title('Confirmation de suppression')
				->subtitle($theme->get_title())
				->form
				->confirm_deletion($this->lang('delete_confirmation'), 'Êtes-vous sûr(e) de vouloir supprimer le thème <b>'.$theme->get_title().'</b> ?');

		if ($this->form->is_valid())
		{
			$theme->uninstall();
			return 'OK';
		}

		echo $this->form->display();
	}
}

/*
NeoFrag Alpha 0.1.6
./modules/addons/controllers/admin.php
*/