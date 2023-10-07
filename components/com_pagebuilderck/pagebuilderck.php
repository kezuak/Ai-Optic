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
if (! defined('CK_LOADED')) define('CK_LOADED', 1);

include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/defines.php';

// Include dependancies
include_once PAGEBUILDERCK_PATH . '/helpers/cktext.php';
include_once PAGEBUILDERCK_PATH . '/helpers/ckpath.php';
include_once PAGEBUILDERCK_PATH . '/helpers/ckfile.php';
include_once PAGEBUILDERCK_PATH . '/helpers/ckfolder.php';
include_once PAGEBUILDERCK_PATH . '/helpers/ckfof.php';
include_once PAGEBUILDERCK_PATH . '/helpers/pagebuilderck.php';
include_once PAGEBUILDERCK_PATH . '/helpers/ckframework.php';
include_once PAGEBUILDERCK_PATH . '/helpers/ckcontroller.php';
include_once PAGEBUILDERCK_PATH . '/helpers/ckmodel.php';
include_once PAGEBUILDERCK_PATH . '/helpers/ckview.php';

// load admin language file for editing mode
if (JFactory::getApplication()->input->get('view') === 'edit') {
	$lang	= JFactory::getLanguage();
	$lang->load('com_pagebuilderck', JPATH_ROOT . '/components/com_pagebuilderck', $lang->getTag(), false);
}

$input = Pagebuilderck\CKFof::getInput();

$controller	= Pagebuilderck\CKController::getInstance('Pagebuilderck');
$controller->execute($input->get('task'));
