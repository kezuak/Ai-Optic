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

/*$option = $this->input->get('dataoption', '', 'string');
$view = $this->input->get('dataview', '', 'string');
$id = $this->input->get('dataid', '', 'int');
// get the custom css option
 * */
$customcss = $this->input->get('customcss', '', 'raw');

?>
<script>
// init the custom editor
var ckcustomcsseditor = CodeMirrorCK.fromTextArea(document.getElementById("ckcustomcsseditor"), {
	mode: "css",
	lineNumbers: true,
});
setTimeout(function() {
	ckcustomcsseditor.refresh();
}, 100);
</script>
<div class="cktitle"><?php echo JText::_('CK_CUSTOMCSS'); ?></div>
<div class="ckdesc"><?php echo JText::_('CK_CUSTOMCSS_DESC'); ?></div>
<textarea id="ckcustomcsseditor" cols="50" rows="20" style="color: #777;"><?php echo $customcss; ?></textarea>
