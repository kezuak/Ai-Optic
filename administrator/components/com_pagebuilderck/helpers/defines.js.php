<?php
/**
 * @copyright	Copyright (C) 2019. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */

// No direct access
defined('_JEXEC') or die;

use Pagebuilderck\CKfof;

// get global component params
$pagebuilderckParams = JComponentHelper::getParams('com_pagebuilderck');
// loads shared colors from the template
$colorsFromTemplate = PagebuilderckHelper::loadTemplateColors();
$colorsFromSettings = PagebuilderckHelper::loadSettingsColors();
?>
<script>
	var PAGEBUILDERCK = {
		TOKEN : '<?php echo JFactory::getSession()->getFormToken() ?>=1',
		URIBASE : '<?php echo JUri::base(true) ?>',
		URIBASEABS : '<?php echo JUri::base() ?>',
		URIROOT : '<?php echo JUri::root(true) ?>',
		URIROOTABS : '<?php echo JUri::root() ?>',
		URIPBCK : '<?php echo JUri::base() ?>index.php?option=com_pagebuilderck',
		MEDIA_URI : '<?php echo PAGEBUILDERCK_MEDIA_URI ?>',
		ADMIN_URL : '<?php echo PAGEBUILDERCK_ADMIN_URL ?>',
		NESTEDROWS : '<?php echo $pagebuilderckParams->get('nestedrows', '0', 'int') ?>',
		COLORSFROMTEMPLATE : '<?php echo $colorsFromTemplate ?>',
		COLORSFROMSETTINGS : '<?php echo $colorsFromSettings ?>',
		ITEMACL : '<?php echo (int)CKFof::userCan('core.itemacl') ?>',
		USERGROUPS : '<?php echo implode(',', JFactory::getUser()->groups) ?>',
		TOOLTIPS : '<?php echo $pagebuilderckParams->get('tooltips', '1', 'int') ?>',
		RESPONSIVERANGE : '<?php echo $pagebuilderckParams->get('responsiverange', 'reducing', 'string') ?>',
		IMAGE_PATH_FIX : '<?php echo $pagebuilderckParams->get('image_path_fix', '1') ?>',
		USERID : '<?php echo JFactory::getUser()->id ?>',
		CLIPBOARD : '',
		FASTEDITION : '<?php echo $pagebuilderckParams->get('fastedition', '1', 'int') ?>',
		STYLESTOLOAD : '<?php echo implode(',',(array)$pagebuilderckParams->get('stylestoload', '', 'array')) ?>'
	};
</script>