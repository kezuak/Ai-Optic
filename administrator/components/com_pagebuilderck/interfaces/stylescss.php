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
$app = JFactory::getApplication();
$objclass = $app->input->get('objclass', '');
$objid = $app->input->get('ckobjid', '');
$expertmode = $app->input->get('expertmode', false);
$saveFunc = $app->input->get('savefunc', 'ckSaveEditionPopup', 'cmd');

$showheight = (stristr($objclass, 'mainbanner') OR stristr($objclass, 'bannerlogo') OR stristr($objclass, 'horizmenu')) ? true : false;
$showwidth = ((stristr($objclass, 'wrapper') OR stristr($objclass, 'bannerlogo') OR stristr($objclass, 'banner') OR stristr($objclass, 'column')) AND !stristr($objclass, 'content')) ? true : false;
$isContent = (stristr($objclass, 'content') OR stristr($objclass, 'bannerlogodesc')) ? true : false;
$isBody = stristr($objclass, 'body') ? true : false;
$isWrapper = false;
$isLogo = (stristr($objclass, 'bannerlogo')) ? true : false;
$isModulesContainer = stristr($objclass, 'flexiblemodules') ? true : false;
$isMaincontentContainer = stristr($objclass, 'maincontent') ? true : false;
$isHoriznav = (stristr($objclass, 'horiznav') || stristr($objclass, 'bannermenu')) ? true : false;
$isFavorite = (stristr($objclass, 'pbckmyfavorite')) ? true : false;
$isColumn = (stristr($objclass, 'blockck')) ? true : false;
$isRow = (stristr($objclass, 'rowck')) ? true : false;
require_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/menustyles.php';
$menustyles = new MenuStyles();
?>

<div class="menuck clearfix fixedck">
	<div class="inner clearfix">
		<div class="headerck">
			<span class="headerckicon cktip" data-placement="bottom" title="<?php echo JText::_('CK_SAVE_CLOSE'); ?>" onclick="<?php echo $saveFunc ?>();ckGetPreviewAreastylescss();ckCloseEdition();">×</span>
			<span class="headerckicon cksave cktip" data-placement="bottom" title="<?php echo JText::_('CK_APPLY'); ?>" onclick="ckGetPreviewAreastylescss('', '', '', false, '<?php echo $saveFunc ?>');"><span class="fa fa-check"></span></span>
			<span class="headercktext"><?php echo JText::_('CK_CSS_EDIT'); ?></span>
		</div>
