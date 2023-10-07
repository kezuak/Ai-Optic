<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */

defined('_JEXEC') or die;

use Pagebuilderck\CKFof;

if (!defined('PAGEBUILDERCK_MEDIA_URI'))
{
	define('PAGEBUILDERCK_MEDIA_URI', JUri::root(true) . '/media/com_pagebuilderck');
}

require_once(PAGEBUILDERCK_PATH . '/helpers/defines.js.php');

$doc = JFactory::getDocument();
$editor = JFactory::getConfig()->get('pagebuilderck_replaced_editor', '') ? JFactory::getConfig()->get('pagebuilderck_replaced_editor') : JFactory::getConfig()->get('editor');
$editor = $editor == 'jce' ? 'jce' : 'tinymce';

$input = CKFof::getInput();

?>
<link rel="stylesheet" href="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/pagebuilderck.css?ver=<?php echo PAGEBUILDERCK_VERSION ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/ckframework.css" type="text/css" />
<?php // needs also to load the frontend styles to make the same visual as on frontend ?>
<link rel="stylesheet" href="<?php echo JUri::root(true) ?>/components/com_pagebuilderck/assets/pagebuilderck.css?ver=<?php echo PAGEBUILDERCK_VERSION ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo JUri::root(true) ?>/components/com_pagebuilderck/assets/font-awesome.min.css" type="text/css" />
<link rel="stylesheet" href="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/colpick.css" type="text/css" />
<link rel="stylesheet" href="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/ckbox.css" type="text/css" />
<link rel="stylesheet" href="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/codemirrorck.css" type="text/css" />
<?php 
if (PAGEBUILDERCK_LOADEDITORCSS === '1') { 
	$defaultTemplate = PagebuilderckHelper::getDefaultTemplate();
	if (file_exists(JPATH_ROOT . '/templates/<?php echo $defaultTemplate ?>/css/editor.css')) {
	?>
	<link rel="stylesheet" href="<?php echo JUri::root(true) ?>/templates/<?php echo $defaultTemplate ?>/css/editor.css" type="text/css" />
<?php
	}

	}
// call google fonts
$googlefontsFromTemplate = PagebuilderckHelper::loadTemplateGooglefonts();
if (! empty($googlefontsFromTemplate)) {
?><link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><?php
	foreach ($googlefontsFromTemplate as $i => $gfont) {
		PagebuilderckHelper::loadTemplateGooglefontFile($gfont);
		/*
		?><link href="https://fonts.googleapis.com/css2?family=<?php echo $gfont ?>&display=swap" rel="stylesheet"><?php
		*/
	}
}
?>
<script type="text/javascript">
	var URIROOT = PAGEBUILDERCK.URIROOT; // BC for old plugins
	var URIBASE = PAGEBUILDERCK.URIBASE; // BC for old plugins
	var CLIPBOARDCK = '';
	var CLIPBOARDCOLORCK = '';
	var BLOCCKSTYLESBACKUP = '';
	var FAVORITELOCKED = '';
	var JoomlaCK = {};
	var PAGEBUILDERCK_MEDIA_URI = '<?php echo PAGEBUILDERCK_MEDIA_URI ?>';
	var PAGEBUILDERCK_ADMIN_URL = '<?php echo PAGEBUILDERCK_ADMIN_URL ?>';
	//var PAGEBUILDERCK_TOKEN = cktoken = '<?php echo JFactory::getSession()->getFormToken() ?>=1';
	var PAGEBUILDERCK_EDITOR = '<?php echo $editor ?>';
	PAGEBUILDERCK.ISCONTENTTYPE = '<?php echo $input->get('iscontenttype', 0, 'int') ?>';
