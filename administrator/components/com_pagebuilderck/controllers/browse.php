<?php
// No direct access
defined('_JEXEC') or die;

use Pagebuilderck\CKController;
use Pagebuilderck\CKFof;

require_once PAGEBUILDERCK_PATH . '/helpers/ckbrowse.php';

class PagebuilderckControllerBrowse extends CKController {

	function __construct() {
		parent::__construct();
	}

	public function ajaxCreateFolder() {
		// security check
		CKFof::checkAjaxToken();

		if (CKFof::userCan('create', 'com_media')) {
			$path = $this->input->get('path', '', 'string');
			$name = $this->input->get('name', '', 'string');

			require_once PAGEBUILDERCK_PATH . '/helpers/ckbrowse.php';
			if ($result = CKBrowse::createFolder($path, $name)) {
				$msg = JText::_('CK_FOLDER_CREATED_SUCCESS');
			} else {
				$msg = JText::_('CK_FOLDER_CREATED_ERROR');
			}

			echo '{"status" : "' . ($result == false ? '0' : '1') . '", "message" : "' . $msg . '"}';
		} else {
			echo '{"status" : "2", "message" : "' . JText::_('CK_ERROR_USER_NO_AUTH') . '"}';
		}
		exit;
	}

	/**
	 * Get the file and store it on the server
	 * 
	 * @return mixed, the method return
	 */
	public function ajaxAddPicture() {
		// security check
		CKFof::checkAjaxToken();

		require_once PAGEBUILDERCK_PATH . '/helpers/ckbrowse.php';
		CKBrowse::ajaxAddPicture();
	}

	public function getFiles() {
		// security check
		CKFof::checkAjaxToken();

		$folder = $this->input->get('folder', '', 'string');
		$type = $this->input->get('type', '', 'string');
		$filetypes = CKBrowse::getFileTypes($type);
		$files = CKBrowse::getImagesInFolder(JPATH_SITE . '/' . $folder, implode('|', $filetypes));

		if ($type == 'folder') {
			$pathway = str_replace('/', '</span><span class="ckfoldertreepath">', $folder);
			?>
			<div id="ckfoldertreelistfolderselection">
				<div class="ckbutton ckbutton-primary" style="font-size:20px;padding: 10px 20px;" onclick="ckBrowseSelectFolder('<?php echo ($folder) ?>')"><i class="fas fa-check-square"></i> <?php echo JText::_('CK_SELECT_FOLDER') ?><br /><small><?php echo $pathway ?></small></div>
			</div>
		<?php }
		if (empty($files)) {
			echo JText::_('CK_NO_FILE_FOUND');
		} else {
			foreach($files as $file) {
				$type = CKBrowse::getTypeByFilename($file);
				switch ($type) {
					case 'video' :
						$fileicon = PAGEBUILDERCK_MEDIA_URI . '/images/file_video.png';
						break;
					case 'audio' :
						$fileicon = PAGEBUILDERCK_MEDIA_URI . '/images/file_audio.png';
						break;
					case 'folder' :
					case 'image' :
						$fileicon = JUri::root(true) . '/' . utf8_encode($folder) . '/' . utf8_encode($file);
						break;
					default :
						$fileicon = PAGEBUILDERCK_MEDIA_URI . '/images/file_generic.png';
						break;
				}
				?>
					<div class="ckfoldertreefile" data-type="<?php echo $type ?>" onclick="ckBrowseSelectFile(this)" data-path="<?php echo utf8_encode($folder) ?>" data-filename="<?php echo utf8_encode($file) ?>">
						<img src="<?php echo $fileicon ?>" title="<?php echo utf8_encode($file); ?>" loading="lazy">
						<div class="ckimagetitle"><?php echo utf8_encode($file); ?></div>
					</div>
				<?php
			}
		}
		exit;
	}
}