<div id="elementscontainer">
<?php
		$menulinktext = $isWrapper ? JText::_('CK_WRAPPER_STYLES') : JText::_('CK_STYLES');
		$blocinfos = $isWrapper ? JText::_('CK_WRAPPER_INFOS') : JText::_('CK_BLOC_INFOS');
		$blocdesc = $isWrapper ? JText::_('CK_WRAPPER_DESC') : JText::_('CK_BLOC_DESC');
		?>
		<div class="clr"></div>
		<div class="tab menustylescustom" data-prefix="rowbg" data-rule=""></div>
		<div id="elementscontent" class="ckinterface">
			<?php if ($isRow) { ?>
			<div class="menulink" tab="tab_rowbgstyles"><?php echo JText::_('CK_ROW_WRAPPER'); ?></div>
			<div class="tab menustyles ckproperty" id="tab_rowbgstyles">
				<?php echo $menustyles->createBlocStyles('rowbg') ?>
				<div class="clr"></div>
			</div>
			<?php } ?>

			<div class="menulink" tab="tab_blocstyles"><?php echo $menulinktext; ?></div>
			<div class="tab menustyles ckproperty" id="tab_blocstyles">
				<?php echo $menustyles->createBlocStyles('bloc', $objclass, $expertmode) ?>
				<div class="clr"></div>
			</div>
			<?php if (! $isFavorite) { ?>
			<div class="menulink" tab="tab_animations"><?php echo JText::_('CK_ANIMATIONS'); ?></div>
			<div class="tab menustyles ckproperty" id="tab_animations">
				<?php echo $menustyles->createAnimations('bloc') ?>
				<div class="clr"></div>
			</div>
			<div class="menulink" tab="tab_effects"><?php echo JText::_('CK_EFFECTS'); ?></div>
			<div class="tab menustyles ckproperty" id="tab_effects">
				<?php echo $menustyles->createEffects('bloc') ?>
				<div class="clr"></div>
			</div>
			<div class="menulink" tab="tab_videobgstyles"><?php echo JText::_('CK_VIDEO_BACKGROUND_STYLES'); ?></div>
			<div class="tab menustyles ckproperty" id="tab_videobgstyles">
				<?php echo $menustyles->createVideobgStyles() ?>
				<div class="clr"></div>
			</div>
			<div class="menulink" tab="tab_overlaystyles"><?php echo JText::_('CK_OVERLAY_STYLES'); ?></div>
			<div class="tab menustyles ckproperty" id="tab_overlaystyles">
				<?php echo $menustyles->createOverlayStyles() ?>
				<div class="clr"></div>
			</div>
			<?php } ?>
			<?php if ($isRow) { ?>
			<div class="menulink" tab="tab_divider"><?php echo JText::_('CK_DIVIDER'); ?></div>
			<div class="tab menustyles ckproperty" id="tab_divider">
				<?php echo $menustyles->createDivider() ?>
				<div class="clr"></div>
			</div>
			<?php } ?>
			<?php if ($isRow || $isColumn) { ?>
			<div class="menulink" tab="tab_link"><?php echo JText::_('CK_LINK'); ?></div>
			<div class="tab menustyles ckproperty ckoption" id="tab_link">
				<div class="ckoption">
					<span class="ckoption-label">
						<img class="ckoption-icon" src="<?php echo PAGEBUILDERCK_MEDIA_URI; ?>/images/menustyles/link.png" width="16" height="16" />
						<?php echo JText::_('CK_LINK_URL'); ?></span>
					<span class="ckoption-field">
						<input class="inputbox" type="text" name="wraplinkurl" id="wraplinkurl" value="" />
						<span class="ckbuttonstyle" style="line-height: 27px;padding: 5px 8px;" onclick="CKBox.open({url: '<?php echo JUri::base(true) ?>/index.php?option=com_pagebuilderck&view=links&type=files&tmpl=component&fieldid=wraplinkurl', id:'ckfilesmodal', style: {padding: '10px'} })">+</span>
					</span>
				</div>
				<div class="ckoption">
					<span class="ckoption-label">
						<img class="ckoption-icon" src="<?php echo PAGEBUILDERCK_MEDIA_URI; ?>/images/menustyles/text_signature.png" width="16" height="16" />
						<?php echo JText::_('CK_LINK_TEXT'); ?></span>
					<span class="ckoption-field">
						<input class="inputbox" type="text" name="wraplinktext" id="wraplinktext" value="" />
					</span>
					<div class="clr"></div>
				</div>
				<div class="ckoption">
					<span class="ckoption-label">
						<img class="ckoption-icon" src="<?php echo PAGEBUILDERCK_MEDIA_URI; ?>/images/menustyles/text_signature.png" width="16" height="16" />
						<?php echo JText::_('CK_CSS_CLASS'); ?></span>
					<span class="ckoption-field">
						<input class="inputbox" type="text" name="wraplinkclass" id="wraplinkclass" value="" />
					</span>
					<div class="clr"></div>
				</div>
				<div class="ckoption">
					<span class="ckoption-label">
						<img class="ckoption-icon" src="<?php echo PAGEBUILDERCK_MEDIA_URI; ?>/images/menustyles/link_go.png" width="16" height="16" />
						<?php echo JText::_('CK_LINK_TARGET'); ?></span>
					<span class="ckoption-field">
						<select class="inputbox" type="list" style="width:128px;" name="wraplinktarget" id="wraplinktarget" value="" >
							<option value="default"><?php echo JText::_('CK_DEFAULT'); ?></option>
							<option value="_blank"><?php echo JText::_('CK_LINK_TARGET_NEW'); ?></option>
						</select>
					</span>
					<div class="clr"></div>
				</div>
				<div class="ckoption">
					<span class="ckoption-label">
						<img class="ckoption-icon" src="<?php echo PAGEBUILDERCK_MEDIA_URI; ?>/images/menustyles/link_add.png" width="16" height="16" />
						<?php echo JText::_('CK_LINK_ICON'); ?></span>
						<span class="ckoption-field ckbutton-group">
							<input type="radio" class="inputbox" name="wraplinkicon" id="wraplinkiconYes" value="1" checked />
							<label for="wraplinkiconYes" class="ckbutton"><?php echo JText::_('JYES') ?></label>
							<input type="radio" class="inputbox" name="wraplinkicon" id="wraplinkiconNo" value="0"  />
							<label for="wraplinkiconNo" class="ckbutton"><?php echo JText::_('JNO') ?></label>
						</span>
					<div class="clr"></div>
				</div>
				<div class="clr"></div>
			</div>
			<div class="menulink" tab="tab_linkstyles"><?php echo JText::_('CK_LINK_STYLES'); ?></div>
			<div class="tab menustyles ckproperty" id="tab_linkstyles">
				<div class="menustylescustom" data-prefix="link" data-rule="a.pbck-link-wrap"><?php echo $menustyles->createBlocStyles('link') ?></div>
				<div class="clr"></div>
			</div>
			<div class="menulink" tab="tab_linkstyleshover"><?php echo JText::_('CK_LINK_STYLES_HOVER'); ?></div>
			<div class="tab menustyles ckproperty" id="tab_linkstyleshover">
				<div class="menustylescustom" data-prefix="linkhover" data-rule="a.pbck-link-wrap:hover|.ckfakehover:hover > a.pbck-link-wrap"><?php echo $menustyles->createBlocStyles('linkhover') ?></div>
				<div class="clr"></div>
			</div>
			<?php } ?>
		</div>
</div>
<div class="clr"></div>
</div>
	</div>
<script language="javascript" type="text/javascript">
ckInitColorPickers();
ckInitOptionsTabs();
ckInitAccordions();
function ckBeforeSaveEditionPopup() {
	ckApplyParallax();
	ckApplyLinkWrap();
	// empty to avoid function from items to be called
}
</script>
<?php
exit();