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

// get global component params
$pagebuilderckParams = JComponentHelper::getParams('com_pagebuilderck');

//if (! defined('CK_LOADED')) define('CK_LOADED', 1);

// set variables
define('PAGEBUILDERCK_PLATFORM', 'joomla');
define('PAGEBUILDERCK_BASE_PATH', JPATH_BASE . '/components/com_pagebuilderck');
define('PAGEBUILDERCK_PATH', JPATH_SITE . '/administrator/components/com_pagebuilderck');
define('PAGEBUILDERCK_PARAMS_PATH', JPATH_SITE . '/administrator/components/com_pagebuilderck/pro');
define('PAGEBUILDERCK_PROJECTS_PATH', JPATH_SITE . '/administrator/components/com_pagebuilderck/projects');
define('PAGEBUILDERCK_ADMIN_URL', JUri::root(true) . '/administrator/index.php?option=com_pagebuilderck');
define('PAGEBUILDERCK_BASE_URL', JUri::base(true) . '/index.php?option=com_pagebuilderck');
define('PAGEBUILDERCK_ADMIN_GENERAL_URL', JUri::root(true) . '/administrator/index.php?option=com_pagebuilderck');
define('PAGEBUILDERCK_MEDIA_URI', JUri::root(true) . '/media/com_pagebuilderck');
define('PAGEBUILDERCK_MEDIA_URL', PAGEBUILDERCK_MEDIA_URI);
define('PAGEBUILDERCK_MEDIA_PATH', JPATH_ROOT . '/media/com_pagebuilderck');
define('PAGEBUILDERCK_PLUGIN_URL', PAGEBUILDERCK_MEDIA_URI);
define('PAGEBUILDERCK_TEMPLATES_PATH', JPATH_SITE . '/templates');
define('PAGEBUILDERCK_SITE_ROOT', JPATH_ROOT);
define('PAGEBUILDERCK_URI', JUri::root(true) . '/administrator/components/com_pagebuilderck');
define('PAGEBUILDERCK_URI_ROOT', JUri::root(true));
define('PAGEBUILDERCK_URI_BASE', JUri::base(true));
define('PAGEBUILDERCK_NESTEDROWS', $pagebuilderckParams->get('nestedrows', '0', 'int'));
define('PAGEBUILDERCK_VERSION', simplexml_load_file(PAGEBUILDERCK_PATH . '/pagebuilderck.xml')->version);
define('PAGEBUILDERCK_ISJ4', version_compare(JVERSION, "4") >= 0);
define('PAGEBUILDERCK_LOADEDITORCSS', $pagebuilderckParams->get('loadeditorcss', '0', 'int'));

// include the classes
require_once PAGEBUILDERCK_PATH . '/helpers/ckinput.php';
require_once PAGEBUILDERCK_PATH . '/helpers/cktext.php';
require_once PAGEBUILDERCK_PATH . '/helpers/ckfile.php';
require_once PAGEBUILDERCK_PATH . '/helpers/ckfolder.php';
require_once PAGEBUILDERCK_PATH . '/helpers/ckfof.php';
//require_once PAGEBUILDERCK_PATH . '/helpers/helper.php';
//require_once PAGEBUILDERCK_PATH . '/helpers/ckcontroller.php';
//require_once PAGEBUILDERCK_PATH . '/helpers/ckmodel.php';
//require_once PAGEBUILDERCK_PATH . '/helpers/ckview.php';