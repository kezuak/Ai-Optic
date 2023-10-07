<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */

// No direct access
defined('_JEXEC') or die;

use Pagebuilderck\CKInput;
Use Pagebuilderck\CKFof;
Use Pagebuilderck\CKText;

if (!defined('PAGEBUILDERCK_MEDIA_URI'))
{
	define('PAGEBUILDERCK_MEDIA_URI', JUri::root(true) . '/media/com_pagebuilderck');
}

//include_once(JPATH_SITE . '/administrator/components/com_pagebuilderck/helpers/ckeditor.php');

/**
 * Helper Class.
 */
class PagebuilderckHelper {

	private static $pluginsItemType;

	private static $pluginsItemTypeByGroup;

	private static $releaseNotes;

	private static $currentVersion;

	private static $templateParams;

	private static $defaultTemplate;

	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($vName = '') {
		$doc = JFactory::getDocument();
		$doc->addStyleSheet(PAGEBUILDERCK_MEDIA_URI . '/assets/pagebuilderck.css');
		if (! $vName) $vName = JFactory::getApplication()->input->get('view', 'pages');
		JHtmlSidebar::addEntry(
				JText::_('COM_PAGEBUILDERCK_PAGES'), 'index.php?option=com_pagebuilderck&view=pages', $vName == 'pages'
		);
		JHtmlSidebar::addEntry(
				JText::_('COM_PAGEBUILDERCK_CATEGORIES'), 'index.php?option=com_pagebuilderck&view=categories', $vName == 'categories'
		);
		JHtmlSidebar::addEntry(
				JText::_('COM_PAGEBUILDERCK_ARTICLES'), 'index.php?option=com_pagebuilderck&view=articles', $vName == 'articles'
		);
		JHtmlSidebar::addEntry(
				JText::_('COM_PAGEBUILDERCK_MODULES'), 'index.php?option=com_pagebuilderck&view=modules2', $vName == 'modules2'
		);
		JHtmlSidebar::addEntry(
				JText::_('COM_PAGEBUILDERCK_MY_ELEMENTS'), 'index.php?option=com_pagebuilderck&view=elements', $vName == 'elements'
		);
//		JHtmlSidebar::addEntry(
//				JText::_('COM_PAGEBUILDERCK_TOOLS'), 'index.php?option=com_pagebuilderck&view=tools', $vName == 'tools'
//		);
		JHtmlSidebar::addEntry(
				JText::_('CK_ABOUT') . '<span class="pagebuilderckchecking isbadgeck"></span>', 'index.php?option=com_pagebuilderck&view=about', $vName == 'about'
		);
	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return	JObject
	 * @since	1.6
	 */
	public static function getActions() {
		$user = JFactory::getUser();
		$result = new JObject;

		$assetName = 'com_pagebuilderck';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}

	/*
	 * Load the default editor
	 * 
	 * Return object the editor instance
	 */
	public static function loadEditor() {
		$conf = JFactory::getConfig();
		// $editorName = $conf->get('editor');
		$editorName = $conf->get('pagebuilderck_replaced_editor') ? $conf->get('pagebuilderck_replaced_editor') : $conf->get('editor');
		$editor = CKEditor::getInstance($editorName);

		// return the instance
		return $editor;
	}

	/*
	 * Check for the plugin params and returns the PHP Class
	 * 
	 * @param string a PHP class to load
	 *
	 * Return mixed the PHP Class, or a message, true or false if no class given in param
	 */
	public static function getParams($class = '') {
		if (file_exists(PAGEBUILDERCK_PATH . '/pro')) {

				if (! $class) return true; // only check if the plugin is installed and active

				// check for the file class and loads it if exists
				if (file_exists(PAGEBUILDERCK_PATH . '/pro/includes/' . strtolower($class) . '.php')) {
					include_once(PAGEBUILDERCK_PATH . '/pro/includes/' . strtolower($class) . '.php');
					$newClassName = 'PagebuilderckParams' . ucfirst($class);
					return new $newClassName;
				} else {
					echo '<p class="alert alert-danger">' . JText::_('CK_PAGEBUILDERCK_PARAMS_CLASS_NOT_FOUND') . ' : ' . $class . '</p>';
					return false;
				}
		} else {
			return false;
		}
	}

	/*
	 * Load the JS and CSS files needed to use CKBox
	 *
	 * Return void
	 */
	public static function loadCkbox() {
		$doc = JFactory::getDocument();
		JHtml::_('jquery.framework', true);
//		$doc->addScript(JUri::root(true) . '/media/jui/js/jquery.min.js');
		$doc->addStyleSheet(PAGEBUILDERCK_MEDIA_URI . '/assets/ckbox.css');
		$doc->addScript(PAGEBUILDERCK_MEDIA_URI . '/assets/ckbox.js');
	}

	/*
	 * Load the JS and CSS files needed to use CKBox
	 *
	 * Return void
	 */
	public static function loadCKFramework() {
		$doc = JFactory::getDocument();
		JHtml::_('jquery.framework', true);
//		$doc->addScript(JUri::root(true) . '/media/jui/js/jquery.min.js');
		$doc->addStyleSheet(PAGEBUILDERCK_MEDIA_URI . '/assets/ckframework.css');
	}

	/*
	 * Load the JS and CSS files needed to use CKBox
	 *
	 * Return void
	 */
	public static function loadInlineCKFramework() {
		JHtml::_('jquery.framework', true);
	?>
		<link rel="stylesheet" href="<?php echo JUri::root(true) ?>/components/com_pagebuilderck/assets/font-awesome.min.css" type="text/css" />
		<link rel="stylesheet" href="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/ckframework.css" type="text/css" />
	<?php
	}

	/*
	 * Load the JS and CSS files needed to use CKBox
	 *
	 * Return void
	 */
	public static function loadInlinejQueryck() {
	?>
		<script src="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/jqueryck.js"></script>
	<?php
	}

	/*
	 * Load the JS and CSS files needed to use CKBox
	 *
	 * Return void
	 */
	public static function loadParamsAssets() {
		$doc = JFactory::getDocument();
		 JHtml::_('jquery.framework', true);
		$doc->addStyleSheet(PAGEBUILDERCK_URI .'/pro/assets/pagebuilderckparams.css');
		$doc->addScript(PAGEBUILDERCK_URI .'/pro/assets/pagebuilderckparams.js');
	}

	/*
	 * Show the message about Page Builder CK Params
	 *
	 * Return string - html code
	 */
	public static function showParamsMessage($show = true, $message = '') {
	
		if (self::getParams()) return '';
		$message = $message ? $message : JText::_('CK_PAGEBUILDERCK_PRO_INFO');
		$html = '<div id="pagebuilderckparamsmessage" style="padding:10px;display:'.($show ? 'block' : 'none').';">
					<div class="alert alert-info">
						' . $message . '
						<div style="text-align:center;"><a class="btn btn-small btn-inverse" target="_blank" href="https://www.joomlack.fr/en/joomla-extensions/page-builder-ck"><span class="icon-download"></span>&nbsp;Page Builder CK Pro</a></div>
					</div>
				</div>';
		return $html;
	}

	/*
	 * Get the page from its id
	 *
	 * Return Array - The list of pages
	 */
	public static function getPage($id = null) {
		if ($id == null) return;
		// get the page model
		include_once JPATH_ROOT . '/administrator/components/com_pagebuilderck/models/page.php';
		$model	= CKFof::getModel('Page');
		
		// parse the html code through the model page
		$page = $model->getItem((int) $id);

		return $page;
	}

	/**
	 * Get the page html code from its id
	 * 
	 * @return string, the html code
	 */
	public static function ajaxLoadPageHtml() {
		$input = JFactory::getApplication()->input;
		$id = $input->get('id', 0, 'int');
		$page = PagebuilderckHelper::getPage($id);
		if (isset($page->htmlcode)) {
			echo trim($page->htmlcode);
		} else {
			echo 'error';
		}
		exit();
	}

	/*
	 * Take the item and save it into a .pbck file as autoamtic backup
	 * 
	 * @return void
	 */
	public static function makeBackup($item, $subfolder = '') {
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');
		$subfolder = $subfolder ? '/' . $subfolder : '';
		$path = JPATH_ROOT . '/administrator/components/com_pagebuilderck/backup' . $subfolder;

//		$item = $this->getData();
		// $item->htmlcode = str_replace(JUri::root(true), "|URIROOT|", $item->htmlcode);
		// $exportfiletext = json_encode($item);
		$exportfiletext = self::getExportFile($item);

		// create the folder
		if (! JFolder::exists($path . '/' . $item->id . '_bak/')) {
			JFolder::create($path . '/' . $item->id . '_bak/');
		}

		// check if we have more than 5 existing backups, delete the old one
		if (count(JFolder::files($path . '/' . $item->id . '_bak/')) > 5) {
			self::deleteOldestBackup($path . '/' . $item->id . '_bak/', $item->id);
		}

		$exportfiledest = $path . '/' . $item->id . '_bak/backup_' . $item->id . '_' . date("d-m-Y-G-i-s") . '.pbck';
		file_put_contents($exportfiledest, $exportfiletext);
	}

	/*
	 * Replace the variables to store the file
	 * 
	 * @return string, the json encoded item
	 */
	public static function getExportFile($item) {
		$item->htmlcode = str_replace(JUri::root(true), "|URIROOT|", $item->htmlcode);
		$exportfiletext = json_encode($item);

		return $exportfiletext;
	}

	/*
	 * Remove the oldest backup from the folder
	 * 
	 * @return void
	 */
	private static function deleteOldestBackup($path, $id) {
		$files = JFolder::files($path);

		$files = array_map(function ($v) use ($id) {
			$date = str_replace('backup_' . $id . '_', '', str_replace('.pbck', '', $v));
			$new_d = PagebuilderckHelper::invertDateForSorting($date);

			return $new_d;
		}, $files);
		natsort($files);

		$oldest = reset($files);
		$oldest = PagebuilderckHelper::invertDateForSorting($oldest);
		$oldest = 'backup_' . $id . '_' . $oldest . '.pbck';
		Jfile::delete($path . $oldest);
	}

	public static function invertDateForSorting($date) {
		$new_d = explode('-', $date);
		$d = $new_d[0];
		$Y = $new_d[2];
		$new_d[0] = $Y;
		$new_d[2] = $d;
		return implode('-', $new_d);
	}

	public static function renderEditionButtons() {
		$html = '<span class="ckbutton ckbutton-success" onclick="ckSaveInlineEditionPopup();"><span class="fa fa-save"></span> ' . JText::_('CK_SAVE_CLOSE') . '</span>';
		$html .= '<span class="ckbutton" onclick="ckCancelInlineEditionPopup(this);">' . JText::_('CK_CANCEL') . '</span>';
		return $html;
	}

	public static function getAjaxToken() {
		// check the token for security
		if (! JSession::checkToken('get')) {
			$msg = JText::_('JINVALID_TOKEN');
			echo '{"result": "0", "message": "' . $msg . '"}';
			return false;
		}
		return true;
	}

	/**
	 * Check the token for security reason
	 * @return boolean
	 */
	public static function checkAjaxToken() {
		if (! JSession::checkToken('get')) {
			$msg = JText::_('CK_INVALID_TOKEN');
			echo '{"status": "0", "message": "' . $msg . '"}';
			exit();
		}
		return true;
	}

	public static function checkToken() {
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	}

	public static function getToken() {
		return JSession::getFormToken();
	}

	public static function getPluginsMenuItemType($usage = false, $contenttype = false) {
		$input = JFactory::getApplication()->input;
		if (empty(self::$pluginsItemType) || $usage !== false) {
			if ($input->get('view', '') == 'element' || $input->get('view', '') == 'style') {
				$standarditems = array();
			} else if (PAGEBUILDERCK_NESTEDROWS == '1') {
				$standarditems = array('row', 'rowinrow', 'readmore');
			} else {
				$standarditems = array('row', 'readmore');
			}
			$i = 0;
			$items = array();
			foreach ($standarditems as $standarditem) {
				if ($usage == 'contenttype' && $standarditem == 'readmore') continue;
				$items[$i] = new stdClass();
				$items[$i]->type = $standarditem;
				$items[$i]->group = 'layout';
				$items[$i]->title = JText::_('COM_PAGEBUILDERCK_CONTENT_' . strtoupper($standarditem));
				$items[$i]->description = JText::_('COM_PAGEBUILDERCK_CONTENT_' . strtoupper($standarditem) . '_DESC');
				$items[$i]->image = PAGEBUILDERCK_MEDIA_URI . '/images/contents/' . $standarditem . '.png';
				$i++;
			}
			if ($usage === 'contenttype') {
				// load the custom plugins
				CKFof::importPlugin( 'pagebuilderck' );
				$otheritems = CKFof::triggerEvent( 'onPagebuilderckAdd' . ucfirst($contenttype) . 'ItemsToMenu' );
				$otheritems = isset($otheritems[0]) ? $otheritems[0] : $otheritems;
				
			} else {
				// load the custom plugins
				CKFof::importPlugin( 'pagebuilderck' );
				$otheritems = CKFof::triggerEvent( 'onPagebuilderckAddItemToMenu' );
			}

			$items = array_merge($items, $otheritems);
			// $items = $otheritems;
			self::$pluginsItemType = array();
			if (count($items)) {
				foreach ($items as $item) {
					if (! isset($item->group)) $item->group = 'other';
					$imageFile = PAGEBUILDERCK_SITE_ROOT . substr($item->image, strlen(JUri::root(true)));
					if (! file_exists($imageFile)) {
						$item->image = PAGEBUILDERCK_MEDIA_URI . '/images/contents/add_on.png';
					}
					self::$pluginsItemType[$item->type] = $item;
				}
			}
		}
		return self::$pluginsItemType;
	}

	public static function getPluginsMenuItemTypeByGroup() {
		if (empty(self::$pluginsItemTypeByGroup)) {
			$groups = array(
				'layout' => array('name' => JText::_('CK_GROUP_LAYOUT'), 'items'), 
				'text' => array('name' => JText::_('CK_GROUP_TEXT'), 'items'), 
				'image' => array('name' => JText::_('CK_GROUP_IMAGE'), 'items'), 
				'multimedia' => array('name' => JText::_('CK_GROUP_MULTIMEDIA'), 'items'),
				'other' => array('name' => JText::_('CK_GROUP_OTHER'), 'items')
				);
			$items = self::getPluginsMenuItemType();
			if (count($items)) {
				foreach ($items as $item) {
					if (! isset($item->group)) $item->group = 'other';
					if (! isset($groups[$item->group])) $groups[$item->group] = array('name' => JText::_('CK_GROUP_' . $item->group), 'items');
					$groups[$item->group]['items'][] = $item;
				}
			}
			self::$pluginsItemTypeByGroup = $groups;
		}
// var_dump($groups);die;

		return self::$pluginsItemTypeByGroup;
	}

	/**
	 * Convert a hexa decimal color code to its RGB equivalent
	 *
	 * @param string $hexStr (hexadecimal color value)
	 * @param boolean $returnAsString (if set true, returns the value separated by the separator character. Otherwise returns associative array)
	 * @param string $seperator (to separate RGB values. Applicable only if second parameter is true.)
	 * @return array or string (depending on second parameter. Returns False if invalid hex color value)
	 */
	static function hex2RGB($hexStr, $opacity) {
		$hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
		$rgbArray = array();
		if (strlen($hexStr) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
			$colorVal = hexdec($hexStr);
			$rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
			$rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
			$rgbArray['blue'] = 0xFF & $colorVal;
		} elseif (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
			$rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
			$rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
			$rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
		} else {
			return false; //Invalid hex color code
		}
		$rgbacolor = "rgba(" . $rgbArray['red'] . "," . $rgbArray['green'] . "," . $rgbArray['blue'] . "," . ((int)$opacity / 100) . ")";

		return $rgbacolor;
	}

	/**
	 * Get a subtring with the max length setting.
	 *
	 * @param string $text;
	 * @param int $length limit characters showing;
	 * @param string $replacer;
	 * @return tring;
	 */
	public static function substring($text, $length = 100, $replacer = '...', $isStrips = true, $stringtags = '') {
	
		if($isStrips){
			$text = preg_replace('/\<p.*\>/Us','',$text);
			$text = str_replace('</p>','<br/>',$text);
			$text = strip_tags($text, $stringtags);
		}
		
		if(function_exists('mb_strlen')){
			if (mb_strlen($text) < $length)	return $text;
			$text = mb_substr($text, 0, $length);
		}else{
			if (strlen($text) < $length)	return $text;
			$text = substr($text, 0, $length);
		}
		
		return $text . $replacer;
	}

	public static function getOption($name) {
//		require_once PAGEBUILDERCK_PATH . '/helpers/ckfof.php';
		if (! $name) {
			CKFof::error(CKText::_('CK_NAME_MISSING'));
		}
		if (! CKFof::dbCheckTableExists('#__pagebuilderck_options')) {
			if (! self::createTableOptions()) return false;
		}

		$query = "SELECT value FROM #__pagebuilderck_options WHERE name = '" .$name . "'";

		return CKFof::dbLoadResult($query);
	}

	public static function setOption($name, $value) {
//		require_once PAGEBUILDERCK_PATH . '/helpers/ckfof.php';
		if (! $name) {
			CKFof::error(CKText::_('CK_NAME_MISSING'));
		}
		if (! CKFof::dbCheckTableExists('#__pagebuilderck_options')) {
			if (! self::createTableOptions()) return false;
		}

		$query = "SELECT id FROM #__pagebuilderck_options WHERE name = '" . $name . "'";
		$id = CKFof::dbLoadResult($query);

		if (! $id) $id = 0;
		$data = ['id' => (int)$id, 'name' => $name, 'value' => $value];
		$id = CKFof::dbStore('#__pagebuilderck_options', $data);

		return $id;
	}

	/*
	* update the table
	*/
	public static function createTableOptions() {
		$sqlsrc = PAGEBUILDERCK_PATH . '/sql/updates/2.4.0.sql';
		$query = file_get_contents($sqlsrc);
		$db = JFactory::getDbo();
		$db->setQuery($query);
		if (!$db->execute()) {
//			echo '<p class="alert alert-danger">Error during table options creation</p>';
		} else {
//			echo '<p class="alert alert-success">Table options successfully created</p>';
		}
	}

	public static function getElements() {
		$db = CKFof::getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select('a.*');
		$query->from('`#__pagebuilderck_elements` AS a');

		// Do not list the trashed items
		$query->where('a.state > -1');

		// Add the list ordering clause.
		$query->order('a.ordering ASC');

		$elements = $db->setQuery($query)->loadObjectList();

		return $elements;
	}

	private static function loadTemplateParams() {
		if (empty(self::$templateParams)) {
			// check that the template is compatible
			$app    = JFactory::getApplication(1);
			$template = self::getDefaultTemplate();
			$file = JPATH_SITE . '/templates/' . $template . '/params.tck';
			if (file_exists($file)) {
				$params = file_get_contents($file);
				self::$templateParams = json_decode($params);

				return self::$templateParams;
			} else {
				return '';
			}
		} else {
			return self::$templateParams;
		}
	}

	public static function loadTemplateColors() {

		$params = self::loadTemplateParams();
		$colors = isset($params->colors) ? $params->colors : '';
		return $colors;
	}

	public static function loadTemplateGooglefonts() {

		$params = self::loadTemplateParams();
		$fonts = isset($params->googlefonts) && $params->googlefonts ? $params->googlefonts : '';
		if (empty($fonts)) return null;
		return explode(',', $fonts);
	}

	public static function loadTemplateGooglefontFile($gfont) {
		$template = self::getDefaultTemplate();
		$path = '/templates/' . $template . '/css/' . $gfont .'.css';

		if (file_exists(JPATH_SITE . $path)) {
			echo '<link rel="stylesheet" type="text/css" href="' . JUri::root(true) . $path . '" />';
		}
	}

	public static function getDefaultTemplate() {
		if (empty(self::$defaultTemplate)) {
			$db = JFactory::getDBO();
			$query = "SELECT template FROM #__template_styles WHERE client_id = 0 AND home = 1";
			$db->setQuery($query);
			self::$defaultTemplate = $db->loadResult();
		}

		return self::$defaultTemplate;
	}

	public static function loadSettingsColors() {
		// get global component params
		$params = JComponentHelper::getParams('com_pagebuilderck');
		$colors = array(
			$params->get('colorpalette1')
			,$params->get('colorpalette2')
			,$params->get('colorpalette3')
			,$params->get('colorpalette4')
			,$params->get('colorpalette5')
		);
		return implode(',', $colors);
	}

	/**
	 * Set the correct video link
	 *
	 * $videolink string the video path
	 *
	 * @return string the new video path
	 */
	static function setImageUrl($url) {
		if (strpos($url, 'http') !== 0) {
			$url = JUri::root(true) . '/' . trim($url, '/');
		}

		return $url;
	}

	public static function getThirdPartyIntegrations() {
		$input = JFactory::getApplication()->input;

		$contextOption = $input->get('option', '');
		$contextView = $input->get('view', '');
		$check = self::getThirdPartyIntegrationsFomParams($contextOption, $contextView);

		// if no authorization, then stop here
		if ($check == false) return false;

		switch ($contextOption) {
			case 'com_content';
				$data = array(
					'auhtorizedContext' => 'com_content.article'
					, 'attribs' => 'attribs'
					, 'fieldsname' => 'articletext'
					, 'adminForm' => 'adminForm'
					, 'form' => 'jform'
					);
			break;
			case 'com_flexicontent';
				$data = array(
					'auhtorizedContext' => 'com_content.article'
					, 'attribs' => 'attribs'
					, 'fieldsname' => 'text'
					, 'adminForm' => 'adminForm'
					, 'form' => 'jform'
					);
			break;
			case 'com_djcatalog2';
				$data = array(
					'auhtorizedContext' => 'com_djcatalog2.item'
					, 'attribs' => 'params'
					, 'fieldsname' => 'description'
					, 'adminForm' => 'item-form'
					, 'form' => 'jform'
					);
			break;
			default :
				$data = array(
					'auhtorizedContext' => ''
					, 'attribs' => ''
					, 'fieldsname' => ''
					, 'adminForm' => ''
					, 'form' => ''
					);
			break;
		}

		return $data;
	}

	private static function getThirdPartyIntegrationsFomParams($context, $view) {
		$pagebuilderckParams = JComponentHelper::getParams('com_pagebuilderck');
		$check = $pagebuilderckParams->get('thirdpartyintegreation_' . $context . $view, true);
		return $check;
	}

	/**
	 * Check if a new version is available
	 * 
	 * @return false, or the latest version
	 */
	public static function getLatestVersion() {
		$releaseNotes = self::getReleaseNotes();
		$latest_version = false;
		if ($releaseNotes) {
			// $test_version = preg_match('/\*(.*?)\n/', $releaseNotes, $results);
			// $latest_version = trim($results[1]);
			$latest_version = $releaseNotes->version;
		}

		return $latest_version;
	}
	
	/*
	 * Get a variable from the manifest file.
	 * 
	 * @return the current version
	 */
	public static function getCurrentVersion() {
		if (! self::$currentVersion) {
			// get the version installed
			self::$currentVersion = false;
			$file_url = JPATH_SITE .'/administrator/components/com_pagebuilderck/pagebuilderck.xml';
			if (! $xml_installed = simplexml_load_file($file_url)) {
				// die;
			} else {
				self::$currentVersion = (string)$xml_installed->version;
			}
		}

		return self::$currentVersion;
	}

	/**
	 * Get the release notes content
	 * 
	 * @return false or the file content
	 */
	public static function getReleaseNotes() { 
		if (! self::$releaseNotes) {
			// $url = 'http://update.joomlack.fr/pagebuilderck_update.txt';
			$url = 'https://update.joomlack.fr/pagebuilderck_notes.json';
			$releaseNotes = @file_get_contents($url);
			self::$releaseNotes = json_decode($releaseNotes);
		}
		
		return self::$releaseNotes;
	}

	/**
	 * Format the release notes in html
	 */
	public static function displayReleaseNotes() {
		$releaseNotes = self::getReleaseNotes();
		if (! isset($releaseNotes->releasenotes)) return;

		if (self::isOutdated()) {
			echo '<br /><p style="text-transform:uppercase;text-decoration: underline;">Release notes :</p><br />';
		}
		foreach ($releaseNotes->releasenotes as $i => $v) {
			// stop at the current version notes
			if (version_compare($i, self::getCurrentVersion() ) <= 0) break;

			echo '<h4>VERSION : ' . $i . ' - ' . $v->date . '</h4>';
			echo '<ul>';
				foreach ($v->notes as $n) {
					echo '<li>' . htmlspecialchars($n) . '</li>';
				}
			echo '</ul>';
		}
	}

	/**
	 * Check if you have the latest version
	 * 
	 * @return boolean, true if outdated
	 */
	public static function isOutdated() {
		return version_compare(self::getLatestVersion(), self::getCurrentVersion() ) > 0;
	}

	public static function ajaxSetPluginOption() {
		// security check
		CKFof::checkAjaxToken();

		$input = CKFof::getInput();
		$customdata = $input->get('customdata', '', 'raw');
		$name = $input->get('name', '', 'string');

		if (is_array($customdata)) {
			$customdata = serialize($customdata);
}

		PagebuilderckHelper::setOption($name, $customdata);
		exit;
	}

	public static function ajaxGetPluginOption() {
		// security check
		CKFof::checkAjaxToken();

		$input = CKFof::getInput();
		$name = $input->get('name', '', 'string');
		$key = $input->get('key', '', 'string');

		$data = PagebuilderckHelper::getOption($name);

		if ( is_string( $data ) ) {
			$tmpdata = @unserialize($data);
			if ($tmpdata) $data = $tmpdata;
		}

		echo isset($data[$key]) ? $data[$key] : '';
		exit;
	}

	/**
	 * Remove special character
	 */
	public static function cleanName($path) {
		return preg_replace('/[^a-z0-9]/i', '_', $path);
	}

	public static function formatPath($p) {
		return trim(str_replace("\\", "/", $p), "/");
	}

	static function getArticle($item) {
		$app = JFactory::getApplication();
		if (version_compare(JVERSION, '4') >= 0) {
			$factory = $app->bootComponent('com_content')->getMVCFactory();

			// Get an instance of the generic articles model
			$articles = $factory->createModel('Articles', 'Site', ['ignore_request' => true]);
		} else {
			// load the content articles file
			$com_path = JPATH_SITE . '/components/com_content/';
			include_once $com_path . 'router.php';
			include_once $com_path . 'helpers/route.php';
			JModelLegacy::addIncludePath($com_path . '/models', 'ContentModel');

			// Get an instance of the generic articles model
			$articles = JModelLegacy::getInstance('Articles', 'ContentModel', array('ignore_request' => true));
		}
		// Access filter
		$access = !JComponentHelper::getParams('com_content')->get('show_noauth');
		$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
		// Get an instance of the generic articles model
		$articles = JModelLegacy::getInstance('Articles', 'ContentModel', array('ignore_request' => true));
		// Set application parameters in model
		$app = JFactory::getApplication();
//		$appParams = $app->getParams();
		$articles->setState('params', JComponentHelper::getParams('com_content'));
//		$articles->setState('params', $appParams);
		$articles->setState('filter.published', 1);
		$item->slidearticleid = isset($item->slidearticleid) ? $item->slidearticleid : $item->articleid;
		$articles->setState('filter.article_id', $item->slidearticleid);
		$items2 = $articles->getItems();
		$item->article = $items2[0];
		$item->text = $item->article->introtext;
		// $item->text = JHTML::_('content.prepare', $item->text);
		$item->title = $item->article->title;
		// set the item link to the article depending on the user rights
		if ($access || in_array($item->article->access, $authorised)) {
			// We know that user has the privilege to view the article
			$item->slug = $item->article->id . ':' . $item->article->alias;
			$item->catslug = $item->article->catid ? $item->article->catid . ':' . $item->article->category_alias : $item->article->catid;
			$item->link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug));
		} else {
			$app = JFactory::getApplication();
			$menu = $app->getMenu();
			$menuitems = $menu->getItems('link', 'index.php?option=com_users&view=login');
			if (isset($menuitems[0])) {
				$Itemid = $menuitems[0]->id;
			} elseif ($app->input->get('Itemid', 0, 'int') > 0) {
				$Itemid = $app->input->get('Itemid', 0, 'int');
			}
			$item->link = JRoute::_('index.php?option=com_users&view=login&Itemid=' . $Itemid);
		}
		return $item;
	}

	public static function getCategories() {
		$db = CKFof::getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select('a.*');
		$query->from('`#__pagebuilderck_categories` AS a');

		// Do not list the trashed items
		$query->where('a.state > 0');

		// Add the list ordering clause.
		// $query->order('a.name ASC');

		$items = $db->setQuery($query)->loadObjectList('id');

		return $items;
	}

	public static function getStyles() {
		$db = CKFof::getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select('a.*');
		$query->from('`#__pagebuilderck_styles` AS a');

		// Do not list the trashed items
		$query->where('a.state > 0');

		// Add the list ordering clause.
		// $query->order('a.name ASC');

		$items = $db->setQuery($query)->loadObjectList('id');

		return $items;
	}

	public static function getStyleCode($id) {
		$query = "SELECT stylecode FROM #__pagebuilderck_styles WHERE id = " . (int)$id;

		return CKFof::dbLoadResult($query);
	}
}
