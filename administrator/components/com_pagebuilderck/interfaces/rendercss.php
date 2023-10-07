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
//$fields = stripslashes( $this->input->get('fields', '', 'string'));
$fields = $this->input->get('fields', '', 'string');
$fields = json_decode($fields);
$customstyles = stripslashes( $this->input->get('customstyles', '', 'string'));
$customstyles = json_decode($customstyles);
$action = $this->input->get('action', '', 'string');
$cssstyles = new CssStyles();
$styles = $cssstyles->create($fields, $id, $action, $class, 'ltr', $customstyles);

if ($action == 'preview') {
	echo '<style>' . $styles . '</style>';
} else {
	return $styles;
}

exit();