</script>
<?php 
// modal view : strange that Joomla 4 do not want to load the script at the end in modal view
if ((version_compare(JVERSION,'4') <= 1) || $input->get('layout', '') === 'modal' || (CKFof::isSite() && $input->get('layout', '') === 'edit')) { ?>
<?php if (version_compare(JVERSION,'4') < 1) JHtml::_('behavior.core'); ?>
<?php CKFof::addScript(PAGEBUILDERCK_MEDIA_URI . '/assets/jqueryck.min.js'); ?>
<?php CKFof::addScript(PAGEBUILDERCK_MEDIA_URI . '/assets/jquery-uick-custom.min.js'); ?>
<?php CKFof::addScript(PAGEBUILDERCK_MEDIA_URI . '/assets/ckbox.js'); ?>
<?php CKFof::addScript(PAGEBUILDERCK_MEDIA_URI . '/assets/codemirrorck.js'); ?>
<?php CKFof::addScript(PAGEBUILDERCK_MEDIA_URI . '/assets/php.js'); ?>
<?php CKFof::addScript(PAGEBUILDERCK_MEDIA_URI . '/assets/javascript.js'); ?>
<?php CKFof::addScript(PAGEBUILDERCK_MEDIA_URI . '/assets/ckframework.js'); ?>
<?php CKFof::addScript(JUri::root(true) . '/components/com_pagebuilderck/assets/jquery-uick.min.js'); ?>
<?php CKFof::addScript(PAGEBUILDERCK_MEDIA_URI . '/assets/ckbrowse.js?ver=' . PAGEBUILDERCK_VERSION); ?>
<?php CKFof::addScript(PAGEBUILDERCK_MEDIA_URI . '/assets/colpick.js'); ?>
<?php CKFof::addScript(PAGEBUILDERCK_MEDIA_URI . '/assets/pagebuilderck.js?ver=' . PAGEBUILDERCK_VERSION); ?>

<?php 
// normal view
} else { ?>
<?php CKFof::addScript(JUri::root(true) . '/components/com_pagebuilderck/assets/jquery-uick.min.js'); ?>
<?php CKFof::addScript(PAGEBUILDERCK_MEDIA_URI . '/assets/ckbrowse.js?ver=' . PAGEBUILDERCK_VERSION); ?>
<?php CKFof::addScript(PAGEBUILDERCK_MEDIA_URI . '/assets/colpick.js'); ?>
<?php CKFof::addScript(PAGEBUILDERCK_MEDIA_URI . '/assets/pagebuilderck.js?ver=' . PAGEBUILDERCK_VERSION); ?>
<?php JHtml::_('jquery.framework'); ?>
<script src="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/jqueryck.min.js" type="text/javascript"></script>
<script src="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/jquery-uick-custom.min.js" type="text/javascript"></script>
<script src="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/ckbox.js" type="text/javascript"></script>
<script src="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/codemirrorck.js" type="text/javascript"></script>
<script src="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/php.js" type="text/javascript"></script>
<script src="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/javascript.js" type="text/javascript"></script>
<script src="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/css.js" type="text/javascript"></script>
<script src="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/ckframework.js" type="text/javascript"></script>
<?php } ?>

<?php 
// load the CK Framework
//require_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/ckframework.php';
//\Pagebuilderck\CKFramework::loadInline();

switch ($editor) {
	case 'jce':
			?><script src="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/editors/jce.js" type="text/javascript"></script><?php
		break;
	case 'tinymce':
	default:
		if (version_compare(JVERSION, '4') >= 0) { // check if we are in Joomla 3.7
			?><script src="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/editors/tinymce3.js" type="text/javascript"></script><?php
		} else if (version_compare(JVERSION, '3.7') >= 0) { // check if we are in Joomla 3.7
			?><script src="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/editors/tinymce2.js" type="text/javascript"></script><?php
		} else { // we are still in an old version
			?><script src="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/editors/tinymce1.js" type="text/javascript"></script><?php
		}
		break;
}

