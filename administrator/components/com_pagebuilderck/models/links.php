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

class PagebuilderckModelLinks extends CKModel {

	protected $context = 'pagebuilderck.links';

	public function __construct() {
		parent::__construct();
	}

	public function getMenus() {
		// import model and use state to load items
		JModelLegacy::addIncludePath(PAGEBUILDERCK_PATH . '/models', 'PagebuilderckModel');
		// Get an instance of the generic articles model
		$model = JModelLegacy::getInstance('Menus', 'PagebuilderckModel', array('ignore_request' => true));
		return $model->getMenus();
	}

	public function getFiles() {
		// load the items
		require_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/ckbrowse.php';
		return CKBrowse::getItemsList();
	}

	public function getArticleCategoriesRoot() {
		$query = "SELECT id, title, alias, extension, access, 'category' as type"
			. " FROM #__categories"
			. " WHERE extension = 'com_content'"
			. " AND level = 1"
			. " ORDER BY lft ASC, title ASC"
			;
		$categories = CKFof::dbLoadObjectList($query);

		return $categories;
	}

	public function getCategoriesById($parentId) {
		$query = "SELECT id, title, alias, extension, lft, rgt, 'category' as type, access"
			. " ,(SELECT COUNT(*) FROM #__content as a WHERE a.catid = c.id) AS counter"
			. " FROM #__categories as c"
			. " WHERE extension = 'com_content'"
			. " AND parent_id = " . (int)$parentId
			. " ORDER BY lft ASC, title ASC"
			;
		$categories = CKFof::dbLoadObjectList($query);

		return $categories;
	}

	public function getArticlesByCategoryId($parentId) {
		$query = "SELECT id, title, alias, catid, language, 'article' as type, access"
			. " FROM #__content"
			. " WHERE catid = " . (int)$parentId
			. " ORDER BY title ASC"
			;
		$categories = CKFof::dbLoadObjectList($query);

		return $categories;
	}
}
