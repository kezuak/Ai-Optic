<?php
/**
 * @name		Slider CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2016. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */

// no direct access
defined('_JEXEC') or die;

if (!defined('PAGEBUILDERCK_MEDIA_URI'))
{
	define('PAGEBUILDERCK_MEDIA_URI', JUri::root(true) . '/media/com_pagebuilderck');
}
$imagespath = PAGEBUILDERCK_MEDIA_URI .'/images/';
$input = JFactory::getApplication()->input;
$fieldid = $input->get('fieldid', '', 'string');

JHtml::_('jquery.framework');
$doc = JFactory::getDocument();
$doc->addStylesheet(PAGEBUILDERCK_MEDIA_URI . '/assets/ckbrowse.css');
$doc->addScript(PAGEBUILDERCK_MEDIA_URI . '/assets/ckbrowse.js');

?>
<h3><?php echo JText::_('CK_MENU_ITEMS') ?></h3>
<p><?php echo JText::_('CK_MENU_ITEMS_DESC') ?></p>
<div class="ckfoldertreelist" style="width:90%">
<?php
// Access filter
$com_path = JPATH_SITE . '/components/com_content/';
JLoader::register('ContentHelperRoute', $com_path . 'helpers/route.php');
$access     = !JComponentHelper::getParams('com_content')->get('show_noauth');
$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
foreach ($this->articles as $item) {
	// check if category or article
	if ($item->type == 'article') {
		$item->slug    = $item->id . ':' . $item->alias;
		if ($access || in_array($item->access, $authorised))
		{
			// We know that user has the privilege to view the article
			$item->link = ContentHelperRoute::getArticleRoute($item->slug, $item->catid, $item->language);
		} else {
			continue;
		}
	} else {
		if ($access || in_array($item->access, $authorised))
		{
			$item->link =  ContentHelperRoute::getCategoryRoute($item->id);
		} else {
			continue;
		}
	}
	?>
	<div class="ckfoldertree parent">
		<div class="ckfoldertreetoggler" onclick="ckLinksArticlesToggleTreeSub(this, <?php echo $item->id; ?>)" data-id="<?php echo $item->id; ?>"></div>
		<div class="ckfoldertreename hasTip" title="<?php echo $item->link ?>" onclick="ckSetLinksArticlesUrl('<?php echo $item->link ?>')"><span class="icon-folder"></span><?php echo utf8_encode($item->title); ?></div>
	</div>
	<?php
}
?>
</div>
<script>
var $ck = window.$ck || jQuery.noConflict();
var URIROOT = window.URIROOT || '<?php echo JUri::root(true) ?>';
var URIBASE = window.URIBASE || '<?php echo JUri::base(true) ?>';
var cktoken = '<?php echo JSession::getFormToken() ?>';
//ckMakeTooltip();

function ckLinksArticlesToggleTreeSub(btn, parentid) {
	var item = $ck(btn).parent();
	if (item.hasClass('ckopened')) {
		item.removeClass('ckopened');
	} else {
		item.addClass('ckopened');
		// load only the items if not already there
		if (! item.find('.cksubfolder').length) {
			// var parentid = $ck(btn).attr('data-id');
			ckLinksArticlesShowItems(btn, parentid);
		}
	}
}

function ckLinksArticlesShowItems(btn, parentid) {
	if ($ck(btn).hasClass('empty')) return;
	ckAddWaitIcon(btn);
	var item = $ck(btn).parent();
	// ajax call to code and return items layout
	var myurl = PAGEBUILDERCK.URIPBCK + "&task=links.ajaxShowArticles&" + PAGEBUILDERCK.TOKEN;
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			// menutype: menutype,
			parentid: parentid
		}
	}).done(function(code) {
		if (code.trim().length == 0) {
			$ck(btn).css('opacity', 0).addClass('empty');
		} else {
			item.append(code);
			ckInitTooltips();
		}
		ckRemoveWaitIcon(btn);
	}).fail(function() {
		alert(Joomla.JText._('CK_FAILED', 'Failed'));
	});
}

function ckSetLinksArticlesUrl(url) {
	window.parent.document.getElementById('<?php echo $fieldid ?>').value = url;
	$ck(window.parent.document.getElementById('<?php echo $fieldid ?>')).trigger('change');
	window.parent.CKBox.close('#ckfilesmodal .ckboxmodal-button');
}
</script>