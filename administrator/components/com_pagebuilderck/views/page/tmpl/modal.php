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
$canEdit    = $user->authorise('core.edit', 'com_pagebuilderck');
?>

<?php 
// loads the css and js files
include PAGEBUILDERCK_PATH . '/views/page/tmpl/include.php';
?>

<button id="applyBtn" type="button" style="display:none;" onclick="Joomla.submitbutton('page.apply');"></button>
<button id="saveBtn" type="button" style="display:none;" onclick="Joomla.submitbutton('page.save');"></button>
<button id="closeBtn" type="button" style="display:none;" onclick="Joomla.submitbutton('page.cancel');"></button>
<?php 
// loads the context menu
include PAGEBUILDERCK_PATH . '/views/page/tmpl/main.php';
include PAGEBUILDERCK_PATH . '/views/page/tmpl/contextmenu.php';
