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
use Pagebuilderck\CKFramework;

require_once(PAGEBUILDERCK_PATH . '/helpers/defines.js.php');
$imagespath = PAGEBUILDERCK_MEDIA_URI . '/images/';

JHtml::_('jquery.framework');
CKFof::addStylesheet(PAGEBUILDERCK_MEDIA_URI . '/assets/ckbrowse.css?ver=' . PAGEBUILDERCK_VERSION);
CKFof::addScript(PAGEBUILDERCK_MEDIA_URI . '/assets/ckbrowse.js?ver=' . PAGEBUILDERCK_VERSION);

$returnFunc = $this->input->get('func', 'ckSelectFile', 'cmd');
$returnField = $this->input->get('field', $this->input->get('fieldid', '', 'string'), 'string');
$type = $this->input->get('type', 'image', 'string');
if (JComponentHelper::getParams('com_pagebuilderck')->get('imagespathexclusive', '0') == '0') {
	$folderPath = $this->input->get('folder', 'images', 'string');
} else {
	$folderPath = $this->input->get('folder', '', 'string') ? trim($this->input->get('folder', '', 'string'), '/') : trim(JComponentHelper::getParams('com_pagebuilderck')->get('imagespath', 'images/pagebuilderck'), '/');
}
CKFramework::loadCss();

switch ($type) {
	case 'video' :
		$fileicon = 'file_video.png';
		break;
	case 'audio' :
		$fileicon = 'file_audio.png';
		break;
	case 'folder' :
	case 'image' :
	default :
		$fileicon = 'file_image.png';
		break;
}
//$fileTypes = array(
//	'video' => array('.mp4', '.ogv', '.webm', '.MP4', '.OGV', '.WEBM')
//	, 'audio' => array('.mp3', '.ogg', '.MP3', '.OGG')
//	, 'pdf' => array('.pdf')
//	, 'image' => array('.jpg', '.jpeg', '.png', '.gif', '.tiff', '.JPG', '.JPEG', '.PNG', '.GIF', '.TIFF', '.ico', '.svg', '.WEBP', '.webp')
//);

?>
<div id="ckbrowse" class="clearfix">
	<div id="ckfolderupload">
		<div class="inner">
			<div class="upload">
			<h2 class="uploadinstructions"><?php echo JText::_('CK_DROP_FILES_TO_UPLOAD'); ?></h2>
				<p><?php echo JText::_('CK_OR_SELECT_FILES'); ?></p><input id="ckfileupload" type="file" name="Filedata[]" multiple class="" />
			</div>
		</div>
	</div>
	
	<div id="ckfoldertreelist">
		<p><?php echo JText::_('CK_BROWSE_INFOS') ?></p>
		<h3><?php echo JText::_('CK_FOLDERS') ?></h3>
<?php
$lastitem = 0;
foreach ($this->items as $i => $folder) {
	$submenustyle = '';
	$folderclass = '';
	if ($folder->level == 1) {
		$submenustyle = 'display: block;';
		$folderclass = 'ckcurrent';
	}
			$pathway = str_replace('/', '</span><span class="ckfoldertreepath">', ($folder->basepath));
	?>
			<div class="ckfoldertree <?php echo $folderclass ?> <?php echo ($folder->deeper ? 'parent' : '') ?> <?php //echo (count($folder->files) ? 'hasfiles' : '') ?>" data-level="<?php echo $folder->level ?>" data-path="<?php echo utf8_encode($folder->basepath) ?>">
			<?php if ($folder->level > 1) { ?><div class="ckfoldertreetoggler" onclick="ckBrowseToggleTreeSub(this)"></div><?php } ?>
				<div class="ckfoldertreename" onclick="ckLoadFiles(this, '<?php echo $type ?>', '<?php echo ($folder->basepath) ?>', '<?php echo $folder->name ?>')"><span class="icon-folder"></span><?php echo utf8_encode($folder->name); ?>
					<?php /*<div class="ckfoldertreecount"><?php echo count($folder->files); ?></div> */ ?>
				</div>


				<?php
				if ($folder->deeper) {
					echo '<div class="cksubfolder" style="' . $submenustyle . '">';
				} elseif ($folder->shallower) {
					// The next item is shallower.
					echo '</div>'; // close ckfoldertree
					echo str_repeat('</div></div>', $folder->level_diff); // close cksubfolder + ckfoldertree
				} else {
					// The next item is on the same level.
					echo '</div>'; // close ckfoldertree
				}
			}
			?>
		</div>
	<div id="ckfoldertreelistfiles" class="ckfoldertreelist">
		<?php // place where the images will be loaded ?>
	</div>
