<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */

// No direct access.
defined('_JEXEC') or die('Restricted access');

use Joomla\Registry\Registry;
use Pagebuilderck\CKModel;
use Pagebuilderck\CKFof;

include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/defines.php';
include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/ckmodel.php';
include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/simple_html_dom.php';
include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/pagebuilderckfront.php';
include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/pagebuilderck.php';

class PagebuilderckModelPage extends CKModel {

	protected $table = '#__pagebuilderck_pages';

	var $item = null;

//	protected $_context = 'com_pagebuilderck.page';

	public $styleTags;

	public $responsiveStyleTags4, $responsiveStyleTags3, $responsiveStyleTags2, $responsiveStyleTags1;

	function __construct() {
		parent::__construct();
	}

	public function getItem($id = 0) {
		$user = JFactory::getUser();

		// for new page
		if ($id === 0) {
			// check that the user has the rights to edit
			$authorised = ($user->authorise('core.create', 'com_pagebuilderck') || (count($user->getAuthorisedCategories('com_pagebuilderck', 'core.create'))));
			if ($authorised !== true)
			{
				throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 403);
				return false;
			}
			$this->item = CKFof::dbLoad($this->table, $id);
		} else {
			// item already exists
			if (empty($this->item)) {
//				$id = $this->input->get('id', $id, 'int');
				$query = "SELECT * FROM " . $this->table . " AS a"
						. " WHERE a.state = 1"
						. " AND a.id = " . (int) $id;
				try {
				$this->item = CKFof::dbLoadObject($query);
				} catch(Exception $e) {
					return false;
			}
			}
			if (empty($this->item)) {
				return false;
			}

			if ($id !== 0) {
				// counter for hits
				$sql = "UPDATE #__pagebuilderck_pages SET hits = hits + 1 WHERE id= " . $this->item->id;
				CKFof::dbExecute($sql);
			}
		}

		if (! empty($this->item)) {
			// transform params to JRegistry object
			if (isset($this->item->params)) $this->item->params = new JRegistry($this->item->params);
		}

		if (isset($this->item->htmlcode) && $this->item->htmlcode) {
			// replace the root path for all elements
			$this->item->htmlcode = trim(str_replace("|URIROOT|", JUri::root(true), $this->item->htmlcode));

			// pass through the html code to convert what is needed
			if ($this->input->get('layout', '', 'cmd') !== 'edit') {
				$this->parseHtml($this->item->htmlcode);
			}

			// active the content plugins interaction
			if ($this->item->params->get('contentprepare', '0')) {
				CKFof::importPlugin('content');
				$this->item->htmlcode = JHtml::_('content.prepare', $this->item->htmlcode, '', 'com_pagebuilderck.page');
			}
		}

