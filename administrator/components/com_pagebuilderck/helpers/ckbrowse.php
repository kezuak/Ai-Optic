<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2016. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */

use Pagebuilderck\CKPath;
use Pagebuilderck\CKFolder;
use Pagebuilderck\CKFile;
use Pagebuilderck\CKFof;

defined('_JEXEC') or die;

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

class CKBrowse {

	static $isRestrictedUser = false;

	public static function getFileTypes($type, $auto = true) {
		$input = JFactory::getApplication()->input;
		$type = $auto ? $input->get('type', $type, 'string') : $type;

		switch ($type) {
			case 'video' :
				$filetypes = array('.mp4', '.ogv', '.webm', '.MP4', '.OGV', '.WEBM');
				break;
			case 'audio' :
				$filetypes = array('.mp3', '.ogg', '.MP3', '.OGG');
				break;
			case 'image' :
			default :
				$filetypes = array('.jpg', '.jpeg', '.png', '.gif', '.tiff', '.JPG', '.JPEG', '.PNG', '.GIF', '.TIFF', '.ico', '.svg', '.webp', '.WEBP');
				break;
			case 'all' :
			case 'files' :
				$filetypes = array('.pdf', '.jpg', '.jpeg', '.png', '.gif', '.tiff', '.JPG', '.JPEG', '.PNG', '.GIF', '.TIFF', '.ico', '.svg', '.mp3', '.ogg', '.MP3', '.OGG', '.mp4', '.ogv', '.webm', '.MP4', '.OGV', '.WEBM', '.WEBP', '.webp', '.zip');
				break;
		}

		return $filetypes;
	}

	/*
	 * Get a list of folders and files 
	 */
	public static function getItemsList($type = 'image') {
		$input = JFactory::getApplication()->input;

		$type = $input->get('type', $type, 'string');

		$filetypes = self::getFileTypes($type);
//		switch ($type) {
//			case 'video' :
//				$filetypes = array('.mp4', '.ogv', '.webm', '.MP4', '.OGV', '.WEBM');
//				break;
//			case 'audio' :
//				$filetypes = array('.mp3', '.ogg', '.MP3', '.OGG');
//				break;
//			case 'image' :
//			default :
//				$filetypes = array('.jpg', '.jpeg', '.png', '.gif', '.tiff', '.JPG', '.JPEG', '.PNG', '.GIF', '.TIFF', '.ico', '.svg', '.WEBP', '.webp');
//				break;
//			case 'all' :
//			case 'files' :
//				$filetypes = array('.pdf', '.jpg', '.jpeg', '.png', '.gif', '.tiff', '.JPG', '.JPEG', '.PNG', '.GIF', '.TIFF', '.ico', '.svg', '.mp3', '.ogg', '.MP3', '.OGG', '.mp4', '.ogv', '.webm', '.MP4', '.OGV', '.WEBM', '.WEBP', '.webp');
//				break;
//		}
		$folder = $input->get('folder', '', 'string') ? '/' . trim($input->get('folder', '', 'string'), '/') : '/' . trim(JComponentHelper::getParams('com_pagebuilderck')->get('imagespath', 'images/pagebuilderck'), '/');

		// makes replacement if specific user management is set
		if (stristr($folder, '$userid')) {
			self::$isRestrictedUser = true;
			$user = JFactory::getUser();
			$folder = str_replace('$userid', 'user_' . $user->id, $folder);
			if (! file_exists(JPATH_SITE . '/' . $folder)) {
				JFolder::create(JPATH_SITE . '/' . $folder);
			}
		}

		// no folder filtering 
		if (JComponentHelper::getParams('com_pagebuilderck')->get('imagespathexclusive', '0') == '0') {
		$folder = $input->get('folder', 'images', 'string');
		} else {
			// check if folder exists, if not then create it
			if (!JFolder::exists(JPATH_SITE . $folder)) {
				JFolder::create(JPATH_SITE . $folder);
			}
		}

		$tree = new stdClass();

		// list the files in the root folder
		$fName = self::createFolderObj(JPATH_SITE . '/' . $folder, $tree, 1);
		$tree->$fName->files = self::getImagesInFolder(JPATH_SITE . '/' . $folder, implode('|', $filetypes));

		// look for all folder and files
		self::getSubfolder(JPATH_SITE . '/' . $folder, $tree, implode('|', $filetypes), 2);
		$tree = self::prepareList($tree);

		return $tree;
	}

