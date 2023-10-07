/**
 * @copyright	Copyright (C) 2017 Cedric KEIFLIN alias ced1870
 * https://www.joomlack.fr
 * @license		GNU/GPL
 * */

/* Call the editor using Javascript. It needs an instance of a sample editor called "ckeditor" to run
 * the ckeditor instance shall be called in PHP using JEditor->display
 */
function ckLoadEditorOnTheFly(id) {
	try {
		var oldid = id;
		var textArea = document.getElementById(id);
		var now = new Date().getTime();
		id = id + parseInt(now, 10);
		textArea.id = id;
		textArea.setAttribute('data-id', id);

		var pluginOptions;
		if (!Joomla.optionsStorage.plg_editor_tinymce) {
			var elements = document.querySelectorAll('.joomla-script-options'),
				str, element, option, counter = 0;

			for (var i = 0, l = elements.length; i < l; i++) {
				element = elements[i];
				str     = element.text || element.textContent;
				str = str.replace(/ckeditor/g, id);
				option  = JSON.parse(str);
				if (option.plg_editor_tinymce) {
					pluginOptions = option.plg_editor_tinymce || {};
				}
			}
		} else {
			pluginOptions = Joomla.optionsStorage.plg_editor_tinymce;
			str = JSON.stringify(pluginOptions);
			str = str.replace(/ckeditor/g, id);
			pluginOptions = JSON.parse(str);
			tinyMCEOptions = pluginOptions.tinyMCE;
//console.log(tinyMCEOptions);
//console.log(id);
//console.log(tinyMCEOptions[id]);
//			tinyMCEOptions[id].joomlaExtButtons.names.forEach(function (btn) {
//				btn.id = btn.id.replace(id, 'ckeditor');
//			});
//console.log(pluginOptions);
		}

		const currentEditor = document.getElementById(id);
		if (! currentEditor.name) currentEditor.setAttribute('name', currentEditor.id);
//		const toggleButton = currentEditor.querySelector('.js-tiny-toggler-button'); // Setup the editor

		Joomla.JoomlaTinyMCE.setupEditor(currentEditor, pluginOptions); // Setup the toggle button

//        if (toggleButton) {
//          toggleButton.removeAttribute('disabled');
//          toggleButton.addEventListener('click', () => {
//            if (Joomla.editors.instances[currentEditor.id].instance.isHidden()) {
//              Joomla.editors.instances[currentEditor.id].instance.show();
//            } else {
//              Joomla.editors.instances[currentEditor.id].instance.hide();
//            }
//          });
//        }
		textArea.id = oldid;
	}
	catch(err) {
		alert(err);
	}
}

/* save the content of the editor into the textarea */
function ckSaveEditorOnTheFly(id) {
	var textArea = document.getElementById(id);
	try {
		var editor = tinymce.get(textArea.getAttribute('data-id'));
		editor.save();
	} 
	catch(err) {
		alert('Error saving one of the editors');
	}
}

/* save the content of the editor into the textarea */
function ckRemoveEditorOnTheFly(id) {
	try {
		tinymce.execCommand('mceRemoveEditor', false, id);
	} 
	catch(err) {
		alert('Error removing one of the editors');
	}
}