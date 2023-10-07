<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */


// no direct access
defined('_JEXEC') or die;
//if (! defined('CK_LOADED')) define('CK_LOADED', 1);

//use Pagebuilderck\CKFof;

// set variables
include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/defines.php';

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_pagebuilderck')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// loads the language files from the frontend
$lang	= JFactory::getLanguage();
$lang->load('com_pagebuilderck', JPATH_SITE . '/components/com_pagebuilderck', $lang->getTag(), false);
$lang->load('com_pagebuilderck', JPATH_SITE, $lang->getTag(), false);

// loads the helper in any case
require_once PAGEBUILDERCK_PATH . '/helpers/cktext.php';
require_once PAGEBUILDERCK_PATH . '/helpers/ckpath.php';
require_once PAGEBUILDERCK_PATH . '/helpers/ckfile.php';
require_once PAGEBUILDERCK_PATH . '/helpers/ckfolder.php';
require_once PAGEBUILDERCK_PATH . '/helpers/ckfof.php';
require_once PAGEBUILDERCK_PATH . '/helpers/pagebuilderck.php';
require_once PAGEBUILDERCK_PATH . '/helpers/ckframework.php';
require_once PAGEBUILDERCK_PATH . '/helpers/ckcontroller.php';
require_once PAGEBUILDERCK_PATH . '/helpers/ckmodel.php';
require_once PAGEBUILDERCK_PATH . '/helpers/ckview.php';

Pagebuilderck\CKFramework::load();
$input = Pagebuilderck\CKFof::getInput();

$controller	= Pagebuilderck\CKController::getInstance('Pagebuilderck');
$controller->execute($input->get('task'));
//$controller->redirect();