</div>
	<script>
		function ckBrowseToggleTreeSub(btn) {
			var item = $ck(btn).parent();
			if (item.hasClass('ckopened')) {
				item.removeClass('ckopened');
			} else {
				item.addClass('ckopened')
				// item.find('> .cksubfolder, > .ckfoldertreefiles').css('opacity','0').animate({'opacity': '1'}, 300);
			}
		}

		function ckBrowseShowFiles(btn) {
			// show the image in place of divs
			var fakeImages = $ck(btn).find('~ .ckfoldertreefiles .ckfakeimage');
			if (fakeImages.length) {
				fakeImages.each(function () {
					$fakeImage = $ck(this);
					var source = $fakeImage.parent().attr('data-type') == 'image' ? $fakeImage.attr('data-src') : '<?php echo $imagespath . 'file_' ?>' + $fakeImage.parent().attr('data-type') + '.png';
					$fakeImage.after('<img src="' + source + '" title="' + $fakeImage.attr('title') + '" />');
					$fakeImage.parent().removeClass('ckwait');
					$fakeImage.remove();
				});
			}
			// set the current state on the folder
			var item = $ck(btn).parent();
			$ck('.ckcurrent').not(btn).removeClass('ckcurrent');
			if (item.hasClass('ckcurrent')) {
				item.removeClass('ckcurrent');
			} else {
				item.addClass('ckcurrent')
			}
		}

		function ckBrowseSelectFile(btn) {
			try {
				if (typeof (window.parent.<?php echo $returnFunc ?>) != 'undefined') {
					window.parent.<?php echo $returnFunc ?>($ck(btn).attr('data-path') + '/' + $ck(btn).attr('data-filename'), '<?php echo $returnField ?>');
					if (typeof (window.parent.CKBox) != 'undefined')
						window.parent.CKBox.close();
				} else {
					alert('ERROR : The function <?php echo $returnFunc ?> is missing in the parent window. Please contact the developer');
				}
			} catch (err) {
				alert('ERROR : ' + err.message + '. Please contact the developper.');
			}
		}

function ckBrowseSelectFolder(path) {
	try {
		if (typeof(window.parent.<?php echo $returnFunc ?>) != 'undefined') {
			window.parent.<?php echo $returnFunc ?>(path, '<?php echo $returnField ?>');
			if (typeof(window.parent.CKBox) != 'undefined') window.parent.CKBox.close();
		} else {
			alert('ERROR : The function <?php echo $returnFunc ?> is missing in the parent window. Please contact the developer');
		}
	}
	catch(err) {
		alert('ERROR : ' + err.message + '. Please contact the developper.');
	}
}

// display the images in the root folder
//ckBrowseShowFiles($ck('.ckfoldertreename').first()[0]);
		let folderPath = (window.sessionStorage && sessionStorage.getItem('pagebuilderck_browse_folder.' + PAGEBUILDERCK.USERID)) ? sessionStorage.getItem('pagebuilderck_browse_folder.' + PAGEBUILDERCK.USERID) : '<?php echo $folderPath ?>';
		ckLoadFiles($ck('.ckfoldertreename').first()[0], '<?php echo $type ?>', folderPath, folderPath)
	</script>
