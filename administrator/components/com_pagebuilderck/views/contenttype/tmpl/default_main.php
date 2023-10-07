<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */

defined('_JEXEC') or die;
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

$user		= JFactory::getUser();
$app		= JFactory::getApplication();
$input = $app->input;
$canEdit    = $user->authorise('core.edit', 'com_pagebuilderck');
$appendUrl = $input->get('layout', '', 'string') == 'modal' ? '&layout=modal&tmpl=component' : '&layout=edit';
// get global component params
// $params = JComponentHelper::getParams('com_pagebuilderck');
// get item params
// if (! is_object($this->item->params)) $this->item->params = new JRegistry($this->item->params);
// merge params
// $params->merge($this->item->params);
// $params->merge($this->item->params);
$conf = JFactory::getConfig();
?>
<div style="display:none;">
	<form>
	<?php
	// Load the editor Tinymce or JCE
	$editor = $conf->get('editor') == 'jce' ? 'jce' : 'tinymce';
	$editor = JEditor::getInstance($editor);
	echo $editor->display('ckeditor', $html = '', $width = '', $height = '200px', $col='', $row='', $buttons = true, $id = 'ckeditor');
	?>
	</form>
</div>

<div id="mainck" class="container-fluid">
	
	<div id="maincktabcontent">

<?php if ($canEdit) { ?>
<form action="<?php echo JRoute::_('index.php?option=com_pagebuilderck&view=contenttype' . $appendUrl . '&type=' . $this->item->type);?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" class="form-validate">
		<?php if (\Pagebuilderck\CKFof::isSite() && $app->input->get('layout') != 'modal') { ?>
	<div class="btn-toolbar">
		<div class="btn-group">
			<button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('contenttype.apply')">
				<span class="icon-apply"></span><?php echo JText::_('JSAVE') ?>
			</button>
		</div>
		<div class="btn-group">
			<button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('contenttype.save')">
				<span class="icon-ok"></span><?php echo JText::_('CK_SAVE_CLOSE') ?>
			</button>
		</div>
		<div class="btn-group">
			<button type="button" class="btn" onclick="Joomla.submitbutton('contenttype.cancel')">
				<span class="icon-cancel"></span><?php echo JText::_('JCANCEL') ?>
			</button>
		</div>
	</div>
	<?php } ?>

	<div class="clearfix">
		<div class="ckoption">
			<span class="ckoption-label" style="width:auto;">
				<?php echo JText::_('CK_CONTENT_TYPE'); ?> :
			</span>
			<span class="ckoption-value">
				<b><?php echo ucfirst($this->item->type) ?></b>
			</span>
			<div class="clr"></div>
		</div>
	</div>
	<div class="mainmenulink menulink current" tab="tab_interface"><?php echo JText::_('CK_EDITION'); ?></div>
	<div class="clr"></div>
	<input type="hidden" name="htmlcode" id="htmlcode" value="" />
	<input type="hidden" name="stylecode" id="stylecode" value="" />
	<input type="hidden" name="option" value="com_pagebuilderck" />
	<input type="hidden" name="type" value="<?php echo $this->item->type; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="controller" value="com_pagebuilderck" />
	<input type="hidden" name="return" value="<?php echo $input->getCmd('return'); ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>
<div class="maintab menustyles current ckproperty" id="tab_interface">
<div id="workspaceparentck">
	<?php
	// loads the menu
	include_once(JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/views/contenttype/tmpl/menu.php');
	?>
	<div id="ckcontenttypeedition" data-type="<?php echo $this->item->type ?>">
		<?php
		if ($this->item->stylecode) {
			echo $this->item->stylecode;
		} else { ?>
			<div class="ckstyle"></div>
		<?php } ?>
		<div id="workspaceck" class="pagebuilderck inner workspaceck<?php echo (\Pagebuilderck\CKFof::isSite() ? ' pagebuilderckfrontend' : '') ?> ckcontenttypeedition" >
			<?php
			if ($this->item->htmlcode) {
				echo $this->item->htmlcode;
			} else { ?>

			<?php }
			?>
		</div>
	</div>
</div>
<?php } else {
	if (!$canEdit) echo JText::_('COM_PAGEBUILDERCK_NORIGHTS_TO_EDIT');
} ?>
</div> <?php // fin tab_interface ?>
		
</div><?php // fin maincktabcontent ?>
</div>
<script>
$ck('#maincktabcontent div.maintab:not(.current)').hide();
$ck('.mainmenulink', $ck('#mainck')).each(function(i, tab) {
	$ck(tab).click(function() {
		if ($ck('#popup_favoriteck').length) {
			ckCloseFavoritePopup(true);
		}
		$ck('#maincktabcontent div.maintab').hide();
		$ck('.mainmenulink', $ck('#mainck')).removeClass('current');
		if ($ck('#' + $ck(tab).attr('tab')).length)
			$ck('#' + $ck(tab).attr('tab')).show();
		$ck(this).addClass('current');
	});
});
</script>

