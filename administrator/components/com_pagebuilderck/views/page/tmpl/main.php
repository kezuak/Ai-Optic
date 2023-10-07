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
$input = $app->input;

$assoc		= isset($app->item_associations) ? $app->item_associations : 0;
$canEdit    = $user->authorise('core.edit', 'com_pagebuilderck');
$appendUrl = $this->input->get('layout', '', 'string') == 'modal' ? '&layout=modal&tmpl=component' : '&layout=edit';
// get global component params
$params = JComponentHelper::getParams('com_pagebuilderck');
// get item params
if (! is_object($this->item->params)) $this->item->params = new JRegistry($this->item->params);
// merge params
$params->merge($this->item->params);
$params->merge($this->item->params);
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
<form action="<?php echo JRoute::_('index.php?option=com_pagebuilderck&view=page' . $appendUrl . '&id=' . $this->item->id);?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" class="form-validate">
		<?php if (\Pagebuilderck\CKFof::isSite() && $app->input->get('layout') != 'modal') { ?>
	<div class="btn-toolbar">
		<div class="btn-group">
			<button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('page.apply')">
				<span class="icon-apply"></span> <?php echo JText::_('JSAVE') ?>
			</button>
		</div>
		<div class="btn-group">
			<button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('page.save')">
				<span class="icon-ok"></span> <?php echo JText::_('CK_SAVE_CLOSE') ?>
			</button>
		</div>
		<div class="btn-group">
			<button type="button" class="btn" onclick="Joomla.submitbutton('page.cancel')">
				<span class="icon-cancel"></span> <?php echo JText::_('JCANCEL') ?>
			</button>
		</div>
	</div>
	<?php } ?>

	<div class="mainmenulink menulink current" tab="tab_interface"><?php echo JText::_('CK_EDITION'); ?></div>
	<div class="mainmenulink menulink" tab="tab_options"><?php echo JText::_('CK_OPTIONS'); ?></div>
	<div class="clr"></div>

	<div class="form-inline form-inline-header clearfix ckinterface">
		<div>
			<label class="required" for="title" id="title-lbl">
				<?php echo JText::_('COM_PAGEBUILDERCK_TITLE'); ?>
				<span class="star">&nbsp;*</span>
			</label>
			<input type="text" aria-required="true" required="required" size="40" class="form-control input-xlarge input-large-text required" value="<?php echo $this->item->title ?>" id="title" name="title">
		</div>
	</div>

	<div class="maintab menustyles ckproperty" id="tab_options">
		<?php 
		// loads the options layout
		include_once(PAGEBUILDERCK_PATH . '/views/page/tmpl/options.php');
		?>
	</div>

	<input type="hidden" name="htmlcode" id="htmlcode" value="" />
	<input type="hidden" name="params" id="params" value="" />
	<input type="hidden" name="option" value="com_pagebuilderck" />
	<input type="hidden" name="id" value="<?php echo $this->item->id; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="controller" value="com_pagebuilderck" />
	<input type="hidden" name="return" value="<?php echo $this->input->getCmd('return'); ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>
<div class="maintab menustyles current ckproperty" id="tab_interface">
<div id="workspaceparentck">
	<?php 
	// loads the menu
	include_once(PAGEBUILDERCK_PATH . '/views/page/tmpl/contextmenu.php');
	include_once(PAGEBUILDERCK_PATH . '/views/page/tmpl/menu.php');
	include_once(PAGEBUILDERCK_PATH . '/views/page/tmpl/toolbar.php');
	?>
	<div id="workspaceck" class="pagebuilderck workspaceck<?php echo (\Pagebuilderck\CKFof::isSite() ? ' pagebuilderckfrontend' : '') ?><?php echo ($this->input->get('iscontenttype', 0, 'int') === 1 ? ' ckiscontenttype' : '') ?>" >
		<?php
		if ($this->item->htmlcode) {
			echo $this->item->htmlcode;
		} else { ?>
			<div class="googlefontscall"></div>
		<?php }
		?>
	</div>
</div>
<?php // load the modal for the restoration ?>
<div id="pagebuilderckRestoreModalck" class="pagebuilderckRestoreModalck pagebuilderckModalck" style="display:none;">
	<h3><?php echo JText::_('CK_RESTORE') ?></h3>
	<div class="ckinterface">
		<?php
		if ($this->item->id) {
			$path = JPATH_ROOT . '/administrator/components/com_pagebuilderck/backup/' . $this->item->id . '_bak';
			if (JFolder::exists($path)) {
				$files = JFolder::files($path, '.pbck', false, false);
				if (count($files)) {
					natsort($files);
					$i = 0;
					foreach ($files as $file) {
						if (stristr($file, 'locked_')) {
							$backupdate = str_replace('locked_' . $this->item->id . '_', '', JFile::stripExt($file));
							$isLocked = true;
						} else {
							$backupdate = str_replace('backup_' . $this->item->id . '_', '', JFile::stripExt($file));
							$isLocked = false;
						}
						$date = DateTime::createFromFormat('d-m-Y-G-i-s', $backupdate);
						$lockedIcon = $isLocked ? '<span class="fa fa-lock"></span>' : '<span class="fa fa-unlock"></span>';

						echo '<div class="restoreline restoreline' . $i . ' clearfix">
								<span class="span6">
									<span class="cklabel cklabel-info">' . $date->format('d-M-Y H:i:s') . '</span>
								</span>
								<span class="span6">
									<span onclick="ckToggleLockedBackup(' . $this->item->id . ',\'' . $backupdate . '\', ' . $i . ')" data-locked="' . ($isLocked ? ' 1' : '0') . '" class="ckbutton locked' . ($isLocked ? ' active' : '') . '" style="margin:0 3px;display:inline-block;">' . $lockedIcon . '</span>
									<a class="ckbutton" href="javascript:void(0)" onclick="ckDoRestoration(' . $this->item->id . ', \'' . $backupdate . '\', ' . $i . ')">
										<span class="icon icon-reply"></span>' . JText::_('CK_DO_RESTORATION') . '
									</a>
									<span class="processing" style="width:16px;margin:0 3px;display:inline-block;">&nbsp;</span>
									</span>
								</div>';
						$i++;
					}
				} else {
					echo '<div class="ckalert">' . JText::_('CK_NO_RESTORE_FILE_FOUND') . '</div>';
				}
			} else {
				echo '<div class="ckalert">' . JText::_('CK_NO_RESTORE_FILE_FOUND') . '</div>';
			}
		}
		?>
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
<?php
require(JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/views/page/tmpl/submitform.php');