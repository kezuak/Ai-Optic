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

// load the helper to help us to use the parameters
include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/pagebuilderckparams.php';

/**
 * Helper Class.
 */
class PagebuilderckFrontHelper {

	protected static $cssDeclaration;

	protected static $cssSheet = array();

	protected static $compile = null;

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return	JObject
	 * @since	1.6
	 */
	public static function createParamsFromElement($obj) {
		if (is_array($obj) && isset($obj[0]->attr)) {
			$result = new PagebuilderckParams($obj[0]->attr);
		} else {
			$result = new PagebuilderckParams();
		}
		return $result;
	}

	/**
	 * Return the needed url for the source
	 * 
	 * @return string
	 */
	public static function getSource($src) {
		$isLocal = substr($src, 0, 4) == 'http' ? false : true;
		if ($isLocal) $src = JUri::root(true) . '/' . $src;
		return $src;
	}

	/**
	 * Test if there is already a unit, else add the px
	 *
	 * @param string $value
	 * @return string
	 */
	public static function testUnit($value, $defaultunit = "px") {

		if (
			(stristr($value, 'px')) 
			OR (stristr($value, 'em')) 
			OR (stristr($value, 'rem')) 
			OR (stristr($value, '%')) 
			OR (stristr($value, 'vh')) 
			OR (stristr($value, 'vw')) 
			OR (stristr($value, 'vmin')) 
			OR (stristr($value, 'vmax')) 
			OR (stristr($value, 'mm')) 
			OR (stristr($value, 'in')) 
			OR (stristr($value, 'pt')) 
			OR (stristr($value, 'pc')) 
			OR $value == 'auto'
			)
			return $value;

		return $value . $defaultunit;
	}

	/**
	 * Check if we have to compile according to component options
	 */
	private static function doCompile() {
		// if admin, then exit immediately
		if (\Pagebuilderck\CKFof::isAdmin()) return false;
		// if in edition mode, then exit immediately
		$input = JFactory::getApplication()->input;
		if ($input->get('layout', '', 'cmd') === 'edit') return false;
		// check the option from the component
		if (self::$compile == null) {
			$params = JComponentHelper::getParams('com_pagebuilderck');
			self::$compile = $params->get('compile', '0');
		}
		return self::$compile;
	}

	/**
	 * Call the needed JS and CSS files to render in frontend
	 */
	public static function loadFrontendAssets() {
		$params = JComponentHelper::getParams('com_pagebuilderck');
		JHtml::_('jquery.framework');
		$doc = JFactory::getDocument();
//		$doc->addScript(JUri::root(true) . '/media/jui/js/jquery.min.js');
		PagebuilderckFrontHelper::addStyleSheet(JUri::root(true) . '/components/com_pagebuilderck/assets/pagebuilderck.css?ver=' . PAGEBUILDERCK_VERSION);
		if ($params->get('loadfontawesome','1')) $doc->addStyleSheet(JUri::root(true) . '/components/com_pagebuilderck/assets/font-awesome.min.css');
		$doc->addScriptDeclaration('var PAGEBUILDERCK_DISABLE_ANIMATIONS = "' . (int)$params->get('disableanimations','') . '";');
		$doc->addScript(JUri::root(true) . '/components/com_pagebuilderck/assets/jquery-uick.min.js?ver=' . PAGEBUILDERCK_VERSION);
		$doc->addScript(JUri::root(true) . '/components/com_pagebuilderck/assets/pagebuilderck.js?ver=' . PAGEBUILDERCK_VERSION);
		$doc->addScript(JUri::root(true) . '/components/com_pagebuilderck/assets/parallaxbackground.js?ver=' . PAGEBUILDERCK_VERSION);
	}

	/**
	 * Manage the css inclusion in the page according to the component options
	 *
	 * @param string $value
	 * @return void
	 */
	public static function addStyleDeclaration($css) {
		$input = JFactory::getApplication()->input;
//		$compile = true; //TODO : tester si admin, ne pas compiler
		if (self::doCompile()) {
			self::$cssDeclaration .= $css;
		} else {
			$doc = JFactory::getDocument();
			$doc->addStyleDeclaration($css);
		}
	}

	/**
	 * Manage the css inclusion in the page according to the component options
	 *
	 * @param string $value
	 * @return void
	 */
	public static function addStylesheet($file) {
//		$input = JFactory::getApplication()->input;
//		$compile = true; //TODO : tester si admin, ne pas compiler
		if (self::doCompile()) {
			if (! in_array($file, self::$cssSheet)) self::$cssSheet[] = $file;
		} else {
			$doc = JFactory::getDocument();
			$doc->addStylesheet($file);
		}
	}

	/**
	 * Return the global css styles stored
	 *
	 * @param string $value
	 * @return void
	 */
	public static function loadAllCss() {
		if (! self::doCompile()) return;
		$input = JFactory::getApplication()->input;
		$uri = JUri::getInstance();
		$query = $uri->getQuery();
		$query = str_replace(JUri::root(true), '', $query);
		
		$clearPath = preg_replace("/[^a-zA-Z0-9]+/", "", $query);

		$cssContent = '';
		// get the style sheets
		foreach(self::$cssSheet as $sheet) {
			if (stristr($sheet, 'googleapis.com')) continue;
			$path = str_replace(JUri::root(true), '', $sheet);
			// remove params in url
			if ($hashpos = strpos($path, '?')) {
				$path = substr($path, 0, $hashpos);
			}
			
			$tmp = file_get_contents(JPATH_ROOT . $path);

			// get the url for the images path in the css files
			$url = str_replace(JPATH_ROOT, JUri::root(), $path);
			$url = explode('/', $url);
			$num=count($url);
			$num=$num-1;
			unset($url[$num]); // remove the css filename from the path
			$url = implode('/', $url);

			// replace the path to the images
			$tmp = str_replace("url('", "url('" . $url . "/", $tmp);
			$cssContent .= $tmp;
		}

		// get the inline styles
		$cssContent .= self::$cssDeclaration;

		if (trim($cssContent)) {
			$file = '/compiled/pagebuilderck_' . $clearPath . '_compiled.css';
			file_put_contents(PAGEBUILDERCK_MEDIA_PATH . $file, $cssContent);
			$doc = JFactory::getDocument();
			$doc->addStyleSheet(PAGEBUILDERCK_MEDIA_URI . $file);
		}
	}
}