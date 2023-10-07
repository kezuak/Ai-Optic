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

class PagebuilderckModelPages extends CKModel {

	protected $context = 'pagebuilderck.pages';

	protected $table = '#__pagebuilderck_pages';

	private static $prefix;

	private static $name;

	public function __construct() {
		parent::__construct();
	}

	protected function populateState()
	{
		parent::populateState();
		$config = \JFactory::getConfig();
		$state = CKFof::getUserState(self::$prefix . '.' . self::$name, null);

		// first request, or custom user request
		if ($state === null || $this->input->get('state_request', 0, 'int') === 1) {
			$this->state->set('categories', $this->input->get('categories', 0));
		}
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return	array of items
	 */
	public function getItems() {
		// Create a new query object.
		$db = CKFof::getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select('a.*');
		$query->from('`#__pagebuilderck_pages` AS a');

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

		// filter by state if available
		$state = $this->getState('filter_state');
		if (! empty($state)) $query->where('a.state = ' . $state);
		// Do not list the trashed items
		$query->where('a.state > -1');

		// filter by category
		if (! empty($this->getState('categories'))) {
			// $query->where((int)$this->getState('categories') . ' IN (2,3)');
			$query->where('FIND_IN_SET(' . (int)$this->getState('categories') . ',categories) > 0');
		}

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

		// get the categories
		// $query->join('LEFT', $db->quoteName('#__pagebuilderck_categories', 'b') . ' ON (' . $db->quoteName('b.id') . ' IN( ' . $db->quoteName('a.categories') . ')' . ')');
		$categories = PagebuilderckHelper::getCategories();
		foreach ($items as $item) {
			$item->categoriesByName = array();
			if (isset($item->categories) && ! empty($item->categories)) {
				$itemCategoriesArray = explode(',', $item->categories);
				foreach($itemCategoriesArray as $catid) {
					$item->categoriesByName[] = $categories[$catid]->name;
				}
			}
		}

		return $items;
	}
}
