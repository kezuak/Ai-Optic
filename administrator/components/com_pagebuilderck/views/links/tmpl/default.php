<?php
// no direct access
defined('_JEXEC') or die;

use Pagebuilderck\CKFof;

if (!defined('PAGEBUILDERCK_MEDIA_URI'))
{
	define('PAGEBUILDERCK_MEDIA_URI', JUri::root(true) . '/media/com_pagebuilderck');
}
$imagespath = PAGEBUILDERCK_MEDIA_URI .'/images/';
$input = JFactory::getApplication()->input;
$fieldid = $input->get('fieldid', '', 'string');
$type = $input->get('type', 'all', 'string');

JHtml::_('jquery.framework');
$doc = JFactory::getDocument();
$doc->addStylesheet(PAGEBUILDERCK_MEDIA_URI . '/assets/ckbrowse.css');
$doc->addScript(PAGEBUILDERCK_MEDIA_URI . '/assets/ckbrowse.js');
?>
<link rel="stylesheet" href="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/pagebuilderck.css?ver=<?php echo PAGEBUILDERCK_VERSION ?>" type="text/css" />

<div id="maincktabcontent">
	<?php if ($type !== 'image') { ?>
	<div class="mainmenulink menulink <?php echo ($type !== 'image' ? 'current' : '') ?>" tab="tab_menus"><h3><?php echo JText::_('CK_MENUS'); ?></h3></div>
	<div class="mainmenulink menulink" tab="tab_articles"><h3><?php echo JText::_('CK_ARTICLES'); ?></h3></div>
	<?php } ?>
	<div class="mainmenulink menulink <?php echo ($type === 'image' ? 'current' : '') ?>" tab="tab_files"><h3><?php echo JText::_('CK_FILES'); ?></h3></div>
	<?php if (CKFof::userCan('core.pixabay')) { ?>
	<div class="mainmenulink menulink" tab="tab_pixabay"><h3><?php echo JText::_('Pixabay'); ?></h3></div>
	<?php } ?>
	<div class="clr"></div>

	<?php if ($type !== 'image') { ?>
	<div class="maintab <?php echo ($type !== 'image' ? 'current' : '') ?>" id="tab_menus">
		<?php
		require_once PAGEBUILDERCK_PATH . '/views/menus/tmpl/default.php';
		?>
	</div>
	<div class="maintab" id="tab_articles">
		<?php 
		if (PagebuilderckHelper::getParams()) { 
			include PAGEBUILDERCK_PATH . '/views/links/tmpl/articles.php';
		} else {
			echo PagebuilderckHelper::showParamsMessage();
		}
		?>
	</div>
	<?php } ?>

	<div class="maintab <?php echo ($type === 'image' ? 'current' : '') ?>" id="tab_files">
		<?php
		include PAGEBUILDERCK_PATH . '/views/browse/tmpl/default.php';
		?>
	</div>

	<?php if (CKFof::userCan('core.pixabay')) { ?>
	<div class="maintab" id="tab_pixabay">
		<?php 
		if (PagebuilderckHelper::getParams()) { 
			include PAGEBUILDERCK_PATH . '/views/pixabay/tmpl/default.php';
		} else {
			echo PagebuilderckHelper::showParamsMessage();
		}
		?>
	</div>
	<?php } ?>
</div>
<script>
var $ck = window.$ck || jQuery.noConflict();

$ck('#maincktabcontent div.maintab:not(.current)').hide();
$ck('.mainmenulink', $ck('#maincktabcontent')).each(function(i, tab) {
	$ck(tab).click(function() {
		if ($ck('#popup_favoriteck').length) {
			ckCloseFavoritePopup(true);
		}
		$ck('#maincktabcontent div.maintab').hide();
		$ck('.mainmenulink', $ck('#maincktabcontent')).removeClass('current');
		if ($ck('#' + $ck(tab).attr('tab')).length)
			$ck('#' + $ck(tab).attr('tab')).show();
		$ck(this).addClass('current');
	});
});
</script>