		return $this->item;
	}

	public function parseHtml(&$htmlcode) {
		$customcss = '';
		// replace the root path for all elements
		$htmlcode = trim(str_replace("|URIROOT|", JUri::root(true), (string)$htmlcode));
		// replace the tags for W3C compliance
		// $htmlcode = trim(str_replace('<div class="ckstyle"><style>', '<div class="ckstyle"><style scoped>', $htmlcode)); // WARNING : scoped limits to the ckstyle parent !
		$htmlcode = str_replace('https://fonts.googleapis.com', 'https://fonts.googleapis.com', $htmlcode);
		$htmlcode = trim(str_replace('height=""', '', $htmlcode));
		$htmlcode = trim(str_replace('width=""', '', $htmlcode));
		// for RS Firewall fix
		$htmlcode = trim(str_replace('<s-tyle', '<style', $htmlcode));
		$htmlcode = trim(str_replace('</s-tyle', '</style', $htmlcode));
		// other fix
		$htmlcode = trim(str_replace('<style type="text/css" wfd-invisible="true">', '<style>', $htmlcode));
		$htmlcode = trim(str_replace('<style wfd-invisible="true"', '<style', $htmlcode));

		// load the modules
		// <div class="modulerow" data-module="mod_menu" data-title="a propos de joomla!" data-id="23" style="cursor:pointer;">
		$regex2 = "#<div\s[^>]*class=\"modulerow([^\"]*)\"([^>]*)>(.*)<\/div>#siU"; // masque de recherche pour le tag
		$htmlcode = preg_replace_callback($regex2, array($this, 'replaceModule'), $htmlcode);

		// load the pages
		$regex3 = "#<div\s[^>]*class=\"pagerow([^\"]*)\"([^>]*)>(.*)<\/div>#siU"; // masque de recherche pour le tag
		$htmlcode = preg_replace_callback($regex3, array($this, 'replacePage'), $htmlcode);

		// loop through elements to be replaced in frontend
		// $regex_type = "#<div\s[^>]*class=\"cktype([^\"]*)\"([^>]*)>(.*?)<\/div>#siU"; // masque de recherche pour le tag
		// $htmlcode = preg_replace_callback($regex_type, array($this, 'replaceElement'), $htmlcode);

		if ($htmlcode) {
			$html = \Pagebuilderck\str_get_html($htmlcode);

			// find all acl in the page
			foreach($html->find('[data-acl-view]') as $e) {
				$aclview = explode(',', $e->attr['data-acl-view']);
				$user = JFactory::getUser();
				$aclviewexists = array_intersect($user->groups, $aclview);
				if (! empty($aclviewexists)) {
					$e->outertext = '';
				}
			}

			// find all types in the page
			foreach($html->find('div.cktype') as $e) {
				if ($e->attr['data-type'] !== 'text') {
					foreach($e->find('div.cktype') as $f) {
						$f->innertext = $this->replaceElement($f);
					}
				}
				$e->innertext = $this->replaceElement($e);
			}

			// find all google fonts, call them as stylesheet in the page header
			foreach($html->find('div.googlefontscall') as $e) {
				$regex = "#href=\"\s*[^>]*\?family=([^\"]*)\"[^>]*>#siU"; // replace all divs with class ckprops
				foreach(explode('<link', $e->innertext) as $call) {
					preg_match($regex, $call, $matches);
					$fontcalled = isset($matches[1]) && $matches[1] ? $matches[1] : '';
					if ($fontcalled) PagebuilderckFrontHelper::addStylesheet('https://fonts.googleapis.com/css?family=' . $fontcalled);
					$e->innertext = '';
				}
			}

			// find all google fonts, call them as stylesheet in the page header
			foreach($html->find('div.ckcustomcssfield') as $e) {
				$customcss = htmlspecialchars_decode($e->innertext);
				$e->outertext = '';
			}

			$htmlcode = $html->save();
			$html->clear();
			unset($html);
		}

		$htmlcode = trim(str_replace("|URIROOT|", JUri::root(true), $htmlcode));

		// remove the id="googlefontscall" to avoid html error in frontend
		$htmlcode = preg_replace('/ id="googlefontscall"/', '', $htmlcode);
		$regexGooglefont = "#<div\s[^>]*googlefontscall([^\"]*)\"[^>]*>(.*)<\/div>#siU"; // replace all divs with class ckprops
		$htmlcode = preg_replace_callback($regexGooglefont, array($this, 'replaceGooglefont'), $htmlcode);

		// remove the params div
		$regex = "#<div\s[^>]*pagebuilderckparams([^\"]*)\"[^>]*>[^<]*<\/div>#siU"; // replace all divs with class ckprops
		$htmlcode = preg_replace($regex, '', $htmlcode);

		// remove all the settings values (that are not needed in front) (TODO with parser)
		$regex = "#<div\s[^>]*ckprops([^\"]*)\"[^>]*>[^<]*<\/div>#siU"; // replace all divs with class ckprops
		$htmlcode = preg_replace($regex, '', $htmlcode);
		$htmlcode = preg_replace('/<div fieldslist="(.*?)"(.*?)><\/div>/', '', $htmlcode);

		// remove all the unwanted html code
		$regex = "#<div\s[^>]*cktype-button-add([^\"]*)\"[^>]*>[^<]*<\/div>#siU"; // replace all divs with class blockck-button-add
		$htmlcode = preg_replace($regex, '', $htmlcode);
		$regex = "#<div\s[^>]*blockck-button-add([^\"]*)\"[^>]*>[^<]*<\/div>#siU"; // replace all divs with class blockck-button-add
		$htmlcode = preg_replace($regex, '', $htmlcode);
		$regex = "#<div\s[^>]*rowck-button-add([^\"]*)\"[^>]*>[^<]*<\/div>#siU"; // replace all divs with class blockck-button-add
		$htmlcode = preg_replace($regex, '', $htmlcode);

		$regexStyle = "#<div\s[^>]*ckstyleresponsive([^\"]*)\"[^>]*>(.*)<\/div>#siU"; // replace all divs with class ckprops
		$htmlcode = preg_replace_callback($regexStyle, array($this, 'replaceResponsiveStyleTag'), $htmlcode);

		$regexStyle = "#<div\s[^>]*ckstyle([^\"]*)\"[^>]*>(.*)<\/div>#siU"; // replace all divs with class ckprops
		$htmlcode = preg_replace_callback($regexStyle, array($this, 'replaceStyleTag'), $htmlcode);

		$regexStyle2 = "#<style\s[^>]*ckcolumnwidth([^\"]*)\"[^>]*>(.*)<\/style>#siU"; // replace all divs with class ckprops
		$htmlcode = preg_replace_callback($regexStyle2, array($this, 'replaceResponsiveStyleTag'), $htmlcode);

		$regexText = "#CKTEXT\[(.*?)\]#"; // replace all divs with class ckprops
		// $htmlcode = preg_replace($regex, '', $htmlcode);
		$htmlcode = preg_replace_callback($regexText, array($this, 'replaceTranslation'), $htmlcode);

		$regexOnshow = "#onshow=\"(.*?)\"#"; // replace all divs with class ckprops
		$htmlcode = preg_replace($regexOnshow, '', $htmlcode);

		if ($this->item && isset($this->item->modified)) $this->searchAndReplaceDates($htmlcode, $this->item->created, $this->item->modified);
		
		$regexImg = "#<img(.*?)>#si"; // masque de recherche pour le tag
//		$htmlcode = preg_replace($find, '', $htmlcode);
		$htmlcode = preg_replace_callback($regexImg, array($this, 'replaceImage'), $htmlcode);

		$doc = JFactory::getDocument();
		PagebuilderckFrontHelper::addStyleDeclaration($this->styleTags);
		$componentParams = JComponentHelper::getParams('com_pagebuilderck');
		$responsiverange = $componentParams->get('responsiverange', 'reducing');
		if (! empty(trim((string)$this->responsiveStyleTags4))) {
			PagebuilderckFrontHelper::addStyleDeclaration('@media only screen and (max-width:' . (int)$componentParams->get('responsive4value', '800') . 'px) ' . ($responsiverange == 'between' ? 'and (min-width:' . ((int)$componentParams->get('responsive3value', '640')+1) . 'px)' : '')  . '{ ' . $this->responsiveStyleTags4 . ' }');
		}
		if (! empty(trim((string)$this->responsiveStyleTags3))) {
			PagebuilderckFrontHelper::addStyleDeclaration('@media only screen and (max-width:' . (int)$componentParams->get('responsive3value', '640') . 'px) ' . ($responsiverange == 'between' ? 'and (min-width:' . ((int)$componentParams->get('responsive2value', '480')+1) . 'px)' : '')  . '{ ' . $this->responsiveStyleTags3 . ' }');
		}
		if (! empty(trim((string)$this->responsiveStyleTags2))) {
			PagebuilderckFrontHelper::addStyleDeclaration('@media only screen and (max-width:' . (int)$componentParams->get('responsive2value', '480') . 'px) ' . ($responsiverange == 'between' ? 'and (min-width:' . ((int)$componentParams->get('responsive1value', '320')+1) . 'px)' : '')  . '{ ' . $this->responsiveStyleTags2 . ' }');
		}
		if (! empty(trim((string)$this->responsiveStyleTags1))) {
			PagebuilderckFrontHelper::addStyleDeclaration('@media only screen and (max-width:' . (int)$componentParams->get('responsive1value', '320') . 'px){ ' . $this->responsiveStyleTags1 . ' }');
		}

		// get the custom css option
		if ($customcss) {
			PagebuilderckFrontHelper::addStyleDeclaration($customcss);
		}
	}

	public function searchAndReplaceDates(&$text, $created, $modified) {
		$regexText = "#CKDATE_CREATED\[(.*?)\]#"; // replace all divs with class ckprops
		// $text = $article->text;
		// $created = $article->created;
		// $modified = $article->modified;
		$text = preg_replace_callback($regexText, function($matches) use ($text, $created) {
			// if (! $this->item) return $matches[0];

			if (!$matches[1]) {
				$format = 'DATE_FORMAT_LC4';
			} else {
				$format = $matches[1];
			}
			$date = date(JText::_($format), strtotime($created));

			return $date;
		}, $text);

		$regexText = "#CKDATE_MODIFIED\[(.*?)\]#"; // replace all divs with class ckprops
		$text = preg_replace_callback($regexText, function($matches) use ($text, $modified) {
			// if (! $this->item) return $matches[0];

			if (!$matches[1]) {
				$format = 'DATE_FORMAT_LC4';
			} else {
				$format = $matches[1];
			}
			$date = date(JText::_($format), strtotime($modified));

			return $date;
		}, $text);
	}

	/*
	 * @param array the matching strings
	 * 
	 * return the translated string
	 */
	public function replaceDateCreated($matches) {
		if (! $this->item) return $matches[0];

		if (!$matches[1]) {
			$format = 'DATE_FORMAT_LC4';
		} else {
			$format = $matches[1];
		}
		$date = date(JText::_($format), strtotime($this->item->created));

		return $date;
	}

	/*
	 * @param array the matching strings
	 * 
	 * return the translated string
	 */
	public function replaceDateModified($matches) {
		if (! $this->item) return $matches[0];

		if (!$matches[1]) {
			$format = 'DATE_FORMAT_LC4';
		} else {
			$format = $matches[1];
		}
		$date = date(JText::_($format), strtotime($this->item->modified));

		return $date;
	}

	/*
	 * @param array the matching strings
	 * 
	 * return the translated string
	 */
	public function replaceTranslation($matches) {

		if (!$matches[1]) return;
		return JText::_($matches[1]);
	}

	/*
	 * @param array the matching strings
	 * 
	 * return the update image tag
	 */
	public function replaceImage($matches) {
		if (!$matches[1]) return;
		$imgtag = $matches[0];

		// get global component params
		$pagebuilderckParams = JComponentHelper::getParams('com_pagebuilderck');
		// check for the image path, and fix it
		if (strpos($imgtag, 'data-src=')) {
			if ((int)$pagebuilderckParams->get('image_path_fix', 0, 'int') === 1) {
				// remove the src attribute, it will be created later
				$find = "# src=\"(.*?)\"#si"; // masque de recherche pour le tag
				$imgtag = preg_replace($find, '', $imgtag);
				if (strpos($imgtag, 'data-src="/')) {
					// fix url starting with a slash
					$find = "#data-src=\"/(.*?)\"#si"; // masque de recherche pour le tag
					$imgtag = preg_replace($find, ' src="' . JUri::root(true) . '/$1"', $imgtag);
				} else {
					// fix url not starting with a slash
					$find = "#data-src=\"(.*?)\"#si"; // masque de recherche pour le tag
					$imgtag = preg_replace($find, ' src="' . JUri::root(true) . '/$1"', $imgtag);
				}
			} else {
				$find = "#data-src=\"(.*?)\"#si"; // masque de recherche pour le tag
				$imgtag = preg_replace($find, '', $imgtag);
			}
		}

		// add lazy loading
		if ((int)$pagebuilderckParams->get('lazy_loading', 0, 'int') === 1) {
			$imgtag = str_replace ('<img', '<img loading="lazy"', $imgtag);
		}

		return $imgtag;
	}

	/*
	 * @param array the matching strings
	 * 
	 * return void
	 */
	public function replaceGooglefont($matches) {
		if (!isset($matches[2])) return;
		$fontfamilies = explode('<', $matches[2]);

		foreach ($fontfamilies as $fontfamily) {
			$fontfamily = trim($fontfamily);
			$fontfamily = str_replace('link href="', '', $fontfamily);
			$fontfamily = str_replace('" rel="stylesheet" type="text/css">', '', $fontfamily);

			if ($fontfamily) PagebuilderckFrontHelper::addStylesheet($fontfamily);
		}

		return;
	}

	/*
	 * @param array the matching strings
	 * 
	 * return the module cdoe
	 */
	public function replaceResponsiveStyleTag($matches) {
		if (!$matches[2]) return;
		$styleTag = trim($matches[2]);
		$styleTag = str_replace('<style>', '', $styleTag);
		$styleTag = str_replace('<style type="text/css">', '', $styleTag);
		$styleTag = str_replace('</style>', '', $styleTag);
		$styleTag = str_replace('&nbsp;', ' ', $styleTag);

		if ($styleTag) {
			if (stristr($matches[1], 'ckresponsiverange4')) {
				$this->responsiveStyleTags4 .= str_replace('.ckresponsiveactive[ckresponsiverange="4"] ', '', $styleTag);
				$this->responsiveStyleTags4 .= str_replace('.ckresponsiveactive[ckresponsiverange*="4"] ', '', $styleTag);
			} else if (stristr($matches[1], 'ckresponsiverange3')) {
				$this->responsiveStyleTags3 .= str_replace('.ckresponsiveactive[ckresponsiverange="3"] ', '', $styleTag);
				$this->responsiveStyleTags3 .= str_replace('.ckresponsiveactive[ckresponsiverange*="3"] ', '', $styleTag);
			} else if (stristr($matches[1], 'ckresponsiverange2')) {
				$this->responsiveStyleTags2 .= str_replace('.ckresponsiveactive[ckresponsiverange="2"] ', '', $styleTag);
				$this->responsiveStyleTags2 .= str_replace('.ckresponsiveactive[ckresponsiverange*="2"] ', '', $styleTag);
			} else if (stristr($matches[1], 'ckresponsiverange1')) {
				$this->responsiveStyleTags1 .= str_replace('.ckresponsiveactive[ckresponsiverange="1"] ', '', $styleTag);
				$this->responsiveStyleTags1 .= str_replace('.ckresponsiveactive[ckresponsiverange*="1"] ', '', $styleTag);
			} else if (stristr($styleTag, 'ckresponsiverange="4"')) {
				$this->responsiveStyleTags4 .= str_replace('[ckresponsiverange="4"] ', '', $styleTag);
				$this->responsiveStyleTags4 .= str_replace('[ckresponsiverange*="4"] ', '', $styleTag);
			} else if (stristr($styleTag, 'ckresponsiverange="3"')) {
				$this->responsiveStyleTags3 .= str_replace('[ckresponsiverange="3"] ', '', $styleTag);
				$this->responsiveStyleTags3 .= str_replace('[ckresponsiverange*="3"] ', '', $styleTag);
			} else if (stristr($styleTag, 'ckresponsiverange="2"')) {
				$this->responsiveStyleTags2 .= str_replace('[ckresponsiverange="2"] ', '', $styleTag);
				$this->responsiveStyleTags2 .= str_replace('[ckresponsiverange*="2"] ', '', $styleTag);
			} else if (stristr($styleTag, 'ckresponsiverange="1"')) {
				$this->responsiveStyleTags1 .= str_replace('[ckresponsiverange="1"] ', '', $styleTag);
				$this->responsiveStyleTags1 .= str_replace('[ckresponsiverange*="1"] ', '', $styleTag);
			} else if (stristr($styleTag, 'ckresponsiverange="4321"')) {
				$this->responsiveStyleTags1 .= str_replace('[ckresponsiverange="4321"] ', '', $styleTag);
			} else if (stristr($styleTag, 'ckresponsiverange="432"') || stristr($styleTag, 'ckresponsiverange="432')) {
				$this->responsiveStyleTags2 .= str_replace('[ckresponsiverange="432"] ', '', $styleTag);
			} else if (stristr($styleTag, 'ckresponsiverange="43"') || stristr($styleTag, 'ckresponsiverange="43')) {
				$this->responsiveStyleTags3 .= str_replace('[ckresponsiverange="43"] ', '', $styleTag);
			} else {
				if ($styleTag) $this->styleTags .= $styleTag;
			}
		}
		return '';
	}

	/*
	 * @param array the matching strings
	 * 
	 * return the module cdoe
	 */
	public function replaceStyleTag($matches) {

		if (!$matches[2]) return;
		$styleTag = trim($matches[2]);
		$styleTag = str_replace('<style>', '', $styleTag);
		$styleTag = str_replace('<style type="text/css">', '', $styleTag);
		$styleTag = str_replace('</style>', '', $styleTag);
		$styleTag = str_replace('&nbsp;', ' ', $styleTag);

		$pagebuilderckParams = JComponentHelper::getParams('com_pagebuilderck');
		// check for the image path, and fix it
		if (strpos($styleTag, 'url(\'/')) {
			if ((int)$pagebuilderckParams->get('image_path_fix', 0, 'int') === 1) {
				$find = "# url\('/(.*?)\'\)#si"; // masque de recherche pour le tag
				$styleTag = preg_replace($find, ' url(\'' . JUri::root(true) . '/$1\')' , $styleTag);
			}
		}

		if ($styleTag) $this->styleTags .= $styleTag;

		return '';
	}
	
	/*
	 * @param array the matching strings
	 * 
	 * return the module code
	 */
	public function replaceModule($matches) {
		if (!$matches[2]) return;

		// look for the module ID
		$find = "#data-id=\"(.*?)\"#si"; // masque de recherche pour le tag
		preg_match($find, $matches[2], $result_id);
		if ($result_id && $result_id[1]) {
			return $this->renderModule($result_id[1]);
		}

		return '';
	}

	/*
	 * @param array the matching strings
	 * 
	 * return the page code
	 */
	public function replacePage($matches) {
		if (!$matches[2]) return;

		// look for the module ID
		$find = "#data-id=\"(.*?)\"#si"; // masque de recherche pour le tag
		preg_match($find, $matches[2], $result_id);
		if ($result_id && $result_id[1]) {
			return $this->renderPage($result_id[1]);
		}

		return '';
	}

	/*
	 * @param object the element
	 * 
	 * return the module cdoe
	 */
	public function replaceElement($e) {
		$type = $e->attr['data-type'];

		if ($type) {
			$new_e = $this->renderElement($type, $e);
			if ($new_e) {
				return $new_e;
			} else {
				return $e->innertext;
			}
		} else if ($type == 'audio') {
			return $this->renderAudioElement($e);
		} else {
			return '<p style="text-align:center;color:red;font-size:14px;">ERROR - PAGEBUILDER CK DEBUG : ELEMENT TYPE NOT FOUND</p>';
		}

		return $e->innertext;
	}

	/*
	 * @param object the element
	 * 
	 * return the element html code for html5 audio
	 */
	public function renderCode($e) {
		// check if there is a plugin for this type, and if it is enabled
		if ( !JPluginHelper::isEnabled('pagebuilderck', 'code')) {
			return '';
		}


		JPluginHelper::importPlugin( 'pagebuilderck' );
//		$dispatcher = JEventDispatcher::getInstance();
		$otheritems = Pagebuilderck\CKFof::triggerEvent( 'onPagebuilderckRenderItem' .  ucfirst('code') , array($e));
		// load only the first instance found, because each plugin type must be unique
			// add override feature here, look in the template
			$template = JFactory::getApplication()->getTemplate();
			$overridefile = JPATH_ROOT . '/templates/' . $template . '/html/pagebuilderck/' . strtolower('code') . '.php';
//			var_dump($otheritems);
			if (file_exists($overridefile)) {
				$item = $e;
				include_once $overridefile;
			} else {
				// normal use
//				$html = $otheritems[0];
			}
			// echo $html;

		return '';
	}

	/*
	 * @param object the element
	 * 
	 * return the element html code for html5 audio
	 */
	public function renderAudioElement($e) {
		$attrs = $e->find('.tab_audio');
		$params = PagebuilderckFrontHelper::createParamsFromElement($attrs);

		$audiosrc = PagebuilderckFrontHelper::getSource($params->get('audiourl'));
		$html ='<audio style="width:100%;box-sizing:border-box;max-width:100%;" controls src="' . $audiosrc . '" ' . ($params->get('autoplayyes') == 'checked' ? 'autoplay' : '') . '>'
				. 'Your browser does not support the audio element.'
				. '</audio>';

		$html2 = preg_replace('#<div class="audiock">(.*?)<\/div>#is', $html, $e->innertext);

		return $html2;
	}

	/*
	 * @param string the element type
	 * @param object the element
	 * 
	 * return the element html code
	 */
	public function renderElement($type, $e) {
		// check if there is a plugin for this type, and if it is enabled
		if ( !JPluginHelper::isEnabled('pagebuilderck', $type)) {
			return '';
		}
		$doc = JFactory::getDocument();

		JPluginHelper::importPlugin( 'pagebuilderck' );
//		$dispatcher = JEventDispatcher::getInstance();
		$otheritems = Pagebuilderck\CKFof::triggerEvent( 'onPagebuilderckRenderItem' .  ucfirst($type) , array($e));

		ob_start();
		if (count($otheritems) == 1) {
			// load only the first instance found, because each plugin type must be unique
			// add override feature here, look in the template
			$template = JFactory::getApplication()->getTemplate();
			$overridefile = JPATH_ROOT . '/templates/' . $template . '/html/pagebuilderck/' . strtolower($type) . '.php';
			if (file_exists($overridefile)) {
				$item = $e;
				include_once $overridefile;
				try {
					$class = 'plgPagebuilderck' . ucfirst($type) . 'Override';
					$func = 'onPagebuilderckRenderItem' .  ucfirst($type);
					$reflection = new ReflectionClass($class);
					$instance = $reflection->newInstanceWithoutConstructor();
					if ($instance) {
						$html = $instance->$func($e);
					}
				} catch (Exception $e) {
				
				}
			} else {
				// normal use
				$html = $otheritems[0];
			}
			echo $html;
		} else {
			echo '<p style="text-align:center;color:red;font-size:14px;">ERROR - PAGEBUILDER CK DEBUG : ELEMENT TYPE INSTANCE : ' . $type . '. Number of instances found : ' . count($otheritems) . '</p>';
		}
		$element_code = ob_get_clean();
		return $element_code;
	}

	/*
	 * @param int the module ID
	 * 
	 * return the module html code
	 */
	public function renderModule($module_id) {
		$document	= JFactory::getDocument();
		$renderer	= $document->loadRenderer('module');
		$mod		= $this->getModule($module_id);
		if (empty($mod)) $mod = JModuleHelper::getModuleById($module_id);

		if (!$mod) return;

		$params = array('style' => 'xhtml');
		ob_start();

		echo $renderer->render($mod, $params);

		$module_code = ob_get_clean();

		return $module_code;
	}

	public function getModule($module_id) {
		$app = JFactory::getApplication();
		$groups = implode(',', JFactory::getUser()->getAuthorisedViewLevels());
		$lang = JFactory::getLanguage()->getTag();
		$clientId = (int) $app->getClientId();

		$db = JFactory::getDbo();
		
		$query = $db->getQuery(true)
			->select('m.id, m.title, m.module, m.position, m.content, m.showtitle, m.params, mm.menuid')
			->from('#__modules AS m')
			->join('LEFT', '#__modules_menu AS mm ON mm.moduleid = m.id')
			->where('m.published = 1')
			->join('LEFT', '#__extensions AS e ON e.element = m.module AND e.client_id = m.client_id')
			->where('e.enabled = 1');

		$date = JFactory::getDate();
		$now = $date->toSql();
		$nullDate = $db->getNullDate();
		$query->where('(m.publish_up = ' . $db->quote($nullDate) . ' OR m.publish_up <= ' . $db->quote($now) . ')')
			->where('(m.publish_down = ' . $db->quote($nullDate) . ' OR m.publish_down >= ' . $db->quote($now) . ')')
			->where('m.access IN (' . $groups . ')')
			->where('m.client_id = ' . $clientId)
			->where('m.id = ' . (int) $module_id)
			// ->where('(mm.menuid = ' . (int) $Itemid . ' OR mm.menuid <= 0)');
			;

		// Filter by language
		if (\Pagebuilderck\CKFof::isSite() && $app->getLanguageFilter())
		{
			$query->where('m.language IN (' . $db->quote($lang) . ',' . $db->quote('*') . ')');
		}

		// $query->order('m.position, m.ordering');

		// Set the query
		$db->setQuery($query);

		try
		{
			$module = $db->loadObject();
		}
		catch (RuntimeException $e)
		{
			JLog::add(JText::sprintf('JLIB_APPLICATION_ERROR_MODULE_LOAD', $e->getMessage()), JLog::WARNING, 'jerror');

			return array();
		}

		return $module;
	}

	/**
	 * Module list
	 *
	 * @return  array
	 */
	public static function getModuleList()
	{
		$app = JFactory::getApplication();
		$Itemid = $app->input->getInt('Itemid');
		$groups = implode(',', JFactory::getUser()->getAuthorisedViewLevels());
		$lang = JFactory::getLanguage()->getTag();
		$clientId = (int) $app->getClientId();

		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select('m.id, m.title, m.module, m.position, m.content, m.showtitle, m.params, mm.menuid')
			->from('#__modules AS m')
			->join('LEFT', '#__modules_menu AS mm ON mm.moduleid = m.id')
			->where('m.published = 1')
			->join('LEFT', '#__extensions AS e ON e.element = m.module AND e.client_id = m.client_id')
			->where('e.enabled = 1');

		$date = JFactory::getDate();
		$now = $date->toSql();
		$nullDate = $db->getNullDate();
		$query->where('(m.publish_up = ' . $db->quote($nullDate) . ' OR m.publish_up <= ' . $db->quote($now) . ')')
			->where('(m.publish_down = ' . $db->quote($nullDate) . ' OR m.publish_down >= ' . $db->quote($now) . ')')
			->where('m.access IN (' . $groups . ')')
			->where('m.client_id = ' . $clientId)
			->where('(mm.menuid = ' . (int) $Itemid . ' OR mm.menuid <= 0)');

		// Filter by language
		if (\Pagebuilderck\CKFof::isSite() && $app->getLanguageFilter())
		{
			$query->where('m.language IN (' . $db->quote($lang) . ',' . $db->quote('*') . ')');
		}

		$query->order('m.position, m.ordering');

		// Set the query
		$db->setQuery($query);

		try
		{
			$modules = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			JLog::add(JText::sprintf('JLIB_APPLICATION_ERROR_MODULE_LOAD', $e->getMessage()), JLog::WARNING, 'jerror');

			return array();
		}

		return $modules;
	}

	/**
	 * Method to get an ojbect.
	 *
	 * @param	integer	The id of the object to get.
	 *
	 * @return	mixed	Object on success, false on failure.
	 */
	public function &getData($id = null) {
		if (empty($id)) {
			$id = $this->input->get('id', $id, 'int');
		}
		if ($id === 0) {
			// check that the user has the rights to edit
			$authorised = ($user->authorise('core.create', 'com_pagebuilderck') || (count($user->getAuthorisedCategories('com_pagebuilderck', 'core.create'))));
			if ($authorised !== true)
			{
				throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 403);
				return false;
			}
			$item = CKFof::dbLoad($this->table, $id);
		} else {
			$query = "SELECT * FROM " . $this->table . " AS a"
				. " WHERE a.state = 1"
				. " AND a.id = " . (int) $id;
			$item = CKFof::dbLoadObject($query);
		}

		return $this->item;
	}

	/**
	 * Method to save the form data.
	 *
	 * @param	array		The form data.
	 * @return	mixed		The user id on success, false on failure.
	 * @since	1.6
	 */
	public function save($data) {
		$id = (!empty($data['id'])) ? $data['id'] : (int) $this->getState('page.id');
		$user = CKFof::getUser();
		$data['htmlcode'] = $this->input->get('htmlcode', '', 'raw');
		$data['htmlcode'] = str_replace(JUri::root(true), "|URIROOT|", $data['htmlcode']);

		if (isset($data['options']) && is_array($data['options']))
		{
			$registry = new Registry;
			$registry->loadArray($data['options']);
			$data['params'] = (string) $registry;
		}

		// save the date
		$date = JFactory::getDate();
		$data['modified'] = $date->toSql();
		if ($id) {
			//Check the user can edit this item
			$authorised = $user->authorise('core.edit', 'page.' . $id);
		} else {
			//Check the user can create new items in this section
			$authorised = $user->authorise('core.create', 'com_pagebuilderck');
			$data['created'] = $date->toSql();
		}

		if ($authorised !== true) {
			throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 403);
			return false;
		}

		// make a backup before save
		PagebuilderckHelper::makeBackup($this->getData($data['id']));

		$pageid = CKFof::dbStore($this->table, $data);
		return $pageid;
	}

	/*
	 * @param int the module ID
	 * 
	 * return the module html code
	 */
	public function renderPage($id) {
		include_once JPATH_ROOT . '/components/com_pagebuilderck/models/page.php';
		$model = JModelLegacy::getInstance('Page', 'PagebuilderckModel');
		$page = $model->getItem($id);

		ob_start();

		if (isset($page->htmlcode) && $page->htmlcode) {
			echo $page->htmlcode;
}

		$code = ob_get_clean();

		return $code;
	}
}