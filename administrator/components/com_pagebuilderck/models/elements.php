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

class PagebuilderckModelElements extends CKModel {

	protected $context = 'pagebuilderck.elements';

	public function __construct() {
		parent::__construct();
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 */
	public function getItems() {
		// Create a new query object.
		$db = CKFof::getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select('a.*');
		$query->from('`#__pagebuilderck_elements` AS a');

		// Filter by search in title
		$search = $this->getState('filter_search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = ' . (int) substr($search, 3));
			} else if (stripos($search, 'type:') === 0) {
				$query->where('a.type = "' . (string) substr($search, 5) . '"');
			} else {
				$search = $db->Quote('%' .$search . '%');
				$query->where('(' . 'a.title LIKE ' . $search . ' )');
			}
		}

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

	public function ajaxSave($title, $type, $html, $id = 0) {
		// security check
		CKFof::checkAjaxToken();

		$item = CKfof::dbLoad('#__pagebuilderck_elements', (int)$id);
		$item->title = $title;
		$item->type = $type;
		$item->htmlcode = $html;
		$return = CKFof::dbStore('#__pagebuilderck_elements', $item);
		if (! $return) return false;
		return $return;
	}

	public function loadHtml($id = 0) {
		if ($id == 0) return false;
		$item = CKfof::dbLoad('#__pagebuilderck_elements', (int)$id);

		return $item->htmlcode;
	}

	public function saveOrder($ordering) {
		// security check
		CKFof::checkAjaxToken();

		// update ordering values
		foreach ( $ordering as $id => $order )
		{
			$item = CKfof::dbLoad('#__pagebuilderck_elements', (int)$id);

			if ($item->ordering != $order)
			{
				$item->ordering = $order;
				if (! CKFof::dbStore('#__pagebuilderck_elements', $item)) {
					return false;
				}
			}
		}
		return true;
	}
}
