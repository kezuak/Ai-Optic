<?php
/**
* @version 2.1
* @package DJ Like Box
* @copyright Copyright (C) 2010 Blue Constant Media LTD, All rights reserved.
* @license http://www.gnu.org/licenses GNU/GPL
* @author url: http://design-joomla.eu
* @author email contact@design-joomla.eu
* @developer Szymon Woronowski - szymon.woronowski@design-joomla.eu
*
*
* DJ Like Box is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* DJ Like Box is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with DJ Like Box. If not, see <http://www.gnu.org/licenses/>.
*
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once JPath::clean(dirname(__FILE__).'/helper.php');

$params->set('href', $params->get('href','https://www.facebook.com/djextensions/'));
$params->set('tabs', implode(',', $params->get('tabs',array())));
$params->set('hide_cover', $params->get('hide_cover', 0) ? 'true' : 'false');
$params->set('show_facepile', $params->get('show_facepile', 1) ? 'true' : 'false');
$params->set('hide_cta', $params->get('hide_cta', 0) ? 'true' : 'false');
$params->set('small_header', $params->get('small_header', 0) ? 'true' : 'false');
$params->set('adapt', $params->get('adapt', 1) ? 'true' : 'false');

$language = JFactory::getLanguage();
$lang = str_replace('-', '_', $language->getTag());

require JModuleHelper::getLayoutPath('mod_dj_likebox', $params->get('layout', 'default'));