	/* 
	 * List the subfolders and files according to the filter
	 */
	private static function getSubfolder($folder, &$tree, $filter, $level) {
		$folders = JFolder::folders($folder, '.', $recurse = false, $fullpath = true);
		natcasesort($folders);

		if (! count($folders)) return;

		foreach ($folders as $f) {
			$fName = self::createFolderObj($f, $tree, $level);

			// list all authorized files from the folder
			// self::getImagesInFolder($f, $tree, $fName, $filter, $level);

			// recursive loop
			self::getSubfolder($f, $tree, $filter, $level+1);
		}
		return;
	}
	
	private static function createFolderObj($f, &$tree, $level) {
			$fName = JFile::makeSafe(str_replace(JPATH_SITE, '', $f));
			$tree->$fName = new stdClass();
			$name = explode('/', $f);
			$name = end($name);
			$tree->$fName->name = ($level == 1 && self::$isRestrictedUser == true) ? 'images' : $name;
			$tree->$fName->path = $f;
			$tree->$fName->level = $level;
		$tree->$fName->files = false;

		return $fName;
		}

	/* 
	 * List the subfolders and files according to the filter
	 */
	public static function getImagesInFolder($f, $filter = '.') {

			// list all authorized files from the folder
			$files = JFolder::files($f, $filter, $recurse = false, $fullpath = false);
			if (is_array($files)) natcasesort($files);

			return $files;
		}

	/* 
	 * Set level diff and check for depth
	 */
	private static function prepareList($items) {
		if (! $items) return $items;

		$lastitem = 0;
		foreach ($items as $i => $item)
		{
			self::prepareItem($item);

			if ($item->level != 0) {
				if (isset($items->$lastitem))
				{
					$items->$lastitem->deeper     = ($item->level > $items->$lastitem->level);
					$items->$lastitem->shallower  = ($item->level < $items->$lastitem->level);
					$items->$lastitem->level_diff = ($items->$lastitem->level - $item->level);
				}
			}
			$lastitem = $i;

			
		}

		// for the last item
		if (isset($items->$lastitem))
		{
			$items->$lastitem->deeper     = (1 > $items->$lastitem->level);
			$items->$lastitem->shallower  = (1 < $items->$lastitem->level);
			$items->$lastitem->level_diff = ($item->level - 1);
		}

		return $items;
	}

	/* 
	 * Set the default values
	 */
	private static function prepareItem(&$item) {
		$item->deeper     = false;
		$item->shallower  = false;
		$item->level_diff = 0;
		$item->basepath = str_replace(JPATH_SITE, '', $item->path);
		$item->basepath = str_replace('\\', '/', $item->basepath);
		$item->basepath = trim($item->basepath, '/');
	}

