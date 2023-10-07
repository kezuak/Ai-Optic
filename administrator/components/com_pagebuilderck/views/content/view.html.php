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

class PagebuilderckViewContent extends CKView {

	/**
	 * About view display method
	 * @return void
	 * */
	function display($tpl = null) {
		$tpl = $this->input->get('cktype', null, 'cmd');

		if ($tpl == null) {
			echo JText::_('COM_PAGEBUILDERCK_ERROR_LAYOUT');
			exit();
		}
		$standarditems = array('readmore');
		$layout = JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/views/content/tmpl/default_' . $tpl . '.php';
		if (file_exists($layout) && in_array($tpl, $standarditems)) {
			include_once($layout);
		} else {
			// load the custom plugins
			CKFof::importPlugin('pagebuilderck');

			// loads all additional pagebuilderck items via plugins
			CKFof::triggerEvent( 'onPagebuilderckLoadItemContent' .  ucfirst($tpl) );
		}
		exit();
	}
}
