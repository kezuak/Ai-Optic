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

// load the custom plugins
JPluginHelper::importPlugin( 'pagebuilderck' );
//$dispatcher = JEventDispatcher::getInstance();
?>
<style type="text/css">
body {
	margin-left: 310px;
	/*position: relative;*/
}

body.tck-edition-body {
	margin-left: 310px;
	width: calc(100% - 310px);
}

.menuck {
	background-color: #f5f5f5;
	border: 1px solid #e3e3e3;
	display: block;
	left: 0;
	padding: 0 0 10px 0px;
	width: 310px;
	box-sizing: border-box;
	z-index: 1040;
	position: fixed;
	top: 0;
	bottom: 0;
	overflow-y: scroll;
	overflow-x: hidden;
	margin: 0;
	font-family: Segoe UI, arial;
}

.menuck .menuckinfos {
	margin: 5px 0;
	padding: 5px;
	font-size: 12px;
	line-height: 12px;
}

.menuck .menuitemck:hover {
	border: 1px solid #000;
}

.menuck img {
	margin: 1px;
}

.menuck .menuitemck, .workspaceck .menuitemck, .workspaceck .ckcontenttype {
	background: #fff;
	border: 1px solid #ddd;
	float: left;
	height: 40px;
	min-height: 40px;
	margin: 5px 0 0 5px;
	padding: 5px;
	width: 47%;
	max-width: 125px;
	cursor: grab;
	cursor: -webkit-grab;
	box-sizing: border-box;
}

.workspaceck .ckcontenttype {
	margin: 5px;
	background: rgba(255,255,255,0.7);
}

.workspaceck .ckcontenttype {
	cursor: auto;
}

.menuck .menuitemck.grabbing, .workspaceck .menuitemck.grabbing, .workspaceck .ckcontenttype.grabbing {
	cursor: grabbing;
	cursor: -webkit-grabbing;
}

.menuck .menuitemck .menuitemck_title, .workspaceck .menuitemck .menuitemck_title, .workspaceck .ckcontenttype .menuitemck_title {
	font-size: 12px;
	line-height: 15px;
	align-self: center;
}

.menuck .menuitemck .menuitemck_desc, .workspaceck .menuitemck .menuitemck_desc, .workspaceck .ckcontenttype .menuitemck_desc {
	color: #1a1a1a;
	font-size: 10px;
}

.menuck .menuitemck > div, .workspaceck .menuitemck > div, .workspaceck .ckcontenttype > div.ckcontenttype-infos {
	float: right;
	width: calc(100% - 28px);
	min-height: 28px;
	display: flex;
}

.workspaceck .ckcontenttype > div.recipesck-group {
	clear: both;
}

.workspaceck .ckcontenttype {
	height: auto;
}

.workspaceck .ckcontenttype > div[class*="-group"] {
	float: none;
}

.menuck .menuitemck img, .workspaceck .menuitemck img, .workspaceck .ckcontenttype img {
	float: left;
	margin: 5px 0 0 2px;
	width: 20px;
}

.menuitemck_group {
	clear: both;
	padding: 2px 5px;
	text-transform: capitalize;
	background: #2E5A84;
	color: #fff;
	margin: 5px 5px 0;
	font-size: 12px;
	cursor: pointer;
}

.menuck .headerck {
	cursor: pointer;
	padding: 0 0 0 5px;
	background: #ececec;
	border-bottom: 1px solid #ddd;
	font-size: 18px;
	min-height: 40px;

}

#workspaceparentck.collapsedck .menuck {
	width: 75px;
}

#workspaceparentck.collapsedck .menuck .headercktext,
#workspaceparentck.collapsedck .menuck .menuckinfos,
#workspaceparentck.collapsedck .menuck .menuitemck > div,
#workspaceparentck.collapsedck .menuck .menuitemck_title,
#workspaceparentck.collapsedck .menuck .menuitemck_desc {
	display: none;
}

#workspaceparentck.collapsedck .menuck .menuitemck {
	width: 50px;
	height: 50px;
	margin: 5px 2px;
}

/*#workspaceparentck:not(.collapsedck) .workspaceck {
	margin-left: 235px;
}

#module-form #workspaceparentck:not(.collapsedck) .workspaceck {
	margin-left: 260px;
}*/

.menuck .headercktext {
	display: inline-block;
	width: 145px;
	line-height: 32px;
	vertical-align: top;
	padding-left: 5px;
}

.menuck .menuckinner {
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	height: 100%;
}

