<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */

defined('_JEXEC') or die;
?>
<div id="elementscontainer">
	<div class="menulink" tab="tab_edition"><?php echo JText::_('CK_EDITION'); ?></div>
	<div class="tab menustyles ckproperty tab_fullscreen" id="tab_edition">
		<?php // echo PagebuilderckHelper::renderEditionButtons(); ?>
		<div class="menupanetitle"><?php echo JText::_('CK_TITLE'); ?></div>
		<input type="text" id="<?php echo $id; ?>_title" onchange="ckUpdatePreviewArea()" style="width: 700px;" />
		<div class="menupanetitle"><?php echo JText::_('CK_TEXT'); ?></div>
		<textarea id="<?php echo $id; ?>_text" data-id="<?php echo $id; ?>_text"onchange="ckUpdatePreviewArea()"></textarea>
	</div>
	<div class="menulink" tab="tab_blocstyles"><?php echo JText::_('CK_STYLES'); ?></div>
	<div class="tab menustyles ckproperty ckoption" id="tab_blocstyles">
		<div>
			<span class="ckoption-label">
				<img class="ckoption-icon" src="<?php echo $this->imagespath; ?>switch.png" width="16" height="16" />
				<?php echo JText::_('PLG_PAGEBUILDERCK_MESSAGE_CLOSEBUTTON_LABEL'); ?>
			</span>
			<span class="ckoption-field ckbutton-group">
				<input id="message_close_button_1" class="inputbox" name="message_close_button" value="1" type="radio" >
				<label class="ckbutton" for="message_close_button_1">
					<?php echo JText::_('JYES'); ?>
				</label>
				<input id="message_close_button_0" class="inputbox" name="message_close_button" value="0" type="radio" checked>
				<label class="ckbutton" for="message_close_button_0">
					<?php echo JText::_('JNO'); ?>
				</label>
			</span>
			<div class="clr"></div>
		</div>
		<div class="menupanetitle"><?php echo JText::_('CK_ICON'); ?></div>
		<?php echo $this->menustyles->createIconOptions('message', '.editfocus .messageck i.fa', $space = true, $align = true, $removebutton = true) ?>
		<div class="menupanetitle"><?php echo JText::_('CK_VARIATIONS'); ?></div>
		<div class="messageckvariation" onclick="ckApplyMessageVariation('info');">
			<div class="messageck alertck alertck-info">
				<div>
					<div class="messageck_title">Title</div>
					<div class="messageck_text">Text Here</div>
				</div>
			</div>
		</div>
		<div class="messageckvariation" onclick="ckApplyMessageVariation('success');">
			<div class="messageck alertck alertck-success">
				<div>
					<div class="messageck_title">Title</div>
					<div class="messageck_text">Text Here</div>
				</div>
			</div>
		</div>
		<div class="messageckvariation" onclick="ckApplyMessageVariation('warning');">
			<div class="messageck alertck alertck-warning">
				<div>
					<div class="messageck_title">Title</div>
					<div class="messageck_text">Text Here</div>
				</div>
			</div>
		</div>
		<div class="messageckvariation"  onclick="ckApplyMessageVariation('danger');">
			<div class="messageck alertck alertck-danger">
				<div>
					<div class="messageck_title">Title</div>
					<div class="messageck_text">Text Here</div>
				</div>
			</div>
		</div>
		<div class="menustylescustom" data-prefix="message" data-rule=".messageck">
			<?php echo $this->menustyles->createBackgroundColor('message', $usegradient = false, $title = '', $expert = false) ?>
			<?php echo $this->menustyles->createSingleBorders('message', $usegradient = false, $title = '', $expert = false) ?>
		</div>
		<div class="clr"></div>
	</div>
	<div class="menulink" tab="tab_titlestyles"><?php echo JText::_('CK_TITLE_STYLES'); ?></div>
	<div class="tab menustyles ckproperty" id="tab_titlestyles">
		<?php echo $this->menustyles->createTextStyles('messagetitle', 'messageck', '') ?>
	</div>
	<div class="menulink" tab="tab_contentstyles"><?php echo JText::_('CK_TEXT_STYLES'); ?></div>
	<div class="tab menustyles ckproperty" id="tab_contentstyles">
		<?php echo $this->menustyles->createTextStyles('messagetext', 'messageck', '') ?>
	</div>
