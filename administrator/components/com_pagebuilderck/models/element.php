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

use Pagebuilderck\CKModel;
use Joomla\Registry\Registry;
use Pagebuilderck\CKFof;

class PagebuilderckModelElement extends CKModel {

	protected $table = '#__pagebuilderck_elements';

	var $item = null;

	public function __construct() {
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

		$this->item->htmlcode = str_replace("|URIROOT|", JUri::root(true), $this->item->htmlcode);

		return $this->item;
	}

	/**
	* Return ony the html code from the item
	*/
	public function getHtml($id) {
		if (! $id) return '';
		$data = $this->getItem($id);
		return isset($data->htmlcode) ? $data->htmlcode : '';
	}

	/**
	 * Method to save the form data.
	 *
	 * @param	array		The form data.
	 * @return	mixed		The user id on success, false on failure.
	 * @since	1.6
	 */
	public function save($data) {
		$id = (!empty($data['id'])) ? $data['id'] : (int) $this->getState('page.id');
		$user = CKFof::getUser();

		if (isset($data['options']) && is_array($data['options']))
		{
			$registry = new Registry;
			$registry->loadArray($data['options']);
			$data['params'] = (string) $registry;
		}

		if ($id) {
			//Check the user can edit this item
			$authorised = $user->authorise('core.edit', 'element.' . $id);
		} else {
			//Check the user can create new items in this section
			$authorised = $user->authorise('core.create', 'com_pagebuilderck');
		}

		if ($authorised !== true) {
			throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 403);
			return false;
		}

		// make a backup before save
		PagebuilderckHelper::makeBackup($this->getItem(), 'myelements');

		$return = CKFof::dbStore($this->table, $data);
		return $return;
	}

	/**
	 * Method to copy a record
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function copy($id) {

		$row = $this->getTable();
		$cid = JFactory::getApplication()->input->get('id', '', 'array');
		$pk = isset($cid[0]) ? (int) $cid[0] : null;
		$data = $this->getItem($pk);
		$data->id = 0;

		// give the new name
		$data->title .= '(copy)';
		
		// Bind the form fields to the table
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Make sure the record is valid
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Store the table to the database
		if (!$row->store()) {
			$this->setError($row->getErrorMsg());
			return false;
		}

		// $this->setId($row->id);

		return true;
	}

	public function getElements() {
		$model = CKFof::getModel('elements');
		return $model->getItems();
	}

}