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

class PagebuilderckModelCategories extends CKModel {

	protected $context = 'pagebuilderck.categories';

	protected $table = '#__pagebuilderck_categories';

	public function __construct() {
		parent::__construct();
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
		$query->from('`#__pagebuilderck_categories` AS a');

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

	public function save($data) {
		$id = (!empty($data['id'])) ? $data['id'] : (int) $this->getState('category.id');
		$user = CKFof::getUser();
		$date = JFactory::getDate();

		if ($id) {
			//Check the user can edit this item
			$authorised = $user->authorise('core.edit', 'category.' . $id);
		} else {
			//Check the user can create new items in this section
			$authorised = $user->authorise('core.create', 'com_pagebuilderck');
			$data['created'] = $date->toSql();
		}

		if ($authorised !== true) {
			throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 403);
			return false;
		}

		// save the date
		$data['modified'] = $date->toSql();
		// make a backup before save
		// PagebuilderckHelper::makeBackup($this->getItem());

		$storeid = CKFof::dbStore($this->table, $data);
		return $storeid;
	}
}
