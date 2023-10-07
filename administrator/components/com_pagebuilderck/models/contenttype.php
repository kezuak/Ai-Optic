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

jimport('joomla.event.dispatcher');

class PagebuilderckModelContenttype extends CKModel {


	var $_item = null;

	public function __construct() {

		parent::__construct();
	}

	/**
	 * Method to get the profile form.
	 *
	 * The base form is loaded from XML
	 *
	 * @param	array	$data		An optional array of data for the form to interogate.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	JForm	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true) {
		// Get the form.
		$form = $this->loadForm('com_pagebuilderck.contenttype', 'contenttype', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}

	/**
	 * Method to get an ojbect.
	 *
	 * @param	integer	The id of the object to get.
	 *
	 * @return	mixed	Object on success, false on failure.
	 */
	public function &getData($type = null) {
		$app = JFactory::getApplication();
		if ($this->_item === null) {
			$this->_item = false;

			if (empty($type)) {
				$type = $app->input->get('type', '', 'string');
			}

			// Get a new object.
			$this->_item = new stdClass();
			$this->_item->type = $type;
			$this->_item->htmlcode = PagebuilderckHelper::getOption('contenttype.' . $type);
			$this->_item->stylecode = PagebuilderckHelper::getOption('contenttype.' . $type . '.stylecode');
		}

		$this->_item->htmlcode = str_replace("|URIROOT|", JUri::root(true), $this->_item->htmlcode);
		return $this->_item;
	}

	/**
	* Return ony the html code from the item
	*/
	/*public function getHtml($id) {
		if (! $id) return '';
		$data = $this->getData($id);
		return isset($data->htmlcode) ? $data->htmlcode : '';
	}*/

	/**
	 * Method to save the form data.
	 *
	 * @param	array		The form data.
	 * @return	mixed		The user id on success, false on failure.
	 * @since	1.6
	 */
	public function save($data) {
		$input = JFactory::getApplication()->input;
//		$id = (!empty($data['id'])) ? $data['id'] : (int) $this->getState('contenttype.id');
		$user = JFactory::getUser();
		// $data['htmlcode'] = JRequest::getVar('htmlcode', '', 'post', 'string', JREQUEST_ALLOWRAW);
//		$data['htmlcode'] = $data['htmlcode'] ? $data['htmlcode'] : $input->get('htmlcode', '', 'raw');
		$data['htmlcode'] = str_replace(JUri::root(true), "|URIROOT|", $data['htmlcode']);
		$data['stylecode'] = str_replace(JUri::root(true), "|URIROOT|", $data['stylecode']);
		$type = $data['type'];

		if ($type) {
			//Check the user can edit this item
			$authorised = $user->authorise('core.edit', 'contenttype.' . $type);
		} else {
			//Check the user can create new items in this section
			$authorised = $user->authorise('core.create', 'com_pagebuilderck');
		}

		if ($authorised !== true) {
			JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
			return false;
		}

		// make a backup before save
		PagebuilderckHelper::makeBackup($this->getData(), 'contenttype.' . $type);

		// save the data
		$id = PagebuilderckHelper::setOption('contenttype.' . $type, $data['htmlcode']);
		$id = PagebuilderckHelper::setOption('contenttype.' . $type . '.stylecode', $data['stylecode']);

		return $id;
	}

	/**
	 * Method to copy a record
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function copy() {

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
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
				$this->getState(
						'list.select', 'a.*'
				)
		);
		$query->from('`#__pagebuilderck_contenttypes` AS a');

		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = ' . (int) substr($search, 3));
			} else {
				$search = $db->Quote('%' .$search . '%');
				$query->where('(' . 'a.title LIKE ' . $search . ' )');
			}
		}

		// Do not list the trashed items
		$query->where('a.state > -1');

		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');
		if ($orderCol && $orderDirn) {
			$query->order($orderCol . ' ' . $orderDirn);
		}

		$contenttypes = $db->setQuery($query)->loadObjectList();

		return $contenttypes;
	}

}