.menuck > .inner {
	/*position: relative;*/
	max-height: 100%;
	height: calc(100vh - 50px);
	overflow-y: auto;
	margin-left: 50px;
}

.menuck > .inner:not(.ckelementsedition) {
	display: none;
}

.menuck .ckcolumnsedition {
	display: none;
}

.menuck .headerckicon {
	width: 40px;
	height: 40px;
	float: right;
	display: inline-block;
	box-sizing: border-box;
	line-height: 32px;
	padding: 0;
	background: #cfcfcf;
	color: #555;
	font-size: 1.3em;
	border: none;
	border-left-width: medium;
	border-left-style: none;
	border-left-color: currentcolor;
	border-left: 1px solid #aaa;
	box-shadow: 0 0 15px #b5b5b5 inset;
	text-align: center;
	cursor: pointer;
	font-weight: normal;
	border-radius: 0;
}

.collapsedck .menuck .headerckicon {
	transform: rotate(180deg);
}

.menuck .headerckicon.cksave {
	font-size: 13px;
	line-height: 35px;
}

.menuck input {
	width: auto;
	margin-left: 5px;
}
/* Fix for Safari */
.ckpopup, .menuck {
	overflow: visible !important;
}

.menuckpanels {
	width: 50px;
	background: #1C3D5C;
	height: calc(100vh - 40px);
	float: left;
	border-right: 1px solid #ddd;
}

.menuckpanelfake img,
.menuckpanel img {
	margin: 8px;
}

.menuckpanelfake,
.menuckpanel {
	color: #ddd;
	text-align: center;
	padding: 12px 0;
}

.menuckpanelfake .fa,
.menuckpanel .fa {
	font-size: 24px;
}

.menuckpanelfake.active, .menuckpanelfake:hover,
.menuckpanel.active, .menuckpanel:hover {
	background: #2E5A84;
	color: #fff;
	cursor: pointer;
}

.menuckbootomtoolbar {
	height: 40px;
	background: #2E5A84;
	position: absolute;
	bottom: 0;
	width: 100%;
	left: 0;
	text-align: center;
}

.menuckbootomtoolbar span.menuckbootomtoolbar-button {
	height: 40px;
	width: 30px;
	border-radius: 0;
	margin: 0;
	padding: 0;
	line-height: 40px;
	color: #fff;
	padding: 0 10px;
	opacity: 0.7;
	display: inline-block;
	cursor: pointer;
	font-size: 16px;
}

.menuckbootomtoolbar span.menuckbootomtoolbar-button:hover, .menuckbootomtoolbar span.menuckbootomtoolbar-button.active {
	opacity: 1;
}

.ckresponsivebutton {
	width: 170px;
}

.ckleftpanelsearch {
	position: relative;
}

.ckleftpanelsearch input, .cksearchleftpanel {
	margin: 0 5px;
	width: 96%;
	height: 32px;
	padding: 0 5px;
	box-sizing: border-box;
	border-radius: 0;
}

.ckleftpanelsearch .fa, .cksearchleftpanel {
	font-size: 11px;
	position: absolute;
	top: 12px;
	right: 20px;
	color: #6d6d6d;
	cursor: pointer;
}

.ckleftpanelsearch .fa-times {
	right: 40px;
	font-size: 12px;
	line-height: 10px;
}

.ckleftpanelsearch .fa-times:hover {
	color: #000;
}

.ckmyelements.headerck {
	margin-top: 10px;
}

