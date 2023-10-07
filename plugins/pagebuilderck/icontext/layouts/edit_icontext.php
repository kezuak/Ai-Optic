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
	<div class="menulink" tab="tab_iconstyles"><?php echo JText::_('CK_ICON_EDITION'); ?></div>
	<div class="tab menustyles ckproperty" id="tab_iconstyles">
		<?php echo $this->menustyles->createIcon('icon', '.editfocus .iconck i.fa', false, false, true) ?>
		<div class="ckoption">
			<div class="menupanetitle"><?php echo JText::_('CK_POSITION'); ?></div>
			<div class="ckbutton" onclick="ckUpdateLayout(this)" data-layout="top"><?php echo JText::_('CK_TOP'); ?></div>
			<div class="ckbutton" onclick="ckUpdateLayout(this)" data-layout="bottom"><?php echo JText::_('CK_BOTTOM'); ?></div>
			<div class="ckbutton" onclick="ckUpdateLayout(this)" data-layout="left"><?php echo JText::_('CK_LEFT'); ?></div>
			<div class="ckbutton" onclick="ckUpdateLayout(this)" data-layout="right"><?php echo JText::_('CK_RIGHT'); ?></div>
		</div>
		<?php echo $this->menustyles->createBackground('icon') ?>
		<?php echo $this->menustyles->createDimensions('icon', true, true) ?>
		<?php echo $this->menustyles->createDecoration('icon') ?>
		<?php echo $this->menustyles->createShadow('icon') ?>
		<?php echo $this->menustyles->createCustom('icon') ?>
	</div>
	<div class="menulink" tab="tab_titleedition"><?php echo JText::_('CK_TITLE_EDITION'); ?></div>
	<div class="tab menustyles ckproperty" id="tab_titleedition">
		<div class="menupanetitle"><?php echo JText::_('CK_TITLE_CONTENT'); ?></div>
		<div class="ckoption">
			<input type="text" id="<?php echo $id; ?>_title" onchange="ckUpdatePreviewArea()" style="width: 90%;" />
		</div>
		<div class="ckoption">
			<select type="text" id="<?php echo $id; ?>_headingtag" onchange="ckUpdatePreviewArea()" style="width: 90%;" >
				<option value="div">DIV</option>
				<option value="h1">H1</option>
				<option value="h2">H2</option>
				<option value="h3">H3</option>
				<option value="h4">H4</option>
				<option value="h5">H5</option>
				<option value="h6">H6</option>
			</select>
		</div>
		<div class="ckoption">
			<span class="ckoption-label">
				<img class="ckoption-icon" src="<?php echo $this->imagespath; ?>text_signature.png" width="16" height="16" />
				<?php echo JText::_('CK_CSS_CLASS'); ?>
			</span>
			<span class="ckoption-field">
				<input id="titlecss" name="titlecss" class="inputbox"  value="" type="text" />
			</span>
			<div class="clr"></div>
		</div>
		<div class="menupanetitle"><?php echo JText::_('CK_TITLE_STYLES'); ?></div>
		<?php echo $this->menustyles->createTextStyles('title', 'titleck', false) ?>
	</div>
	<div class="menulink" tab="tab_textedition"><?php echo JText::_('CK_TEXT_EDITION'); ?></div>
	<div class="tab menustyles ckproperty tab_fullscreen" id="tab_textedition">
		<?php // echo PagebuilderckHelper::renderEditionButtons(); ?>
		<textarea id="<?php echo $id; ?>_text" data-id="<?php echo $id; ?>_text"></textarea>
	</div>
	<div class="menulink" tab="tab_textstyles"><?php echo JText::_('CK_TEXT_STYLES'); ?></div>
	<div class="tab menustyles ckproperty" id="tab_textstyles">
		<?php echo $this->menustyles->createTextStyles('text', 'titleck', false) ?>
	</div>
	<div class="menulink" tab="tab_blocstyles"><?php echo JText::_('CK_BLOCK_STYLES'); ?></div>
	<div class="tab menustyles ckproperty" id="tab_blocstyles">
		<?php echo $this->menustyles->createBlocStyles('bloc') ?>
	</div>
</div>

<script language="javascript" type="text/javascript">
function ckLoadEditionPopup() {
	var focus = $ck('.editfocus');
	var textID = '<?php echo $id; ?>_text';

	content =  focus.find('.textck').html();
	content = ckContentToEditor(content);
	$ck('#<?php echo $id; ?>_text').val(content);

	$ck('#<?php echo $id; ?>_title').val(focus.find('.titleck').html());
	$ck('#<?php echo $id; ?>_headingtag').val(focus.find('.titleck')[0].tagName.toLowerCase());
	ckUpdatePreviewArea();

	ckLoadEditorOnTheFly(textID);

	ckFillEditionPopup(focus.attr('id'));
	ckGetIconSize('.editfocus .iconck i.fa', '#iconicon-size button');
	ckGetIconPosition('.editfocus .iconck i.fa', '#iconicon-position button');
	ckGetIconMargin('.editfocus .iconck i.fa', '#iconicon_margin');
	$ck('#iconicon-class').val(focus.find('.iconck > i').attr('data-iconclass'));
}

function ckUpdateLayout(btn) {
	$ck(btn).parent().find('.active').removeClass('active');
	$ck(btn).addClass('active');
	var layout = $ck(btn).attr('data-layout');
	var focus = $ck('.editfocus');
	// for B/C
	if (! focus.find('.contentck').length) {
		focus.find('> .inner').append('<div class="contentck" />');
		var contentck = focus.find('.contentck');
		contentck.append(focus.find('.titleck'));
		contentck.append(focus.find('.textck'));
	}

	focus.attr('data-layout', layout); 
}

/*
 * Method automatically called in ckCloseEditionPopup() if exists
 */
function ckBeforeCloseEditionPopup() {
	var textID = '<?php echo $id; ?>_text';
	ckRemoveEditorOnTheFly(textID);
}

function ckBeforeSaveEditionPopup() {
	var textID = '<?php echo $id; ?>_text';
	ckSaveEditorOnTheFly(textID);
	var content = $ck('[data-id="' + textID + '"]').val();
	var focus = $ck('.editfocus');
	focus.find('.textck').html(content);
}

function ckSaveInlineEditionPopup() {
	ckBeforeSaveEditionPopup();
}

function ckUpdatePreviewArea() {
	var focus = $ck('.editfocus');
	$ck('.titleck', focus).html($ck('#<?php echo $id; ?>_title').val());
	// update the title
	var oldheading = focus.find('.titleck');
	var css = $ck('#titlecss').val() ? $ck('#titlecss').val() : '';
	var newheading = $ck('<' + $ck('#<?php echo $id; ?>_headingtag').val() + ' class="titleck' + (css ? ' ' + css : css) + '" contenteditable="true"></' + $ck('#<?php echo $id; ?>_headingtag').val() + '>');
	oldheading.after(newheading);
	newheading.append(oldheading.html());
	oldheading.remove();
}

function ckSelectIcon(icon) {
	var focusIcon = $ck('.editfocus .iconck');
	focusIcon.empty().append(icon);
	return focusIcon;
}

ckInitIconSize('.editfocus .iconck i.fa', '#iconicon-size button');
ckInitIconPosition('.editfocus .iconck i.fa', '#iconicon-position button');
</script>