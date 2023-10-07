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
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
$app = JFactory::getApplication();
$input = $app->input;
// get global component params
$componentParams = JComponentHelper::getParams('com_pagebuilderck');
?>
<div id="cktoolbarLoadPageOptions" class="clearfix" style="display:none;">
	<div class="">
		<h1>
		<?php echo JText::_('CK_SELECT') ?>
		<div style="font-size:10px;white-space:nowrap;margin-top:-7px;"><?php echo JText::_('CK_OPTIONS') ?></div>
		</h1>
	</div>
	<div style="border-top: 1px solid #ddd;">
		<br />
		<p><?php echo JText::_('CK_HOW_TO_LOAD_PAGE') ?></p>
		<br />
		<div class="cktoolbar" class="clearfix" style="text-align:center;">
			<span class="ckbutton ckaction" onclick="ckLoadPage(this, 'replace')"><?php echo JText::_('CK_REPLACE') ?></span>
			<span class="ckbutton ckaction" onclick="ckLoadPage(this, 'top')"><?php echo JText::_('CK_TOP_PAGE') ?></span>
			<span class="ckbutton ckaction" onclick="ckLoadPage(this, 'bottom')"><?php echo JText::_('CK_END_PAGE') ?></span>
		</div>
	</div>
</div>
<div id="cktoolbarExportPage" class="clearfix" style="display:none;">
	<div class="">
		<h1>
		<?php echo JText::_('CK_EXPORT_TO_PAGE') ?>
		<div style="font-size:10px;white-space:nowrap;margin-top:-7px;"><?php echo JText::_('CK_OPTIONS') ?></div>
		</h1>
	</div>
	<div style="border-top: 1px solid #ddd;">
		<br />
		<p><?php echo JText::_('CK_HOW_TO_LOAD_PAGE') ?></p>
		<br />
		<div class="cktoolbar" class="clearfix" style="text-align:center;">
			<span class="ckbutton ckaction" onclick="ckLoadPage(this, 'replace')"><?php echo JText::_('CK_REPLACE') ?></span>
			<span class="ckbutton ckaction" onclick="ckLoadPage(this, 'top')"><?php echo JText::_('CK_TOP_PAGE') ?></span>
			<span class="ckbutton ckaction" onclick="ckLoadPage(this, 'bottom')"><?php echo JText::_('CK_END_PAGE') ?></span>
		</div>
	</div>
</div>
<?php
if ($favoriteClass = PagebuilderckHelper::getParams('favorites')) {
	$favoriteClass->loadStylesPanel();
}
// load the params message in the page
echo PagebuilderckHelper::showParamsMessage(false);