.ckaddonsdisplaytype {
	display: none;
}
<?php
$displaytype = $app->getUserState('pagebuilderck.addons.displaytype', 'grid');
echo '#ckaddonsdisplaytype' . $displaytype . ' { display: block; }';
?>
</style>
<?php
// get global component params
$componentParams = JComponentHelper::getParams('com_pagebuilderck');
$view = $input->get('view', 'page');
$type = $input->get('type', '', 'string');
?>
<div id="menuck" class="menuck clearfix ckinterface">
	<div class="menuckpanels">
		<div class="menuckpanel cktip active" data-target="addons" title="<?php echo JText::_('CK_ADDONS') ?>" data-placement="right"><span class="fa fa-puzzle-piece"></span></div>
		<div id="ckeditionbutton" data-target="edition" class="menuckpanel cktip" title="<?php echo JText::_('CK_EDITION') ?>" data-placement="right" onclick="ckShowEditionPopup('ckcontenttypeedition')"><span class="fa fa-edit"></span></div>
		<div id="ckresponsivesettingsbutton" data-target="responsive" class="menuckpanel cktip" title="<?php echo JText::_('CK_RESPONSIVE_SETTINGS') ?>" data-placement="right"><span class="fa fa-mobile" style="font-size:2.8em;"></span></div>
		<div id="ckcustomcsssettingsbutton" data-target="customcss" class="menuckpanelfake cktip" title="<?php echo JText::_('CK_CUSTOMCSS') ?>" data-placement="right"><span class="fa fa-file-code-o"></span></div>

	</div>
	<div class="inner clearfix ckelementsedition menuckpaneltarget" data-target="addons">
		<div class="headerck">
			<span class="headercktext" style="width: 130px;"><?php echo JText::_('CK_ELEMENTS'); ?></span>
			<span class="ckbutton-group" style="margin: 5px;">
				<span class="headerckdisplaytype ckbutton <?php echo ($displaytype == 'list' ? 'active' : '') ?>" data-type="list" onclick="ckSetAddonsDisplaytypeState('list')"><i class="fa fa-list cktip" data-placement="bottom" title="<?php echo JText::_('CK_DISPLAY_TYPE_LIST') ?>" onclick="ckSearchAddon()"></i></span>
				<span class="headerckdisplaytype ckbutton <?php echo ($displaytype == 'grid' ? 'active' : '') ?>" data-type="grid" onclick="ckSetAddonsDisplaytypeState('grid')"><i class="fa fa-th cktip" data-placement="bottom" title="<?php echo JText::_('CK_DISPLAY_TYPE_GRID') ?>" onclick="ckSearchAddon()"></i></span>
			</span>
		</div>
		<div class="menuckinfos"><?php echo JText::_('COM_PAGEBUILDERCK_INSERT_CONTENT'); ?></div>
		<div id="ckaddonsearch" class="ckleftpanelsearch">
			<input type="text" class="cksearchleftpanel" placeholder="<?php echo JText::_('CK_SEARCH') ?>" onchange="ckSearchAddon()" />
			<i class="fa fa-search cktip" title="<?php echo JText::_('CK_SEARCH') ?>" onclick="ckSearchAddon()"></i>
			<i class="fa fa-times cktip" title="<?php echo JText::_('CK_CLEAN') ?>" onclick="ckSearchAddonClear()"></i>
		</div>
		<?php 
//		$items = Pagebuilderck\CKFof::triggerEvent( 'onPagebuilderckAddItemToMenu' );
		
		$items = PagebuilderckHelper::getPluginsMenuItemType('contenttype', $type);
