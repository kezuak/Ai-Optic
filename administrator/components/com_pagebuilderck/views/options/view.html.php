<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */
 
// No direct access
defined('_JEXEC') or die;

use Pagebuilderck\CKView;
use Pagebuilderck\CKfof;

class PagebuilderckViewOptions extends CKView {

	protected $menustyles;

	protected $imagespath;

	protected $imagespath_plugin;

	protected $cktype;

	function display($tpl = null) {

		include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/menustyles.php';
		include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/stylescss.php';

		// load the helper class to construct the styles areas
		$this->menustyles = new MenuStyles();
		$this->imagespath = PAGEBUILDERCK_MEDIA_URI .'/images/menustyles/';
		$this->cktype = $this->input->get('cktype', null);

		if (! $this->cktype && $this->input->get('layout', null, 'cmd') !== 'editor') {
			echo JText::_('COM_PAGEBUILDERCK_ERROR_LAYOUT');
			exit();
		}

		$user = CKFof::getUser();
		$authorised = ($user->authorise('core.create', 'com_pagebuilderck') || (count($user->getAuthorisedCategories('com_pagebuilderck', 'core.create'))));

		if ($authorised !== true)
		{
			throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 403);
			return false;
		}

		parent::display($tpl);
		exit();
	}
}
