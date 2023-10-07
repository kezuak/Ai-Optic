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

?>
<style>
.container {
	color: #333;
}

.fontawesome-icon-list {
	display: flex;
	flex-wrap: wrap;
}

.fontawesome-icon-list a {
	color: #000;
}

div.fa-hover {
	float: left;
	height: 30px;
	margin: 10px;
	padding: 10px;
	width: 14%;
	text-align: center;
}

div.fa-hover:hover a {
	transform: scale(2);
	background: #fff;
	z-index: 1;
	display: block;
}

div.fa-hover a {
	text-decoration: none;
}
</style>
<?php 
PagebuilderckHelper::loadInlineCKFramework(); 
?>
<link rel="stylesheet" href="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/ckframework.css" type="text/css" />
<link rel="stylesheet" href="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/pagebuilderck.css?ver=<?php echo PAGEBUILDERCK_VERSION ?>" type="text/css" />
<script src="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/jqueryck.min.js" type="text/javascript"></script>

<div id="maincktabcontent" class="ckinterface">
	<div class="mainmenulink menulink current" tab="tab_svgicons"><h3><?php echo JText::_('CK_SVG_ICONS'); ?></h3></div>
	<div class="mainmenulink menulink " tab="tab_fontawesome"><h3><?php echo JText::_('CK_FONTAWESOME'); ?></h3></div>

	<div class="clr"></div>
	<div class="maintab " id="tab_fontawesome">
		<?php include PAGEBUILDERCK_PATH . '/views/icons/tmpl/fontawesome4.php'; ?>
	</div>
	<div class="maintab current" id="tab_svgicons">
		<?php include PAGEBUILDERCK_PATH . '/views/icons/tmpl/svg.php'; ?>
	</div>
</div>


<script>
var $ck = window.$ck || jQuery.noConflict();

$ck('#maincktabcontent div.maintab:not(.current)').hide();
$ck('.mainmenulink', $ck('#maincktabcontent')).each(function(i, tab) {
	$ck(tab).click(function() {
		$ck('#maincktabcontent div.maintab').hide();
		$ck('.mainmenulink', $ck('#maincktabcontent')).removeClass('current');
		if ($ck('#' + $ck(tab).attr('tab')).length)
			$ck('#' + $ck(tab).attr('tab')).show();
		$ck(this).addClass('current');
	});
});
</script>