JText::script('CK_CONFIRM_DELETE');
JText::script('CK_FAILED_SET_TYPE');
JText::script('CK_FAILED_SAVE_ITEM_ERRORMENUTYPE');
JText::script('CK_ALIAS_EXISTS_CHOOSE_ANOTHER');
JText::script('CK_FAILED_SAVE_ITEM_ERROR500');
JText::script('CK_FAILED_SAVE_ITEM');
JText::script('CK_FAILED_TRASH_ITEM');
JText::script('CK_FAILED_CREATE_ITEM');
JText::script('CK_UNABLE_UNPUBLISH_HOME');
JText::script('CK_TITLE_NOT_UPDATED');
JText::script('CK_LEVEL_NOT_UPDATED');
JText::script('CK_SAVE_LEVEL_FAILED');
JText::script('CK_SAVE_ORDER_FAILED');
JText::script('CK_CHECKIN_NOT_UPDATED');
JText::script('CK_CHECKIN_FAILED');
JText::script('CK_PARAM_NOT_UPDATED');
JText::script('CK_PARAM_UPDATE_FAILED');
JText::script('CK_FIRST_CREATE_ROW');
JText::script('CK_EDIT');
JText::script('CK_ICON');
JText::script('CK_MODULE');
JText::script('CK_GOOGLE_FONT');
JText::script('CK_FULLSCREEN');
JText::script('CK_RESTORE');
JText::script('CK_REMOVE_BLOCK');
JText::script('CK_MOVE_BLOCK');
JText::script('CK_EDIT_STYLES');
JText::script('CK_DECREASE_WIDTH');
JText::script('CK_INCREASE_WIDTH');
JText::script('CK_ADD_BLOCK');
JText::script('CK_REMOVE_ROW');
JText::script('CK_EDIT_COLUMNS');
JText::script('CK_MOVE_ROW');
JText::script('CK_ADD_NEW_ROW');
JText::script('CK_REMOVE_ITEM');
JText::script('CK_REMOVE_ITEM');
JText::script('CK_MOVE_ITEM');
JText::script('CK_DUPLICATE_ITEM');
JText::script('CK_DUPLICATE_ROW');
JText::script('CK_EDIT_ITEM');
JText::script('CK_ADD_COLUMN');
JText::script('CK_DELETE');
JText::script('CK_SAVE_CLOSE');
JText::script('CK_DESIGN_SUGGESTIONS');
JText::script('CK_MORE_MENU_ELEMENTS');
JText::script('CK_FULLWIDTH');
JText::script('CK_DUPLICATE_COLUMN');
JText::script('CK_ENTER_CLASSNAMES');
JText::script('CHECK_IDS_ALERT_PROBLEM');
JText::script('CHECK_IDS_ALERT_OK');
JText::script('CK_ENTER_UNIQUE_ID');
JText::script('CK_INVALID_ID');
JText::script('CK_ENTER_VALID_ID');
JText::script('CK_CONFIRM_BEFORE_CLOSE_EDITION_POPUP');
JText::script('CK_SUGGESTIONS');
JText::script('CK_RESPONSIVE_SETTINGS_ALIGNED');
JText::script('CK_RESPONSIVE_SETTINGS_STACKED');
JText::script('CK_RESPONSIVE_SETTINGS_HIDDEN');
JText::script('CK_SAVE');
JText::script('CK_WRAPPER_IN_WRAPPER_NOT_ALLOWED');
JText::script('CK_DUPLICATE_WRAPPER');
JText::script('CK_MOVE_WRAPPER');
JText::script('CK_REMOVE_WRAPPER');
JText::script('CK_ADD_NEW_PAGEBUILDER_MODULE');
JText::script('CK_ADD');
JText::script('CK_DRAG_DROP_PAGE');
JText::script('CK_PAGE');
JText::script('CK_ACCESS_RIGHTS');
JText::script('CK_CLIPBOARDEMPTY');
JText::script('CK_COPYFROMCLIPBOARD');
JText::script('CK_FIRST_CLEAR_VALUE');
JText::script('CK_CLICK_TO_EDIT_CONTENT');
JText::script('CK_CLEAN');
JText::script('CK_SELECT');
JText::script('CK_CONFIRM_CLEAR_STYLES');
JText::script('CK_VALIGN_DEFAULT');
JText::script('CK_VALIGN_TOP');
JText::script('CK_VALIGN_CENTER');
JText::script('CK_VALIGN_BOTTOM');
?>
<script type="text/javascript">
	function ckKeepAlive() {
		$ck.ajax({type: "POST", url: "index.php"});
	}

	<?php if (! PagebuilderckHelper::getParams()) { ?>
	function ckShowFavoritePopup() {
		CKBox.open({handler:'inline',content: 'pagebuilderckparamsmessage', fullscreen: false, size: {x: '600px', y: '150px'}});
	}
	function ckShowLibraryPopup() {
		CKBox.open({handler:'inline',content: 'pagebuilderckparamsmessage', fullscreen: false, size: {x: '600px', y: '150px'}});
	}
	<?php } ?>

	$ck(document).ready(function()
	{
		CKApi.Tooltip('.cktip');
		window.setInterval("ckKeepAlive()", 600000);
	});
</script>
<style>
	.tox.tox-silver-sink.tox-tinymce-aux {z-index: 99999}
	#ckeditor_ArticleModal {z-index: 99999}
</style>