//		$groups = PagebuilderckHelper::getPluginsMenuItemTypeByGroup();

		?>
		<div id="ckaddonsdisplaytypegrid" class="ckaddonsdisplaytype">
		<?php
		$pagebuilderckTypesImagesArray = array();
		if (count($items)) {
			foreach ($items as $item) {
//				if ($item->type != 'row' && $item->type != 'rowinrow') continue;
				$pagebuilderckTypesImagesArray[$item->type] = $item->image;
				$className = $item->type == 'row' ? '' : 'ckcontenttype';
				?>
				<div data-type="<?php echo $item->type ?>" data-group="<?php echo $item->group ?>" class="menuitemck <?php echo $className ?>" title="<b><?php echo $item->title ?></b><br /><?php echo $item->description ?>">
					<div>
						<div class="menuitemck_title"><?php echo $item->title ?></div>
					</div>
					<img src="<?php echo $item->image ?>" />
					<?php /*<div class="menuitemck_remove" onclick="ckRemoveItem($ck(this).parent())">×</div> */ ?>
				</div>
				<?php
			}
		}
		?>
		</div>
		
		<div style="clear:both;"></div>
	</div>
	<div class="inner clearfix ckresponsiveedition menuckpaneltarget" data-target="responsive">
		<div class="menuckcollapse headerck">
			<span class="headercktext" style="width:auto;"><?php echo JText::_('CK_RESPONSIVE_SETTINGS'); ?></span>
			<?php /*<span class="headerckicon cktip" title="<?php echo JText::_('CK_SAVE_CLOSE'); ?>" onclick="ckShowResponsiveSettings(true);">×</span> */ ?>
		</div>
		<div id="cktoolbarResponsive" class="clearfix ckinterface" style="overflow:visible;background:none;padding:5px;">
			<span class="ckbutton-group">
				<span id="ckresponsive1button" class="ckbutton ckresponsivebutton" onclick="ckSwitchResponsive(1)" data-range="1"><span class="fa fa-mobile" ></span> <?php echo JText::_('CK_PHONE') ?> / <small><?php echo JText::_('CK_PORTRAIT') ?></small></span>
				<input id="ckresponsive1value" type="text" value="<?php echo $componentParams->get('responsive1value', '320') ?>" data-default="" disabled="disabled" title="<?php echo JText::_('CK_SET_RESPONSIVE_VALUE_IN_OPTIONS') ?>" style="width:40px;"/>
			</span>
			<span class="ckbutton-group">
				<span id="ckresponsive2button" class="ckbutton ckresponsivebutton" onclick="ckSwitchResponsive(2)" data-range="2"><span class="fa fa-mobile" style="font-size: 1.4em;vertical-align: bottom;transform:rotate(90deg);"></span> <?php echo JText::_('CK_PHONE') ?> / <small><?php echo JText::_('CK_LANDSCAPE') ?></small></span>
				<input id="ckresponsive2value" type="text" value="<?php echo $componentParams->get('responsive2value', '480') ?>" class="cktip" data-default="" disabled="disabled" title="<?php echo JText::_('CK_SET_RESPONSIVE_VALUE_IN_OPTIONS') ?>" style="width:40px;"/>
			</span>
			<span class="ckbutton-group">
				<span id="ckresponsive3button" class="ckbutton ckresponsivebutton" onclick="ckSwitchResponsive(3)" data-range="3"><span class="fa fa-tablet" ></span> <?php echo JText::_('CK_TABLET') ?> / <small><?php echo JText::_('CK_PORTRAIT') ?></small></span>
				<input id="ckresponsive3value" type="text" value="<?php echo $componentParams->get('responsive3value', '640') ?>" class="cktip" data-default="" disabled="disabled" title="<?php echo JText::_('CK_SET_RESPONSIVE_VALUE_IN_OPTIONS') ?>" style="width:40px;"/>
			</span>
			<span class="ckbutton-group">
				<span id="ckresponsive4button" class="ckbutton ckresponsivebutton" onclick="ckSwitchResponsive(4)" data-range="4"><span class="fa fa-tablet" style="font-size: 1.4em;vertical-align: bottom;transform:rotate(90deg);"></span> <?php echo JText::_('CK_TABLET') ?> / <small><?php echo JText::_('CK_LANDSCAPE') ?></small></span>
				<input id="ckresponsive4value" type="text" value="<?php echo $componentParams->get('responsive4value', '800') ?>" class="cktip" data-default="" disabled="disabled" title="<?php echo JText::_('CK_SET_RESPONSIVE_VALUE_IN_OPTIONS') ?>" style="width:40px;"/>
			</span>
			<span class="ckbutton-group">
				<span id="ckresponsive5button" class="ckbutton ckresponsivebutton" onclick="ckSwitchResponsive(5)" data-range="5"><span class="fa fa-desktop" ></span> <?php echo JText::_('CK_COMPUTER') ?></span>
			</span>
		</div>
	</div>
	<div class="inner clearfix ckcolumnsedition" >
		<div class="headerck">
			<span class="headerckicon" onclick="ckHideColumnsEdition()">×</span>
			<span class="headercktext"><?php echo JText::_('CK_COLUMNS'); ?></span>
		</div>
		<div class="ckcolumnsoptions">
			<div class="ckbutton-group" style="margin-top: 5px;">
				<input id="autowidth" name="autowidth" value="1" type="radio" onchange="ckUpdateAutowidth($ck('.rowck.ckfocus'), this.value);" />
				<label class="ckbutton btn" for="autowidth" style="width:auto;margin-left:5px;" ><?php echo JText::_('CK_AUTO_WIDTH') ?></label>
				<input id="advlayout" name="autowidth" value="0" type="radio" onchange="ckUpdateAutowidth($ck('.rowck.ckfocus'), this.value);" />
				<label class="ckbutton btn" for="advlayout" style="width:auto;"><?php echo JText::_('CK_ADVANCED_LAYOUT') ?></label>
			</div>
			<div id="ckgutteroptions">
				<div class="menuckinfos"><?php echo JText::_('CK_GUTTER') ?></div>
				<input class="ckguttervalue" type="text" onchange="ckUpdateGutter($ck('.rowck.ckfocus'), this.value);" style="margin-left:5px;" />
			</div>
			<div>
				<div class="ckbutton ckbutton-success" onclick="ckAddBlock($ck('.rowck.ckfocus'));" style="display: block;">+ <?php echo JText::_('CK_ADD_COLUMN') ?></div>
			</div>
			<div id="ckcolumnsuggestions">

			</div>
		</div>
	</div>
	<div class="menuckbootomtoolbar">
		<?php if ($input->get('id', 0, 'int') 
				&& $input->get('option', '', 'cmd') == 'com_pagebuilderck' 
				&& $input->get('view', '', 'cmd') == 'page' 
				&& \Pagebuilderck\CKFof::isAdmin()){ ?>
		<a class="" target="_blank" href="<?php echo JUri::root(true) ?>/index.php?option=com_pagebuilderck&view=page&id=<?php echo $input->get('id', '', 'int') ?>"><span class="menuckbootomtoolbar-button cktip" title="<?php echo JText::_('CK_PREVIEW_FRONT') ?>"><span class="fa fa-eye"></span><span></a>
		<?php } else if ($input->get('id', 0, 'int') && $input->get('option', '', 'cmd') == 'com_content' && $input->get('view', '', 'cmd') == 'article' && \Pagebuilderck\CKFof::isAdmin()) { ?>
		<a class="" target="_blank" href="<?php echo JUri::root(true) ?>/index.php?option=com_content&view=article&id=<?php echo $input->get('id', '', 'int') ?>"><span class="menuckbootomtoolbar-button cktip" title="<?php echo JText::_('CK_PREVIEW_FRONT') ?>"><span class="fa fa-eye"></span><span></a>
		<?php }?>
		<?php if ( 
				($input->get('option', '', 'cmd') == 'com_content' && $input->get('view', '', 'cmd') == 'article' && \Pagebuilderck\CKFof::isAdmin())
			|| ($input->get('option', '', 'cmd') == 'com_flexicontent' && $input->get('view', '', 'cmd') == 'article' && \Pagebuilderck\CKFof::isAdmin())
			|| ($input->get('option', '', 'cmd') == 'com_modules' && $input->get('view', '', 'cmd') == 'module' && \Pagebuilderck\CKFof::isAdmin())
			|| ($input->get('option', '', 'cmd') == 'com_advmodulesmanager' && $input->get('view', '', 'cmd') == 'module' && \Pagebuilderck\CKFof::isAdmin())
				) { ?>
		<span class="menuckbootomtoolbar-button cktip" onclick="ckSaveAsPage()" title="<?php echo JText::_('CK_SAVE_AS_PAGE') ?>"><span class="fa fa-floppy-o"></span></span>
		<?php } ?>
		<span id="ckhtmlchecksettingsbutton" class="menuckbootomtoolbar-button cktip" onclick="ckCheckHtml()" title="<?php echo JText::_('CK_HTML_CSS') ?>"><span class="fa fa-dot-circle-o"></span></span>
		<span id="ckundo" class="menuckbootomtoolbar-button cktip" onclick="ckUndo()" title="<?php echo JText::_('CK_UNDO') ?>"><span class="fa fa-mail-reply"></span></span>
		<span id="ckundo" class="menuckbootomtoolbar-button cktip" onclick="ckRedo()" title="<?php echo JText::_('CK_REDO') ?>"><span class="fa fa-mail-forward"></span></span>
	</div>
	<div id="ckcustomcssedition" style="display: none;"></div>
