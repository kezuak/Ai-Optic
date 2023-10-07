<?php
// No direct access
defined('_JEXEC') or die;

use Pagebuilderck\CKController;
use Pagebuilderck\CKfile;
use Pagebuilderck\CKfolder;
use Pagebuilderck\CKFof;

class PagebuilderckControllerPixabay extends CKController {

	function __construct() {
		parent::__construct();
	}

	/**
	 * Upload the image
	 * 
	 * @return json : result = boolean on success, file = the image filename
	 */
	public function upload() {
		// security check
		CKFof::checkAjaxToken();

		$url = $this->input->get('image_url', '', 'url');
		
		// $url = 'https://pixabay.com/get/57e6d04b4b5bb114a6da837ec32f2a7f1038dbed5b59724e7c_1280.jpg';
		$destFolder = JPATH_ROOT . '/images/pixabay/';
		$fileName = basename($url);
		$filePath = $destFolder . $fileName;

		// create the destination folder if not exists
		if (! file_exists($destFolder)) {
			$result = CKFolder::create($destFolder);
			if (! $result) {
				echo '{"status" : "0", "file" : "", "message" : "Error on folder creation"}';
				exit();
			}
		}

		// get the file from url
		set_time_limit(0);
		try {
			$file = file_get_contents(urldecode($url));
		} catch (Exception $e) {
			echo 'Exception : ',  $e->getMessage(), "\n";
			exit;
		}

		if (! $file && extension_loaded('curl')) {
			$ch = curl_init();  
			$timeout = 30;  
			curl_setopt($ch, CURLOPT_URL, $url);  
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);  
			$file = curl_exec($ch);  
			curl_close($ch);  
		}
		

		if (file_exists($filePath)) {
			$result = true;
		} else {
			// store the file locally
			$result = file_put_contents($filePath, $file);
			if (! $result) {
				echo '{"status" : "0", "file" : "", "message" : "Error on file creation"}';
				exit();
			}

			$fileArray = array( 
				"name" => $fileName 
				,"type" =>  "image/png" 
				,"tmp_name" => $filePath 
				,"error" => 0
				,"size" => filesize($filePath)
				,"filepath" => $destFolder
			);
			// Trigger the onContentBeforeSave event.
			$fileObj = new JObject($fileArray);
			$result = CKFof::triggerEvent('onContentBeforeSave', array('com_media.file', &$fileObj, true));

			if (in_array(false, $result, true))
			{
				// There are some errors in the plugins
				echo '{"status" : "0", "message" : "' . JText::plural('COM_MEDIA_ERROR_BEFORE_SAVE', count($errors = $object_file->getErrors()), implode('<br />', $errors)) . '"}';
				exit;
			}

		}

		echo '{"status" : "1", "file" : "' . 'images/pixabay/' . $fileName . '"}';
		exit;
	}
}