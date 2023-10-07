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
	<div class="menulink" tab="tab_edition"><?php echo JText::_('CK_TEXT'); ?></div>
	<div class="tab menustyles ckproperty ckoption" id="tab_edition">
		<div>
			<span class="ckoption-label">
				<img class="ckoption-icon" src="<?php echo $this->imagespath; ?>switch.png" width="16" height="16" />
				<?php echo JText::_('CK_USE_TEXT'); ?></span>
			<span class="ckoption-field">
				<select class="inputbox" type="list" value="" name="usetext" id="usetext" style="width: 70px;">
					<option value="0"><?php echo JText::_('JNO'); ?></option>
					<option value="1" selected><?php echo JText::_('JYES'); ?></option>
				</select>
			</span>
			<div class="clr"></div>
		</div>
		<textarea id="<?php echo $id; ?>_text" onchange="ckUpdatePreviewArea()" style=""></textarea>
		<?php echo $this->menustyles->createTextStyles('separator', 'separatorck') ?>
	</div>
	<div class="menulink" tab="tab_linestyles"><?php echo JText::_('CK_LINE'); ?></div>
	<div class="tab menustyles ckproperty" id="tab_linestyles">
			<?php echo $this->menustyles->createBlocStyles('line', 'line', true, false, 'j3', false) ?>
	</div>
	<div class="menulink" tab="tab_blocstyles"><?php echo JText::_('CK_ICON'); ?></div>
	<div class="tab menustyles ckproperty" id="tab_blocstyles">
			<?php //echo $this->menustyles->createIcon('icon', '.editfocus .pbckicon', false, false, true, false, true, true) ?>
		<?php echo $this->menustyles->createIconStyles('icon', 'icon', '.editfocus .buttonck .pbckicon', false, true, false, true, true, false) ?>
	</div>
</div>
<div class="menustylescustom" data-prefix="line" data-rule=".separatorck_before|.separatorck_after"></div>
<script language="javascript" type="text/javascript">
function ckLoadEditionPopup() {
	var focus = $ck('.editfocus');
	$ck('#<?php echo $id; ?>_text').val(focus.find('.separatorck_text').html());
//	ckUpdatePreviewArea();
	ckFillEditionPopup(focus.attr('id'));
//	ckGetIconSize('.editfocus .separatorck i.fa', '#separatoricon-size button');
//	ckGetIconPosition('.editfocus .separatorck i.fa', '#separatoricon-position button');
//	ckGetIconMargin('.editfocus .separatorck i.fa', '#separatoricon_margin');
}

function ckBeforeSaveEditionPopup() {
	var focus = $ck('.editfocus');
	focus.find('.separatorck').html($ck('#previewareabloc .separatorck').html());
	focus.find('.ckstyle').html($ck('#previewareabloc .ckstyle').html());

	if ($ck('#usetext').val() === '0') {
		$ck('.editfocus .separatorck').attr('data-text', '0');
	} else {
		$ck('.editfocus .separatorck_text').html($ck('#<?php echo $id; ?>_text').val());
		$ck('.editfocus .separatorck').attr('data-text', '1');
	}
}

function ckUpdatePreviewArea() {
	$ck('.editfocus .separatorck_text').html($ck('#<?php echo $id; ?>_text').val());

}

function ckSelectIcon(icon) {
	$ck('.editfocus .separatorck').find('i.fa, .pbckicon').remove();
	$ck('.editfocus .separatorck_text').before(icon);
	$ck('.editfocus .separatorck i.fa').css('vertical-align', $ck('#separatoricon-position button.active').attr('data-position'))
		.addClass($ck('#separatoricon-size button.active').attr('data-width'));
	ckSetIconMargin('.editfocus .separatorck i.fa', '#separatoricon_margin');
	return $ck('.editfocus .pbckicon');
}

ckInitIconSize('.editfocus .separatorck i.fa', '#iconicon-size button');
ckInitIconPosition('.editfocus .separatorck .pbckicon', '#iconicon-position button');
</script>