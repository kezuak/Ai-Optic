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
		<div class="menustylesblocktitle"><?php echo JText::_('CK_LINK') ?></div>
		<div class="menustylesblockaccordion">
			<div>
				<span class="ckoption-label">
					<img class="ckoption-icon" src="<?php echo $this->imagespath; ?>link.png" width="16" height="16" />
					<?php echo JText::_('CK_LINK_URL'); ?>
				</span>
				<span class="ckoption-field">
					<input id="linkurl" name="linkurl" class="inputbox"  value="" type="text" />
					<span class="ckbuttonstyle" style="line-height: 27px;padding: 5px 8px;" onclick="CKBox.open({url: '<?php echo JUri::base(true) ?>/index.php?option=com_pagebuilderck&view=links&type=all&tmpl=component&fieldid=linkurl', id:'ckfilesmodal', style: {padding: '10px'} })">+</span>
				</span>
				<div class="clr"></div>
			</div>
			<div>
				<span class="ckoption-label">
					<img class="ckoption-icon" src="<?php echo $this->imagespath; ?>text_signature.png" width="16" height="16" />
					<?php echo JText::_('CK_REL_TAG'); ?>
				</span>
				<span class="ckoption-field">
					<input id="linkrel" name="linkrel" class="inputbox"  value="" type="text" />
				</span>
				<div class="clr"></div>
			</div>
			<div>
				<span class="ckoption-label">
					<img class="ckoption-icon" src="<?php echo $this->imagespath; ?>text_signature.png" width="16" height="16" />
					<?php echo JText::_('CK_CSS_CLASS'); ?>
				</span>
				<span class="ckoption-field">
					<input id="linkcss" name="linkcss" class="inputbox"  value="" type="text"  />
				</span>
				<div class="clr"></div>
			</div>
			<div>
				<span class="ckoption-label">
					<img class="ckoption-icon" src="<?php echo $this->imagespath; ?>hand-point-090.png" width="16" height="16" />
					<?php echo JText::_('CK_ONCLICK'); ?>
				</span>
				<span class="ckoption-field">
					<input id="linkonlick" name="linkonlick" class="inputbox"  value="" type="text"  />
				</span>
				<div class="clr"></div>
			</div>
			<div>
				<span class="ckoption-label">
					<img class="ckoption-icon" src="<?php echo $this->imagespath; ?>link_add.png" width="16" height="16" />
					<?php echo JText::_('CK_TARGET'); ?>
				</span>
				<span class="ckoption-field">
					<input id="linktarget" name="linktarget" class="inputbox"  value="" type="text"  />
				</span>
				<div class="clr"></div>
			</div>
		</div>
	</div>
	<div class="menulink" tab="tab_blocstyles"><?php echo JText::_('CK_STYLES'); ?></div>
	<div class="tab menustyles ckproperty" id="tab_blocstyles">
		<?php echo $this->menustyles->createBackground('icon') ?>
		<?php echo $this->menustyles->createDimensions('icon') ?>
		<?php echo $this->menustyles->createDecoration('icon') ?>
		<?php echo $this->menustyles->createShadow('icon') ?>
	</div>
</div>

<script language="javascript" type="text/javascript">
var focus = $ck('.editfocus');
function ckLoadEditionPopup() {
	ckFillEditionPopup(focus.attr('id'));
	ckGetIconSize('.editfocus .iconck i.fa', '#iconicon-size button');
	ckGetIconPosition('.editfocus .iconck i.fa', '#iconicon-position button');
	ckGetIconMargin('.editfocus .iconck i.fa', '#iconicon_margin');
	$ck('#iconicon-class').val(focus.find('.iconck > i').attr('data-iconclass'));
}

function ckBeforeSaveEditionPopup() {
	ckUpdatePreviewArea();
}

function ckUpdatePreviewArea() {
	var focusIcon = focus.find('.iconck');
	var html = focus.find('i,.pbckicon')[0].outerHTML;
	var linkurl = $ck('#linkurl').val();
	// add link
	if (linkurl) {
		var linkrel = $ck('#linkrel').val();
		var linkcss = $ck('#linkcss').val();
		var linkonlick = $ck('#linkonlick').val();
		var linktarget = $ck('#linktarget').val();
		html = '<a href="' + linkurl + '"'
				+ (linkrel ? ' rel="' + linkrel + '"' : '')
				+ (linkcss ? ' class="' + linkcss + '"' : '')
				+ (linkonlick ? ' onclick="' + linkonlick + '"' : '')
				+ (linktarget ? ' target="' + linktarget + '"' : '')
				+ '>'
				+ html 
				+ '</a>';
	}
	focusIcon.html(html);
}

function ckSelectIcon(icon) {
	var focusIcon = $ck('.editfocus .iconck');
	focusIcon.empty().append(icon);
	return focusIcon;
}

ckInitIconSize('.editfocus .iconck i.fa', '#iconicon-size button');
ckInitIconPosition('.editfocus .iconck i.fa', '#iconicon-position button');
</script>