</div>
<script type="text/javascript">
// create tooltip for the items
ckMakeTooltip($ck('#menuck .menuitemck'));
// check if we are in frontend
var isSiteCK = '<?php echo \Pagebuilderck\CKFof::isSite() ?>';
$ck('#menuck').hide();

var ckcustomcsseditor;
jQuery(document).ready(function (){
	$ck('#menuck').fadeIn();
	ckMakeContentTypeSortable();
});

if (isSiteCK) {
	$ck(window).on("load resize scroll", function() {
		if (! $ck('.workspaceck').length || ! $ck('#workspaceparentck').length) return;
		var menuToWorkspaceOffset = $ck('#workspaceparentck').offset().left -50 - $ck('#menuck').width()
		if (menuToWorkspaceOffset < 0) {
			$ck('.workspaceck').css({'margin-left': -menuToWorkspaceOffset + 'px'});
		} else {
			$ck('.workspaceck').css({'margin-left': '0'});
		}
	});
}

$ck('.menuckpanel').click(function() {
	ckActivatePanel($ck(this).attr('data-target'));
});

$ck('#ckcustomcsssettingsbutton').click(function() {
	ckOpenCustomCssEditor();
});

$ck('.menuitemck_group').click(function() {
	$ck(this).find('+ div').toggle('fast');
});

</script>