	/**
	 * Get the file and store it on the server
	 * 
	 * @return mixed, the method return
	 */
	public static function ajaxAddPicture() {
		// check the token for security
		if (! JSession::checkToken('get')) {
			$msg = JText::_('JINVALID_TOKEN');
			echo '{"error" : "' . $msg . '"}';
			exit;
		}

		$app = JFactory::getApplication();
		$input = $app->input;
		$file = $input->files->get('file', array(), 'array');
		// $imgpath = '/' . trim($input->get('path', '', 'string'), '/') . '/';
		$imgpath = $input->get('path', '', 'string') ? '/' . trim($input->get('path', '', 'string'), '/') . '/' : '/' . trim(JComponentHelper::getParams('com_pagebuilderck')->get('imagespath', 'images/pagebuilderck'), '/') . '/';

		// makes replacement if specific user management is set
		$user = JFactory::getUser();
		$imgpath = str_replace('$userid', 'user_' . $user->id, $imgpath);

		if (!is_array($file)) {
			$msg = JText::_('CK_NO_FILE_RECEIVED');
			echo '{"error" : "' . $msg . '"}';
			exit;
		}

		// If there are no files to upload - then bail
		if (empty($file))
		{
			$msg = JText::_('CK_NO_FILE_RECEIVED');
			echo '{"error" : "' . $msg . '"}';
			exit;
		}

		// Total length of post back data in bytes.
//		$contentLength = (int) $_SERVER['CONTENT_LENGTH'];

		// Instantiate the media helper
//		$mediaHelper = new JHelperMedia;

		// Maximum allowed size of post back data in MB.
//		$postMaxSize = $mediaHelper->toBytes(ini_get('post_max_size'));

		// Maximum allowed size of script execution in MB.
//		$memoryLimit = $mediaHelper->toBytes(ini_get('memory_limit'));

		// Check for the total size of post back data.
//		if (($postMaxSize > 0 && $contentLength > $postMaxSize)
//			|| ($memoryLimit != -1 && $contentLength > $memoryLimit))
//		{
//			JError::raiseWarning(100, JText::_('COM_MEDIA_ERROR_WARNUPLOADTOOLARGE'));
//
//			return false;
//		}

		$filename = JFile::makeSafe($file['name']);

		// check the file extension // TODO recup preg_match de local dev
		// if (JFile::getExt($filename) != 'jpg') {
			// $msg = JText::_('CK_NOT_JPG_FILE');
			// echo '{"error" : "'  $msg  '"}';
			// exit;
		// }

		//Set up the source and destination of the file
		$src = $file['tmp_name'];

		// check if the file exists
		if (!$src || !file_exists($src)) {
			$msg = JText::_('CK_FILE_NOT_EXISTS');
			echo '{"error" : "' . $msg . '"}';
			exit;
		}

		// check if folder exists, if not then create it
		if (!JFolder::exists(JPATH_SITE . $imgpath)) {
			if (!JFolder::create(JPATH_SITE . $imgpath)) {
				$msg = JText::_('CK_UNABLE_TO_CREATE_FOLDER') . ' : ' . $imgpath;
				echo '{"error" : "' . $msg . '"}';
				exit;
			}
		}

		$file['filepath'] = JPATH_SITE . $imgpath;

		// Trigger the onContentBeforeSave event.
		$object_file = new JObject($file);
		$result = CKFof::triggerEvent('onContentBeforeSave', array('com_media.file', &$object_file, true));

		if (in_array(false, $result, true))
		{
			// There are some errors in the plugins
			JError::raiseWarning(100, JText::plural('COM_MEDIA_ERROR_BEFORE_SAVE', count($errors = $object_file->getErrors()), implode('<br />', $errors)));

			return false;
		}

		// write the file
		if (! JFile::copy($src, JPATH_SITE . $imgpath . $filename)) {
			$msg = JText::_('CK_UNABLE_WRITE_FILE');
			echo '{"error" : "' . $msg . '"}';
			exit;
		}

		// Trigger the onContentAfterSave event.
		CKFof::triggerEvent('onContentAfterSave', array('com_media.file', &$object_file, true));
//		$this->setMessage(JText::sprintf('COM_MEDIA_UPLOAD_COMPLETE', substr($object_file->filepath, strlen(COM_MEDIA_BASE))));

		
		$type = JFile::getExt($filename);
		switch ($type) {
			 case 'video' :
				 $fileicon = PAGEBUILDERCK_MEDIA_URI . '/images/' . 'file_video.png';
				 break;
			 case 'audio' :
				 $fileicon = PAGEBUILDERCK_MEDIA_URI . '/images/' . 'file_audio.png';
				 break;
			case 'pdf' :
				$fileicon = PAGEBUILDERCK_MEDIA_URI . '/images/' . 'file_pdf.png';
				break;
			default :
				$fileicon = $imgpath . $filename;
				break;
		}
		

		echo '{"img" : "' . $fileicon . '", "filename" : "' . $filename . '"}';
		exit;
	}

	public static function createFolder($path, $folder) {
		$path = CKPath::clean(JPATH_SITE . '/' . $path . '/' . $folder);

		if (!is_dir($path) && !is_file($path))
			{
				if (CKFolder::create($path))
				{
					$data = "<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>";
					file_put_contents($path . '/index.html', $data);
				} else {
					return false;
				}
		}
		return true;
	}

	public static function getTypeByFilename($filename) {
		$videoFiletypes = self::getFileTypes('video', false);
		$audioFiletypes = self::getFileTypes('audio', false);
		$imageFiletypes = self::getFileTypes('image', false);
		$allFiletypes = self::getFileTypes('all', false);
		$ext = JFile::getExt($filename);

		if (in_array('.' . $ext, $videoFiletypes)) return 'video';
		if (in_array('.' . $ext, $audioFiletypes)) return 'audio';
		if (in_array('.' . $ext, $imageFiletypes)) return 'image';
		if (in_array('.' . $ext, $allFiletypes)) return 'all';
		return 'none';
	}
}
