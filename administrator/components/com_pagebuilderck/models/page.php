<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */

// No direct access.
defined('_JEXEC') or die;

//jimport('joomla.event.dispatcher');

use Joomla\Registry\Registry;
use Pagebuilderck\CKModel;
use Pagebuilderck\CKFof;

class PagebuilderckModelPage extends CKModel {

	protected $table = '#__pagebuilderck_pages';

	var $item = null;

	function __construct() {
		parent::__construct();
	}

	/**
	 * Method to get an ojbect.
	 *
	 * @param	integer	The id of the object to get.
	 *
	 * @return	mixed	Object on success, false on failure.
	 */
	public function getItem($id = 0) {
		if (empty($this->item)) {
			$id = $this->input->get('id', $id, 'int');
			$this->item = CKFof::dbLoad($this->table, $id);
		}

		// transform params to JRegistry object
		if (isset($this->item->params)) $this->item->params = new JRegistry($this->item->params);

		// get the list of categories
		if (isset($this->item->categories)) $this->item->categories = explode(',', $this->item->categories);

		$this->item->htmlcode = str_replace("|URIROOT|", JUri::root(true), $this->item->htmlcode);
		return $this->item;
	}

	/**
	 * Method to save the page.
	 *
	 * @param	array		The form data.
	 * @return	mixed		The id on success, false on failure.
	 */
	public function save($data) {
		$id = (!empty($data['id'])) ? $data['id'] : (int) $this->getState('page.id');
		$user = CKFof::getUser();
		$date = JFactory::getDate();

		if (isset($data['options']) && is_array($data['options']))
		{
			$registry = new Registry;
			$registry->loadArray($data['options']);
			$data['params'] = (string) $registry;
		}

		if (isset($data['categories']) && is_array($data['categories']))
		{
			// $registry = new Registry;
			// $registry->loadArray($data['categories']);
			// $data['categories'] = (string) $registry;
			$data['categories'] = implode(',', $data['categories']);
		}

		if ($id) {
			//Check the user can edit this item
			$authorised = $user->authorise('core.edit', 'page.' . $id);
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
		PagebuilderckHelper::makeBackup($this->getItem());

		$pageid = CKFof::dbStore($this->table, $data);
		return $pageid;
	}

	public function getElements() {
		$model = CKFof::getModel('elements');
		return $model->getItems();
	}
}