<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/stylescss.php';

$id = $this->input->get('ckobjid', '', 'string');
$class = $this->input->get('objclass', '', 'string');
$responsiverange = $this->input->get('responsiverange', '', 'int');
//$fields = stripslashes( $this->input->get('fields', '', 'string'));
$fields = $this->input->get('fields', '', 'string');
$fields = json_decode($fields);
$customstyles = stripslashes( $this->input->get('customstyles', '', 'string'));
$customstyles = json_decode($customstyles);
$cssstyles = new CssStyles();
$textstyles = '';

$newstyles  = '';
$styles = $cssstyles->create($fields, $id, $action = 'preview', $class, 'ltr', $customstyles);
$newstyles .= str_replace('#' . $id, '.ckresponsiveactive[ckresponsiverange*="' . $responsiverange . '"] #' . $id, $styles);

if (stristr($class, 'blockck')) {
	// split the fontsize to give it a new rule
	if (isset($fields->blocfontsize)) {
		$textfields = new stdClass();
		$textfields->blocfontsize = $fields->blocfontsize;
		unset($fields->blocfontsize);
	}
	$textstyles = $cssstyles->create($textfields, $id, $action = 'preview', $class, 'ltr', $customstyles);
	$textstyles = str_replace('#' . $id, '.ckresponsiveactive[ckresponsiverange="' . $responsiverange . '"] #' . $id, $textstyles);
	$textstyles = str_replace('> .inner', '> .inner *', $textstyles);
}

echo '<style>' . $newstyles . $textstyles . '</style>';
exit();