/**
 * @copyright	Copyright (C) 2017 Cedric KEIFLIN alias ced1870
 * https://www.joomlack.fr
 * @license		GNU/GPL
 * */

/* Call the editor using Javascript. It needs an instance of a sample editor called "ckeditor" to run
 * the ckeditor instance shall be called in PHP using JEditor->display
 */
function ckLoadEditorOnTheFly(id) {
//	try {
		var oldid = id;
		var textArea = document.getElementById(id);
		var now = new Date().getTime();
		id = id + parseInt(now, 10);
		textArea.id = id;
		textArea.setAttribute('data-id', oldid);

		var editorModals = document.querySelectorAll('.modal[id^="ckeditor_"][id$="_modal"]');
		editorModals.forEach(function(modal) {
			var newModal = modal.cloneNode(true);
			for (var i=0; i<newModal.attributes.length; i++) {
				var name = newModal.attributes[i].name;
				var value = newModal.attributes[i].nodeValue.replace(/ckeditor/g, id);
				newModal.setAttribute(name, value);
				newModal.innerHTML = newModal.innerHTML.replace(/ckeditor/g, id);
				document.body.appendChild(newModal);
				Joomla.initialiseModal(newModal);
			}
		});

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
			pluginOptions.tinyMCE.ckeditor.height = '500px';
			pluginOptions.tinyMCE.ckeditor.width = '100%';
			tinyMCEOptions = pluginOptions.tinyMCE; // utile ?
			str = JSON.stringify(pluginOptions);
			str = str.replace(/ckeditor/g, id);
			pluginOptions = JSON.parse(str);
		}

		const currentEditor = document.getElementById(id);
		if (! currentEditor.name) {
			currentEditor.setAttribute('name', currentEditor.id);
			currentEditor.setAttribute('data-name', currentEditor.name);
		} else {
			currentEditor.setAttribute('data-name', currentEditor.name);
			currentEditor.setAttribute('name', currentEditor.id);
		}
//		const toggleButton = currentEditor.querySelector('.js-tiny-toggler-button'); // Setup the editor

		Joomla.JoomlaTinyMCE.setupEditor(currentEditor, pluginOptions); // Setup the toggle button
}

/* save the content of the editor into the textarea */
function ckSaveEditorOnTheFly(id) {
	var textArea = document.querySelector('[data-id="' + id + '"]');
	if (! textArea)
		textArea = document.querySelector('[id="' + id + '"]');

	try {
		var editor = tinymce.get(textArea.id);
		editor.save();
	} 
	catch(err) {
		alert('Error saving one of the editors');
	}
}

/* save the content of the editor into the textarea */
function ckRemoveEditorOnTheFly(id) {
	try {
		var textArea = document.querySelector('[data-id="' + id + '"]');
		if (! textArea)
			textArea = document.querySelector('[id="' + id + '"]');
		// console.log(textArea);
		if (! textArea) return;

		tinymce.execCommand('mceRemoveEditor', false, textArea.id);

		var editorModals = document.querySelectorAll('.modal[id^="' + textArea.id + '_"][id$="Modal"]');
		// console.log(editorModals);
		for (var i=0; i<editorModals.length; i++) {
			editorModals[i].remove();
		}
	}
	catch(err) {
		alert('Error removing one of the editors');
	}
}