</div>
<div class="clr"></div>
<script language="javascript" type="text/javascript">
var focus = $ck('.editfocus');
var textID = '<?php echo $id; ?>_text';
function ckLoadEditionPopup() {
	var textID = '<?php echo $id; ?>_text';
	
	// $ck('#previewarea .ckstyle').html(focus.find('.ckstyle').html());
	// $ck('#previewarea .messageck').html(focus.find('.messageck').html());
	// $ck('#previewarea .messageck').attr('class', focus.find('.messageck').attr('class'));
	$ck('#<?php echo $id; ?>_title').val(focus.find('.messageck_title').html());
	$ck('#<?php echo $id; ?>_text').val(focus.find('.messageck_text').html());
	ckLoadEditorOnTheFly(textID);
	ckFillEditionPopup(focus.attr('id'));
	ckGetIconSize('.editfocus .messageck i.fa', '#messageicon-size button');
	ckGetIconPosition('.editfocus .messageck i.fa', '#messageicon-position button');
	ckGetIconMargin('.editfocus .messageck i.fa', '#messageicon_margin');
	$ck('#messageicon-class').val(focus.find('i').attr('data-iconclass'));
}

function ckBeforeSaveEditionPopup() {
	var focus = $ck('.editfocus');
	var textID = '<?php echo $id; ?>_text';
	ckSaveEditorOnTheFly(textID);
	ckUpdatePreviewArea();
//	ckSaveEditionPopup(focus.attr('id'));
//	ckCloseEditionPopup();
}

function ckSaveInlineEditionPopup() {
	ckBeforeSaveEditionPopup();
}

function ckBeforeCloseEditionPopup() {
	var textID = '<?php echo $id; ?>_text';
	ckRemoveEditorOnTheFly(textID);
}

function ckUpdatePreviewArea() {
	$ck('.editfocus .messageck_title').html($ck('#<?php echo $id; ?>_title').val());
	$ck('.editfocus .messageck_text').html($ck('[data-id="' + textID + '"]').val());
	var closeButton = $ck('#elementscontainer input[name="message_close_button"]:checked').val();
	if (closeButton == '1') {
		if (! focus.find('.messageck button.close').length) {
			focus.find('.messageck').prepend('<button type="button" class="close" data-dismiss="alert">×</button>');
		}
		ckMessageCloseButtonDisable();
	} else {
		focus.find('.messageck button.close').remove();
	}
}

function ckSelectIcon(icon) {
	$ck('#messageicon-class').val(iconclass);
	$ck('.editfocus .messageck').find('i').remove();
	$ck('.editfocus .messageck > div').before('<i class="' + iconclass + '" data-iconclass="' + iconclass + '"></i>');
	$ck('.editfocus .messageck i.fa').css('vertical-align', $ck('#messageicon-position button.active').attr('data-position'))
		.addClass($ck('#messageicon-size button.active').attr('data-width'));
	ckSetIconMargin('.editfocus .messageck i.fa', '#messageicon_margin');
}

function ckSelectIcon(icon) {
	$ck('.editfocus .messageck').find('.pbckicon, i').remove();
	$ck('.editfocus .messageck > div').prepend(icon);
	$ck('.editfocus .messageck .pbckicon').css('vertical-align', $ck('#messageicon-position button.active').attr('data-position'))
		.addClass($ck('#messageicon-size button.active').attr('data-width'));
	ckSetIconMargin('.editfocus .messageck i.fa', '#messageicon_margin');
	return $ck('.editfocus .pbckicon');
}

// function _ckSelectIcon(icon) {
	// $ck('.editfocus .messageck').find('i.fa').remove();
	// $ck('.editfocus .messageck_text').before('<i class="' + iconclass + '"></i>');
	// $ck('.editfocus .messageck i.fa').css('vertical-align', $ck('#messageicon-position button.active').attr('data-position'))
		// .addClass($ck('#separatoricon-size button.active').attr('data-width'));
	// ckSetIconMargin('.editfocus .messageck i.fa', '#messageicon_margin');
// }

function ckApplyMessageVariation(variation) {
	$ck('.editfocus .messageck').attr('class', 'messageck alertck alertck-' + variation);
}

ckInitIconSize('.editfocus .messageck i.fa', '#messageicon-size button');
ckInitIconPosition('.editfocus .messageck i.fa', '#messageicon-position button');
</script>