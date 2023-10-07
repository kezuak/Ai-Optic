<?php
/**
 * @name		CK Framework
 * @copyright	Copyright (C) 2019. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */
namespace Pagebuilderck;

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

//require_once 'cktext.php';

//use Joomla\CMS\Language\Text as CKText;
use Joomla\CMS\Uri\Uri as CKUri;

/**
 * Framework Helper
 */
class CKFramework {

	private static $assetsPath = '/media/com_pagebuilderck/assets';

	private static $version = '1.0.0';

	private static $doload;

	public static function init() {
		global $ckframeworkloaded;
		global $ckframeworkloadedversion;

		// if the framework is already loaded with a same or better version, do nothing
		if ($ckframeworkloaded && version_compare($ckframeworkloadedversion, self::$version, '>=')) {
			self::$doload = false;
		}

		self::$doload = true;
	}

	public static function getInline() {
		if (self::$doload === false) return '';

		$assets = self::getInlineCss() . self::getInlineJs();

		return $assets;
	}

	public static function getInlineCss() {
		if (self::$doload === false) return '';

		$assets = '<link rel="stylesheet" href="' . CKUri::root(true) . self::$assetsPath . '/ckframework.css" type="text/css" />';

		return $assets;
	}

	public static function getInlineJs() {
		if (self::$doload === false) return '';

		$assets = '<script src="' . CKUri::root(true) . self::$assetsPath . '/ckframework.js" type="text/javascript"></script>';

		return $assets;
	}

	public static function loadInline() {
		echo self::getInline();
	}

	public static function load() {
		if (self::$doload === false) return;

		\JHtml::_('jquery.framework');
		$doc = \JFactory::getDocument();
		$doc->addStylesheet(CKUri::root(true) . self::$assetsPath . '/ckframework.css');
		$doc->addScript(CKUri::root(true) . self::$assetsPath . '/ckframework.js');
	}

	public static function loadCss() {
		if (self::$doload === false) return;

		$doc = \JFactory::getDocument();
		$doc->addStylesheet(CKUri::root(true) . self::$assetsPath . '/ckframework.css');
	}

	public static function loadJs() {
		if (self::$doload === false) return;

		$doc = \JFactory::getDocument();
		$doc->addScript(CKUri::root(true) . self::$assetsPath . '/ckframework.js');
	}

	public static function getFaIconsInline() {
		return '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" type="text/css" />';
	}

	public static function loadFaIconsInline() {
		echo self::getFaIconsInline();
	}
}

CKFramework::init();