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

class Table2 extends Library
{
	const ID = NULL;

	protected $_collection;
	protected $_no_data = '';
	protected $_create_button = TRUE;
	protected $_columns = [];

	public function __invoke($data, $no_data = '', $create_button = TRUE)
	{
		$this->_collection    = is_a($data, 'Collection') ? $data : $data->collection();
		$this->_no_data       = $no_data;
		$this->_create_button = $create_button;

		return $this->save();
	}

	public function col()
	{
		$args    = func_get_args();
		$content = array_pop($args);

		if (!$args)
		{
			if (is_a($content, 'Table_col'))
			{
				$this->_columns[] = $content;
			}
			else
			{
				$this->_columns[] = $this	->table_col()
											->content($content);
			}
		}
		else
		{
			$col = $this->table_col()
						->content($content);

			$vars = ['title', 'size', 'align'];

			while ($args && $vars)
			{
				$col->{array_shift($vars)}(array_shift($args));
			}

			$this->_columns[] = $col;
		}

		return $this;
	}

	public function compact($content)
	{
		return $this->col($this	->table_col()
								->content($content)
								->compact()
		);
	}

	public function panel()
	{
		$table  = (string)$this;
		$footer = '';

		if ($has_search = $this->_has_search())
		{
			$this->ajax();
		}

		if (!empty($this->_collection->pagination) && ($pagination = $this->_collection->pagination->get_pagination()))
		{
			$footer = (string)$pagination;
		}

		if ($id = post('_'))
		{
			if ($id == $this->id)
			{
				$output = [];

				if ($table)
				{
					$output['table'] = $table;
				}

				if ($footer)
				{
					$output['footer'] = $footer;
				}

				$this->output->json($output);
			}
			else
			{
				return parent::panel();
			}
		}

		$panel = parent	::panel()
						->heading()
						->style('panel-table')
						->data('id', $this->id);

		if ($table)
		{
			$panel->body($table, FALSE);

			if ($has_search)
			{
				$panel->heading($this	->html('input')
										->attr('class', 'table-search')
										->attr('placeholder', 'Rechercher...')
										->attr('autocomplete', 'off'));
			}
		}
		else
		{
			$panel->body($this->_no_data ?: NeoFrag()->lang('no_data'));
		}

		if ($footer)
		{
			$panel->footer($footer);
		}

		return $panel;
	}

	public function __toString()
	{
		array_walk($this->_columns, function($col){
			$col->collection($this->_collection, post('search'), '');
		});

		if ($data = $this->_collection->get())
		{
			NeoFrag()	->css('table2')
						->js('table2');

			$columns = $this->_columns;

			foreach ($data as $row)
			{
				foreach ($columns as $i => $col)
				{
					if ((string)$col->execute($row) !== '')
					{
						unset($columns[$i]);
						continue;
					}
				}

				if (!$columns)
				{
					break;
				}
			}

			foreach (array_keys($columns) as $i)
			{
				unset($this->_columns[$i]);
			}

			$table = $this	->html('table')
							->attr('class', 'table table-hover table-striped')
							->content($this	->html('tbody')
											->content(array_map(function($row){
												return $this->html('tr')
															->content(array_map(function($col) use ($row){
																return $col->display($row);
															}, $this->_columns));
											}, $data)));

			if ($this->_has_header())
			{
				$table->prepend_content($this	->html('thead')
												->content($this	->html('tr')
																->content(array_map(function($col){
																	return $col->header();
																}, $this->_columns))));
			}

			return $table->__toString();
		}

		return '';
	}

	protected function _has_header()
	{
		foreach ($this->_columns as $col)
		{
			if ($col->has_header())
			{
				return TRUE;
			}
		}
	}

	protected function _has_search()
	{
		foreach ($this->_columns as $col)
		{
			if ($col->has_search())
			{
				return TRUE;
			}
		}
	}
}

/*
NeoFrag Alpha 0.1.7
./libraries/table2.php
*/