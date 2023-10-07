<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */

defined('_JEXEC') or die;

use Pagebuilderck\CKModel;
use Pagebuilderck\CKFof;

class PagebuilderckModelArticles extends CKModel {

	protected $context = 'pagebuilderck.articles';

	public function __construct() {
		parent::__construct();
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return	integer the item id
	 */
	public function getItems() {
		// Create a new query object.
		$db = CKFof::getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select('a.*');
		$query->from('`#__content` AS a');
		// Filter by search in title
		$search = $this->getState('filter_search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = ' . (int) substr($search, 3));
			} else {
				$search = $db->Quote('%' .$search . '%');
				$query->where('(' . 'a.title LIKE ' . $search . ' )');
			}
		}

		// pagebuilderck_editor":"1
		$query->where('a.attribs LIKE ' . $db->Quote('%pagebuilderck_editor":"1%') );

		// Do not list the trashed items
		$query->where('a.state > -1');

		// Add the list ordering clause.
		$orderCol = $this->state->get('filter_order');
		$orderDirn = $this->state->get('filter_order_Dir');
		if ($orderCol && $orderDirn) {
			$query->order($orderCol . ' ' . $orderDirn);
		}

		$limitstart = $this->state->get('limitstart');
		$limit = $this->state->get('limit');
		$db->setQuery($query, $limitstart, $limit);

		$items = $db->loadObjectList();

		// automatically get the total number of items from the query
		$total = $this->getTotal($query);
		$this->state->set('limit_total', (empty($total) ? 0 : (int)$total));

		return $items;
	}
}
