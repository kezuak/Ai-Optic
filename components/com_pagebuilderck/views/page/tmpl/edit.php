<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */

defined('_JEXEC') or die;

$user		= JFactory::getUser();
$app		= JFactory::getApplication();

$assoc		= isset($app->item_associations) ? $app->item_associations : 0;
// $canEdit    = $user->authorise('core.edit', 'com_pagebuilderck');

// loads the css and js files
include_once(JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/views/page/tmpl/include.php');

// loads the css and js files 
include_once(JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/views/page/tmpl/main.php');
