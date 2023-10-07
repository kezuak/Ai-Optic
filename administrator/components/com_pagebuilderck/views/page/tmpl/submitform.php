<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2020. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */
defined('_JEXEC') or die;
?>
<script type="text/javascript">
	<?php if (Pagebuilderck\CKFof::getInput()->get('option') == 'com_pagebuilderck') { ?>
	// override to avoid it to be called;
	if (typeof(Joomla) != 'undefined') {
		// Joomla.submitbutton = function() {
			// alert('ok Joomla.submitbutton');
		// };
	}
	document.formvalidator = function() {}
	document.formvalidator.isValid = function(form) {return true;}
	<?php } ?>

	var ckcontentform;

	$ck(document).ready(function() {
		if (! ckcontentform) {
			ckcontentform = document.getElementById("module-form") 
			|| document.getElementById("modules-form") 
			|| document.getElementById("item-form") 
			|| document.getElementById("adminForm");
		}

		ckcontentform.onsubmit  = function(e) {
			event.preventDefault();
			var task = ckcontentform.task.value;
			if (task == 'page.restore') {
				ckCallRestorePopup();
			} else {
				if ((task.includes('page.save') || task.includes('page.apply')) && ckcontentform.title.value == '') {
					ckcontentform.title.className += ' invalid';
					alert('<?php echo JText::_('CK_TITLE_EMPTY') ?>');
					return;
				}
				if (! task.includes('.cancel')) {
					var workspace = $ck('#workspaceck').length ? $ck('#workspaceck') : $ck('.workspaceck');
					// delete all unwanted interface elements in the final code
					ckCleanInterfaceBeforeSave(workspace);
					// replace the base path to keep images during website migration
					if (PAGEBUILDERCK.URIROOT != '' && PAGEBUILDERCK.URIROOT != '/') {
						var replaceUriroot = new RegExp('src="' + PAGEBUILDERCK.URIROOT, "g");
						workspace.html(workspace.html().replace(replaceUriroot, 'src="|URIROOT|'));
					}

					if ($ck('#htmlcode').length) $ck('#htmlcode').val(workspace.html());

					// check if the function exists, loads it
					if (typeof JoomlaCK.beforesubmitbutton == 'function') { JoomlaCK.beforesubmitbutton(); }
				}

				// send the form
				ckcontentform.submit();
			}
		}
	});</script>