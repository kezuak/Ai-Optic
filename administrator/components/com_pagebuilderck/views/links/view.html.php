<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */
 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Pagebuilderck\CKView;
use Pagebuilderck\CKfof;

/**
 * View class for a list of Maximenuck.
 */
class PagebuilderckViewLinks extends CKView {

	protected $items;

	protected $articles;

	protected $menus;

	/**
	 * Display the view
	 */
	public function display($tpl = null) {
		$user = CKFof::getUser();
		$authorised = ($user->authorise('core.create', 'com_pagebuilderck') || (count($user->getAuthorisedCategories('com_pagebuilderck', 'core.create'))));

		if ($authorised !== true)
		{
			throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 403);
			return false;
		}

		$this->menus = $this->get('Menus');
		$this->articles = $this->get('ArticleCategoriesRoot');
		$this->items = $this->get('Files');

		parent::display($tpl);
	}
}
