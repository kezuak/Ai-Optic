/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */


var $ck = $ck.noConflict();
var CKUNIQUEIDLIST = new Array();
var workspace;
var accordionckOptions = {
	collapsible: true,
	heightStyle: "content",
	scrollToActive: false
};

$ck(document).ready(function(){
	workspace = ckGetWorkspace();
	ckInitWorkspace();
});

function ckInitDndForImageUpload(workspace) {
	if (!workspace) workspace = ckGetWorkspace();
	$ck('.cktype[data-type="image"], [data-identifier="image"]', workspace).each(function(i, holder) {
		ckAddDndForImageUpload(holder);
	});
}

function ckGetWorkspace() {
	return $ck('.workspaceck');
}

function ckCleanInterfaceBeforeSave(workspace) {
	if (!workspace) workspace = ckGetWorkspace();
	workspace.find('.addcontent').remove();
	workspace.find('.ui-resizable-handle').remove();
	workspace.find('.blockck_width').remove();
	workspace.find('.addrow').remove();
	workspace.find('.editorck').remove();
	workspace.find('.editorckresponsive').remove();
	workspace.find('.ui-sortable').removeClass('ui-sortable');
	workspace.find('.ui-resizable').removeClass('ui-resizable');
	workspace.find('.editfocus').removeClass('editfocus');
	workspace.find('.cssfocus').removeClass('cssfocus');
	workspace.find('.ckfocus').removeClass('ckfocus');
	workspace.find('.animateck').removeClass('animateck');
	workspace.find('.ui-accordion-header').removeClass('ui-accordion-header-active').removeClass('ui-state-active').removeClass('ui-corner-top');
	try {
		workspace.find('.accordionsck').accordionck('destroy');
		workspace.find('.tabsck').tabsck('destroy');
	} catch(error) {
		console.error('PBCK LOG : ' + error);
	}
	workspace.find('> #system-readmore').removeAttr('style');
	workspace.find('.ckcolwidthedition').remove();
	workspace.find('.ckcolwidthediting').removeClass('ckcolwidthediting');
	workspace.find('.mce-content-body').removeClass('mce-content-body');
	workspace.find('.ckinlineeditable').removeClass('ckinlineeditable');
	workspace.find('.ckfakehover').removeClass('ckfakehover');
	workspace.removeClass('pagebuilderck');
	ckShowResponsiveSettings('1');
	ckCheckHtml('1');
	workspace.find('[id^="mce_"]').removeAttr('id');
	workspace.find('input[name^="mce_"]').remove();
	workspace.find('[contenteditable="true"]').removeAttr('contenteditable');
	ckFixBC();
	workspace.find('.ckimagedata').remove();
	workspace.find('.chzn-container').parent().find('select').css('style', '');
	workspace.find('.chzn-container').remove();
	workspace.find('sec').remove();
	workspace.find('.cktype-button-add').remove();
	workspace.find('.blockck-button-add').remove();
	workspace.find('.rowck-button-add').remove();
	ckMergeGooglefontscall();
//	ckBackupStyleTags(workspace);
}

function ckCleanContenttypeInterfaceBeforeSave(workspace) {
	if (!workspace) workspace = ckGetWorkspace();
	workspace.find('.uick-draggable').removeClass('uick-draggable');
	workspace.find('[data-original-title]').removeAttr('data-original-title');
	workspace.find('div.ckcontenttype[style]').removeAttr('style').removeAttr('title');
}

function ckInitWorkspace(workspace) {
	if (!workspace) workspace = ckGetWorkspace();

	ckMessageCloseButtonDisable();
	ckCheckNestedRowsBC();
	$ck(document.body).append('<div id="ck_overlay"></div>');
	$ck(document.body).append('<div id="popup_editionck" class="ckpopup"></div>');
	$ck(document.body).append($ck('#menuck'));
	if ($ck('#workspaceck').length) ckDoActionsList[0]=document.getElementById('workspaceck').innerHTML; // save code for undo and redo
	// ckInitDndForImageUpload();

	if (! workspace.length 
			// iframe for the frontedition
			&& !$ck('#tckeditioniframe').length
			) {
		console.log('PBCK JS MESSAGE : no workspace found in the page. ckInitWorkspace aborted.');
		return;
	}

	console.log('PBCK JS MESSAGE : workspace found in the page. Everything is OK.');

	if (PAGEBUILDERCK.ISCONTENTTYPE != '1') { 
		ckInitInterface(workspace); 
	}
	ckMakeTooltip(workspace);
	ckInitContents(workspace);
	ckRemoveLinkRedirect();
	ckFixBC();
	ckAddDataOnImages();
	if (! $ck('.pagebuilderckparams').length && !workspace.hasClass('ckelementedition')) workspace.prepend('<div class="pagebuilderckparams" />');
	ckSetColorPalettes();
	if (! $ck('.googlefontscall').length && !workspace.hasClass('ckelementedition')) workspace.prepend('<div class="googlefontscall" />');
//	ckRestoreStyleTags(workspace);
	if (PAGEBUILDERCK.ISCONTENTTYPE == '1') { 
		ckInitContentTypes();
	}
	// clean the select list made by Bootstrap
	workspace.find('.chzn-container').parent().find('select.ckcontactfield').css('display', '');
	workspace.find('.chzn-container').remove();
	ckAddFakeLinkEvent();
	ckInlineEditor();
	ckInitSearchAddon();
	ckLoadStylesFromParams();
	ckAddEditionForNestedAddons();
}

function ckInitSearchAddon() {
	$ck('.cksearchleftpanel').on('keyup', function() {
		ckSearchAddon(this);
	});
}

function ckAddFakeLinkEvent() {
	// add event to simulate link hover for preview of styles
	$ck('.pbck-has-link-wrap > .inner').on('mouseover', function() {
		$ck(this).addClass('ckfakehover');
	}).on('mouseleave', function() {
		$ck(this).removeClass('ckfakehover');
	});
}

function ckInitContentTypes(workspace) {
	if (!workspace) workspace = ckGetWorkspace();
	// only edit in normal page, nor in the content type edition page
	if (workspace.hasClass('ckcontenttypeedition')) return;
	workspace.find('.ckcontenttype').each(function() {
		var bloc = $ck(this);
		ckInitContentType(bloc)
	});
}

function ckInitContentType(bloc) {
	var myurl = PAGEBUILDERCK.URIPBCK + "&task=contenttype.ajaxLoadFields&" + PAGEBUILDERCK.TOKEN;
	var type = bloc.attr('data-type');
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			type: type
		}
	}).done(function(code) {
		var result = JSON.parse(code);
		if (result.status == '1') {
			var identifiers = result.identifiers.split('|');
			for (var i=0; i<identifiers.length; i++) {
				var identifier = identifiers[i];
				if (! bloc.find('[data-identifier="' + identifier + '"]').length) {
					console.log('Missing field : ' + identifier);
					ckUpdateContentType(bloc, type, identifier);
				}
			}
			bloc.find('[data-identifier]').each(function() {
				var identifier = $ck(this).attr('data-identifier');
				if (! identifiers.includes(identifier)) {
					$ck(this).hide().attr('data-enabled', '0');
				} else {
					$ck(this).show().attr('data-enabled', '1');
				}
			});
			ckInitDndForImageUpload();
			var returnFunc = 'ckInitContentType' + type;
			if (typeof(window[returnFunc]) == 'function') window[returnFunc](bloc);
		} else {
			alert(Joomla.JText._('CK_FAILED_TO_UPDATE_CONTENTTYPE', 'Failed'));
		}
	}).fail(function() {
		alert(Joomla.JText._('CK_FAILED', 'Failed'));
	});
}

function ckUpdateContentType(bloc, type, identifier) {
	var myurl = PAGEBUILDERCK.URIPBCK + "&task=contenttype.ajaxAddField&" + PAGEBUILDERCK.TOKEN;
	var blocid = bloc.attr('id');
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			type: type,
			identifier: identifier,
			blocid: blocid
		}
	}).done(function(code) {
//		bloc.append(code);
		ckUpdateContentTypeFieldPosition(bloc, type, identifier, code);
	}).fail(function() {
		alert(Joomla.JText._('CK_FAILED', 'Failed'));
	});
}

function ckUpdateContentTypeFieldPosition(bloc, type, identifier, newcode) {
	var myurl = PAGEBUILDERCK.URIPBCK + "&task=contenttype.ajaxGetFieldPosition&" + PAGEBUILDERCK.TOKEN;
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			type: type,
			identifier: identifier
		}
	}).done(function(code) {
		var field = bloc.find('[data-identifier="' + identifier + '"]');
		var result = JSON.parse(code);
		if (result.status == '1') {
			var position = result.position;
			if (position == 'first') {
				bloc.find('> .inner').prepend(newcode);
			} else if (position == 'last') {
				bloc.find('> .inner').append(newcode);
			} else {
				bloc.find('[data-identifier="' + position + '"]').after(newcode);
			}
			var identifierinitfunc = 'ckInit' + ckCapitalize(type) + ckCapitalize(identifier);
			if (typeof(window[identifierinitfunc]) == 'function') window[identifierinitfunc](bloc);
		} else {
			alert(Joomla.JText._('CK_FAILED_TO_UPDATE_CONTENTTYPE', 'Failed'));
		}
	}).fail(function() {
		alert(Joomla.JText._('CK_FAILED', 'Failed'));
	});
}

function ckSetColorPalettes() {
	var params = $ck('.pagebuilderckparams');
	params.attr('data-colorpalettefromtemplate', PAGEBUILDERCK.COLORSFROMTEMPLATE);
	params.attr('data-colorpalettefromsettings', PAGEBUILDERCK.COLORSFROMSETTINGS);
}

function ckBackupStyleTags(workspace) {
	workspace.find('style').each(function() {
		var $s = $ck(this);
		var styles = this.innerHTML;
//		styles = escapeHtml(styles);
		var $sClass = $s.attr('class') ? $s.attr('class') : '';
		$s.after('<div class="ckstylebackup ' + $sClass + '" style="display:none;">' + styles + '</div>');
		$s.remove();
	});
}

function ckRestoreStyleTags(workspace) {
	workspace.find('.ckstylebackup').each(function() {
		var $s = $ck(this).removeClass('ckstylebackup');
		var styles = this.innerHTML;
//		styles = unescapeHtml(styles);
		var $sClass = $s.attr('class') ? $s.attr('class') : '';
		$s.after('<style class="' + $sClass + '" style="display:none;">' + styles + '</style>');
		$s.remove();
	});
}

function escapeHtml(text) {
	var map = {
	  '&': '|amp|',
	  '<': '|lt|',
	  '>': '|gt|',
	  '"': '|quot|',
	  "'": '|039|'
	};

	return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}

function unescapeHtml(text) {

	return text
		.replace(/\|amp\|/g, "&amp;")
		.replace(/\|lt\|/g, "&lt;")
		.replace(/\|gt\|/g, '>')
		.replace(/\|quot\|/g, "&quot;")
		.replace(/\|039\|/g, "&#039;");
}

function ckRemoveLinkRedirect() {
	// stop links redirection whithin the interface
	$ck('.workspaceck').find('a[href]').click(function(ev){ev.preventDefault();return false;})
}
/**
* Insert image from com_media
*/
function jInsertFieldValue(value, id) {
	$ck('#'+id).val(value);
	$ck('#'+id).trigger('change');
}

/**
 * Override the options if the nested rows has already been used before the option implementation
 */
function ckCheckNestedRowsBC() {
	if ($ck('.rowck .rowck').length > 0) {
		PAGEBUILDERCK.NESTEDROWS = '1';
	}
}

/**
* Backward Compatibility : Update the elements to keep the behavior though the versions
*/
function ckFixBC() {
	// for V2.0.3
	// add automatic stack alignement to columns in small resolution
	$ck('.rowck:not([class*="ckstack"])').each(function() {
		$row = $ck(this);
		if (! $row.hasClass('ckhide')) {
			// if no block width from boostrap, then remove useless css class
			if ($ck('.blockck', $row).length && ! $ck('.blockck[class*="span"]', $row).length) {
				$row.removeClass('row-fluid');
			}
			ckFixBCRow($row);
		}
		// fix B/C for old responsive css classes
		$row.find('.ckhidedesktop').addClass('ckhide5').removeClass('ckhidedesktop');
		$row.find('.ckhidephone').addClass('ckhide4').removeClass('ckhidephone');
	});
}

function ckFixBCRow(row) {
	if (! row.length) return;
//	row.removeClass('row-fluid');
	if (! row.attr('class').match(/ckstack/g) && ! row.attr('class').match(/ckhide/g) && row.hasClass('row-fluid')) {
		row.addClass('ckstack1').addClass('ckstack2').addClass('ckstack3');
	}
}

function ckInitContents(workspace) {
	if (!workspace) workspace = ckGetWorkspace();
	var activeTab = {active: parseInt($ck(this).attr('activetab'))};
	var options = Object.assign(accordionckOptions, activeTab);

	workspace.find('.accordionsck').each(function() {
		$ck(this).accordionck(options);
	});
	workspace.find('.tabsck').each(function() {
		$ck(this).tabsck({
			active: parseInt($ck(this).attr('activetab'))
		});
	});
	if (PAGEBUILDERCK.IMAGE_PATH_FIX == '1') {
		workspace.find('img').each(function() {
			$this = $ck(this);
			var dataSrc = $this.attr('data-src');
			if (dataSrc) {
				$this.attr('src', PAGEBUILDERCK.URIROOT + '/' + ckTrimLeft($this.attr('data-src'), '/'));
			}
		});
	}
}

function ckInitInterface(workspace) {
	if (!workspace) workspace = ckGetWorkspace();

	// add animate class to make all items visible
	workspace.find('.rowck, .blockck').addClass('animateck');

	// init the wrappers
	workspace.find('> .wrapperck').each(function(i, wrapper) {
		wrapper = $ck(wrapper);
		if (! wrapper.find('> .ckstyle').length) { // for beta version retrocompatibility
			wrapper.prepend('<div class="ckstyle"></div>');
		}
		ckAddWrapperEdition(wrapper);
//		ckMakeRowSortableInWrapper(wrapper);
	});
	// init the rows in the wrappers
//	$ck('.wrapperck', workspace).each(function() {
//		ckMakeRowSortableInWrapper($ck(this));
//	});

	ckMakeRowsSortable(workspace);
	workspace.find('.rowck').each(function(i, row) {
		row = $ck(row);
		// check user rights
		var acl = row.attr('data-acl-edit') ? row.attr('data-acl-edit') : '';
		if (! ckCheckUserRightsFromAcl(acl))
			return true;

		if (! row.find('> .ckstyle').length) { // for beta version retrocompatibility
			row.prepend('<div class="ckstyle"></div>');
		}
		ckAddRowEditionEvents(row);
		ckMakeBlocksSortable(row);
		row.find('> .inner > .blockck').each(function() {
			block = $ck(this);
			ckAddBlockEditionEvents(block);
			ckMakeItemsSortable(block);
			block.find('> .inner > .innercontent > .cktype').each(function() {
				// item = $ck(this);
				ckAddItemEditionEvents($ck(this));
			});
		});
		// 2.18.3 add item edition events for all items, allow nested things
		workspace.find('.innercontent > .cktype').each(function() {
				// item = $ck(this);
				ckAddItemEditionEvents($ck(this));
		});

		ckInitDndForImageUpload(row);
		ckInlineEditor(row);
	});

	workspace.find('> #system-readmore').each(function() {
		block = $ck(this);
		ckAddBlockEditionEvents(block, 'readmore');
	});

	if (! workspace.find('.rowck').length && !workspace.hasClass('ckelementedition')) {
		ckAddRow(false, workspace);
	}
	// for my elements edition only
	if (workspace.hasClass('ckelementedition')) {
		workspace.find('.cktype').each(function() {
			ckAddItemEditionEvents($ck(this));
		});
	}

	// make the menu items draggable
	ckMakeItemsDraggable();

//	var connectToSortable = PAGEBUILDERCK.NESTEDROWS === '1' ? ".workspaceck, .wrapperck > .inner, .innercontent" : ".workspaceck";
	// make the menu items draggable
	ckMakeRowsDraggable();
	$ck('.menuitemck[data-type="rowinrow"]').draggable({
		connectToSortable: ".innercontent",
		helper: "clone",
		// appendTo: ".workspaceck",
		forcePlaceholderSize: true,
		zIndex: "999999",
		tolerance: "pointer",
		start: function( event, ui ){
			$ck('#menuck').css('overflow', 'visible');
			$ck('.workspaceck .rowck').css('margin-top', '10px').css('margin-bottom', '10px').addClass('ckfocus');
		},
		stop: function( event, ui ){
			$ck('#menuck').css('overflow', '');
			$ck('.workspaceck .rowck').css('margin-top', '').css('margin-bottom', '').removeClass('ckfocus');;
		}
	});
	$ck('.menuitemck[data-type="readmore"], .menuitemck[data-group="layout"]:not([data-type*="row"])').draggable({
		connectToSortable: ".workspaceck",
		helper: "clone",
		// appendTo: ".workspaceck",
		forcePlaceholderSize: true,
		zIndex: "999999",
		tolerance: "pointer",
		start: function( event, ui ){
			$ck('#menuck').css('overflow', 'visible');
		},
		stop: function( event, ui ){
			$ck('#menuck').css('overflow', '');
		}
	});
	ckConnectRowWithWorkspace();
	$ck('.menuitemck[data-type="readmore"]').on('mousedown', function() {
		$ck('.workspaceck').sortable( "option", "connectWith", "" );
	});
}

function ckMakeRowsDraggable() {
	$ck('.menuitemck[data-type="row"]').draggable({
		connectToSortable: ".workspaceck",
		// iframeFix: true,
//		connectToSortable: connectToSortable,
		helper: "clone",
		// appendTo: ".workspaceck",
		forcePlaceholderSize: true,
		zIndex: "999999",
		tolerance: "pointer",
		start: function( event, ui ){
			$ck('#menuck').css('overflow', 'visible');
			$ck('.workspaceck .rowck').css('margin-top', '10px').css('margin-bottom', '10px').addClass('ckfocus');
		},
		stop: function( event, ui ){
			$ck('#menuck').css('overflow', '');
			$ck('.workspaceck .rowck').css('margin-top', '').css('margin-bottom', '').removeClass('ckfocus');;
		}
	});
}

function ckConnectRowWithWorkspace() {
	if (PAGEBUILDERCK.NESTEDROWS === '1') {
		// fix to make the rows connected to the existing wrappers
		$ck('.rowck > .editorck .controlMove').on('mousedown', function() {
//			$ck('.workspaceck').sortable( "option", "connectWith", ".innercontent" );
		});
	}
}
/*
function ckMakeRowSortableInWrapper(wrapper) {
	wrapper.find('> .inner').sortable({
		items: ".rowck",
		helper: "clone",
		handle: "> .editorck > .ckfields  > .controlMove",
		forcePlaceholderSize: true,
		// forceHelperSize: true,
//		axis: "y",
		tolerance: "pointer",
		placeholder: "placeholderck",
		connectWith: ".wrapperck > .inner, .workspaceck, .innercontent",
//		zIndex: 9999,
		activate: function (event, ui) {
			if (ui != undefined && !$ck(ui.item).hasClass('menuitemck')) {
				$ck(ui.helper).css('width', '250px').css('height', '100px').css('overflow', 'hidden');
			}
		},
		out: function (event, ui) {
			if ($ck(this).data().uickSortable.currentContainer){
				var receiver = $ck(this).data().uickSortable.currentContainer.bindings;
				receiver.parent().removeClass('ckfocus');
			}
		},
		start: function (event, ui) {
			if ($ck(this).data().uickSortable.currentContainer){
				var receiver = $ck(this).data().uickSortable.currentContainer.bindings;
				receiver.parent().addClass('ckfocus');
			}
		},
		stop: function( event, ui ){
			if ($ck(this).data().uickSortable.currentContainer){
				var receiver = $ck(this).data().uickSortable.currentContainer.bindings;
				receiver.parent().removeClass('ckfocus');
			}
			if (ui != undefined) {
					$ck(ui.item).css('width', '').css('height', '').css('overflow', '');
			}
			if (! $ck(ui.item).hasClass('menuitemck')) {
					ckSaveAction('ckMakeRowsSortable'); // only save action if not from left menu
			}
		},
		receive: function( event, ui ) {
			// need to init the connectWith option to avoid wrappers in wrappers
			$ck('.workspaceck').sortable( "option", "connectWith", "" );

			if (ui.sender.hasClass('menuitemck') && ui.sender.hasClass('ckmyelement')) {
					var newblock = $ck(this).find('.menuitemck');
					newblock.css('float', 'none').empty().addClass('ckwait');
					ckAddElementItem(ui.sender.attr('data-type'), newblock);
			} else if (ui.sender.hasClass('menuitemck')) {
					var newblock = $ck(this).find('.menuitemck');
					newblock.css('float', 'none').empty().addClass('ckwait');
					ckAddItem(ui.sender.attr('data-type'), newblock)
			} else {

			}
		}
	});
}
*/

function ckMakeItemsDraggable() {
	// make the menu items draggable
	$ck('.menuitemck:not([data-type="row"]):not([data-type="wrapper"])').draggable({
		connectToSortable: ".blockck .innercontent",
		helper: "clone",
//		appendTo: "body",
		forcePlaceholderSize: true,
		zIndex: "999999",
		tolerance: "pointer",
		start: function( event, ui ){
			$ck('#menuck').css('overflow', 'visible');
		},
		stop: function( event, ui ){
			$ck('#menuck').css('overflow', '');
		}
	});
}

function ckInitOptionsTabs() {
	$ck('#popup_editionck div.tab:not(.current)').hide();
	$ck('#popup_editionck .menulink').each(function(i, tab) {
		tab = $ck(tab);
		tab.click(function() {
			if (!$ck(this).hasClass('open') && !$ck(this).hasClass('current')) {
				// $ck(this).removeClass('current');
				// $ck('#' + tab.attr('tab')).removeClass('current');
				$ck(this).addClass('open');
				$ck('#popup_editionck .tab.tab_fullscreen').fadeOut('fast');
				$ck('#' + tab.attr('tab')).slideDown('fast');
			} else {
				// $ck(this).removeClass('current');
				$ck('#' + tab.attr('tab')).slideUp('fast');
				$ck(this).removeClass('open');
			}
			$ck(this).removeClass('current');
			$ck('#' + tab.attr('tab')).removeClass('current');
		});
	});
}

function ckInitColorPickers(container) {
	if (! container) container = $ck(document.body);
	var startcolor = '';
	$ck('.colorPicker', container).each(function(i, picker) {
		picker = $ck(picker);
		picker.mousedown(function() {
			if (picker.val()) {
				startcolor = picker.val().replace('#','');
			} else {
				startcolor = 'fff000';
			}
			new ColpickCK(picker, {
//			picker.colpick({
				layout:'full',
				color: startcolor,
				livePreview: true,
				onChange:function(hsb,hex,rgb,el,bySetColor,rgba) {
					if (rgba && rgba !== 0) {
						picker.val('rgba(' + rgba + ')').css('background-color','rgba(' + rgba + ')');
					} else if (picker.val()
						&& picker.val().indexOf("r") == -1
						&& picker.val().indexOf("v") == -1
						&& picker.val().indexOf("t") == -1) {
						$ck(el).css('background-color','#'+hex);
						setpickercolor(picker);
						// force the # character
						if (picker.val().indexOf("#") == -1) picker.val('#'+picker.val());
					} else {
						$ck(el).css('background-color','');
					}
					// Fill the text box just if the color was set using the picker, and not the colpickSetColor function.
					if (picker.val().indexOf("r") == -1
						&& picker.val().indexOf("t") == -1) {
						if(!bySetColor) $ck(el).val('#' + hex);
					}
				},
				onSubmit:function(hsb,hex,rgb,el,bySetColor) {
//					picker.trigger('blur');console.log('chang');
				},
				onClean: function(button, cal) {
					picker.val('');
					picker.css('background', 'none');
//					picker.trigger('blur');console.log('onClean');
				},
				onCopy: function(color, cal) {
					CLIPBOARDCOLORCK = picker.val();
				},
				onPaste: function(color, cal) {
					picker.val(CLIPBOARDCOLORCK);
					picker.css('background', CLIPBOARDCOLORCK);
//					picker.trigger('blur');console.log('onPaste');
					setpickercolor(picker);
				},
				onPaletteColor: function(hsb,hex,rgb,el,bySetColor) {
					picker.val('#'+hex);
					picker.css('background','#'+hex);
//					picker.trigger('blur');console.log('onPaletteColor');
					setpickercolor(picker);
				},
			});
		}).keyup(function(){
			$ck(this).colpickSetColor(this.value);
//				picker.trigger('blur');console.log('keyup');
		});
	});
}

/**
 * Method to give a black or white color to have a good contrast
 */
function setpickercolor(picker) {
	pickercolor =
			0.213 * hexToR(picker.val()) / 100 +
			0.715 * hexToG(picker.val()) / 100 +
			0.072 * hexToB(picker.val()) / 100
			< 1.5 ? '#FFF' : '#000';
	picker.css('color', pickercolor);
	return pickercolor;
}

/*
 * Functions to manage colors conversion
 *
 */
function hexToR(h) {
	return parseInt((cutHex(h)).substring(0, 2), 16)
}
function hexToG(h) {
	return parseInt((cutHex(h)).substring(2, 4), 16)
}
function hexToB(h) {
	return parseInt((cutHex(h)).substring(4, 6), 16)
}
function cutHex(h) {
	return (h.charAt(0) == "#") ? h.substring(1, 7) : h
}
function hexToRGB(h) {
	return 'rgb(' + hexToR(h) + ',' + hexToG(h) + ',' + hexToB(h) + ')';
}

function ckInitAccordions() {
	$ck('.menustylesblockaccordion').hide();
	$ck('.ckproperty').each(function(i, tab) {
		tab = $ck(tab);
		// $ck('.menustylesblockaccordion', tab).first().show();
		// $ck('.menustylesblocktitle', tab).first().addClass('open');
		$ck('.menustylesblocktitle', tab).click(function() {
			if (!$ck(this).hasClass('open')) {
				$ck('.menustylesblockaccordion', tab).slideUp('fast');
				blocstyle = $ck(this).next('.menustylesblockaccordion');
				$ck('.menustylesblocktitle', tab).removeClass('open');
				$ck(this).addClass('open');
				blocstyle.slideDown('fast');
			} else {
				blocstyle = $ck(this).next('.menustylesblockaccordion');
				blocstyle.slideUp('fast');
				$ck(this).removeClass('open');
			}
		});
	});
}

function ckInitMenustylesAccordion(tab) {
	$ck('.menustylesblockaccordion', tab).first().show();
	$ck('.menustylesblocktitle', tab).first().addClass('open');
	$ck('.menustylesblocktitle', tab).click(function() {
		if (!$ck(this).hasClass('open')) {
			$ck('.menustylesblockaccordion', tab).slideUp('fast');
			blocstyle = $ck(this).next('.menustylesblockaccordion');
			$ck('.menustylesblocktitle', tab).removeClass('open');
			$ck(this).addClass('open');
			blocstyle.slideDown('fast');
		} else {
			blocstyle = $ck(this).next('.menustylesblockaccordion');
			blocstyle.slideUp('fast');
			$ck(this).removeClass('open');
		}
	});
}

function ckAddRowEditionEvents(row) {
		ckAddRowEdition(row);
}

function ckAddBlockEditionEvents(block, type) {
	if (!type) type = '';
	block.mouseenter(function() {
		var i = block.parents('.rowck').length;
		ckAddEdition(this, i, type);
	}).mouseleave(function() {
		var t = setTimeout( function() {
			block.removeClass('highlight_delete');
			ckRemoveEdition(block);
		}, 200);
		
	});
}

function ckAddItemEditionEvents(el) {
	el.mouseenter(function() {
		ckAddEdition(this);
	}).mouseleave(function() {
		$ck(this).removeClass('highlight_delete');
		ckRemoveEdition(this);
	});
}

function ckHtmlRow(newrowid) {
	var row = 
	$ck('<div class="rowck ckstack3 ckstack2 ckstack1" id="'+ckGetUniqueId('row_')+'">'
			+'<div class="inner animate clearfix"></div>'
			+'<div class="ckstyle"></div>'
	+'</div>');
	return row;
}

function ckAddRow(cur_row, workspace) {
	if (!workspace) workspace = ckGetWorkspace();
	var newrowid = ckGetUniqueId('row_');
	var row = ckHtmlRow(newrowid);
	// $ck('.workspaceck .addrow').before(row);
	if (cur_row == false) {
		workspace.append(row);
	} else {
		cur_row.after(row);
	}
	ckAddBlock(row);
	ckAddRowEditionEvents(row);
	ckMakeBlocksSortable(row);
	ckMakeTooltip(row);
	ckConnectRowWithWorkspace();
	return row;
}

function ckAddRowFromButton(btn) {
	var cur_row = $ck($ck(btn).parents('.rowck')[0]);
	ckAddRow(cur_row);
}

function ckAddWrapper(cur_row, workspace) {
	if (!workspace) workspace = ckGetWorkspace();
	var wrapper = 
	$ck('<div class="wrapperck" id="'+ckGetUniqueId('wrapper_')+'">'
			+'<div class="inner animate clearfix"></div>'
			+'<div class="ckstyle"></div>'
	+'</div>');
	
	// $ck('.workspaceck .addrow').before(row);
	if (cur_row == false) {
		workspace.append(wrapper);
	} else {
		cur_row.after(wrapper);
	}
//	ckAddBlock(row);
	ckAddWrapperEdition(wrapper);
//	ckMakeRowSortableInWrapper(wrapper);
	ckMakeTooltip(wrapper);
}

function ckHtmlBlock(newblockid) {
	var newblock = 
	$ck('<div class="blockck" id="'+newblockid+'">'
		+ '<div class="ckstyle"></div>'
		+ '<div class="inner animate resizable">'
			+ '<div class="innercontent">'
				// + response
			+ '</div>'
		+ '</div>'
	+ '</div>');
	return newblock;
}

function ckAddBlock(row, aftercolumn) {
	if (!aftercolumn) aftercolumn = false;

	var newblockid = ckGetUniqueId('block_');
	newblock = ckHtmlBlock(newblockid);
	if (aftercolumn) {
		$ck(aftercolumn).after(newblock);
	} else {
		$ck('> .inner', row).append(newblock);
	}
	ckAddBlockEditionEvents(newblock);
	ckInitBlocksSize(row);
	ckMakeItemsSortable(newblock);
	ckMakeTooltip(newblock);
	ckAddColumnsSuggestions();
	return newblockid;
}

function ckAddItem(type, currentbloc) {
	ckHideContentList();
	var myurl = PAGEBUILDERCK.URIPBCK + "&view=content";
	var id = ckGetUniqueId();
	$ck.ajax({
	type: "POST",
	url: myurl,
	// dataType : 'json',
	data: {
		cktype: type,
		ckid: id
		}
	}).done(function(result) {
		if (type == 'row' || type == 'rowinrow') {
			ckAddRow(currentbloc);
			$ck(currentbloc).remove();
			ckSaveAction();
		} else if (type == 'wrapper') {
			ckAddWrapper(currentbloc);
			$ck(currentbloc).remove();
			ckSaveAction();
		} else {
			el = $ck(result);
			if (currentbloc) {
				$ck(currentbloc).fadeOut(200, function() {
					if ($ck(currentbloc).hasClass('blockck')) {
						$ck(currentbloc).find('> .inner > .innercontent').append(el);
						$ck(currentbloc).show();
						$ck(currentbloc).find('.cktype-button-add').remove();
					} else if ($ck(currentbloc).hasClass('cktype')) {
						$ck(currentbloc).after(el);
						$ck(currentbloc).show();
						$ck(currentbloc).find('.cktype-button-add').remove();
					} else if ($ck(currentbloc).hasClass('innercontent')) {
						$ck(currentbloc).append(el);
						$ck(currentbloc).show();
						$ck(currentbloc).find('.cktype-button-add').remove();
					} else {
						$ck(currentbloc).before(el);
						$ck(currentbloc).remove();
					}
	//				el.trigger('show');
					if (el.attr('onshow')) {
						$ck(document.body).append('<script id="cktempscript">function cktempscript() {' + ckGetOnshowFunc(el) + '}</script>');
						cktempscript();
						$ck('#cktempscript').remove();
					}
					ckSaveAction();
					ckInlineEditor();
				});
			} else {
				$ck('.ckfocus').append(el).removeClass('ckfocus');
				ckSaveAction();
			}
			if (PAGEBUILDERCK.ISCONTENTTYPE !== '1') {
				ckAddItemEditionEvents(el);
				ckMakeItemsSortable($ck($ck('#'+id).parents('.blockck')[0]));
			}
			ckTriggerAfterAdditem(id);
			if (el.hasClass('ckcontenttype')) ckInitContentType(el);
			ckInlineEditor();
		}
	}).fail(function() {
		alert('A problem occured when trying to load the content. Please retry.');
		$ck(currentbloc).remove();
	});
}

function ckGetOnshowFunc(el) {
	var func = el.attr('onshow').replace('$ck(this)', '$ck("#' + el.attr('id') + '")');
	func = func.replace('jQuery(this)', '$ck("#' + el.attr('id') + '")');

	return func;
}

function ckAddRowItem(type, currentbloc) {
	ckHideContentList();
	// var block_inner = $ck('.ckfocus');
	var myurl = PAGEBUILDERCK.URIPBCK + "&view=content";
	var id = ckGetUniqueId();
	$ck.ajax({
	type: "POST",
	url: myurl,
	// dataType : 'json',
	data: {
		cktype: type,
		ckid: id
		}
	}).done(function(result) {
		if (type == 'row') {
			ckAddRow(currentbloc);
			$ck(currentbloc).remove();
			ckSaveAction();
		} else if (type == 'wrapper') {
			ckAddWrapper(currentbloc);
			$ck(currentbloc).remove();
			ckSaveAction();
		} else {
			item = $ck(result);
			if (currentbloc) {
				$ck(currentbloc).fadeOut(500, function() {
					$ck(currentbloc).before(item);
					$ck(currentbloc).remove();
//					item.trigger('show');
					if (item.attr('onshow')) {
					$ck(document.body).append('<script id="cktempscript">function cktempscript() {' + ckGetOnshowFunc(item) + '}</script>');
					cktempscript();
					$ck('#cktempscript').remove();
				}
					ckSaveAction();
				});
			} else {
				$ck('.ckfocus').append(item).removeClass('ckfocus');
				ckSaveAction();
			}
			item.mouseenter(function() {
				ckAddEdition(this, 0 , type);
			}).mouseleave(function() {
				$ck(this).removeClass('highlight_delete');
				ckRemoveEdition(this);
			});
			ckTriggerAfterAdditem(id);
		}
	}).fail(function() {
		alert('A problem occured when trying to load the content. Please retry.');
		$ck(currentbloc).remove();
	});
}

function ckAddContentTypeItem(type, currentbloc) {
	$ck(currentbloc).removeClass('menuitemck').addClass('cktype');
	var id = ckGetUniqueId();
	$ck(currentbloc).attr('id', id);
	$ck(currentbloc).find('> div').addClass('ckcontenttype-infos');
	var group = $ck(currentbloc).attr('data-group');
	$ck(currentbloc).append('<div class="ckstyle"></div><div class="' + group + 'ck-group inner"><div class="' + group + 'ck-label">[label]</div><div class="' + group + 'ck-field">[value]</div></div>')
	ckMakeContentTypeSortable();
	
	ckAddItemEditionEvents(currentbloc);
//		ckMakeItemsSortable($ck($ck('#'+id).parents('.blockck')[0]));
//		ckTriggerAfterAdditem(id);
}

function ckMakeContentTypeSortable() {
	$ck('.innercontent').sortable( "option", "items", ".ckcontenttype" );
//	$ck('.innercontent').sortable( "option", "handle", "" );
}

function ckMergeGooglefontscall() {
	var workspace = ckGetWorkspace();
	if (!workspace.hasClass('ckelementedition')) {
		$ck('.googlefontscall').remove();
		workspace.prepend('<div class="googlefontscall"></div>');
	}
	var gfontnames = new Array();
	workspace.find('.rowck, .blockck, .cktype').each(function() {
		var bloc = $ck(this);
		$ck('> .ckprops', bloc).each(function(i, ckprops) {
			ckprops = $ck(ckprops);
			fieldslist = ckprops.attr('fieldslist') ? ckprops.attr('fieldslist').split(',') : Array();
			for (j=0;j<fieldslist.length;j++) {
				fieldname = fieldslist[j];
				cssvalue = ckprops.attr(fieldname);
				field = $ck('#' + fieldname);
				if (fieldname.indexOf('googlefont') > -1) {
					fontname = ckCapitalize(cssvalue).trim("'");
					if (gfontnames.indexOf(fontname) == -1) gfontnames.push(fontname);
				}
			}
		});
	});
	for (var i=0;i<gfontnames.length;i++) {
		fonturl = "https://fonts.googleapis.com/css?family="+gfontnames[i].replace(' ', '+');
		ckAddGooglefontStylesheet(fonturl);
	}
}

function ckAddElementItem(type, currentbloc) {
	ckHideContentList();
	var myurl = PAGEBUILDERCK.URIPBCK + '&task=ajaxAddElementItem&' + PAGEBUILDERCK.TOKEN;
	var id = ckGetUniqueId();
	$ck.ajax({
	type: "POST",
	url: myurl,
	// dataType : 'json',
	data: {
		id: currentbloc.attr('data-id')
		}
	}).done(function(result) {
		if (type == 'row' || type == 'wrapper' || type == 'rowinrow') {
			el = $ck(result);
			if (currentbloc) {
				// $ck(currentbloc).fadeOut(500, function() {
					$ck(currentbloc).before(el);
					$ck(currentbloc).remove();

					if (el.attr('onshow')) {
						$ck(document.body).append('<script id="cktempscript">function cktempscript() {' + ckGetOnshowFunc(el) + '}</script>');
						cktempscript();
						$ck('#cktempscript').remove();
					}
					// ckSaveAction();
				// });
			} else {
				$ck('.ckfocus').append(el).removeClass('ckfocus');
				// ckSaveAction();
			}

			ckMergeGooglefontscall();
			if (type == 'wrapper') {
				var wrappercopyid = ckGetUniqueId('wrapper_');
				var elcopy = el;
				elcopy.removeClass('editfocus');
				elcopy.find('> .editorck').remove();
				ckReplaceId(elcopy, wrappercopyid);
//				ckMakeRowSortableInWrapper(elcopy);
				ckAddWrapperEdition(elcopy);

				elcopy.find('.rowck').each(function() {
					$row = $ck(this);
					$row.removeClass('editfocus');
					$row.find('> .editorck').remove();
					var copyid = ckGetUniqueId('row_');
					// copy the styles
					ckReplaceId($row, copyid);
					ckMakeBlocksSortable($row);
					ckAddRowEditionEvents($row);
				});
			} else {
				// manage the new ids
				var rowcopyid = ckGetUniqueId('row_');
				var elcopy = el;
				elcopy.removeClass('editfocus');
				elcopy.find('> .editorck').remove();
				ckReplaceId(elcopy, rowcopyid);

				ckAddRowEditionEvents(elcopy);
				ckMakeBlocksSortable(elcopy);
				
			}

			// for tiny inline editing
			if (elcopy.find('[id^="mce_"]').length) {
				elcopy.find('[id^="mce_"]').removeAttr('id');
			}

			elcopy.find('.blockck, .cktype').each(function() {
				$this = $ck(this);
				$this.removeClass('editfocus');
				// init the effect if needed
				if ($this.hasClass('cktype') && $this.find('.tabsck').length) {
					$this.find('.tabsck').tabsck();
				}
				if ($this.hasClass('cktype') && $this.find('.accordionsck').length) {
					$this.find('.accordionsck').accordionck();
				}
				
				var prefix = '';
				if ($this.hasClass('blockck')) {
					prefix = 'block_';
					ckMakeItemsSortable(elcopy);
					ckAddBlockEditionEvents($this);
				} else {
					ckAddItemEditionEvents($this);
				}

				// add dnd for image
				if ($this.attr('data-type') == 'image') ckAddDndForImageUpload($this[0]);

				var copyid = ckGetUniqueId(prefix);
				// copy the styles
				ckReplaceId($this, copyid);
			});
			
			ckMakeTooltip(elcopy);
			ckTriggerAfterAdditem(id);
			ckSaveAction();
			ckInlineEditor();
			
		} else {
			el = $ck(result);
			if (currentbloc) {
				$ck(currentbloc).fadeOut(500, function() {
					$ck(currentbloc).before(el);
					$ck(currentbloc).remove();
	//				el.trigger('show');
					if (el.attr('onshow')) {
						$ck(document.body).append('<script id="cktempscript">function cktempscript() {' + ckGetOnshowFunc(el) + '}</script>');
						cktempscript();
						$ck('#cktempscript').remove();
					}
					ckSaveAction();
					ckInlineEditor();
				});
			} else {
				$ck('.ckfocus').append(el).removeClass('ckfocus');
				ckSaveAction();
			}
			ckMergeGooglefontscall();
			
			var copy = el;
			copyid = ckGetUniqueId();
			// copy the styles
			ckReplaceId(copy, copyid);
			// copy.attr('id', copyid);

			copy.removeClass('editfocus');
			ckAddItemEditionEvents(copy);

			// init the effect if needed
			if (copy.find('.tabsck').length) {
				copy.find('.tabsck').tabsck();
			}
			if (copy.find('.accordionsck').length) {
				copy.find('.accordionsck').accordionck();
			}
			// for tiny inline editing
			if (copy.find('[id^="mce_"]').length) {
				copy.find('[id^="mce_"]').removeAttr('id');
			}

			// add dnd for image
			if (copy.attr('data-type') == 'image') ckAddDndForImageUpload(copy[0]);

			// copy the styles
			
			// var re = new RegExp(blocid, 'g');
			// copy.find('.ckstyle').html(bloc.find('.ckstyle').html().replace(re,copyid));
			ckSaveAction();
			ckInlineEditor();
			
			
			
			
			
			// ckAddItemEditionEvents(el);
			// ckMakeItemsSortable($ck($ck('#'+id).parents('.blockck')[0]));
			ckTriggerAfterAdditem(id);
		}
	}).fail(function() {
		alert('A problem occured when trying to load the content. Please retry.');
		$ck(currentbloc).remove();
	});
}

// empty function to override in each layout if needed
if (typeof(window['ckTriggerAfterAdditem']) !== 'function') {
	function ckTriggerAfterAdditem(id) {
		return;
	}
}

function ckMakeRowsSortable(workspace) {
	if (!workspace) workspace = ckGetWorkspace();
//	var items = PAGEBUILDERCK.NESTEDROWS === '1' ? ".rowck, > #system-readmore, > .wrapperck" : "> .rowck, > #system-readmore";
	workspace.sortable({
		items: "> .rowck, > #system-readmore",
		helper: "clone",
//		axis: "y",
		handle: "> .editorck > .ckfields  > .controlMove",
		connectWith: ".workspaceck",
		forcePlaceholderSize: true,
//		forceHelperSize: true,
		tolerance: "pointer",
		placeholder: "placeholderck",
		// zIndex: 9999,
		activate: function (event, ui) {
			$ck(this).sortable("refreshPositions");
			if (ui != undefined && !$ck(ui.item).hasClass('menuitemck') && !$ck(ui.item).hasClass('ckcontenttype') && !$ck(ui.item).hasClass('ckpageitem')) {
				$ck(ui.helper).css('width', '250px').css('height', '100px').css('overflow', 'hidden');
			}
		},
		over: function( event, ui ) {

		},
		start: function( event, ui ) {

		},
		receive: function( event, ui ) {

			if (ui.sender.hasClass('menuitemck') && ui.sender.attr('data-type') == 'readmore') {
				if (workspace.find('#system-readmore').length) {
					alert('There is already a Readmore in your content. You can only have one readmore.');
					return false;
				}
			}
			if (ui.sender.hasClass('menuitemck') && ui.sender.hasClass('ckmyelement')) {
				var newblock = $ck(this).find('.menuitemck');
				newblock.css('float', 'none').empty().addClass('ckwait');
				ckAddElementItem(ui.sender.attr('data-type'), newblock);
			} else if (ui.sender.hasClass('menuitemck')) {
				var newblock = $ck(this).find('.menuitemck');
				newblock.css('float', 'none').empty().addClass('ckwait');
				ckAddRowItem(ui.sender.attr('data-type'), newblock);
			} else if (ui.sender.hasClass('ckgalleryitem')) {
				var newblock = $ck(this).find('.ckgalleryitem');
				var name = ui.sender.attr('data-name');
				var cat = ui.sender.attr('data-category');
				ckLoadPageFromMediaLibrary(cat + '/' + name, newblock)
			} else if (ui.sender.hasClass('ckpageitem')) {
				var newblock = $ck(this).find('.ckpageitem');
				var id = ui.sender.attr('data-id');
				ckLoadPageFromPagebuilder(id, newblock)
			}
		}
	});
//	workspace.sortable( "option", "axis", "y" );
}

function ckMakeBlocksSortable(row) {
	row.sortable({
		items: ".blockck",
		helper: "clone",
		// axis: "x",
		handle: "> .editorck > .ckfields  > .controlMove",
		forcePlaceholderSize: true,
		// forceHelperSize: true,
		tolerance: "pointer",
		placeholder: "placeholderchild",
//		zIndex: 9999,
		activate: function (event, ui) {
			$ck(this).sortable("refreshPositions");
		},
		sort: function( event, ui ) {
			ui.helper.find('.editorck').hide();
		},
		start: function( event, ui ){
			ui.placeholder.width(parseInt($ck('> .inner',ui.helper).width()));
			ui.placeholder.append('<div class="inner" />')
		},
		stop: function( event, ui ) {
			ckSaveAction();
			ui.item.css('display', '');
		}
	});
}

function ckMakeItemsSortable(block) {
	$ck('.innercontent', block).sortable({
		connectWith: ".innercontent",
		items: '.cktype, .rowck',
		helper: "clone",
		// dropOnEmpty: true,
		handle: ".controlMoveItem, > .roweditor > .ckfields > .controlMove",
		tolerance: "pointer",
		// forcePlaceholderSize: true,
		placeholder: "placeholderck",
		// cancel: 'div',
		activate: function (event, ui) {
			if (ui != undefined && !$ck(ui.item).hasClass('menuitemck') && !$ck(ui.item).hasClass('ckcontenttype') && !$ck(ui.item).hasClass('ckpageitem')) {
				$ck(ui.helper).css('width', '250px').css('height', '100px').css('overflow', 'hidden');
			}
		},
		stop: function( event, ui ){
			if (ui != undefined) {
				$ck(ui.item).css('width', '').css('height', '').css('overflow', '');
			}
			if (! $ck(ui.item).hasClass('menuitemck')) {
				ckSaveAction('ckMakeItemsSortable'); // only save action if not from left menu
			}
			// ui.placeholder.width(parseInt($ck('> .inner',ui.helper).width()));
			// ui.placeholder.append('<div class="inner" />')
		},
		receive: function( event, ui ) {
			if (ui.sender.hasClass('menuitemck') && ui.sender.hasClass('ckmyelement')) {
				var newblock = $ck(this).find('.menuitemck');
				newblock.css('float', 'none').empty().addClass('ckwait');
				ckAddElementItem(ui.sender.attr('data-type'), newblock);
			} else if (ui.sender.hasClass('menuitemck') && ui.sender.hasClass('ckcontenttype')) {
				var newblock = $ck(this).find('.menuitemck');
//				newblock.css('float', 'none').empty().addClass('ckwait');
				ckAddContentTypeItem(ui.sender.attr('data-type'), newblock);
			} else if (ui.sender.hasClass('menuitemck')) {
				var newblock = $ck(this).find('.menuitemck');
				newblock.css('float', 'none').empty().addClass('ckwait');
				ckAddItem(ui.sender.attr('data-type'), newblock)
				// createBloc(newblock, ui.sender.attr('data-type'));
				// makeRowcontainerSortable($ck('ckrowcontainer'));
			} else {
				// newblock.remove();
			}
		}
	});
}

function ckInitBlocksSize(row) {
	// check if we don't want to calculate automatically, then return
	if (row.hasClass('ckadvancedlayout')) {
		ckSetColumnWidth(row.find('> .inner > .blockck').last(), row.find('> .inner > .blockck').last().prev().attr('data-width'));
		if (row.find('.ckcolwidthselect').length) ckEditColumns(row, true);
		return;
	}
	var number_blocks = row.find('> .inner > .blockck').length;
	var gutter = ckGetRowGutterValue(row);
	var default_data_width = 100 / number_blocks;
	var default_real_width = ( 100 - ( (number_blocks - 1) * parseFloat(gutter) ) ) / number_blocks;
	row.find('> .inner > .blockck').each(function() {
		$ck(this).attr('class', function(i, c) {
			return c.replace(/(^|\s)span\S+/g, ''); // backward compat to remove old bootstrap styles
		});
		$ck(this).attr('data-real-width', default_real_width + '%').attr('data-width', default_data_width);
		if ($ck(this).find('.ckcolwidthselect').length) $ck(this).find('.ckcolwidthselect').val(default_data_width);
	});
	ckFixBCRow(row);
	row.removeClass('row-fluid');
	ckSetColumnsWidth(row);
	if (row.find('.ckcolwidthselect').length) ckEditColumns(row, true);
	ckSaveAction();
}

function ckGetObjectAnyway(foobar) {
	if (! (foobar instanceof $ck)) {
		if (! foobar.id && (typeof foobar == 'string' && foobar.indexOf('#') == -1)) {
			foobar = $ck('#' + foobar);
		} else {
			foobar = $ck(foobar);
		}
	}
	return foobar;
}

function ckRemoveBlock(block) {
	block = ckGetObjectAnyway(block);
	var row = $ck($ck(block).parents('.rowck')[0]);
	if (!confirm(Joomla.JText._('CK_CONFIRM_DELETE','CK_CONFIRM_DELETE'))) return;
	$ck(block).remove();
	$ck('.cktooltip').remove();
	// check if the last block is resizable, disable it
	if (row.find('.blockck').last().is('.ui-resizable')) {
		row.find('.blockck').last().resizable('destroy');
	}
	// check if there is just one block left
	if (! row.find('.blockck').length) {
		ckAddBlock(row);
	}
	// give the correct width to the elements
	ckInitBlocksSize(row);
	ckAddColumnsSuggestions();
}

function ckRemoveWrapper(wrapper) {
	ckRemoveRow(wrapper);
	$ck('.cktooltip').remove();
}

function ckRemoveRow(row) {
	row = ckGetObjectAnyway(row);
	if (!confirm(Joomla.JText._('CK_CONFIRM_DELETE','CK_CONFIRM_DELETE'))) return;
	row.remove();
	$ck('.cktooltip').remove();
	// if we delete the last row, then add a new empty one
	if (! $ck('.workspaceck .rowck').length) {
		ckAddRow(false);
	}
}

function ckRemoveItem(el) {
	el = ckGetObjectAnyway(el);
	if (!confirm(Joomla.JText._('CK_CONFIRM_DELETE','CK_CONFIRM_DELETE'))) return;
	$ck(el).remove();
	$ck('.cktooltip').remove();
}

function ckAddWrapperEdition(bloc) {
	bloc = $ck(bloc);
	if (bloc.hasClass('ui-sortable-helper')) 
		return;
	if ($ck('> .editorck', bloc).length)
		return;
	bloc.css('position','relative');
	var editor = '<div class="editorck wrappereditor" id="' + bloc.attr('id') + '-edition"></div>';
	editor = $ck(editor);
	editor.css({
		'left': '-29px',
		'top': '0px',
		'position': 'absolute',
		'z-index': 99,
		'height': '30px'
	});
	ckAddWrapperEditionControls(editor, bloc);
	bloc.append(editor);
	ckMakeTooltip(editor);
	editor.css('display', 'none').fadeIn('fast');
}

function ckAddRowEdition(bloc) {
	bloc = $ck(bloc);
	if (bloc.hasClass('ui-sortable-helper')) 
		return;
	if ($ck('> .editorck', bloc).length)
		return;
	bloc.css('position','relative');
	var editor = '<div class="editorck roweditor" id="' + bloc.attr('id') + '-edition"></div>';
	editor = $ck(editor);
	editor.css({
		'left': '-30px',
		'top': '0',
		'position': 'absolute',
		'z-index': 999,
		'height': '100%'
	});
	ckAddRowEditionControls(editor, bloc);
	bloc.append(editor);
	ckMakeTooltip(editor);
	editor.css('display', 'none').fadeIn('fast');

	if (PAGEBUILDERCK.FASTEDITION === '1') {
		// add feature to have a direct button to add
		bloc.mouseenter(function() {
			// bloc.append('<div class="rowck-button-add rowck-button-add-top"><div class="rowck-button-add-icon"></div></div>');
			bloc.append('<div class="rowck-button-add rowck-button-add-bottom"><div class="rowck-button-add-icon" onclick="ckAddRowFromButton(this)">+</div></div>');
		}).mouseleave(function() {
			var t = setTimeout( function() {
				bloc.find('.rowck-button-add').remove();
			}, 200);

		});
	}
}

function ckAddEdition(bloc, i, type) {
	if (!i)
		i = 0;
	if (!type)
		type = '';
	bloc = $ck(bloc);
	if (bloc.hasClass('ui-sortable-helper')) return;
	if ($ck('> .editorck', bloc).length && i == 0)
		return;
	var leftpos = bloc.position().left;
	var toppos = bloc.position().top;
	bloc.css('position','relative');
	var editorclass = '';
	var editor = '<div class="editorck' + editorclass + '" id="' + bloc.attr('id') + '-edition" contenteditable="false"></div>';
	editor = $ck(editor);
	editor.css({
		'left': 0,
		'top': 0,
		'position': 'absolute',
		'z-index': 99 + 1,
		'width': bloc.outerWidth()
	});
	if (bloc.hasClass('cktype')) {
		ckAddItemEditionControls(editor, bloc);
		editor = $ck(editor);
		editor.css({
			'left': '10px',
			'z-index': 999 + i
		});
	} else {
		switch (type) {
			case 'readmore':
				ckAddEditionControlsReadmore(editor, bloc);
			break;
			default:
				ckAddEditionControls(editor, bloc);
			break;
		}
	}
	bloc.append(editor);
	ckMakeTooltip(editor);
	editor.css('display', 'none').fadeIn('fast');
	if (bloc.hasClass('blockck')) {
		editor.css('top', -(editor.find('> .ckfields').height()-30));

		// add feature to have a direct button to add
		if (PAGEBUILDERCK.FASTEDITION === '1') {
			bloc.append('<div class="blockck-button-add blockck-button-add-right"><div class="blockck-button-add-icon" onclick="ckAddColumnFromButton(this)">+</div></div>');
			if (! bloc.find('.cktype').length) {
				bloc.append('<div class="cktype-button-add cktype-button-add-center"><div class="cktype-button-add-icon" onclick="ckAddItemFromButton(this)">+</div></div>');
			}
		}
	} else {
		// add feature to have a direct button to add
		if (PAGEBUILDERCK.FASTEDITION === '1') {
			bloc.append('<div class="cktype-button-add cktype-button-add-bottom"><div class="cktype-button-add-icon" onclick="ckAddItemFromButton(this)">+</div></div>');
		}
	}
}

function ckAddItemFromButton(btn) {
	$ck('#ckaddonspanelpopup').remove();
	var cur_column = $ck($ck(btn).parents('.blockck')[0]);
	var prev_item = $ck($ck(btn).parents('.cktype')[0]);
	var from = $ck(btn).parent().parent().hasClass('innercontent') ? $ck(btn).parent().parent() : prev_item.length ? prev_item : cur_column;
	var panel = $ck('.menuckpaneltarget[data-target="addons"]').clone();
	panel.attr('data-target', '').addClass('menuck');
	var popup = $ck('<div id="ckaddonspanelpopup"></div>');
	panel.find('.menuitemck_group:first-child, .menuitemck_group:first-child + div').hide();
	panel.find('#ckmyelements .menuitemck[data-type="row"]').hide();
	$ck(document.body).append(popup);
	popup.append(panel);
	// CKBox.open({handler:\'inline\', fullscreen: true, content:\'pbckpanelpopup\', id: \'pbckpanelckbox\', onCKBoxLoaded: function() {ckInitOnBoxLoaded(\'ckadvanceditembox\', \''+itemcontentId+'\')}})
	CKBox.open({handler:'inline', size: {'x': '900px', 'y': '700px'}, content:'ckaddonspanelpopup', id: 'pbckpanelckbox' });
	ckInitAddonsPanelEvents(from);
}

function ckInitAddonsPanelEvents(cur_column) {
	$ck('#ckaddonspanelpopup .menuitemck').on('click', function() {
		ckAddItem($ck(this).attr('data-type'), cur_column);
		CKBox.close();
	});
	$ck('#ckaddonspanelpopup .cksearchleftpanel').focus();
	ckInitSearchAddon();
}

function ckAddColumnFromButton(btn) {
	var cur_row = $ck($ck(btn).parents('.rowck')[0]);
	var cur_column = $ck($ck(btn).parents('.blockck')[0]);
	ckAddBlock(cur_row, cur_column);
}

function ckMakeTooltip(el) {
	if (! el) el = '.cktip';
	if (! $ck(el).attr('title')) el = $ck(el).find('.cktip, .isControl')
	if (PAGEBUILDERCK.TOOLTIPS !== '0') CKApi.Tooltip(el);
	/*if (! el) el = $ck('.hastoolTip');
	el.tooltipck({
		// items: ".infotip",
		content: function() {
			return $ck(this).attr('title');
		},
		close: function( event, ui ) {
			ui.tooltipck.hide();
		},
		position: {
			my: "center top",
			at: "center top-40",
			using: function( position, feedback ) {
				$ck( this ).css( position );
			}
		},
		track: false,
		tooltipClass: "cktooltipinfo",
		container: "body"
	});*/
}

function ckAddEditionControls(editor, bloc) {

	var blocclass = bloc.attr('ckclass') ? bloc.attr('ckclass') : '';
	var controls = '<div class="ckfields">'
			+ '<div class="controlDel isControl" title="'+Joomla.JText._('CK_REMOVE_BLOCK')+'" onclick="ckRemoveBlock($ck(this).parents(\'.blockck\')[0]);" onmouseover="$ck($ck(this).parents(\'.blockck\')[0]).addClass(\'highlight_delete\');" onmouseleave="$ck($ck(this).parents(\'.blockck\')[0]).removeClass(\'highlight_delete\');"></div>'
			+ '<div class="controlMove isControl" title="'+Joomla.JText._('CK_MOVE_BLOCK')+'"></div>'
			+ '<div class="controlCopy isControl" title="'+Joomla.JText._('CK_DUPLICATE_COLUMN')+'" onclick="ckDuplicateColumn(\'' + bloc.attr('id') + '\');"></div>'
			+ '<div class="controlCss isControl" title="'+Joomla.JText._('CK_EDIT_STYLES')+'" onclick="ckShowCssPopup(\'' + bloc.attr('id') + '\');"></div>'
			+ '<div class="controlFavorite isControl" title="'+Joomla.JText._('CK_DESIGN_SUGGESTIONS')+'" onclick="ckShowFavoritePopup(\'' + bloc.attr('id') + '\');"></div>'
			+ "<div class=\"controlValignDefault isControl ckhastip" + (blocclass == '' ? ' active' : '') + "\" title=\"" + Joomla.JText._('CK_VALIGN_DEFAULT', 'Default vertical alignment') + "\" onclick=\"ckToggleVerticalAlign('" + bloc.attr('id') + "', 'default', this);\"></div>"
			+ "<div class=\"controlValignTop isControl ckhastip" + (blocclass == 'valign-top' ? ' active' : '') + "\" title=\"" + Joomla.JText._('CK_VALIGN_TOP', 'Top vertical alignment') + "\" onclick=\"ckToggleVerticalAlign('" + bloc.attr('id') + "', 'top', this);\"></div>"
			+ "<div class=\"controlValignCenter isControl ckhastip" + (blocclass == 'valign-center' ? ' active' : '') + "\" title=\"" + Joomla.JText._('CK_VALIGN_CENTER', 'Center vertical alignment') + "\" onclick=\"ckToggleVerticalAlign('" + bloc.attr('id') + "', 'center', this);\"></div>"
			+ "<div class=\"controlValignBottom isControl ckhastip" + (blocclass == 'valign-bottom' ? ' active' : '') + "\" title=\"" + Joomla.JText._('CK_VALIGN_BOTTOM', 'Bottom vertical alignment') + "\" onclick=\"ckToggleVerticalAlign('" + bloc.attr('id') + "', 'bottom', this);\"></div>"
			+ "</div>";

	editor.append(controls);
}

function ckToggleVerticalAlign(blocid, pos, btn) {
	var focus = $ck('#' + blocid);
	focus.removeClass('valign-top').removeClass('valign-center').removeClass('valign-bottom');
	if (pos != 'default') {
		focus.addClass('valign-' + pos);
		focus.attr('ckclass', 'valign-' + pos);
	} else {
		focus.attr('ckclass', '');
	}
	$ck(btn).parent().find('[class*="controlValign"]').removeClass('active');
	$ck(btn).addClass('active');
}

function ckAddEditionControlsReadmore(editor, bloc) {
	var controls = '<div class="ckfields">'
			+ '<div class="controlDel isControl" title="'+Joomla.JText._('CK_REMOVE_BLOCK')+'" onclick="ckRemoveRow($ck(this).parents(\'#system-readmore\')[0].id);" onmouseover="$ck($ck(this).parents(\'.blockck\')[0]).addClass(\'highlight_delete\');" onmouseleave="$ck($ck(this).parents(\'.blockck\')[0]).removeClass(\'highlight_delete\');"></div>'
			+ '<div class="controlMove isControl moverow" title="'+Joomla.JText._('CK_MOVE_BLOCK')+'"></div>'
			+ '</div>';

	editor.append(controls);
}

function ckAddWrapperEditionControls(editor, bloc) {
	var controls = '<div class="ckfields">'
			+ '<div class="controlResponsiveShown isControlResponsive isControl" data-class="ckshow" title="'+Joomla.JText._('CK_RESPONSIVE_SETTINGS_SHOWN')+'" onclick="ckToggleResponsiveWrapper(this);" ><span class="fa fa-eye"></span></div>'
//			+ '<div class="controlResponsiveStacked isControlResponsive isControl" data-class="ckstack" title="'+Joomla.JText._('CK_RESPONSIVE_SETTINGS_STACKED')+'" onclick="ckToggleResponsiveRow(this);" ></div>'
			+ '<div class="controlResponsiveHidden isControlResponsive isControl" data-class="ckhide" title="'+Joomla.JText._('CK_RESPONSIVE_SETTINGS_HIDDEN')+'" onclick="ckToggleResponsiveWrapper(this);" ><span class="fa fa-eye-slash"></span></div>'
//			+ '<div class="controlCss isControlResponsive isControl" title="'+Joomla.JText._('CK_EDIT_STYLES')+'" onclick="ckShowResponsiveCssEdition(\'' + bloc.attr('id') + '\');" ></div>'
			+ '<div class="controlMore isControl" title="'+Joomla.JText._('CK_MORE_MENU_ELEMENTS')+'" onclick="$ck(this).toggleClass(\'ckhover\').next().toggle();" >...</div>'
			+ '<div style="display:none;" class="controlMoreChildren">'
					+ '<div class="controlMove isControl" title="'+Joomla.JText._('CK_MOVE_WRAPPER')+'"></div>'
//					+ '<div class="controlSize isControl" title="'+Joomla.JText._('CK_EDIT_COLUMNS')+'" onclick="ckShowColumnsEdition($ck(this).parents(\'.rowck\')[0]);" ></div>'
					+ '<div class="controlCss isControl" title="'+Joomla.JText._('CK_EDIT_STYLES')+'" onclick="ckShowCssPopup(\'' + bloc.attr('id') + '\');"></div>'
//					+ '<div class="controlFavorite isControl" title="'+Joomla.JText._('CK_DESIGN_SUGGESTIONS')+'" onclick="ckShowFavoritePopup(\'' + bloc.attr('id') + '\');"></div>'
					+ '<div class="controlCopy isControl" title="'+Joomla.JText._('CK_DUPLICATE_WRAPPER')+'" onclick="ckDuplicateWrapper(\'' + bloc.attr('id') + '\');"></div>'
					+ '<div class="controlFullwidth isControl' + (bloc.hasClass('wrapperckfullwidth') ? ' ckactive' : '') + '" title="'+Joomla.JText._('CK_FULLWIDTH')+'" onclick="ckShowFullwidthRowEdition(\'' + bloc.attr('id') + '\');"></div>'
					+ '<div class="controlSave isControl" title="'+Joomla.JText._('CK_SAVE')+'" onclick="ckSaveItem(\'' + bloc.attr('id') + '\');"></div>'
				+ '<div class="controlDel isControl" title="'+Joomla.JText._('CK_REMOVE_WRAPPER')+'" onclick="ckRemoveWrapper($ck(this).parents(\'.wrapperck\')[0]);" onmouseover="$ck($ck(this).parents(\'.wrapperck\')[0]).addClass(\'highlight_delete\');" onmouseleave="$ck($ck(this).parents(\'.wrapperck\')[0]).removeClass(\'highlight_delete\');" ></div>'
			+ '</div>'
			+ '</div>';

	editor.append(controls);
}

function ckAddRowEditionControls(editor, bloc) {
	var controls = '<div class="ckfields">'
			+ '<div class="editorckresponsiverow">'
				+ '<div class="controlResponsiveAligned isControlResponsive isControl" data-class="ckalign" title="'+Joomla.JText._('CK_RESPONSIVE_SETTINGS_ALIGNED')+'" onclick="ckToggleResponsiveRow(this);" ></div>'
				+ '<div class="controlResponsiveStacked isControlResponsive isControl" data-class="ckstack" title="'+Joomla.JText._('CK_RESPONSIVE_SETTINGS_STACKED')+'" onclick="ckToggleResponsiveRow(this);" ></div>'
				+ '<div class="controlResponsiveHidden isControlResponsive isControl" data-class="ckhide" title="'+Joomla.JText._('CK_RESPONSIVE_SETTINGS_HIDDEN')+'" onclick="ckToggleResponsiveRow(this);" ><span class="fa fa-eye-slash"></span></div>'
				+ '<div class="controlCss isControlResponsive isControl" title="'+Joomla.JText._('CK_EDIT_STYLES')+'" onclick="ckShowResponsiveCssEdition(\'' + bloc.attr('id') + '\');" ></div>'
			+ '</div>'
			+ '<div class="controlMove isControl moverow" title="'+Joomla.JText._('CK_MOVE_ROW')+'"></div>'
			+ '<div class="controlMore isControl" title="'+Joomla.JText._('CK_MORE_MENU_ELEMENTS')+'" onclick="$ck(this).toggleClass(\'ckhover\').next().toggle();" >...</div>'
			+ '<div style="display:none;" class="controlMoreChildren">'
					+ '<div class="controlSize isControl" title="'+Joomla.JText._('CK_EDIT_COLUMNS')+'" onclick="ckShowColumnsEdition($ck(this).parents(\'.rowck\')[0]);" ></div>'
					+ '<div class="controlCss isControl" title="'+Joomla.JText._('CK_EDIT_STYLES')+'" onclick="ckShowCssPopup(\'' + bloc.attr('id') + '\');"></div>'
					+ '<div class="controlFavorite isControl" title="'+Joomla.JText._('CK_DESIGN_SUGGESTIONS')+'" onclick="ckShowFavoritePopup(\'' + bloc.attr('id') + '\');"></div>'
					+ '<div class="controlCopy isControl" title="'+Joomla.JText._('CK_DUPLICATE_ROW')+'" onclick="ckDuplicateRow(\'' + bloc.attr('id') + '\');"></div>'
					+ '<div class="controlFullwidth isControl' + (bloc.hasClass('rowckfullwidth') ? ' ckactive' : '') + '" title="'+Joomla.JText._('CK_FULLWIDTH')+'" onclick="ckShowFullwidthRowEdition(\'' + bloc.attr('id') + '\');"></div>'
					+ '<div class="controlSave isControl" title="'+Joomla.JText._('CK_SAVE')+'" onclick="ckSaveItem(\'' + bloc.attr('id') + '\');"></div>'
					+ (PAGEBUILDERCK.ITEMACL == '1' ? '<div class="controlAcl isControl" title="'+Joomla.JText._('CK_ACCESS_RIGHTS')+'" onclick="ckShowAclEdition(\'' + bloc.attr('id') + '\');"><span class="fa fa-key"></span></div>' : '')
				+ '<div class="controlDel isControl" title="'+Joomla.JText._('CK_REMOVE_ROW')+'" onclick="ckRemoveRow(\'' + bloc.attr('id') + '\');" onmouseover="$ck($ck(this).parents(\'.rowck\')[0]).addClass(\'highlight_delete\');" onmouseleave="$ck($ck(this).parents(\'.rowck\')[0]).removeClass(\'highlight_delete\');" ></div>'
			+ '</div>'
			+ '</div>';

	editor.append(controls);
}

function ckAddItemEditionControls(editor, bloc) {

	var isContentType = bloc.hasClass('ckcontenttype');
	var controls = '<div class="ckfields">'
			+ '<div class="controlDel isControl" title="'+Joomla.JText._('CK_REMOVE_ITEM')+'" onclick="ckRemoveItem($ck(this).parents(\'.cktype\')[0]);" onmouseover="$ck($ck(this).parents(\'.cktype\')[0]).addClass(\'highlight_delete\');" onmouseleave="$ck($ck(this).parents(\'.cktype\')[0]).removeClass(\'highlight_delete\');" ></div>'
			+ '<div class="controlMoveItem isControl" title="'+Joomla.JText._('CK_MOVE_ITEM')+'"></div>'
			+ (!isContentType ? '<div class="controlCopy isControl" title="'+Joomla.JText._('CK_DUPLICATE_ITEM')+'" onclick="ckDuplicateItem(\'' + bloc.attr('id') + '\');"></div>' : '')
			+ '<div class="controlEdit isControl" title="'+Joomla.JText._('CK_EDIT_ITEM')+'" onclick="ckShowEditionPopup(\'' + bloc.attr('id') + '\');"></div>'
			+ (!isContentType ? '<div class="controlFavorite isControl" title="'+Joomla.JText._('CK_DESIGN_SUGGESTIONS')+'" onclick="ckShowFavoritePopup(\'' + bloc.attr('id') + '\');"></div>' : '')
			+ (!isContentType ? '<div class="controlSave isControl" title="'+Joomla.JText._('CK_SAVE')+'" onclick="ckSaveItem(\'' + bloc.attr('id') + '\');"></div>' : '')
			+ '</div>';

	editor.append(controls);
}

function ckRemoveEdition(bloc, all) {
	if (!all)
		all = false;
	if (all == true) {
			$ck('.editorck', bloc).remove();
		} else {
			$ck('> .editorck', bloc).remove();
		}
	$ck(bloc).find('.blockck-button-add').remove();
	$ck(bloc).find('.cktype-button-add').remove();
}

function ckShowLeftPanel(panel) {
	$ck('#menuck > .inner').fadeOut();
	$ck(panel).fadeIn();
	ckMakeTooltip($ck('.ckcolumnsedition'));
}

function ckCloseLeftPanel(panel) {
	$ck('.ckfocus').removeClass('ckfocus');
	$ck(panel).fadeOut();
	$ck('#menuck > .inner').fadeIn();
	$ck('.cktooltip').remove();
}

function ckShowColumnsEdition(row) {
	row = ckGetObjectAnyway(row);
	ckCloseEdition();
	ckEditColumns($ck('.ckfocus'), false, true);
	$ck('.ckfocus').removeClass('ckfocus');
	$ck('.editfocus').removeClass('editfocus');
	row = $ck(row);
	row.addClass('ckfocus');
	ckShowLeftPanel('.ckcolumnsedition');
	ckAddColumnsSuggestions();
	$ck('#menuck .ckguttervalue').val(ckGetRowGutterValue(row));
	var autowidth = row.hasClass('ckadvancedlayout') ? '0' : '1';
	$ck('#menuck [name="autowidth"]').removeAttr('checked');
	$ck('#menuck [name="autowidth"][value="' + autowidth + '"]').prop('checked','checked');
	if (! autowidth) {
		$ck('#ckcolumnsuggestions').hide();
	} else {
		$ck('#ckcolumnsuggestions').show();
	}
	var columnsspacebetween = row.attr('data-columns-space-between') == '0' ? '0' : '1';
	row.attr('data-columns-space-between', columnsspacebetween);
	$ck('#menuck [name="columns-space-between"]').removeAttr('checked');
	$ck('#menuck [name="columns-space-between"][value="' + columnsspacebetween + '"]').prop('checked','checked');
	ckEditColumns(row, true);
	for (var i=1;i<5;i++) {
		$ck('.ckresponsiveoptions [data-range="' + i + '"] .ckbutton').removeClass('active');
		if (row.hasClass('ckhide' + i)) {
			$ck('.ckresponsiveoptions [data-range="' + i + '"] [data-class="ckhide"]').addClass('active');
		} else if (row.hasClass('ckstack' + i)) {
			$ck('.ckresponsiveoptions [data-range="' + i + '"] [data-class="ckstack"]').addClass('active');
		} else {
			$ck('.ckresponsiveoptions [data-range="' + i + '"] [data-class="ckalign"]').addClass('active');
		}
	}
}

function ckUpdateAutowidth(row, autowidth) {
	if (autowidth == '1') {
		$ck(row).removeClass('ckadvancedlayout');
		$ck('#ckcolumnsuggestions, .ckcolwidthlocker, #ckgutteroptions').show();
	} else {
		$ck(row).addClass('ckadvancedlayout');
		$ck('#ckcolumnsuggestions, .ckcolwidthlocker, #ckgutteroptions').hide();
	}
}

function ckUpdateSpacebetween(row, value) {
	$ck(row).attr('data-columns-space-between', value);
}

function ckHideColumnsEdition() {
	var row = $ck('.rowck.ckfocus');
	ckEditColumns(row, false, true);
	ckCloseLeftPanel('.ckcolumnsedition');
}

function ckAddColumnsSuggestions() {
	var row = $ck('.rowck.ckfocus');
	var nb_blocks = row.find('.blockck').length;
	if (nb_blocks == 0) return;
	var buttons = ckCalculateColumnSuggestion(row, nb_blocks);

	$ck('#menuck #ckcolumnsuggestions').empty();
	if (buttons) {
		$ck('#menuck #ckcolumnsuggestions').append('<div>' + Joomla.JText._('CK_SUGGESTIONS') + '</div>');
		$ck('#menuck #ckcolumnsuggestions').append(buttons);
	}
}

function ckEditColumns(row, force, forcehide) {
	if (! force) force = false;
	if (! forcehide) forcehide = false;
	var responsiverange = ckGetResponsiveRange();
	if (row.find('.ckcolwidthedition').length && ! force || forcehide) {
		row.find('.ckcolwidthedition').remove();
		row.find('.ckcolwidthediting').removeClass('ckcolwidthediting');
	} else {
		var number_blocks = row.find('> .inner > .blockck').length;
		if (responsiverange == '1' || responsiverange == '2') {
			var default_data_width = 100;
		} else {
			var default_data_width = 100 / number_blocks;
		}
		row.find('> .inner > .blockck > .inner').each(function(i, blockinner) {
			var blockinner = $ck(blockinner);
			var block = blockinner.parent();
			blockinner.addClass('ckcolwidthediting');
			var responsiverangeattrib = ckGetResponsiveRangeAttrib(responsiverange);
			var block_data_width = block.attr('data-width' + responsiverangeattrib) ? block.attr('data-width' + responsiverangeattrib) : default_data_width;
			block.attr('data-width' + responsiverangeattrib, block_data_width);
			if (! blockinner.find('.ckcolwidthedition').length) blockinner.append('<div class="ckcolwidthedition"><div class="ckcolwidthlocker" title="Click to lock / unlock the width" onclick="ckToggleColWidthState(this);"></div><input id="' + row.attr('id') + '_w' + i + '" class="ckcolwidthselect inputbox" value="' + block_data_width + '" onchange="ckCalculateBlocsWidth(this);" type="text" /> %</div>')
		});
	}
}

function ckGetResponsiveRange() {
	var responsiverange = $ck('.workspaceck').attr('ckresponsiverange') ? $ck('.workspaceck').attr('ckresponsiverange') : '';
	return responsiverange;
}

function ckGetResponsiveRangeNumber() {
	var range = ckGetResponsiveRange();
	var rangeNumber = range.charAt(range.length-1);
	return rangeNumber;
}

function ckGetResponsiveRangeAttrib(responsiverange) {
	var responsiverangeattrib = responsiverange ? '-' +responsiverange : '';
	return responsiverangeattrib;
}

function ckToggleColWidthState(locker) {
	var input = $ck(locker).parent().find('input.ckcolwidthselect');
	var enableamount = $ck('.ckcolwidthselect:not(.disabled)', $ck(locker).parents('.rowck')).length;
	var loackedamount = $ck('.ckcolwidthedition.locked', $ck(locker).parents('.rowck')).length;

	if (!input.hasClass('locked')) {
		input.addClass('locked');
		$ck(locker).addClass('locked');
		$ck(locker).parent().addClass('locked');
	} else {
		input.removeClass('locked');
		$ck(locker).removeClass('locked');
		$ck(locker).parent().removeClass('locked');
	}
}

function ckCalculateBlocsWidth(field) {
	// if advanced layout selected, no calculation
	var row = $ck('.rowck.ckfocus');
	if (! row.length) {
		row = $ck($ck(field).parents('.rowck')[0]);
	}
	if (row.hasClass('ckadvancedlayout') || $ck('.workspaceck.ckresponsiveactive').length) {
		ckSetColumnsWidth(row);
		ckSaveAction();
		return;
	}
	var responsiverange = ckGetResponsiveRange();

	var enabledfields = $ck('.ckcolwidthedition:not(.disabled) .ckcolwidthselect:not(.disabled,.locked,#' + $ck(field).attr('id') + ')', row);
	var amount = enabledfields.length;
	var lockedvalue = 0;
	$ck('.ckcolwidthselect.locked', row).each(function(i, modulefield) {
		modulefield = $ck(modulefield);
		if (modulefield.val() == '') {
			modulefield.removeClass('locked').next('input').prop('checked', false);
			ckCalculateBlocsWidth(field);
		}
		if (modulefield.attr('id') != $ck(field).attr('id')) {
			lockedvalue = parseFloat(modulefield.val()) + parseFloat(lockedvalue);
		}
	});
	var mw = parseFloat($ck(field).val());
	// $ck(field).val(mw+'%');
//	if (responsiverange && parseInt(responsiverange) > 2) {
	var percent = (100 - mw - lockedvalue) / amount;
//	} else {
//		var percent = 100;
//	}
	enabledfields.each(function(i, modulefield) {
		if ($ck(modulefield).attr('id') != $ck(field).attr('id')
				&& !$ck(modulefield).hasClass('locked')) {
				
			$ck(modulefield).val(parseFloat(percent));
		}
	});
	ckSetColumnsWidth(row);
	ckSaveAction();
}

function ckCalculateColumnSuggestion(row, nb_blocks) {
	var suggestions = [];
	switch(nb_blocks) {
		case 2:
			suggestions = 	[ 	[ '1/4', '3/4' ],
								[ '1/2', '1/2' ],
								[ '3/4', '1/4' ],
								[ '2/3', '1/3' ],
								[ '1/3', '2/3' ],
								[ '5/6', '1/6' ],
								[ '1/6', '5/6' ]
							]
			break;
		case 3:
			suggestions = 	[ 	[ '1/3', '1/3', '1/3' ],
								[ '1/4', '1/2', '1/4' ],
								[ '1/6', '2/3', '1/6' ]
							]
			break;
		case 4:
			suggestions = 	[ 	[ '1/4', '1/4', '1/4', '1/4' ],
								[ '1/6', '1/3', '1/3', '1/6' ]
							]
			break;
		case 6:
			suggestions = 	[ 	[ '1/6', '1/6', '1/6', '1/6', '1/6', '1/6' ],
								[ '1/12', '1/12', '1/3', '1/3', '1/12', '1/12' ]
							]
			break;
		default:
			break;
	}

	buttons = '';
	for (i=0; i<suggestions.length; i++) {
		cols = '';
		cols_value = [];
		suggestion = suggestions[i];
		for (j=0; j<suggestion.length; j++) {
			cols += '<div class="iscolumnsuggestion" data-width="' + ckFracToDec(suggestion[j])*100 + '" style="width: ' + ckFracToDec(suggestion[j])*100 + '%;"><div></div></div>';
			cols_value.push(suggestion[j]);
		}
		cols_value_txt = cols_value.join(' | ');
		buttons += '<div class="clearfix" title="' + cols_value_txt + '" onclick="ckApplyColumnSuggestion($ck(\'.rowck.ckfocus\'), this);">' + cols + '</div>';
	}
	ckMakeTooltip($ck('#ckcolumnsuggestions'));
	return buttons;
}

/* convert a fraction to decimal */
function ckFracToDec(frac) {
	dec = frac.split('/');
	return (dec[0]/dec[1]);
}

function ckApplyColumnSuggestion(row, selection) {
	if (row.find('.blockck').length != $ck(selection).find('.iscolumnsuggestion').length) {
		alert('Error : the number of columns selected does not match the number of columns in the row');
		return;
	}
	suggestions = $ck(selection).find('.iscolumnsuggestion');
	for (i=0; i<suggestions.length; i++) {
		var col = row.find('.blockck').eq(i);
		data_width = $ck(suggestions[i]).attr('data-width');
		if (col.find('.ckcolwidthselect').length) col.find('.ckcolwidthselect').val(data_width);
		col.attr('data-width', data_width);
	}
	ckSetColumnsWidth(row);
	ckSaveAction();
}

function ckGetRowGutterValue(row) {
	var gutter = row.attr('data-gutter') ? row.attr('data-gutter') : '2%';
	row.attr('data-gutter',gutter);
	return gutter;
}

function ckUpdateGutter(row, gutter) {
	row.attr('data-gutter',parseFloat(gutter)+'%');
	ckSetColumnsWidth(row);
}

function ckSetColumnsWidth(row) {
	var responsiverange = ckGetResponsiveRange();
	var responsiverangeattrib = ckGetResponsiveRangeAttrib(responsiverange);
	if (! row.find('> .ckcolumnwidth' + responsiverange).length) {
		row.prepend('<style class="ckcolumnwidth' + responsiverange + '"></style>');
	}
	var stylewidths = row.find('> .ckcolumnwidth' + responsiverange);
	var gutter = ckGetRowGutterValue(row);
	var nb = row.find('> .inner > .blockck').length;
	row.attr('data-nb', nb);
	stylewidths.empty();
	var prefixselector = responsiverange ? '[ckresponsiverange="' + responsiverange + '"] ' : '';
	row.find('> .inner > .blockck').each(function(i, col) {
		var w = $ck(col).find('.ckcolwidthselect').val();
		if ($ck(col).find('.ckcolwidthselect').length) $ck(col).attr('data-width' + responsiverangeattrib, $ck(col).find('.ckcolwidthselect').val());
		w = $ck(col).attr('data-width' + responsiverangeattrib);
		ckSetColumnWidth($ck(col), w);
		if (responsiverange > 0 && responsiverange < 5) {
			stylewidths.append(prefixselector + '.rowck[data-gutter="' + gutter + '"][data-nb="' + nb + '"] [data-width' + responsiverangeattrib + '="' + w + '"] {width:' + parseFloat($ck(col).attr('data-width' + responsiverangeattrib)) + '%;}');
		} else {
			stylewidths.append(prefixselector + '[data-gutter="' + gutter + '"][data-nb="' + nb + '"]:not(.ckadvancedlayout) [data-width' + responsiverangeattrib + '="' + w + '"] {width:' + $ck(col).attr('data-real-width' + responsiverangeattrib) + ';}');
			stylewidths.append(prefixselector + '[data-gutter="' + gutter + '"][data-nb="' + nb + '"].ckadvancedlayout [data-width' + responsiverangeattrib + '="' + w + '"] {width:' + parseFloat($ck(col).attr('data-width' + responsiverangeattrib)) + '%;}');
		}
	});
	ckFixBCRow(row);
	row.removeClass('row-fluid');
}

function ckSetColumnWidth(col, w) {
	var responsiverange = ckGetResponsiveRange();
	var responsiverangeattrib = ckGetResponsiveRangeAttrib(responsiverange);
	if (! w) w = col.attr('data-width' + responsiverangeattrib) ? col.attr('data-width' + responsiverangeattrib) : '30';
	var row = $ck(col.parents('.rowck')[0]);
	var numberblocks = row.find('.blockck').length;
	var gutter = ckGetRowGutterValue(row);
	var realwidth =  w - (( (numberblocks - 1) * parseFloat(gutter) ) / numberblocks);
	col.attr('class', function(i, c) {
		return c.replace(/(^|\s)span\S+/g, '');
	});
	col.attr('data-real-width' + responsiverangeattrib, realwidth + '%').attr('data-width' + responsiverangeattrib, w).css('width', '');
}

function ckSearchExistingColWidth(row, gutter, nb, w, prefixselector) {
	var stylewidths = row.find('> .ckcolumnwidth');
	var s = prefixselector + '[data-gutter="' + gutter + '"][data-nb="' + nb + '"] [data-width="' + w + '"]';
	// if we don't alreay have the style
	if (stylewidths.html().indexOf(s) == -1) {
		return false;
	}
	return true;
}

function ckSearchExistingGutterWidth(row, gutter, nb, prefixselector) {
	var stylewidths = row.find('> .ckcolumnwidth');
	var s = prefixselector + '[data-gutter="' + gutter + '"][data-nb="' + nb + '"] .blockck';
	// if we don't alreay have the style
	if (stylewidths.html().indexOf(s) == -1) {
		return false;
	}
	return true;
}

function ckDuplicateItem(blocid) {
	bloc = $ck('#' + blocid);
	var copy = bloc.clone();
	copyid = ckGetUniqueId();
	copy.attr('id', copyid);

	bloc.after(copy);
	copy.removeClass('editfocus');
	ckAddItemEditionEvents(copy);

	// init the effect if needed
	if (copy.find('.tabsck').length) {
		copy.find('.tabsck').tabsck();
	}
	if (copy.find('.accordionsck').length) {
		copy.find('.accordionsck').accordionck();
	}
	// for tiny inline editing
	if (copy.find('[id^="mce_"]').length) {
		copy.find('[id^="mce_"]').removeAttr('id');
	}

	// add dnd for image
	if (copy.attr('data-type') == 'image') ckAddDndForImageUpload(copy[0]);

	// copy the styles
	var re = new RegExp(blocid, 'g');
	copy.find('.ckstyle').html(bloc.find('.ckstyle').html().replace(re,copyid));
	if (bloc.find('.ckstyleresponsive').length) copy.find('.ckstyleresponsive').html(bloc.find('.ckstyleresponsive').html().replace(re,copyid));
	ckSaveAction();
	ckInlineEditor();
}

function ckDuplicateColumn(blocid) {
	var col = $ck('#' + blocid);
	var row = $ck(col.parents('.rowck')[0]);
	// add an empty column
	var colcopyid = ckAddBlock(row);
	var colcopy = $ck('#' + colcopyid);
	// copy the styles
//	colcopy.find('> .ckstyle').html(col.find('> .ckstyle').html());
	colcopy.html(col.html());
	colcopy.attr('id', blocid);
	ckReplaceId(colcopy, colcopyid);

	// for tiny inline editing
	if (colcopy.find('[id^="mce_"]').length) {
		colcopy.find('[id^="mce_"]').removeAttr('id');
	}
	ckMakeItemsSortable(row);
//	col.find('.cktype').each(function() {
//		colcopy.find('.innercontent').append($ck(this).clone());
//	});

	colcopy.find('.cktype').each(function() {
		$this = $ck(this);
		$this.removeClass('editfocus');
		// init the effect if needed
		if ($this.hasClass('cktype') && $this.find('.tabsck').length) {
			$this.find('.tabsck').tabsck();
		}
		if ($this.hasClass('cktype') && $this.find('.accordionsck').length) {
			$this.find('.accordionsck').accordionck();
		}

		ckAddItemEditionEvents($this);

		// add dnd for image
		if ($this.attr('data-type') == 'image') ckAddDndForImageUpload($this[0]);

		var copyid = ckGetUniqueId();
		// copy the styles
		ckReplaceId($this, copyid);
	});
	ckSaveAction();
	ckInlineEditor();
}

function ckDuplicateWrapper(blocid) {
	var wrapper = $ck('#' + blocid);
	var wrappercopy = wrapper.clone();
	var wrappercopyid = ckGetUniqueId('wrapper_');
//	wrappercopy.attr('id', wrappercopyid);
	wrapper.after(wrappercopy);
	wrappercopy.removeClass('editfocus');
	wrappercopy.find('> .editorck').remove();
	ckReplaceId(wrappercopy, wrappercopyid);
//	ckMakeRowSortableInWrapper(wrappercopy);
	ckAddWrapperEdition(wrappercopy);

	// for tiny inline editing
	if (wrappercopy.find('[id^="mce_"]').length) {
		wrappercopy.find('[id^="mce_"]').removeAttr('id');
	}

	wrappercopy.find('.rowck').each(function() {
		$row = $ck(this);
		$row.removeClass('editfocus');
		$row.find('> .editorck').remove();
		var copyid = ckGetUniqueId('row_');
		// copy the styles
		ckReplaceId($row, copyid);
		ckMakeBlocksSortable($row);
		ckAddRowEditionEvents($row);
	});

	wrappercopy.find('.blockck, .cktype').each(function() {
		$this = $ck(this);
		$this.removeClass('editfocus');
		// init the effect if needed
		if ($this.hasClass('cktype') && $this.find('.tabsck').length) {
			$this.find('.tabsck').tabsck();
		}
		if ($this.hasClass('cktype') && $this.find('.accordionsck').length) {
			$this.find('.accordionsck').accordionck();
		}
		
		var prefix = '';
		if ($this.hasClass('blockck')) {
			prefix = 'block_';
			ckMakeItemsSortable(wrappercopy);
			ckAddBlockEditionEvents($this);
		} else {
			ckAddItemEditionEvents($this);
		}

		// add dnd for image
		if ($this.attr('data-type') == 'image') ckAddDndForImageUpload($this[0]);

		var copyid = ckGetUniqueId(prefix);
		// copy the styles
		ckReplaceId($this, copyid);
	});
	ckSaveAction();
	ckInlineEditor();
}

function ckDuplicateRow(blocid) {
	var row = $ck('#' + blocid);
	var rowcopy = row.clone();
	var rowcopyid = ckGetUniqueId('row_');
//	rowcopy.attr('id', rowcopyid);
	row.after(rowcopy);
	rowcopy.removeClass('editfocus');
	rowcopy.find('> .editorck').remove();
	ckReplaceId(rowcopy, rowcopyid);
	ckMakeBlocksSortable(rowcopy);
	ckAddRowEditionEvents(rowcopy);

	// for tiny inline editing
	if (rowcopy.find('[id^="mce_"]').length) {
		rowcopy.find('[id^="mce_"]').removeAttr('id');
	}

	rowcopy.find('.rowck').each(function() {
		$row = $ck(this);
		$row.removeClass('editfocus');
		$row.find('> .editorck').remove();
		var copyid = ckGetUniqueId('row_');
		// copy the styles
		ckReplaceId($row, copyid);
		ckMakeBlocksSortable($row);
		ckAddRowEditionEvents($row);
	});

	rowcopy.find('.blockck, .cktype').each(function() {
		$this = $ck(this);
		$this.removeClass('editfocus');
		// init the effect if needed
		if ($this.hasClass('cktype') && $this.find('.tabsck').length) {
			$this.find('.tabsck').tabsck();
		}
		if ($this.hasClass('cktype') && $this.find('.accordionsck').length) {
			$this.find('.accordionsck').accordionck();
		}
		
		var prefix = '';
		if ($this.hasClass('blockck')) {
			prefix = 'block_';
			ckMakeItemsSortable(rowcopy);
			ckAddBlockEditionEvents($this);
		} else {
			ckAddItemEditionEvents($this);
		}

		// add dnd for image
		if ($this.attr('data-type') == 'image') ckAddDndForImageUpload($this[0]);

		var copyid = ckGetUniqueId(prefix);
		// copy the styles
		ckReplaceId($this, copyid);
	});
	ckSaveAction();
	ckInlineEditor();
}

function ckToggleFullwidthRow(blocid, enable) {
	var row = $ck('#' + blocid);
	if (enable == 1) {
		row.addClass('rowckfullwidth').find('.controlFullwidth').addClass('ckactive');
	} else {
		row.removeClass('rowckfullwidth').find('.controlFullwidth').removeClass('ckactive');
	}
}

function ckShowFullwidthRowEdition(blocid) {
	var row = ckGetObjectAnyway(blocid);
	row.addClass('editfocus');
	var myurl = PAGEBUILDERCK.URIPBCK + "&task=interface.load&layout=fullwidth&" + PAGEBUILDERCK.TOKEN;
	$ck.ajax({
		type: "POST",
		url: myurl,
		async: true,
		data: {
		}
	}).done(function(code) {
		$ck('#popup_editionck').empty().append(code).removeClass('ckwait').show();
		$ck('#ckwaitoverlay').remove();
		ckFillEditionPopup(blocid);
		ckLoadPreviewAreaStyles(blocid);
		ckMakeTooltip($ck('#popup_editionck'));
	}).fail(function() {
		alert(Joomla.JText._('CK_FAILED', 'Failed'));
	});
}

function replaceIdsInRow(newrow, addEvents) {
	if (! addEvents) addEvents = false;
	var newrowid = ckGetUniqueId('row_');
	newrow.removeClass('editfocus');
	newrow.find('> .editorck').remove();
	ckReplaceId(newrow, newrowid);

	if (addEvents) ckMakeBlocksSortable(newrow);
	if (addEvents) ckAddRowEditionEvents(newrow);
	newrow.find('.blockck, .cktype').each(function() {
		$this = $ck(this);
		$this.removeClass('editfocus');
		// init the effect if needed
		if ($this.hasClass('cktype') && $this.find('.tabsck').length) {
			if (addEvents) $this.find('.tabsck').tabsck();
		}
		if ($this.hasClass('cktype') && $this.find('.accordionsck').length) {
			if (addEvents) $this.find('.accordionsck').accordionck();
		}
		
		var prefix = '';
		if ($this.hasClass('blockck')) {
			prefix = 'block_';
			if (addEvents) ckMakeItemsSortable(row);
			if (addEvents) ckAddBlockEditionEvents($this);
		} else {
			if (addEvents) ckAddItemEditionEvents($this);
		}
		var copyid = ckGetUniqueId(prefix);
		ckReplaceId($this, copyid);
	});

	return newrow;
}

function ckReplaceId(el, newID) {
	var re = new RegExp(el.attr('id'), 'g');
	if (el.find('> .ckstyle').length) el.find('> .ckstyle').html(el.find('> .ckstyle').html().replace(re,newID));
	if (el.find('> .ckstyleresponsive').length) el.find('> .ckstyleresponsive').html(el.find('> .ckstyleresponsive').html().replace(re,newID));
	el.attr('id', newID);
}

function ckAddContent(block) {
	var id = ckGetUniqueId();
	$ck('.ckfocus').removeClass('ckfocus');
	$ck('.innercontent', block).addClass('ckfocus');
	ckShowContentList();
	// $ck('.innercontent', block).append('<p id="' + id + '">ceci est un test de ced</p>');
}

/*
 * Method to give a random unique ID
 */
function ckGetUniqueId(prefix) {
	if (! prefix) prefix = '';
	var now = new Date().getTime();
	var id = prefix + 'ID' + parseInt(now, 10);

	if ($ck('#' + id).length || CKUNIQUEIDLIST.indexOf(id) != -1)
		id = ckGetUniqueId(prefix);
	CKUNIQUEIDLIST.push(id);

	return id;
}

function ckShowContentList() {
	// $ck(document.body).append('<div id="ck_overlay"></div>');
	$ck('#ck_overlay').fadeIn().click(function() { ckHideContentList() });
	$ck('#ckcontentslist').fadeIn().css('top', $ck(window).scrollTop());
}

function ckHideContentList() {
	$ck('#ck_overlay').fadeOut();
	$ck('#ckcontentslist').fadeOut();
}

function ckShowEditionPopup(blocid, workspace) {
//	if ($ck('#popup_editionck .ckclose').length) {
//		if (! ckConfirmBeforeCloseEditionPopup()) return;
//	}
	ckCloseEdition(1);
	if (!workspace) workspace = ckGetWorkspace();
	blocid = '#' + blocid;
//	$ck(document.body).append('<div id="ckwaitoverlay"></div>');
	bloc = workspace.find(blocid);
	if (! bloc.length) bloc = $ck(blocid);
	$ck('.editfocus').removeClass('editfocus');
	bloc.addClass('editfocus');
	$ck('#popup_editionck').empty().fadeIn().addClass('ckwait');
	if ($ck('#popup_favoriteck').length) {
//		ckCloseFavoritePopup(true);
	}

	var myurl = PAGEBUILDERCK.URIPBCK + "&view=options&layout=edit";
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			cktype: bloc.attr('data-type'),
			ckid: bloc.attr('id')
		}
	}).done(function(code) {
		$ck('#popup_editionck').append(code).removeClass('ckwait');
		$ck('#ckwaitoverlay').remove();

		// manage responsive buttons
		var rangeNumber = ckGetResponsiveRangeNumber();
		if (rangeNumber) {
			var button = $ck('#popup_editionck .ckresponsivebutton[data-range="' + rangeNumber + '"]');
		} else {
			var button = $ck('#popup_editionck .ckresponsivebutton[data-range="5"]');
		}
		$ck('#popup_editionck .cktoolbarResponsive .ckresponsivebutton').removeClass('active').removeClass('ckbutton-warning');
		button.addClass('active').addClass('ckbutton-warning');

		ckInitColorPickers();
		ckInitOptionsTabs();
		ckInitAccordions();
		ckLoadEditionPopup();
		ckLoadPreviewAreaStyles(blocid);
		ckMakeTooltip($ck('#popup_editionck'));
	}).fail(function() {
		alert(Joomla.JText._('CK_FAILED', 'Failed'));
		$ck('#ckwaitoverlay').remove();
	});
}

//function ckConfirmBeforeCloseEditionPopup() {
//	var confirmation = confirm(Joomla.JText._('CK_CONFIRM_BEFORE_CLOSE_EDITION_POPUP', 'A popup edition is already in use. Your changes will not be saved. Confirm ?'));
//
//	return confirmation;
//}

function ckCloseEditionPopup(keepopen) {
	// do nothing, removed in 2.0.5. Keep it for B/C
}

function ckCloseEdition(keepopen) {
	if (! keepopen) keepopen = false;
	if (typeof ckBeforeCloseEditionPopup == 'function') { ckBeforeCloseEditionPopup(); }
	if (! keepopen) $ck('#popup_editionck').empty().fadeOut();
	/*$ck('body').animate({'left':'0', complete: function() {$ck('body').css('position', '')}});
	$ck('.workspaceck').css('margin-left', '');*/
	$ck('.editfocus').removeClass('editfocus');
	$ck('.cktooltip').remove();
}

function ckCreateEditItem(i, itemlist, itemtitle, itemcontent, iconfield, icon, setDefault) {
	if (! iconfield) iconfield = false;
	if (typeof(setDefault) === 'undefined') setDefault = true;
	if (! icon) icon = '';
	var itemedition = $ck('<div class="item_edition clearfix" data-i="' + i + '">'
				+'<div class="item_move"></div>'
				+'<div class="item_title"><input type="text" id="item_title_'+i+'" name="item_title_'+i+'" class="item_title_edition" value="" onchange="ckUpdatePreviewArea()"/></div>'
				+ (iconfield ? ckCreateEditItemIconSelection(i, icon) : '')
				+'<div class="item_toggler">'+Joomla.JText._('CK_CLICK_TO_EDIT_CONTENT','Click to edit the content')+'</div>'
				+'<div class="item_content"><textarea id="item_content_'+i+'" name="item_title_'+i+'" class="item_content_edition" onchange="ckUpdatePreviewArea()"></textarea></div>'
				+'<div class="item_delete btn-small btn btn-danger" onclick="ckDeleteEditItem($ck(this).parent())">'+Joomla.JText._('CK_DELETE','Delete')+'</div>'
				+(setDefault ? '&nbsp;<div class="item_setdefault btn-small btn" onclick="ckSetDefaultEditItem($ck(this).parent())"><span class="icon icon-star"></span>'+Joomla.JText._('CK_SET_DEFAULT','Set as default')+'</div>' : '')
				+'</div>');
	itemlist.append(itemedition);
	itemedition.find('.item_title_edition').val(itemtitle);
	itemedition.find('.item_content_edition').val(itemcontent);

	return itemedition;
}

function ckCreateEditItemIconSelection(i, icon) {
	var html = '<span>'
					+'<a href="javascript:void(0)" onclick="ckFocusOnField(\'#item_icon_'+i+'\');CKBox.open({id: \'pagebuilderckiconselect\', handler: \'iframe\', url: \'' + PAGEBUILDERCK.URIPBCK + '&view=icons\'});" class="ckbuttonstyle">' + Joomla.JText._('CK_SELECT') + '</a>'
					+'<a href="javascript:void(0)" onclick="$ck(\'#item_icon_'+i+'\').val(\'\');ckRemoveIconForItem(this)" class="ckbuttonstyle">' + Joomla.JText._('CK_CLEAN') + '</a>'
					+'<input type="text" id="item_icon_'+i+'" name="item_icon_'+i+'" class="item_icon_edition" placeholder="Ex : fa fa-eye" onchange="this.className+=\' ckfieldfocus\';ckSelectFaIcon(this.value)" />'
					+'<span class="iconck">' + (icon ? icon : '') +'</span>'
				+'</span>';
	return html;
}

function ckRemoveIconForItem(btn) {
	$ck(btn).parent().find('.iconck i').remove();
}

function ckCreateEditImageItem(i, itemlist, itemtitle, itemcontent, itemimg) {
	var itemedition = $ck('<div class="item_edition clearfix" data_index="'+i+'">'
				+'<div class="item_move"></div>'
				+'<div class="item_image">'
				+'<a class="item_image_selection" href="javascript:void(0)" onclick="ckCallImageManagerPopup(\'item_imageurl'+i+'\')" >'
				+'<img src="'+itemimg.attr('src')+'" />'
				+'</a>'
				+'</div>'
				+'<div class="item_imageurl"><input type="text" id="item_imageurl'+i+'" name="item_imageurl'+i+'" class="item_imageurl_edition" value="'+getImgPathFromImgSrc(itemimg.attr('src'))+'" style="width: 400px;" onchange="ckUpdatePreviewArea()"/></div>'
				+'<div class="item_title"><input type="text" id="item_title_'+i+'" name="item_title_'+i+'" class="item_title_edition" value="'+itemtitle+'" style="width: 400px;" onchange="ckUpdatePreviewArea()"/></div>'
				+'<div class="item_toggler">'+Joomla.JText._('CK_CLICK_TO_EDIT_CONTENT','Click to edit the content')+'</div>'
				+'<div class="item_content"><textarea id="item_content_'+i+'" name="item_title_'+i+'" class="item_content_edition" onchange="ckUpdatePreviewArea()">'+itemcontent+'</textarea></div>'
				+'<br />'
				+'<div class="item_delete btn-small btn btn-danger" onclick="ckDeleteEditItem($ck(this).parent())">'+Joomla.JText._('CK_DELETE','Delete')+'</div>'
				// +'&nbsp;<div class="item_setdefault btn-small btn" onclick="ckSetDefaultEditItem($ck(this).parent())"><span class="icon icon-star"></span>'+Joomla.JText._('CK_SET_DEFAULT','Set as default')+'</div>'
				+'<div class="item_close ckbutton">×</div>'
				+'<div class="item_overlay"></div>'
				+'</div>');
	itemlist.append(itemedition);

	return itemedition;
}

function ckCreateEditAdvancedItem(i, itemlist, itemtitle, itemcontentId) {
	var itemedition = $ck('<div class="item_edition clearfix">'
				+'<div class="item_move"></div>'
				+'<div class="item_title"><input type="text" id="item_title_'+i+'" name="item_title_'+i+'" class="item_title_edition" value="'+itemtitle+'" onchange="ckUpdatePreviewArea()"/></div>'
				+'<div class="item_toggler"><a href="javascript:void(0)" onclick="CKBox.open({handler:\'inline\', fullscreen: true, content:\'\', id: \'ckadvanceditembox\', onCKBoxLoaded: function() {ckInitOnBoxLoaded(\'ckadvanceditembox\', \''+itemcontentId+'\')}})">'+Joomla.JText._('CK_CLICK_TO_EDIT_CONTENT','Click to edit the content')+'</a></div>'
				// +'<div class="item_content"><a href="">'++'</a></div>'
				+'<div class="item_delete btn-small btn btn-danger" onclick="ckDeleteEditItem($ck(this).parent())">'+Joomla.JText._('CK_DELETE','Delete')+'</div>'
				+'&nbsp;<div class="item_setdefault btn-small btn" onclick="ckSetDefaultEditItem($ck(this).parent())"><span class="icon icon-star"></span>'+Joomla.JText._('CK_SET_DEFAULT','Set as default')+'</div>'
				+'</div>');
	itemlist.append(itemedition);

	return itemedition;
}

/* empty function callback to fill in each edition area */
function ckInitOnBoxLoaded(boxid, itemcontentId) {
	return;
}

function getImgPathFromImgSrc(imgsrc, full) {
	if (! imgsrc) return imgsrc;
	if (! full) full = false;

	if (imgsrc.indexOf('http') == 0) return imgsrc;

	if (PAGEBUILDERCK.URIROOT != '/' && PAGEBUILDERCK.URIROOT && imgsrc.substr(0, PAGEBUILDERCK.URIROOT.length) == PAGEBUILDERCK.URIROOT) imgsrc = imgsrc.replace(PAGEBUILDERCK.URIROOT+'/','').replace(PAGEBUILDERCK.URIROOT,'');

	while(imgsrc.charAt(0) === '/')
		imgsrc = imgsrc.substr(1);

	if (full) imgsrc = PAGEBUILDERCK.URIROOT + '/' + imgsrc;

	return imgsrc;
}

function ckMakeEditItemAccordion(el) {
	$ck(el).accordion({
		header: ".item_toggler",
		collapsible: true,
		active: false,
		heightStyle: "content"
	});
}

function ckSetDefaultEditItem(item) {
	alert('ERROR : If you see this message then the function "ckSetDefaultEditItem" is missing from the element edition. Please contact the developer');
}

function ckSaveInlineEditionPopup() {
	alert('ERROR : If you see this message then the function "ckSaveInlineEditionPopup" is missing from the element edition. Please contact the developer');
}

function ckCancelInlineEditionPopup(btn) {
	$ck(btn).parent().fadeOut();
	// can be overridden in the element edition
//	ckCloseEditionPopup();
}

function ckDeleteEditItem(item) {
	if (item.parent().children().length <= 1) {
		alert(Joomla.JText._('CK_CAN_NOT_DELETE_LAST','You can not delete the last item'));
		return;
	}
	if (!confirm(Joomla.JText._('CK_CONFIRM_DELETE','CK_CONFIRM_DELETE'))) return;
	if (typeof ckBeforeDeleteEditItem == 'function') { ckBeforeDeleteEditItem(item); }
	item.remove();
	if (typeof ck_after_delete_edit_item == 'function') { ck_after_delete_edit_item(); }
	ckSaveAction();
}

function ckShowCssPopup(blocid, savefunc) {
//	if ($ck('#popup_editionck .ckclose').length) {
//		if (! ckConfirmBeforeCloseEditionPopup()) return;
//	}
//	if ($ck('.editfocus').length) ckSaveEdition();
//	if (! savefunc) savefunc = 'ckSaveEditionPopup';
	blocid = '#' + blocid;
	// $ck(document.body).append('<div id="ckwaitoverlay"></div>');
	bloc = $ck(blocid);
	$ck('.editfocus').removeClass('editfocus');
	bloc.addClass('editfocus');
	$ck('#popup_editionck').empty().fadeIn().addClass('ckwait');
	/*$ck('body').css('position', 'relative').animate({'left':'310px'});
	$ck('.workspaceck').css('margin-left', '0');*/
	// $ck('html, body').animate({scrollTop: 0}, 'slow');
	if ($ck('#popup_favoriteck').length) {
//		ckCloseFavoritePopup(true);
	}

	var myurl = PAGEBUILDERCK.URIPBCK + "&task=interface.load&layout=stylescss&" + PAGEBUILDERCK.TOKEN;
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			objclass: bloc.prop('class'),
			expertmode: $ck('#body').hasClass('expert'),
			savefunc: savefunc,
			ckobjid: bloc.prop('id')
		}
	}).done(function(code) {
		$ck('#popup_editionck').append(code).removeClass('ckwait');
		$ck('#ckwaitoverlay').remove();
		ckFillEditionPopup(blocid);
		ckLoadPreviewAreaStyles(blocid);
		ckMakeTooltip($ck('#popup_editionck'));
	}).fail(function() {
		alert(Joomla.JText._('CK_FAILED', 'Failed'));
		$ck('#ckwaitoverlay').remove();
	});
}

function ckFillEditionPopup(blocid, workspace, responsiverange) {
	// blocid = blocid.test('#') ? blocid : '#' + blocid;
	var patt = new RegExp("#");
	var res = patt.test(blocid);
	blocid = res ? blocid : '#' + blocid;

	if (!workspace) workspace = ckGetWorkspace();
	if (!responsiverange) responsiverange = ckGetResponsiveRangeNumber();

	var bloc = workspace.find(blocid);
	if (! bloc.length) bloc = $ck(blocid);
	var responsivesuffix = responsiverange ? '.ckresponsive' + responsiverange : ':not(.ckresponsive)';

	// clear color fields
	$ck('.colorPicker').each(function() {
		field = $ck(this);
		field.css('background-color', '');
		var prefix = field.attr('id').replace("backgroundcolorend", "");
		if (prefix)
			ckCreateGradientPreview(prefix);
	});

	$ck('> .ckprops' + responsivesuffix, bloc).each(function(i, ckprops) {
		ckprops = $ck(ckprops);
		fieldslist = ckprops.attr('fieldslist') ? ckprops.attr('fieldslist').split(',') : Array();
		// fieldslist.each(function(fieldname) { 
		// for (var fieldname of fieldslist) {
		for (j=0;j<fieldslist.length;j++) {
			fieldname = fieldslist[j];
			if (!$ck('#' + fieldname).length)
				return;
			cssvalue = ckprops.attr(fieldname);
			field = $ck('#' + fieldname);
			if (field.attr('type') == 'radio' || field.attr('type') == 'checkbox') {
				if (cssvalue == 'checked') {
					field.prop('checked', 'checked');
				} else {
					field.removeProp('checked');
				}
			} else if (cssvalue) {
				if (field.attr('multiple')) cssvalue = cssvalue.split(',');
				field.val(cssvalue);
				if (field.hasClass('colorPicker') && field.val()) {
					setpickercolor(field);
					field.css('background-color', field.val());
					if (field.attr('id').indexOf('backgroundcolorend') != -1) {
						prefix = field.attr('id').replace("backgroundcolorend", "");
						if (prefix && $ck('#blocbackgroundcolorstart').val())
							ckCreateGradientPreview(prefix);
					}
					if (field.attr('id').indexOf('backgroundcolorstart') != -1) {
						prefix = field.attr('id').replace("backgroundcolorstart", "");
						if (prefix && $ck('#blocbackgroundcolorstart').val())
							ckCreateGradientPreview(prefix);
					}
				}
			} else {
				field.val('');
			}
		// });
		}
	});
	
	$ck('.ckrangeinputupdate').each(function() {ckSetRrangeInputText(this); });
	if ($ck('#iconicontype').val() == 'svg') {
		$ck('#fontawesomefieldset,#fontawesomefieldsetoptions').hide();
		$ck('#iconsvgfieldset').show();
		$ck('#iconicon-class').hide();
	} else {
		$ck('#fontawesomefieldset,#fontawesomefieldsetoptions').show();
		$ck('#iconsvgfieldset').hide();
		$ck('#iconicon-class').show();
	}
}

function ckLoadPreviewAreaStyles(blocid) {
	bloc = $ck(blocid);
	var blocstyles = $ck('> .ckstyle', bloc).text();
	var replacement = new RegExp(blocid, 'g');
	var previewstyles = blocstyles.replace(replacement, '#previewareabloc'); // /blue/g,"red"
	var editionarea = $ck('#popup_editionck');
	$ck('> .ckstyle', $ck('#previewareabloc')).html('<style type="text/css">'+previewstyles+'</style>');
	ckAddEventOnFields(editionarea, blocid);
}

function ckAddEventOnFields(editionarea, blocid) {
	if (!editionarea) editionarea = $ck('#popup_editionck');
	var rangeNumber = ckGetResponsiveRangeNumber();
	if (rangeNumber == '5' || ! rangeNumber) {
		$ck('.inputbox:not(.colorPicker):not(.cknoupdate)', editionarea).off('change').on('change', function() {
			ckGetPreviewAreastylescss('previewareabloc', editionarea, blocid);
		});
		$ck('.colorPicker,.inputbox[type=radio]', editionarea).off('blur').on('blur', function() {
			ckGetPreviewAreastylescss('previewareabloc', editionarea, blocid);
		});
		$ck('.inputbox.cknoupdate', editionarea).mouseup(function() {
			ckGetPreviewAreastylescss('previewareabloc', editionarea, blocid);
		});
	} else {
		$ck('.inputbox:not(.colorPicker):not(.cknoupdate)', editionarea).off('change').on('change', function() {
			ckRenderResponsiveCss();
		});
		$ck('.colorPicker,.inputbox[type=radio]', editionarea).off('blur').on('blur', function() {
			ckRenderResponsiveCss();
		});
		$ck('.inputbox.cknoupdate', editionarea).mouseup(function() {
			ckRenderResponsiveCss();
		});
	}
}

function ckFocusWorkspaceFrom(focus) {
	if (focus.hasClass('workspaceck')) return;
	if (! focus.length) return;
	$ck('.workspaceck').removeAttr('id');
	$ck(focus.parents('.workspaceck')[0]).attr('id', 'workspaceck');
}

function ckGetPreviewAreastylescss(blocid, editionarea, focus, forpreviewarea, returnFunc, doClose) {
	if (! returnFunc) returnFunc = '';
	if (! doClose) doClose = false;
	ckAddSpinnerIcon($ck('.headerckicon.cksave'));
	if (!editionarea)
		editionarea = document.body;
	if (!focus) {
		focus = $ck('.editfocus');
	} else {
		focus = ckGetObjectAnyway(focus);
	}
	ckFocusWorkspaceFrom(focus);
	if (! forpreviewarea) forpreviewarea = false;
	if (focus.attr('data-previewedition') == '1' || focus.attr('data-type') == 'table') {
		forpreviewarea = true; // needed for preview area edition like in table item
	}
	blocid = forpreviewarea ? blocid : focus.attr('id');

	var fieldslist = new Array();
	fields = new Object();
	$ck('.inputbox', editionarea).each(function(i, el) {
		el = $ck(el);
		fields[el.attr('name')] = el.val();
		if (el.attr('type') == 'radio' || el.attr('type') == 'checkbox') {
			fields[el.attr('name')] = $ck('[name="' + el.attr('name') + '"]:checked').val();
			if (el.prop('checked')) {
				fields[el.attr('id')] = 'checked';
			} else {
				fields[el.attr('id')] = '';
			}
		}
	});
	$ck('> .ckprops', focus).each(function(i, ckprops) {
		ckprops = $ck(ckprops);
		fieldslist = ckprops.attr('fieldslist') ? ckprops.attr('fieldslist').split(',') : Array();
		// fieldslist.each(function(fieldname) {
		// for (var fieldname of fieldslist) {
		for (j=0;j<fieldslist.length;j++) {
			fieldname = fieldslist[j];
			if (typeof(fields[fieldname]) == 'null') 
				fields[fieldname] = ckprops.attr(fieldname);
		// });
		}
	});
	fields = JSON.stringify(fields);
	var customstyles = new Object();
	$ck('.menustylescustom').each(function() {
		$this = $ck(this);
		customstyles[$this.attr('data-prefix')] = $this.attr('data-rule');
	});
	customstyles = JSON.stringify(customstyles);
	ckSaveEdition(blocid); // save fields before ajax to keep sequential/logical steps
	var myurl = PAGEBUILDERCK.URIPBCK + "&task=interface.load&layout=rendercss&" + PAGEBUILDERCK.TOKEN;
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			objclass: focus.prop('class'),
			ckobjid: blocid,
			action: 'preview',
			customstyles: customstyles,
			fields: fields
		}
	}).done(function(code) {
		// clean the styles if we are in the style edition
		if (workspace.hasClass('ckstyleedition')) {
			var re = new RegExp('#' + blocid, 'g');
			var focusType = '';
			if (focus.hasClass('rowck')) {
				focusType = 'rowck';
			} else if (focus.hasClass('blockck')) {
				focusType = 'blockck';
			} else if (focus.hasClass('cktype')) {
				focusType = 'cktype[data-type="' + focus.attr('data-type') + '"]';
			}
			code = code.replace(re, '.' + focusType);
		}
		if (forpreviewarea || workspace.hasClass('ckcontenttypeedition')) $ck('> .ckstyle', $ck('#' + blocid)).empty().append(code);
		$ck('> .ckstyle', $ck('.workspaceck #' + blocid + ', #ckelementscontentfavorites #' + blocid)).empty().append(code);
		ckRemoveSpinnerIcon($ck('.headerckicon.cksave'));
		if (typeof(window[returnFunc]) == 'function') window[returnFunc]();
		ckAfterSaveEditionPopup();
		if (doClose == true) ckCloseEdition();
//		ckSaveAction();
	}).fail(function() {
		alert(Joomla.JText._('CK_FAILED', 'Failed'));
	});
	ckUpdateShapeDivider();
}

function ckAddSpinnerIcon(btn) {
	if (! btn.attr('data-class')) var icon = btn.find('.fa').attr('class');
	btn.attr('data-class', icon).find('.fa').attr('class', 'fa fa-spinner fa-pulse');
}

function ckRemoveSpinnerIcon(btn) {
	var t = setTimeout( function() {
		btn.find('.fa').attr('class', btn.attr('data-class'));
	}, 500);
}

function ckBeforeSaveEditionPopup() {
//	if (! blocid) blocid = '';
//	if (! returnFunc) returnFunc = '';
//	if (! workspace) workspace = ckGetWorkspace();
//	ckSaveEditionPopup(blocid, workspace, returnFunc)
}

function ckAfterSaveEditionPopup() {
	
}

function ckSaveEditionPopup(blocid, workspace, returnFunc, genCss) {
	// do nothing, function removed in 2.0.5
}

function ckSaveEdition(blocid, workspace, returnFunc, genCss) {
//	if ($ck('.editfocus.cktype').length) 
		ckBeforeSaveEditionPopup();
	if (! returnFunc) returnFunc = '';
	if (! genCss) genCss = ''; // needed for element like table where we have a preview area in the edition
	if (!workspace) workspace = ckGetWorkspace();

	var editionarea = $ck('#popup_editionck');
	var focus = blocid ? workspace.find('#' + blocid) : workspace.find('.editfocus');
	if (! focus.length) focus = blocid ? $ck('#' + blocid) : $ck('.editfocus');

	$ck('> .ckprops:not(.ckresponsive)', focus).remove();
	$ck('.ckproperty', editionarea).each(function(i, tab) {
		tab = $ck(tab);
		tabid = tab.attr('id');
		(!$ck('> .' + tabid, focus).length) ? ckCreateFocusProperty(focus, tabid) : $ck('> .' + tabid, focus).empty();
		
		focusprop = $ck('> .' + tabid, focus);
		ckSavePopupfields(focusprop, tabid);
		fieldslist = ckGetPopupFieldslist(focus, tabid);
		focusprop.attr('fieldslist', fieldslist);
	});
	if (focus.hasClass('wrapper') && $ck('> .tab_blocstyles', focus).attr('blocfullwidth') == 1) {
		$ck('> .inner', focus).removeClass('container').removeClass('container-fluid');
	} else if (focus.hasClass('wrapper')) {
		$ck('> .inner', focus).addClass('container');
	}

	if (genCss) ckGetPreviewstylescss(blocid, editionarea, workspace, returnFunc);
	ckSetAnimations(blocid, editionarea);
	ckAddVideoBackground(bloc);

//	ckCloseEditionPopup();
	if (typeof(window[returnFunc]) == 'function') window[returnFunc]();
	ckSaveAction();
}

//function ckPreviewVideoBackground() {
//	var webmurl = $ck('#blocvideourlwebm').val().replace(PAGEBUILDERCK.URIROOT,'');
//	var mp4url = $ck('#blocvideourlmp4').val().replace(PAGEBUILDERCK.URIROOT,'');
//	var ogvurl = $ck('#blocvideourlogv').val().replace(PAGEBUILDERCK.URIROOT,'');
//	var videocode = ckGetVideoBackgroundCode(webmurl, mp4url, ogvurl);
//
//	var previewarea = $ck('#previewareabloc > .inner');
//	if (previewarea.find('.videockbackground').length) previewarea.find('.videockbackground').remove();
//	previewarea.css('position', 'relative').css('overflow','hidden').prepend(videocode);
//}

function ckAddVideoBackground(bloc) {
	var webmurl = bloc.find('.tab_videobgstyles').attr('blocvideourlwebm');
	var mp4url = bloc.find('.tab_videobgstyles').attr('blocvideourlmp4');
	var ogvurl = bloc.find('.tab_videobgstyles').attr('blocvideourlogv');
	if (bloc.find('> .tab_videobgstyles').length
			&& (
				webmurl
				|| mp4url
				|| ogvurl
			)
		) {
		var videocode = ckGetVideoBackgroundCode(webmurl, mp4url, ogvurl);

		bloc.addClass('hasvideockbackground');
		bloc.find('.videockbackground').remove();
		bloc.find('> .inner').css('position', 'relative').css('overflow','hidden').prepend(videocode);
		return;
	} else {
		bloc.removeClass('hasvideockbackground');
		if (bloc.find('.videockbackground').length) {
			bloc.find('> .inner').css('overflow','').find('.videockbackground').remove();
		}
	}
	ckSaveAction();
	return;
}

function ckGetVideoBackgroundCode(webmurl, mp4url, ogvurl) {
	var videocode = '<video autoplay loop muted poster="" class="videockbackground">'
							+ (webmurl ? '<source src="'+PAGEBUILDERCK.URIROOT+'/'+webmurl+'" type="video/webm">' : '')
							+ (mp4url ? '<source src="'+PAGEBUILDERCK.URIROOT+'/'+mp4url+'" type="video/mp4">' : '')
							+ (ogvurl ? '<source src="'+PAGEBUILDERCK.URIROOT+'/'+ogvurl+'" type="video/ogg">' : '')
						+'</video>';
	return videocode;
}

function ckCheckVideoBackground(bloc) {
	if (bloc.find('> .tab_videobgstyles') 
			&& (
				bloc.find('> .tab_videobgstyles').attr('blocvideourlmp4')
				|| bloc.find('> .tab_videobgstyles').attr('blocvideourlwebm')
				|| bloc.find('> .tab_videobgstyles').attr('blocvideourlogv')
			)
		)
			return true
	return false;
}

function ckSetAnimations(blocid, editionarea) {
	var editionarea = editionarea ? editionarea : $ck('#popup_editionck');
	var focus = blocid ? $ck('#' + blocid) : $ck('.editfocus');
	// replay
	if ($ck('[name="blocanimreplay"]:checked', editionarea).val() == '0') {
		focus.addClass('noreplayck');
	} else {
		focus.removeClass('noreplayck');
	}
}

function ckCreateFocusProperty(focus, tabid) {
	focus.prepend('<div class="' + tabid + ' ckprops" />')
}

function ckSavePopupfields(focusprop, tabid) {
	$ck('.inputbox', $ck('#' + tabid)).each(function(i, field) {
		field = $ck(field);
		if (field.attr('type') != 'radio' && field.attr('type') != 'checkbox') {
			if (field.val() && field.val() != 'default') {
				focusprop.attr(field.attr('id'), field.val());
			} else {
				focusprop.removeAttr(field.attr('id'));
			}
		} else {
			if (field.prop('checked')) {
				focusprop.attr(field.attr('id'), 'checked');
			} else {
				focusprop.removeAttr(field.attr('id'));
			}
		}
		if (field.hasClass('isgooglefont') && field.val() != '') {
			ckSetGoogleFont('', '', field.val(), '');
		}
	});
}

function ckCapitalize(s) {
    return s[0].toUpperCase() + s.slice(1);
}

function ckSetGoogleFont(prefix, fonturl, fontname, fontweight) {
	if (! fontname) return;
	fontname = ckCapitalize(fontname).trim("'");
	if (! fonturl) fonturl = "https://fonts.googleapis.com/css?family="+fontname.replace(' ', '+');
	if (! fontweight) fontweight = $ck('#' + prefix + 'fontweight').val();
	// check if the google font exists
	$ck.ajax({
		url: fonturl,
	})
	.done(function( data ) {
		if (data) {
			if (prefix) {
				$ck('#' + prefix + 'googlefont').removeClass('invalid');
				$ck('#' + prefix + 'googlefont').val(fontname);
				$ck('#' + prefix + 'fontweight').val(fontweight);
				$ck('#' + prefix + 'fontfamily').val('googlefont').trigger('change');
			}
			ckAddGooglefontStylesheet(fonturl);
		} else {
			$ck('#' + prefix + 'googlefont').addClass('invalid');
		}
	})
	.fail(function() {
		$ck('#' + prefix + 'googlefont').addClass('invalid');
	});
}

function ckAddGooglefontStylesheet(fonturl) {
	var exist = false;
	// loop for retrocompatibility before 1.1.13
	while ($ck('#googlefontscall').length) {
		$ck('#googlefontscall').addClass('googlefontscall').removeAttr('id');
	}

	$ck('.googlefontscall link').each(function(i, sheet) {
		if ($ck(sheet).attr('href') == fonturl) exist = true;
	});
	if (exist == false ) {
		$ck('.googlefontscall').append("<link href='"+fonturl+"' rel='stylesheet' type='text/css'>");
	}
}

function ckGetPopupFieldslist(focus, tabid) {
	fieldslist = new Array();
	$ck('.inputbox', $ck('#' + tabid)).each(function(i, el) {
		if ($ck(el).val() && $ck(el).val() != 'default')
			fieldslist.push($ck(el).attr('id'));
	});
	if (tabid == 'tab_blocstyles' && (focus.hasClass('bannerlogo') || focus.hasClass('banner') || focus.hasClass('bannermenu')) )
		fieldslist.push('blocwidth');
	return fieldslist.join(',');
}

function ckGetPreviewstylescss(blocid, editionarea, workspace, returnFunc) {
	if (!editionarea) editionarea = document.body;
	if (!workspace) workspace = ckGetWorkspace();

	var focus = blocid ? workspace.find('#' + blocid) : workspace.find('.editfocus');
	if (! focus.length) focus = blocid ? $ck('#' + blocid) : $ck('.editfocus');

	var fieldslist = new Array();
	$ck('.inputbox', editionarea).each(function(i, el) {
		if ($ck(el).val())
			fieldslist.push($ck(el).attr('id'));
	});
	fields = new Object();
	$ck('> .ckprops', focus).each(function(i, ckprops) {
		ckprops = $ck(ckprops);
		fieldslist = ckprops.attr('fieldslist') ? ckprops.attr('fieldslist').split(',') : Array();
		// fieldslist.each(function(fieldname) {
		// for (var fieldname of fieldslist) {
		for (j=0;j<fieldslist.length;j++) {
			fieldname = fieldslist[j];
			fields[fieldname] = ckprops.attr(fieldname);
		}
		// });
	});
	fields = JSON.stringify(fields);
	customstyles = new Object();
	$ck('.menustylescustom').each(function() {
		$this = $ck(this);
		customstyles[$this.attr('data-prefix')] = $this.attr('data-rule');
	});
	customstyles = JSON.stringify(customstyles);
	var myurl = PAGEBUILDERCK.URIPBCK + "&view=page&layout=ajaxrendercss";
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			objclass: focus.prop('class'),
			ckobjid: focus.prop('id'),
			action: 'preview',
			customstyles: customstyles,
			fields: fields
		}
	}).done(function(code) {
		$ck('> .ckstyle', focus).empty().append(code);
		if (BLOCCKSTYLESBACKUP != 'undefined') {
			BLOCCKSTYLESBACKUP = $ck('> .ckstyle', focus).html();
		}

		if (typeof(window[returnFunc]) == 'function') window[returnFunc]();
		ckSaveAction();
	}).fail(function() {
		alert(Joomla.JText._('CK_FAILED', 'Failed'));
	});
}

function ckCreateGradientPreview(prefix) {
	if (!$ck('#'+prefix + 'gradientpreview'))
		return;
	var area = $ck('#'+prefix + 'gradientpreview');
	if ($ck('#'+prefix + 'backgroundcolorstart') && $ck('#'+prefix + 'backgroundcolorstart').val()) {
		$ck('#'+prefix + 'backgroundcolorend').removeAttr('disabled');
		$ck('#'+prefix + 'backgroundpositionend').removeAttr('disabled');
	} else {
		$ck('#'+prefix + 'backgroundcolorend').attr({'disabled': 'disabled', 'value': ''});
		$ck('#'+prefix + 'backgroundcolorend').css('background-color', '');
		$ck('#'+prefix + 'backgroundpositionend').attr({'disabled': 'disabled', 'value': '100'});
	}
	if ($ck('#'+prefix + 'backgroundcolorend') && $ck('#'+prefix + 'backgroundcolorend').val()) {
		$ck('#'+prefix + 'backgroundcolorstop1').removeAttr('disabled');
		$ck('#'+prefix + 'backgroundpositionstop1').removeAttr('disabled');
		$ck('#'+prefix + 'backgroundopacity').attr({'disabled': 'disabled', 'value': ''});
	} else {
		$ck('#'+prefix + 'backgroundcolorstop1').attr({'disabled': 'disabled', 'value': ''});
		$ck('#'+prefix + 'backgroundcolorstop1').css('background-color', '');
		$ck('#'+prefix + 'backgroundpositionstop1').attr({'disabled': 'disabled', 'value': ''});
		$ck('#'+prefix + 'backgroundopacity').removeAttr('disabled');
	}
	if ($ck('#'+prefix + 'backgroundcolorstop1') && $ck('#'+prefix + 'backgroundcolorstop1').val()) {
		$ck('#'+prefix + 'backgroundcolorstop2').removeAttr('disabled');
		$ck('#'+prefix + 'backgroundpositionstop2').removeAttr('disabled');
	} else {
		$ck('#'+prefix + 'backgroundcolorstop2').attr({'disabled': 'disabled', 'value': ''});
		$ck('#'+prefix + 'backgroundcolorstop2').css('background-color', '');
		$ck('#'+prefix + 'backgroundpositionstop2').attr({'disabled': 'disabled', 'value': ''});
	}

	var gradientstop1 = '';
	var gradientstop2 = '';
	var gradientend = '';
	var gradientpositionstop1 = '';
	var gradientpositionstop2 = '';
	var gradientpositionend = '';
	if ($ck('#'+prefix + 'backgroundpositionstop1') && $ck('#'+prefix + 'backgroundpositionstop1').val())
		gradientpositionstop1 = $ck('#'+prefix + 'backgroundpositionstop1').val() + '%';
	if ($ck('#'+prefix + 'backgroundpositionstop2') && $ck('#'+prefix + 'backgroundpositionstop2').val())
		gradientpositionstop2 = $ck('#'+prefix + 'backgroundpositionstop2').val() + '%';
	if ($ck('#'+prefix + 'backgroundpositionstop3') && $ck('#'+prefix + 'backgroundpositionend').val())
		gradientpositionend = $ck('#'+prefix + 'backgroundpositionend').val() + '%';
	if ($ck('#'+prefix + 'backgroundcolorstop1') && $ck('#'+prefix + 'backgroundcolorstop1').val())
		gradientstop1 = $ck('#'+prefix + 'backgroundcolorstop1').val() + ' ' + gradientpositionstop1 + ',';
	if ($ck('#'+prefix + 'backgroundcolorstop2') && $ck('#'+prefix + 'backgroundcolorstop2').val())
		gradientstop2 = $ck('#'+prefix + 'backgroundcolorstop2').val() + ' ' + gradientpositionstop2 + ',';
	if ($ck('#'+prefix + 'backgroundcolorend') && $ck('#'+prefix + 'backgroundcolorend').val())
		gradientend = $ck('#'+prefix + 'backgroundcolorend').val() + ' ' + gradientpositionend;
	var stylecode = '<style type="text/css">'
			+ '#' + prefix + 'gradientpreview {'
			+ 'background:' + $ck('#'+prefix + 'backgroundcolorstart').val() + ';'
			+ 'background-image: -o-linear-gradient(top,' + $ck('#'+prefix + 'backgroundcolorstart').val() + ',' + gradientstop1 + gradientstop2 + gradientend + ');'
			+ 'background-image: -webkit-linear-gradient(top,' + $ck('#'+prefix + 'backgroundcolorstart').val() + ',' + gradientstop1 + gradientstop2 + gradientend + ');'
			+ 'background-image: -webkit-gradient(linear, left top, left bottom,' + $ck('#'+prefix + 'backgroundcolorstart').val() + ',' + gradientstop1 + gradientstop2 + gradientend + ');'
			+ 'background-image: -moz-linear-gradient(top,' + $ck('#'+prefix + 'backgroundcolorstart').val() + ',' + gradientstop1 + gradientstop2 + gradientend + ');'
			+ 'background-image: -ms-linear-gradient(top,' + $ck('#'+prefix + 'backgroundcolorstart').val() + ',' + gradientstop1 + gradientstop2 + gradientend + ');'
			+ 'background-image: linear-gradient(top,' + $ck('#'+prefix + 'backgroundcolorstart').val() + ',' + gradientstop1 + gradientstop2 + gradientend + ');'
			+ '}'
			+ '</style>';
	area.find('.injectstyles').html(stylecode);
}

function ckInitIconSize(fromicon, iconsizebutton) {
	$ck(iconsizebutton).each(function() {
		$ck(this).click(function() {
			$ck(fromicon).removeClass($ck(iconsizebutton + '.active').attr('data-width')).addClass($ck(this).attr('data-width'));
			$ck(iconsizebutton).removeClass('active');
			$ck(this).addClass('active');
		});
	});
}

function ckGetIconSize(fromicon, iconsizebutton) {
	var iconsize = 'default';
	var icon = $ck(fromicon);
	iconsize = icon.hasClass('fa-lg') ? 'fa-lg' : iconsize;
	iconsize = icon.hasClass('fa-2x') ? 'fa-2x' : iconsize;
	iconsize = icon.hasClass('fa-3x') ? 'fa-3x' : iconsize;
	iconsize = icon.hasClass('fa-4x') ? 'fa-4x' : iconsize;
	iconsize = icon.hasClass('fa-5x') ? 'fa-5x' : iconsize;
	$ck(iconsizebutton).removeClass('active');
	$ck(iconsizebutton + '[data-width="' + iconsize + '"]').addClass('active');
}

function ckInitIconPosition(fromicon, iconpositionbutton) {
	$ck(iconpositionbutton).each(function() {
		$ck(this).click(function() {
			if ($ck(this).attr('data-position') == 'default') {
				$ck(fromicon).css('vertical-align', '');
			} else {
				$ck(fromicon).css('vertical-align', $ck(this).attr('data-position'));
			}
			$ck(iconpositionbutton).removeClass('active');
			$ck(this).addClass('active');
		});
	});
}

function ckGetIconPosition(fromicon, iconpositionbutton) {
	var iconposition = 'default';
	var icon = $ck(fromicon);
	iconposition = icon.css('vertical-align') == 'default' ? 'default' : iconposition;
	iconposition = icon.css('vertical-align') == 'top' ? 'top' : iconposition;
	iconposition = icon.css('vertical-align') == 'middle' ? 'middle' : iconposition;
	iconposition = icon.css('vertical-align') == 'botom' ? 'bottom' : iconposition;

	$ck(iconpositionbutton).removeClass('active');
	$ck(iconpositionbutton + '[data-position="' + iconposition + '"]').addClass('active');
}

function ckGetIconMargin(fromicon, iconmarginfield) {
	$ck(iconmarginfield).val($ck(fromicon).css('margin-right'));
}

function ckSetIconMargin(fromicon, iconmarginfield) {
	if (! $ck(iconmarginfield).length) return;
	var margin = $ck(iconmarginfield).val();
	var pourcent = new RegExp('%',"g");
	var euem = new RegExp('em',"g");
	var pixel = new RegExp('px',"g");

	margin = pourcent.test(margin) ? margin : (euem.test(margin) ? margin : (pixel.test(margin) ? margin : margin + 'px'));
	$ck(fromicon).css('margin-right', margin);
	ckSaveAction();
}

function ckSelectFaIcon(iconclass) {
	var icon = '<i class="pbckicon ' + iconclass + '" data-iconclass="' + iconclass + '"></i>';
	var field = $ck('.ckfieldfocus');
	field.val(iconclass);

	$ck('#iconicon-class').val(iconclass);
	$ck('#fontawesomefieldset').show();
	$ck('#fontawesomefieldsetoptions').show();
	$ck('#iconicon-class').show();
	$ck('#iconsvgfieldset').hide();
	$ck('#iconicontype').val('fa');

	ckSelectIcon(icon);
	$ck('.editfocus .iconck .pbckicon').css('vertical-align', $ck('#iconicon-position button.active').attr('data-position'))
		.addClass($ck('#iconicon-size button.active').attr('data-width'));
	ckSetIconMargin('.editfocus .iconck i.fa', '#iconicon_margin');
}

//function ckSelectIcon(iconclass) {
//	alert('ERROR : If you see this message then the function "ckSelectIcon" is missing from the element edition. Please contact the developer');
//}

function ckSelectIcon(icon) {
	var focusIcon = $ck('.editfocus .iconck');
	focusIcon.empty().append(icon);
	return focusIcon;
}

function ckSelectModule(module) {
	alert('ERROR : If you see this message then the function "ckSelectModule" is missing from the element edition. Please contact the developer');
}

function ckLoadEditionPopup() {
	alert('ERROR : If you see this message then the function "ckLoadEditionPopup" is missing from the element edition. Please contact the developer');
}

function ckCallImageManagerPopup(id) {
	CKBox.open({handler: 'iframe', id: 'ckfilesmodal', url: PAGEBUILDERCK.URIPBCK + '&view=links&type=image&func=ckSelectFile&fieldid='+id+'&tmpl=component'});
}

//function ckCallIconsPopup() {
//	if (! $ck('#pagebuilderckIconsmodalck').length) {
//		var popup = document.createElement('div');
//		popup.id = 'pagebuilderckIconsmodalck';
//		popup.className = 'pagebuilderckIconsmodalck pagebuilderckModalck modal hide fade';
//		document.body.appendChild(popup);
//		popup.innerHTML = '<div class="modal-header">'
//				+'<button type="button" class="close" data-dismiss="modal">×</button>'
//				+'<h3>' + Joomla.JText._('CK_ICON') + '</h3>'
//			+'</div>'
//			+'<div class="modal-body">'
//				+ '<iframe class="iframe" src="' + PAGEBUILDERCK.URIROOT + '/administrator/index.php?option=com_pagebuilderck&view=icons" height="400px" width="800px"></iframe>'
//			+'</div>'
//			+'<div class="modal-footer">'
//				+'<button class="btn fullscreenck" aria-hidden="true" onclick="ckTooglePagebuilderckModalFullscreen(this)"><i class="icon icon-expand-2"></i>' + Joomla.JText._('CK_FULLSCREEN') +'</button>'
//			+'</div>';
//
//		var BSmodal = $ck('#pagebuilderckIconsmodalck');
//		BSmodal.css('z-index', '44444');
//	} else {
//		var BSmodal = $ck('#pagebuilderckIconsmodalck');
//	}
//
//	BSmodal.find('.fullscreenck').removeClass('active');
//	BSmodal.modal().removeClass('pagebuilderckModalFullscreen');
//	BSmodal.modal('show');
//}

function ckCallGoogleFontPopup(prefix) {
	CKBox.open({url: PAGEBUILDERCK.URIPBCK + '&amp;view=fonts&amp;tmpl=component&amp;prefix='+prefix})
}

function ckOpenModulesPopup() {
	url = PAGEBUILDERCK.URIPBCK + '&view=modules';
	CKBox.open({id: 'ckmodulespopup', 
				url: url,
				style: {padding: '10px'}
			});
}
/* Toggle the fullscreen */
function ckTooglePagebuilderckModalFullscreen(button) {
	var BSmodal = $ck($ck(button).parents('.modal')[0]);
	if ($ck(button).hasClass('active')) {
		BSmodal.removeClass('pagebuilderckModalFullscreen');
		$ck(button).removeClass('active');
	} else {
		BSmodal.addClass('pagebuilderckModalFullscreen');
		ckResizeModalbodyOnFullscreen();
		$ck(button).addClass('active');
	}
}

/* Resize the fullscreen modal window to get the best space for edition */
function ckResizeModalbodyOnFullscreen() {
	var BSmodal = $ck('.modal.pagebuilderckModalFullscreen');
	var modalBody = BSmodal.find('.modal-body');
	modalBody.css('height', BSmodal.innerHeight() - BSmodal.find('.modal-header').outerHeight() - BSmodal.find('.modal-footer').outerHeight());
}

/* Bind the modal resizing on page resize */
$ck(window).bind('resize',function(){
	ckResizeModalbodyOnFullscreen();
	ckResizeEditor();
});

/* Play the animation in the Preview area */
function ckPlayAnimationPreview() {
//	$ck('.editfocus').hide(0).removeClass('animateck');
//	$ck('.editfocus .blockck').hide(0).removeClass('animateck');
	$ck('.editfocus').removeClass('animateck');
	$ck('.editfocus .blockck').removeClass('animateck');
	$ck('.workspaceck').addClass('pagebuilderck');
	var t = setTimeout( function() {
		$ck('.editfocus').addClass('animateck');
		$ck('.editfocus .blockck').addClass('animateck');
//		$ck('.workspaceck').removeClass('pagebuilderck');
	}, $ck('#blocanimdur').val()*1000);
}

/* remove the root path for the image to be shown in the editor */
function ckContentToEditor(content) {
	if (! content) return '';
	var search = new RegExp('<img(.*?)src="'+PAGEBUILDERCK.URIROOT.replace('/', '\/')+'\/(.*?)"',"g");
	content = content.replace(search, '<img $1src="$2"');

	return content;
}

/* add the root path for the image to be shown in the pagebuilder */
function ckEditorToContent(content) {
	if (! content) return '';
	var search = new RegExp('<img(.*?)src="(.*?)"',"g");
	var images = content.match(search);
	if (images) {
		for (var i = 0; i < images.length; i++) {
			if (images[i].indexOf('src="http') == -1) {
				var image = images[i].replace(search, '<img $1src="'+PAGEBUILDERCK.URIROOT+'/$2"');
				content = content.replace(images[i], image);
			}
		}
	}
//	content = content.replace(search, '<img $1src="'+PAGEBUILDERCK.URIROOT+'/$2"');

	return content;
}

/* show the popup to select a restoration date */
function ckCallRestorePopup() {
	CKBox.open({style: {padding: '10px'}, fullscreen: true, size: {x: '600px', y: '400px'}, handler: 'inline', content: 'pagebuilderckRestoreModalck', id: 'ckboxmodalrestore'});
//	var BSmodal = $ck('#pagebuilderckRestoreModalck');
//	BSmodal.css('z-index', '44444');
//	BSmodal.modal('show');
}

/* load the .pbck backup file and load it in the page */
function ckDoRestoration(id, name, index) {
	$ck('.restoreline' + index + ' .processing').addClass('ckwait');
	var isLocked = parseInt($ck('.restoreline' + index + ' .locked').attr('data-locked'));
	var myurl = PAGEBUILDERCK.URIPBCK + "&task=ajaxDoRestoration&" + PAGEBUILDERCK.TOKEN;
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			id: id,
			name: name,
			isLocked: isLocked
		}
	}).done(function(code) {
		$ck('.workspaceck').html(code);
		CKBox.close('#ckboxmodalrestore .ckboxmodal-button');
		$ck('.restoreline' + index + ' .processing').removeClass('ckwait');
		ckInitWorkspace();
	}).fail(function() {
		alert(Joomla.JText._('CK_FAILED', 'Failed'));
	});
}

/* Lock or unlock the backup to avoid it to be erased */
function ckToggleLockedBackup(id, filename, index) {
	var isLocked = parseInt($ck('.restoreline' + index + ' .locked').attr('data-locked'));
	$ck('.restoreline' + index + ' .locked').addClass('ckwait');
	var myurl = PAGEBUILDERCK.URIPBCK + "&task=ajaxToggleLockBackup&" + PAGEBUILDERCK.TOKEN;
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			id: id,
			filename: filename,
			isLocked: isLocked
		}
	}).done(function(code) {
		if (code == '1') {
			$ck('.restoreline' + index + ' .locked').removeClass('ckwait');
			if (parseInt(isLocked)) {
				$ck('.restoreline' + index + ' .locked').removeClass('active').attr('data-locked', '0');
				$ck('.restoreline' + index + ' .locked .fa').removeClass('fa-lock').addClass('fa-unlock');
			} else {
				$ck('.restoreline' + index + ' .locked').addClass('active').attr('data-locked', '1');
				$ck('.restoreline' + index + ' .locked .fa').removeClass('fa-unlock').addClass('fa-lock');
			}
		} else {
			alert('Failed. Please reload the page.');
		}
	}).fail(function() {
		alert(Joomla.JText._('CK_FAILED', 'Failed'));
	});
}

/* Load an existing page into the interface */
function returnLoadPage(id, type, title) {
	if (! type) type = 'page';
	if (! title) title = '';

	var lefpanelhtml = '<div class="menuckpanelpopup">'
							+'<div class="headerck">'
								+'<span class="headerckicon" onclick="$ck(this).parent().parent().remove()">×</span>'
								+'<span class="headercktext">' + Joomla.JText._('CK_PAGE') + '</span>'
							+'</div>'
							+'<p>' + Joomla.JText._('CK_DRAG_DROP_PAGE') + '</p>'
							+'<div class="ckbutton ckpageitem" data-id="' + id  + '"><i class="fa fa-arrows"></i> '
							+ title
							+'</div>'
						+'</div>';
	$ck('#menuck .inner[data-target="pages"]').append(lefpanelhtml);
	// make the menu items draggable
	$ck('#menuck .menuckpanelpopup .ckpageitem').draggable({
		connectToSortable: ".workspaceck",
		helper: "clone",
		zIndex: "999999",
		tolerance: "pointer",
		start: function( event, ui ){
			$ck('#menuck').css('overflow', 'visible');
		},
		stop: function( event, ui ){
			$ck('#menuck').css('overflow', '');
		}
	});
}

function ckLoadPage(btn, option) {
	var id = $ck(btn).attr('data-id');
	var type = $ck(btn).attr('data-type');
	$ck(btn).addClass('ckwait');
	if (type == 'library') {
		ckLoadPageFromMediaLibrary(id, option);
//		var myurl = PAGEBUILDERCK.URIBASE + "/index.php?option=com_pagebuilderck&task=ajaxLoadLibraryHtml";
	} else {
		ckLoadPageFromPagebuilder(id, option);
//		var myurl = PAGEBUILDERCK.URIBASE + "/index.php?option=com_pagebuilderck&task=ajaxLoadPageHtml";
	}

	/*$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			id: id
		}
	}).done(function(code) {
		if (code != 'error') {
			var newcode = $ck(code);
			// look for each row and upate the html ID
			newcode.each(function() {
				if ($ck(this).hasClass('rowck')) replaceIdsInRow($ck(this), false);
			});

			if (option == 'replace') {
				$ck('.workspaceck').html(newcode);
			} else if (option == 'bottom') {
				$ck('.workspaceck').append(newcode);
			} else {
				$ck('.workspaceck').prepend(newcode);
			}
			ckInitWorkspace();
			if ($ck(newcode[2]).find('.cktype[data-type="image"]').length) ckAddDndForImageUpload($ck(newcode[2]).find('.cktype[data-type="image"]')[0]);
			ckInlineEditor();
		} else {
			alert(Joomla.JText._('Error : Can not get the page. Please retry and contact the developer.'));
		}
		$ck('#cktoolbarLoadPageOptions .ckwait').removeClass('ckwait');
		CKBox.close();
	}).fail(function() {
		alert(Joomla.JText._('CK_FAILED', 'Failed'));
		$ck('#cktoolbarLoadPageOptions .ckwait').removeClass('ckwait');
	});*/
}

function ckLoadPageFromPagebuilder(id, currentblock) {
	var myurl = PAGEBUILDERCK.URIPBCK + "&task=ajaxLoadPageHtml&" + PAGEBUILDERCK.TOKEN;
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			id: id
		}
	}).done(function(code) {
		if (code != 'error') {
			var newcode = code.trim();
			var newpage = $ck(newcode);
			currentblock.after(newpage);
			ckReplaceIdAll(newpage);
			currentblock.remove();
			ckInitWorkspace();
			if ($ck(newcode[2]).find('.cktype[data-type="image"]').length) ckAddDndForImageUpload($ck(newcode[2]).find('.cktype[data-type="image"]')[0]);
			ckInlineEditor();
		} else {
			alert(Joomla.JText._('Error : Can not get the page. Please retry and contact the developer.'));
		}

		$ck('#cktoolbarLoadPageOptions .ckwait').removeClass('ckwait');
		CKBox.close();
	}).fail(function() {
		alert(Joomla.JText._('CK_FAILED', 'Failed'));
		$ck('#cktoolbarLoadPageOptions .ckwait').removeClass('ckwait');
	});
}

function ckReplaceIdAll(obj) {
	obj.each(function() {
		$this = $ck(this);
		if ($this.hasClass('rowck')) {
			var copyid = ckGetUniqueId('row_');
			// copy the styles
			ckReplaceId($this, copyid);
		}
	});

	obj.find('.rowck').each(function() {
		$row = $ck(this);
		var copyid = ckGetUniqueId('row_');
		// copy the styles
		ckReplaceId($row, copyid);
	});

	obj.find('.blockck, .cktype').each(function() {
		$this = $ck(this);

		var prefix = '';
		if ($this.hasClass('blockck')) {
			prefix = 'block_';
		} else {
		}

		var copyid = ckGetUniqueId(prefix);
		// copy the styles
		ckReplaceId($this, copyid);
	});
}

function ckLoadPageFromMediaLibrary(id, currentblock) {
//	var myurl = PAGEBUILDERCK.URIBASE + "/index.php?option=com_pagebuilderck&task=ajaxLoadLibraryHtml";
	var myurl = 'https://media.joomlack.fr/api/pagebuilderck/page/' + id;
	$ck.ajax({
		url: myurl,
		dataType: 'jsonp',
		cache: true,
		jsonpCallback: "joomlack_jsonpcallback",
		timeout: 20000,
	}).done(function(code) {
		if (code != 'error') {
			var newcode = $ck(code['htmlcode'].trim());
			currentblock.after(newcode);
			currentblock.remove();
			ckInitWorkspace();
			if ($ck(newcode[2]).find('.cktype[data-type="image"]').length) ckAddDndForImageUpload($ck(newcode[2]).find('.cktype[data-type="image"]')[0]);
			ckInlineEditor();
		} else {
			alert(Joomla.JText._('Error : Can not get the page. Please retry and contact the developer.'));
		}
		$ck('#cktoolbarLoadPageOptions .ckwait').removeClass('ckwait');
		CKBox.close();
	}).fail(function() {
		alert(Joomla.JText._('CK_FAILED', 'Failed'));
		$ck('#cktoolbarLoadPageOptions .ckwait').removeClass('ckwait');
	});
}

function ckLoadPageFromCustomLibrary(id, currentblock) {
//	var myurl = PAGEBUILDERCK.URIBASE + "/index.php?option=com_pagebuilderck&task=ajaxLoadLibraryHtml";
	var myurl = PAGEBUILDERCK.URIPBCK + "&task=library.load&" + PAGEBUILDERCK.TOKEN;
	$ck.ajax({
		url: myurl,
		data : {
			path: id
		}
	}).done(function(code) {
		if (code != 'error') {
			var newcode = $ck(code.trim());
			currentblock.after(newcode);
			currentblock.remove();
			ckInitWorkspace();
			if ($ck(newcode[2]).find('.cktype[data-type="image"]').length) ckAddDndForImageUpload($ck(newcode[2]).find('.cktype[data-type="image"]')[0]);
			ckInlineEditor();
		} else {
			alert(Joomla.JText._('Error : Can not get the library. Please retry and contact the developer.'));
		}
		$ck('#cktoolbarLoadPageOptions .ckwait').removeClass('ckwait');
		CKBox.close();
	}).fail(function() {
		alert(Joomla.JText._('CK_FAILED', 'Failed'));
		$ck('#cktoolbarLoadPageOptions .ckwait').removeClass('ckwait');
	});
}

function ckInjectPage(id, option, newcode) {
	// look for each row and upate the html ID
	newcode.each(function() {
		if ($ck(this).hasClass('rowck')) replaceIdsInRow($ck(this), false);
	});

	if (option == 'replace') {
		$ck('.workspaceck').html(newcode);
	} else if (option == 'bottom') {
		$ck('.workspaceck').append(newcode);
	} else {
		$ck('.workspaceck').prepend(newcode);
	}
	ckInitWorkspace();
	if ($ck(newcode[2]).find('.cktype[data-type="image"]').length) ckAddDndForImageUpload($ck(newcode[2]).find('.cktype[data-type="image"]')[0]);
	ckInlineEditor();
}

function ckSelectFile(file, field) {
		if (! field) {
			alert('ERROR : no field given in the function ckSelectFile');
			return;
		}
		$ck('#'+field).val(file).trigger('change');
		CKBox.close('#ckfilesmodal .ckboxmodal-button')
}

/* for retro compatibility purpose only */
function selectimagefile(file, field) {
	ckSelectFile(file, field);
}

function ckLoadIframeEdition(url, htmlId, taskApply, taskCancel) {
	CKBox.open({id: htmlId, 
				url: url,
				style: {padding: '10px'},
//				url: 'index.php?option=com_content&layout=modal&tmpl=component&task=article.edit&id='+id, 
				onCKBoxLoaded : function(){ckLoadedIframeEdition(htmlId, taskApply, taskCancel);},
				footerHtml: '<a class="ckboxmodal-button" href="javascript:void(0)" onclick="ckSaveIframe(\''+htmlId+'\')">'+Joomla.JText._('CK_SAVE_CLOSE')+'</a>'
			});
}

function ckLoadedIframeEdition(boxid, taskApply, taskCancel) {
	var frame = $ck('#'+boxid).find('iframe');
	frame.load(function() {
		var framehtml = frame.contents();
		framehtml.find('button[onclick^="Joomla.submitbutton"]').remove();
		framehtml.find('form[action]').prepend('<button style="display:none;" id="saveBtn" onclick="Joomla.submitbutton(\''+taskApply+'\');" ></button>')
		framehtml.find('form[action]').prepend('<button style="display:none;" id="cancelBtn" onclick="Joomla.submitbutton(\''+taskCancel+'\');" ></button>')
	});
}

function ckSaveIframe(boxid) {
	var frame = $ck('#'+boxid).find('iframe');
	frame.contents().find('#saveBtn').click();
	CKBox.close($ck('#'+boxid).find('.ckboxmodal-button'), true);
}

function ckTestUnit(value, defaultunit) {
	if (!defaultunit) defaultunit = "px";
	if (value.toLowerCase().indexOf('px') > -1 
		|| value.toLowerCase().indexOf('em') > -1 
		|| value.toLowerCase().indexOf('%') > -1
		|| value == 'auto'
		)
		return value;

	return value + defaultunit;
}

/*------------------------------------------------------
 * Editor management 
 *-----------------------------------------------------*/

function ckShowEditor() {
	$ck('#ckeditorcontainer').show().find('.toggle-editor').hide();
	ckResizeEditor();
}

function ckResizeEditor() {
	var ckeditor_ifr_height = $ck('#ckeditorcontainer .ckboxmodal-body').height() - $ck('#ckeditorcontainer .mce-toolbar').height() - $ck('#ckeditorcontainer .mce-toolbar-grp').height() - $ck('#ckeditorcontainer .mce-statusbar').height();
	$ck('#ckeditor_ifr').height(parseInt(ckeditor_ifr_height) - 6);
}

function ckSaveEditorToContent() {
	
}
/*------------------------------------------------------
 * END of Editor management 
 *-----------------------------------------------------*/
 
 function ckSaveAsPage () {
	var title = prompt('This will create a new page with this layout. Please enter a name for this page');
	if (! title) return;
	var myurl = PAGEBUILDERCK.URIPBCK + "&task=page.ajaxSave&" + PAGEBUILDERCK.TOKEN;
	// CKBox.open({style: {padding: '10px'}, fullscreen: false, size: {x: '500px', y: '200px'}, handler: 'inline', content: 'cktoolbarExportPage'});
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			id: 0,
			title: title,
			method: 'ajax',
			htmlcode: $ck('.workspaceck').html()
		}
	}).done(function(code) {
		alert(Joomla.JText._('CK_PAGE_SAVED', 'Page saved'));
	}).fail(function() {
		alert(Joomla.JText._('CK_FAILED', 'Failed'));
	});
}

function ckShowResponsiveSettings(forcedisable) {
	if (! forcedisable) forcedisable = false;
	var button = $ck('#ckresponsivesettingsbutton');
	if (forcedisable) {
		$ck('.ckelementsedition').show();
		button.removeClass('active');
		$ck('.workspaceck .cktype, .workspaceck .blockck').each(function() {
			$bloc = $ck(this);
			$bloc.removeClass('ckmobileediting');
			$bloc.find('.ckmobileoverlay').remove();
		});
		$ck('.ckresponsiveedition').fadeOut();
		ckRemoveWorkspaceWidth();
		$ck('.ckcolwidthedition').remove();
		$ck('.editorckresponsive').remove();
	} else {
		$ck('.ckelementsedition').hide();
		$ck('.ckcolwidthedition').remove();

		if (! $ck('#ckresponsive4button.active').length) $ck('#ckresponsive4button').trigger('click');
		button.addClass('active');

		var ckresponsiverange = ckGetResponsiveRangeNumber();
		var editor = '<div class="editorckresponsive"></div>';

		$ck('.workspaceck .blockck, .workspaceck .cktype').each(function(i, bloc) {
			bloc = $ck(bloc);
			bloceditor = $ck(editor);
			bloceditor.css({
				'left': 0,
				'top': 0,
				'position': 'absolute',
				'z-index': 99
			});
			if (! bloceditor.find('> .editorckresponsive').length) bloc.append(bloceditor);
			bloceditor.css('display', 'none').fadeIn('fast');
			if (bloc.hasClass('cktype')) {
				var buttons = '<div class="isControl" data-class="ckshow" onclick="ckToggleResponsive(this)"><span class="fa fa-eye"></span></div>'
				+ '<div class="isControl" data-class="ckhide" onclick="ckToggleResponsive(this)"><span class="fa fa-eye-slash"></span></div>'
				+ '<div class="isControl" title="'+Joomla.JText._('CK_EDIT_STYLES')+'" onclick="ckShowEditionPopup(\'' + bloc.attr('id') + '\');" ><span class="fa fa-edit"></span></div>';
			} else {
				var buttons = '<div class="isControl" data-class="ckshow" onclick="ckToggleResponsive(this)"><span class="fa fa-eye"></span></div>'
				+ '<div class="isControl" data-class="ckhide" onclick="ckToggleResponsive(this)"><span class="fa fa-eye-slash"></span></div>'
				+ '<div class="isControl" title="'+Joomla.JText._('CK_EDIT_STYLES')+'" onclick="ckShowResponsiveCssEdition(\'' + bloc.attr('id') + '\');" ><span class="fa fa-edit"></span></div>';
			}
			bloceditor.append(buttons);
			if (bloc.hasClass('ckhide' + ckresponsiverange)) {
				bloc.find('> .editorckresponsive .isControl[data-class="ckhide"]').addClass('active');
			} else {
				bloc.find('> .editorckresponsive .isControl[data-class="ckshow"]').addClass('active');
			}
		});
		// loop through the wrappers
		$ck('.workspaceck .wrapperck').each(function(i, bloc) {
			bloc = $ck(bloc);
			if (bloc.hasClass('ckhide' + ckresponsiverange)) {
				bloc.find('.controlResponsiveHidden').addClass('active');
			} else {
				bloc.find('.controlResponsiveShown').addClass('active');
			}
		});
		$ck('.ckresponsiveedition').fadeIn();
	}
}

function ckSwitchResponsiveEditColumns(btn) {
	var $btn = $ck(btn);
	var state = $btn.attr('data-state');
	if (state == '1') {
		$ck('.workspaceck .rowck').each(function(i, row) {
			ckEditColumns($ck(row), 0, 1);
		});
		$ck('.editorckresponsive').css('display', '');
		$btn.removeClass('active');
	} else {
		$ck('.workspaceck .rowck').each(function(i, row) {
			ckEditColumns($ck(row), 1);
		});
		$ck('.editorckresponsive').css('display', 'none');
		$btn.addClass('active');
	}
	$btn.attr('data-state', 1 - state);
}

function ckSwitchResponsive(responsiverangeNumber, force) {
	if (! force) force = false;
	responsiverangeNumber = responsiverangeNumber ? responsiverangeNumber : ckGetResponsiveRangeNumber();
//	var resolution = parseFloat($ck('#ckresponsive' + responsiverange + 'value').val());
	var button = $ck('#ckresponsive' + responsiverangeNumber + 'button');

	// do nothing if click on the active button
	if (button.hasClass('active')) return;
	if (button.hasClass('active') && !force) {
		ckRemoveWorkspaceWidth();
	} else {
		$ck('#cktoolbarResponsive .ckresponsivebutton').removeClass('active').removeClass('ckbutton-warning');
		button.addClass('active').addClass('ckbutton-warning');
		ckSetWorkspaceWidth(responsiverangeNumber);
	}

	var responsiverangeattrib = ckGetResponsiveRangeAttrib(responsiverangeNumber);
	$ck('.wrapperck').each(function() {
		var $item = $ck(this);
		// set active state for show/hide buttons
		$ck('> .editorck .isControlResponsive', $item).removeClass('active');
		if ($item.hasClass('ckstack' + responsiverangeNumber)) {
			$ck('> .editorck .isControlResponsive[data-class="ckstack"]', $item).addClass('active');
		} else if ($item.hasClass('ckhide' + responsiverangeNumber)) {
			$ck('> .editorck .isControlResponsive[data-class="ckhide"]', $item).addClass('active');
		} else {
			$ck('> .editorck .isControlResponsive[data-class="ckshow"]', $item).addClass('active');
		}
	});
	$ck('.rowck').each(function() {
		var $row = $ck(this);
		// set active state for show/hide buttons
		$ck('> .editorck .isControlResponsive', $row).removeClass('active');
		if ($row.hasClass('ckstack' + responsiverangeNumber)) {
			$ck('> .editorck .isControlResponsive[data-class="ckstack"]', $row).addClass('active');
		} else if ($row.hasClass('ckhide' + responsiverangeNumber)) {
			$ck('> .editorck .isControlResponsive[data-class="ckhide"]', $row).addClass('active');
		} else {
			$ck('> .editorck .isControlResponsive[data-class="ckalign"]', $row).addClass('active');
		}
	});
	$ck('.blockck').each(function() {
		var $bloc = $ck(this);
		var blocdatawidth = $bloc.attr('data-width' + responsiverangeattrib) ? $bloc.attr('data-width' + responsiverangeattrib) : $bloc.attr('data-width');
		$bloc.find('.ckcolwidthselect').val(blocdatawidth);
		// set active state for show/hide buttons
		$ck('> .editorckresponsive .isControl', $bloc).removeClass('active');
		if ($bloc.hasClass('ckhide' + responsiverangeNumber)) {
			$ck('> .editorckresponsive .isControl[data-class="ckhide"]', $bloc).addClass('active');
		} else {
			$ck('> .editorckresponsive .isControl[data-class="ckshow"]', $bloc).addClass('active');
		}
	});
	$ck('.cktype').each(function() {
		var $item = $ck(this);
		// set active state for show/hide buttons
		$ck('> .editorckresponsive .isControl', $item).removeClass('active');
		if ($item.hasClass('ckhide' + responsiverangeNumber)) {
			$ck('> .editorckresponsive .isControl[data-class="ckhide"]', $item).addClass('active');
		} else {
			$ck('> .editorckresponsive .isControl[data-class="ckshow"]', $item).addClass('active');
		}
	});

	// update the css responsive values in the panel
	var range = ckGetResponsiveRange();
	$ck('#popup_editionck .inputbox').val('');
	ckFillEditionPopup($ck('.editfocus').attr('id'), $ck('.workspaceck'), range);
}

function ckGetDefaultDataWidth(row) {
	var number_blocks = row.find('.blockck').length;
	var default_data_width = 100 / number_blocks;

	return default_data_width;
}

function ckSetWorkspaceWidth(range) {
	var resolution = parseFloat($ck('#ckresponsive' + range + 'value').val());
	var workspace = ckGetWorkspace();
	
	var ranges = '';
	var rangeclone = 4;
	if (PAGEBUILDERCK.RESPONSIVERANGE == 'reducing' && range != '5') {
		while (rangeclone >= range) {
			ranges += rangeclone;
			rangeclone--;
		}
	} else {
		ranges = range;
	}

	workspace.css('width', resolution + 'px').attr('ckresponsiverange', ranges).addClass('ckresponsiveactive');
	$ck('.tck-container').css('width', resolution + 'px');
//	workspace.css('width', resolution + 'px');
//	$ck('.tck-container').attr('ckresponsiverange', range).addClass('ckresponsiveactive');
//	workspace.attr('ckresponsiverange', range).addClass('ckresponsiveactive');
	$ck('#menuck').attr('ckresponsiverange', range).addClass('ckresponsiveactive');
	if (range == '5' || range == '0') {workspace.css('width','');}
}

function ckRemoveWorkspaceWidth() {
	$ck('#cktoolbarResponsive .ckbutton').removeClass('active');
	var workspace = ckGetWorkspace();
	workspace.css('width','').attr('ckresponsiverange', '').removeClass('ckresponsiveactive');
	$ck('#menuck').attr('ckresponsiverange', '').removeClass('ckresponsiveactive');
}

function ckToggleResponsive(btn) {
	var btn = $ck(btn);
	var cktype = $ck(btn.parents('.cktype')[0]);
	if (! cktype.length) cktype = $ck(btn.parents('.blockck')[0]);
	var rangeNumber = ckGetResponsiveRangeNumber();
	$ck('> .editorckresponsive .isControl', cktype).removeClass('active');
	btn.addClass('active');
	if (btn.attr('data-class') === 'ckhide') {
			cktype.addClass('ckhide' + rangeNumber);
	} else {
			cktype.removeClass('ckhide' + rangeNumber);
	}
}

function ckToggleResponsiveWrapper(btn) {
	var btn = $ck(btn);
//	var row = $ck('.ckfocus');
	var wrapper = $ck(btn.parents('.wrapperck')[0]);
	var rangeNumber = ckGetResponsiveRangeNumber();
	btn.parent().find('.isControlResponsive').removeClass('active');
	btn.addClass('active');
	if (btn.attr('data-class') === 'ckhide') {
			wrapper.removeClass('ckstack' + rangeNumber).removeClass('ckalign' + rangeNumber);
			wrapper.addClass('ckhide' + rangeNumber);
	} else if (btn.attr('data-class') === 'ckstack') {
			wrapper.removeClass('ckhide' + rangeNumber).removeClass('ckalign' + rangeNumber);
			wrapper.addClass('ckstack' + rangeNumber);
	} else {
			wrapper.removeClass('ckhide' + rangeNumber);
			wrapper.removeClass('ckstack' + rangeNumber);
			wrapper.addClass('ckalign' + rangeNumber);
	}
}

function ckToggleResponsiveRow(btn) {
	var btn = $ck(btn);
//	var row = $ck('.ckfocus');
	var row = $ck(btn.parents('.rowck')[0]);
	var rangeNumber = ckGetResponsiveRangeNumber();
	var setting = btn.attr('data-class');

	// make the eye button to auto disable on reclic
	if (setting === 'ckhide' && btn.hasClass('active')) {
		btn.parent().find('> .isControlResponsive[data-class="ckalign"]').click();
		return;
	}

	btn.parent().find('.isControlResponsive').removeClass('active');
	btn.addClass('active');
	if (rangeNumber == '') rangeNumber = '5';
	if (btn.attr('data-class') === 'ckhide') {
		row.removeClass('ckstack' + rangeNumber);
		row.removeClass('ckalign' + rangeNumber);
		row.addClass('ckhide' + rangeNumber);
	} else if (btn.attr('data-class') === 'ckstack') {
		row.removeClass('ckhide' + rangeNumber);
		row.removeClass('ckalign' + rangeNumber);
		row.addClass('ckstack' + rangeNumber);
	} else {
		row.removeClass('ckhide' + rangeNumber);
		row.removeClass('ckstack' + rangeNumber);
		row.addClass('ckalign' + rangeNumber);
	}
}

function ckCheckHtml(forcedisable) {
	if (! forcedisable) forcedisable = false;
	var button = $ck('#ckhtmlchecksettingsbutton');
	if (button.hasClass('active') || forcedisable) {
		button.removeClass('active');
		$ck('.workspaceck .rowck, .workspaceck .blockck, .workspaceck .cktype').each(function() {
			$bloc = $ck(this);
			$bloc.removeClass('ckhtmlinfoediting');
			$bloc.find('.ckhtmlinfos').remove();
		});
	} else {
		button.addClass('active');
		var showmessage = false;
		$ck('.workspaceck .rowck, .workspaceck .blockck, .workspaceck .cktype').each(function() {
			$bloc = $ck(this);

			// B/C for old method
			if ($bloc.find('> .inner').attr('data-customclass')) {
				var blocinner = $bloc.find('> .inner');
				var customclasses = blocinner.attr('data-customclass');
				$bloc.attr('data-customclass', blocinner.attr('data-customclass'));
				blocinner.removeAttr('data-customclass');
				var customclassesFrom = customclasses.split(' ');
				for (var i=0; i<customclassesFrom.length; i++) {
					// remove previous classes
					blocinner.removeClass(customclassesFrom[i]);
					// add new classes
					$bloc.addClass(customclassesFrom[i]);
				}
			}

			if ($bloc.hasClass('cktype')) {
				var customclasses = $bloc.attr('data-customclass') ? $bloc.attr('data-customclass') : '';
			} else {
				var customclasses = $bloc.attr('data-customclass') ? $bloc.attr('data-customclass') : '';
				customclasses = $bloc.find('> .inner,> .imageck,> .iconck').attr('data-customclass') ? $bloc.find('> .inner,> .imageck,> .iconck').attr('data-customclass') : customclasses;
			}
			$bloc.addClass('ckhtmlinfoediting')
				.prepend('<div class="ckhtmlinfos">'
							+ '<div class="ckhtmlinfosid" onclick="ckChangeBlocId(this)" data-id="'+$bloc.attr('id')+'">'
								+ '<span class="label">ID</span> '
								+ '<span class="ckhtmlinfosidvalue">'
									+ $bloc.attr('id')
								+ '</span>'
							+ '</div>'
							+ '<div class="ckhtmlinfosclass" onclick="ckChangeBlocClassname(this)">'
								+ '<span class="label">Class</span> '
								+ '<span class="ckhtmlinfosclassvalue">'
									+ customclasses 
								+ '</span>'
							+ '</div>'
						+ '</div>');
			// check if duplicated IDs
			if ($ck('[id="'+$bloc.attr('id')+'"]').length > 1) {
				showmessage = true;
				$ck('[id="'+$bloc.attr('id')+'"]').each(function() {
					$ck(this).find('> .ckhtmlinfos .ckhtmlinfosidvalue').addClass('invalid');
				});
			}
		});
		if (showmessage) {
			alert(Joomla.JText._('CHECK_IDS_ALERT_PROBLEM','Some blocks have the same ID. This is a problem that must be fixed. Look at the elements in red and rename them'));
		} else {
			alert(Joomla.JText._('CHECK_IDS_ALERT_OK','Validation finished, all is ok !'));
		}
	}
}

function ckChangeBlocId(btn) {
	// blocid = $ck(btn).attr('data-id');
	// bloc = $ck('#' + blocid);
	bloc = $ck($ck(btn).parents('.rowck, .blockck, .cktype')[0]);
	var result = prompt(Joomla.JText._('CK_ENTER_UNIQUE_ID', 'Please enter a unique ID (must be a text)'), bloc.attr('id'));
	if (!result)
		return;
	result = ckValidateName(result);
	if (ckValidateBlocId(result))
		ckUpdateIdPosition(bloc, result);
}

function ckChangeBlocClassname(btn) {
	bloc = $ck($ck(btn).parents('.rowck, .blockck, .cktype')[0]);
	if (bloc.hasClass('cktype') || bloc.hasClass('rowck') || bloc.hasClass('blockck')) {
		var blocinner = bloc;
	} else {
		var blocinner = bloc.find('> .inner,> .imageck,> .iconck');
	}
	var customclasses = blocinner.attr('data-customclass') ? blocinner.attr('data-customclass') : '';
	customclasses = bloc.attr('data-customclass') ? bloc.attr('data-customclass') : customclasses;
	var result = prompt(Joomla.JText._('CK_ENTER_CLASSNAMES', 'Please enter the class names separated by a space'), customclasses);
//	if (result == null)
//		return;
	// result = result.replace(/\s/g, "");

	// remove previous classes
	var customclassesFrom = customclasses.split(' ');
	for (var i=0; i<customclassesFrom.length; i++) {
		blocinner.removeClass(customclassesFrom[i]);
	}
	// add new classes
	var customclassesTo = result.split(' ');
	for (var i=0; i<customclassesTo.length; i++) {
		blocinner.addClass(customclassesTo[i]);
	}

	blocinner.attr('data-customclass', result);
	bloc.find('> .ckhtmlinfos .ckhtmlinfosclassvalue').text(result);
}

function ckValidateBlocId(newid) {
	if (newid != null && newid != "" && !$ck('#' + newid).length) {
		return true;
	} else if ($ck('#' + newid).length) {
		alert(Joomla.JText._('CK_INVALID_ID', 'ID invalid or already exist'));
		return false;
	} else if (newid == null || newid == "") {
		alert(Joomla.JText._('CK_ENTER_VALID_ID', 'Please enter a valid ID'));
		return false;
	}
	return true;
}

function ckValidateName(name) {
	var name = name.replace(/\s/g, "");
	name = name.toLowerCase();
	return name;
}

function ckUpdateIdPosition(bloc, newid) {
	// bloc = $ck('#' + blocid);
	ckReplaceId(bloc, newid);
	bloc.find('> .ckhtmlinfos .ckhtmlinfosid').attr('data-id', newid);
	bloc.find('> .ckhtmlinfos .ckhtmlinfosidvalue').removeClass('invalid');
	bloc.find('> .ckhtmlinfos .ckhtmlinfosidvalue').text(newid);
}


/******* Undo and Redo actions *************/

var ckActionsPointer=-1;
var ckDoActionsList=new Array();

function ckSaveAction() {
	ckActionsPointer++;
	var x=document.getElementById('workspaceck').innerHTML;
	ckDoActionsList[ckActionsPointer]=x;
	// enable the undo button
	if (ckActionsPointer > 0) $ck('#ckundo').removeClass('ckdisabled');
	// clean the list of actions to avoid next actions to be in memory
	ckDoActionsList = ckDoActionsList.slice(0, ckActionsPointer + 1);
	$ck('#ckredo').addClass('ckdisabled');
}

function ckUndo() {
	var z = ckActionsPointer - 1;

	if (ckDoActionsList[z-1]) {
		document.getElementById('workspaceck').innerHTML = ckDoActionsList[z];
		$ck('#ckredo').removeClass('ckdisabled');
	} else {
		document.getElementById('workspaceck').innerHTML = ckDoActionsList[0];
		$ck('#ckundo').addClass('ckdisabled');
		$ck('#ckredo').removeClass('ckdisabled');
	}

	ckInitInterface();
	if (z < 0) {
		$ck('#ckundo').addClass('ckdisabled');
		return;
	}
	ckActionsPointer--;
}

function ckRedo() {
	var z=ckActionsPointer+1;

	if (ckDoActionsList[z]) {
		document.getElementById('workspaceck').innerHTML=ckDoActionsList[z];
		$ck('#ckundo').removeClass('ckdisabled');
		ckActionsPointer++;
	} else {

		$ck('#ckredo').addClass('ckdisabled');
		return;
	}

	ckInitInterface();
	if (z == ckDoActionsList.length -1) {
		$ck('#ckredo').addClass('ckdisabled');
		return;
	}
}

/*-----------------FIN UNDO REDO ---------------*/

function ckInlineEditor(selector, workspace) {
	// only enable the inline editing if we are using tinymce
	if (PAGEBUILDERCK_EDITOR != 'tinymce') return;
	if (! selector) {
		selector = '.workspaceck [data-type="text"].ckinlineeditable .inner, .workspaceck [data-type="icontext"].ckinlineeditable .textck';
		if (!workspace) workspace = ckGetWorkspace();
		$ck('.cktype[data-type="text"]', workspace).addClass('ckinlineeditable');
		$ck('.cktype[data-type="icontext"]', workspace).addClass('ckinlineeditable');
		$ck('[data-type="text"]', workspace).addClass('ckinlineeditable');
		// .workspaceck [data-type="icontext"].ckinlineeditable .titleck >> attention car code html se retrouve dans textarea
		// add contenteditable for inline edition of all items
		var contenteditables = '.titleck,.buttontextck,.pbck_gallery_item_title,.pbck_gallery_item_desc,.messageck_title,.messageck_text,.separatorck_text,'
		+ '.tableck-cell,.tablerowck-cell,.itemcontentck,.itemtitleck,.pbck_testimonial_text, .pbck_testimonial_author_name, .pbck_testimonial_author_status, .pbck_testimonial_author_url'
		+ ',.pbck_contenteditable, .pbck_list_item_text';
		workspace.find(contenteditables).attr('contenteditable', 'true');
	}
	
	tinymce.init({
		selector: selector,
		inline: true,
		autosave_ask_before_unload: false,
		plugins: [
		'advlist autolink lists link image charmap print preview anchor',
		'searchreplace visualblocks code fullscreen',
		'insertdatetime media table contextmenu paste'
		],
		toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link unlink image',
		menubar: false
	});
	
}

function ckSaveItem(blocid) {
	var name = prompt('Name to save the element');
	if (! name) return;
	// var styleswrapper = ckGetStylesWrapperForBlock(blocid);
	var saveditem = $ck('#' + blocid).clone();
	ckRemoveEdition(saveditem, true);
	if (saveditem.hasClass('rowck') && $ck('#' + blocid).parents('.rowck').length) {
		var type = 'rowinrow';
	} else if (saveditem.hasClass('rowck')) {
		var type = 'row';
	} else if (saveditem.hasClass('wrapperck')) {
		var type = 'wrapper';
	} else {
		var type = saveditem.attr('data-type');
	}
	var myurl = PAGEBUILDERCK.URIPBCK + "&task=ajaxSaveElement&" + PAGEBUILDERCK.TOKEN;
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			name : name,
			type : type,
			id : saveditem.attr('data-id'),
			html : saveditem[0].outerHTML
		}
	}).done(function(code) {
		var result = JSON.parse(code);
		if (result.status == '1') {
			alert(Joomla.JText._('CK_SAVED', 'Saved'));
			$ck('#ckmyelements').append(result.code);
			ckMakeItemsDraggable();
			ckMakeRowsDraggable();
		} else {
			alert(Joomla.JText._('CK_FAILED', 'Failed'));
		}
	}).fail(function() {
		alert(Joomla.JText._('CK_FAILED', 'Failed'));
	});
}

function ckShowColorsPalette() {
	$boxfooterhtml = '<a class="ckboxmodal-button" href="javascript:void(0);" onclick="ckSetPaletteColors();CKBox.close()">' + Joomla.JText._('CK_SAVE_CLOSE') + '</a>';
	var myurl = PAGEBUILDERCK.URIPBCK + "&task=interface.load&layout=colorspalette&" + PAGEBUILDERCK.TOKEN;
	$ck.ajax({
		type: "POST",
		url: myurl,
		async: true,
		data: {
		}
	}).done(function(code) {
		$ck('#ckcolorspalette').remove();
		var colorspalette = $ck(code);
		$ck(document.body).append(colorspalette);
		colorspalette.hide();
		CKBox.open({handler: 'inline', content: 'ckcolorspalette', footerHtml: $boxfooterhtml, style: {padding: '10px'}, size: {x: '600px', y: '400px'}});
		var colors = $ck('.workspaceck > .pagebuilderckparams').attr('data-colorpalette');
		var colorsFromSettings = $ck('.workspaceck > .pagebuilderckparams').attr('data-colorpalettefromsettings');
		var colorsFromTemplate = $ck('.workspaceck > .pagebuilderckparams').attr('data-colorpalettefromtemplate');

		ckLoadPaletteColors('colorpalette', colors);
		ckLoadPaletteColors('colorpalettefromsettings', colorsFromSettings);
		ckLoadPaletteColors('colorpalettefromtemplate', colorsFromTemplate);
	}).fail(function() {
		alert(Joomla.JText._('CK_FAILED', 'Failed'));
	});
}

function ckLoadPaletteColors(selector, colors) {
	if (colors) {
		colors = colors.split(',');
		for (var i=0; i< colors.length; i++) {
			var field = $ck('#ckcolorspalette [data-selector="' + selector + '"] .inputbox').eq(i);
			if (colors[i]) {
				field.val('#' + colors[i].replace('#', '')).trigger('change');
				field.css('background-color', field.val());
				setpickercolor(field);
			}
		}
	}
}

function ckCopyPaletteFrom(btn) {
	var colorToCopy = $ck(btn).parent().find('.inputbox').val();
	if (! colorToCopy) return;
	var row = $ck($ck(btn).parents('tr')[0]);
	var index = $ck($ck(btn).parents('table')[0]).find(('tr')).index(row);
	$ck('#ckcolorspalette table[data-selector="colorpalette"] tr').eq(index).find('.inputbox')
			.val(colorToCopy)
			.css('background-color', colorToCopy);
}

function ckSetPaletteColors() {
	var colors = new Array();
	$ck('#ckcolorspalette .colorPicker').each(function() {
		colors.push($ck(this).val().replace('#', ''));
	});
	colors = colors.join(',');
	ckSetPaletteOnColorPicker(colors, 'colpick_palette');
}

function ckSetPaletteOnColorPicker(colors, object) {
	CKBox.close();
	colors = colors.split(',');
	$ck('span',$ck('#'+object)).each(function(i, el) {
		$ck(el).css('background-color', '#'+colors[i]);
	});
	$ck('.workspaceck > .pagebuilderckparams').attr('data-colorpalette', colors);
}

function ckUpdateShapeDivider(prefix) {
	// prefix is not used anymore
	// automatically update both dividers
	ckCreateShapeDividerNew('divider');
	ckCreateShapeDividerNew('divider-2');
}

function ckCreateShapeDividerNew(prefix) {
	var focus = $ck('.editfocus');
	var flip = $ck('[name="' + prefix + 'flipvertical"]:checked').val();

	switch ($ck('#' + prefix + 'shape').val()) {
		case 'multiclouds' :
			var svgpath = '<svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 1000 86.47" preserveAspectRatio="none"><g style="opacity:0.33"><path d="M823,15.52l.24-.07A27.72,27.72,0,0,0,864.3,30.53a46.9,46.9,0,0,0,51.9,28A55,55,0,0,0,1000,73.07V0H792C795.79,12,809.32,18.85,823,15.52Z"></path><path d="M23.71,83.4A50,50,0,0,0,85.39,48.77v-.05a25.19,25.19,0,0,0,20.89-4.31,32.67,32.67,0,0,0,12.82,7A32.88,32.88,0,0,0,154.31,0H0V68.64A49.74,49.74,0,0,0,23.71,83.4Z"></path></g><g style="opacity:0.66"><path d="M499.63,19.13h.08a8.91,8.91,0,0,0,12.64,6.15A15.07,15.07,0,0,0,528,35.9a17.67,17.67,0,0,0,33.67-9.55v0A8.9,8.9,0,0,0,567.86,22a11.61,11.61,0,0,0,7.48-22H503.08a11.65,11.65,0,0,0-1.71,4.21,9.2,9.2,0,0,0-3.85-.28c-4.65.65-8,4.58-7.37,8.77S495,19.78,499.63,19.13Z"></path><path d="M631.55,20.67c1,7.6,8.68,12.87,17.22,11.78a16.35,16.35,0,0,0,11.45-6.74,16.34,16.34,0,0,0,7.07,2.14A10.86,10.86,0,0,0,686.86,35a10.82,10.82,0,0,0,8.1-1c1.68,6.83,9,11.4,17,10.38a16,16,0,0,0,12.48-8.49,19.56,19.56,0,0,0,10.37,1.45,19.24,19.24,0,0,0,11.72-5.89,10.85,10.85,0,0,0,17.33-.92A10.81,10.81,0,0,0,776,31.2a17.64,17.64,0,0,0,3.38,1,18.52,18.52,0,0,0,16.52,6A18.82,18.82,0,0,0,809.34,30c2.67,10,12.75,17.44,24.8,17.44,9.38,0,17.57-4.5,22-11.2a32,32,0,0,0,16.53,4.5,31.47,31.47,0,0,0,20.23-7.14,17.75,17.75,0,0,0,28.32,2.09,17.74,17.74,0,0,0,22.71,1.75c4.13,10.05,15,17.22,27.72,17.22,13.43,0,24.75-8,28.33-18.88V0H599.32C607.84,23.13,631.55,20.67,631.55,20.67Z"></path><path d="M.74,30.73c0,12.33,11.21,22.33,25.08,22.36,10.84,0,20.08-6.07,23.61-14.62A15.09,15.09,0,0,0,68.74,37a15.1,15.1,0,0,0,24.1-1.74,26.76,26.76,0,0,0,17.2,6.1,27.24,27.24,0,0,0,14.07-3.81,22.33,22.33,0,0,0,18.71,9.56c11.24,0,20.49-7.56,21.62-17.28a14.92,14.92,0,0,0,10.72.18c3.29,7.35,12.1,11.63,21.28,9.81a20.31,20.31,0,0,0,13.62-9.33A20.31,20.31,0,0,0,219,32.56a13.49,13.49,0,0,0,24.86,7.25,13.43,13.43,0,0,0,10-1.91c2.66,8.32,12.06,13.37,21.9,11.42a19.93,19.93,0,0,0,14.75-11.58,24.3,24.3,0,0,0,13,.92,23.88,23.88,0,0,0,14-8.3,13.47,13.47,0,0,0,21.4-2.61,13.46,13.46,0,0,0,17.17-2c4.56,6.88,13.69,10.63,23.18,8.76,12.14-2.4,20.26-13.09,18.13-23.88A73.93,73.93,0,0,0,400.48,0H0V29.49C.24,29.91.48,30.32.74,30.73Z"></path></g><path d="M16.3,13.9c10.2,2.5,20.3-1.1,25.5-8.3a14.66,14.66,0,0,0,18.5,3A14.6,14.6,0,0,0,80,14.9a13.14,13.14,0,0,0,3.4-2.4,25.71,25.71,0,0,0,14.8,9.7,26,26,0,0,0,14.1-.4,21.75,21.75,0,0,0,15.4,13.3c10.6,2.6,21-2.4,24.3-11.3a15,15,0,0,0,10.7,2.6,17.69,17.69,0,0,0,1.6,2.2,14.69,14.69,0,0,0,17.6,3.5,7.46,7.46,0,0,0,1.2-.7,14.54,14.54,0,0,0,6.4-8.9,12.61,12.61,0,0,0,.4-2.8,20.63,20.63,0,0,0,9.8-1.8,11.35,11.35,0,0,0,1.5,2.3A22.35,22.35,0,0,0,214,28.6c11.2,2.8,22.4-3.1,24.8-13.1a24.63,24.63,0,0,0,16.3,11.6c9.8,2.1,19.4-1.7,24.2-8.7a14,14,0,0,0,17.8,2.4,14.07,14.07,0,0,0,19.1,5.4,12.25,12.25,0,0,0,3.1-2.4,22.5,22.5,0,0,0,5.8,5.3,25.42,25.42,0,0,0,16.1,4,30.38,30.38,0,0,0,6-1.2c.2.4.4.9.6,1.3a20.81,20.81,0,0,0,14.6,11c10.2,2.2,20-2.9,22.9-11.5a13.84,13.84,0,0,0,10.3,2.1,14,14,0,0,0,19.3,4.6,14.17,14.17,0,0,0,6.7-11.8,20,20,0,0,0,9.3-2,21.31,21.31,0,0,0,14,9.9c10.6,2.3,20.9-3.4,23.2-12.7a28.46,28.46,0,0,0,37.2,7.1,23.54,23.54,0,0,0,7.3-7.1,15.79,15.79,0,0,0,20.1,2.1,15.69,15.69,0,0,0,21.6,5.5,13.88,13.88,0,0,0,3.5-2.9,26.66,26.66,0,0,0,9.5,7.2,28.5,28.5,0,0,0,7,2.2,29.16,29.16,0,0,0,15.2-1.3c2.8,6.6,9.3,11.8,17.5,13.3,11.4,2.1,22.2-3.8,25.3-13.4,0-.1.1-.2.1-.4.3.2.7.4,1,.6a15.93,15.93,0,0,0,10.7,1.5,15.79,15.79,0,0,0,28.7-6c.1-.4.1-.8.2-1.2a10.87,10.87,0,0,0,.1-1.8,22.26,22.26,0,0,0,10.4-2.6,25,25,0,0,0,3.9,4.7,24.65,24.65,0,0,0,12.2,6A24.5,24.5,0,0,0,715.3,34a19.09,19.09,0,0,0,10.2-13.4h.5a21.68,21.68,0,0,0,21.1,13,13.67,13.67,0,0,0,1.9-.2,22.1,22.1,0,0,0,13.8-7.7,24.79,24.79,0,0,0,11.9,8.5,25.09,25.09,0,0,0,8.1,1.4,25.86,25.86,0,0,0,18.5-6.7,21.77,21.77,0,0,0,5.2-7.2,15,15,0,0,0,19.1-1,15,15,0,0,0,21,2,13.81,13.81,0,0,0,2.8-3.1A26.84,26.84,0,0,0,866.3,26a27.39,27.39,0,0,0,14-3.4,22.36,22.36,0,0,0,18.3,9.9c11.1.3,20.4-7,21.8-16.6a15,15,0,0,0,11.2.2,15,15,0,0,0,21.1,1,15.16,15.16,0,0,0,4.7-13.5A22.32,22.32,0,0,0,966.3,0H0V1.6A25.29,25.29,0,0,0,16.3,13.9Z"></path><path d="M983.6,7.3A22.61,22.61,0,0,0,1000,1.1V0H967.3A22.52,22.52,0,0,0,983.6,7.3Z"></path></svg>';
		break;
		case 'clouds' :
			var svgpath = '<svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 1000 63.67" preserveAspectRatio="none"><path d="M916.2,58.53a46.9,46.9,0,0,1-46.1-17.89,32,32,0,0,1-14-4.4c-4.43,6.7-12.62,11.2-22,11.2-12,0-22.13-7.44-24.8-17.44a18.82,18.82,0,0,1-13.44,8.2,18.51,18.51,0,0,1-12.45-2.59h-.65a25.09,25.09,0,0,1-8.1-1.4,24.79,24.79,0,0,1-3.52-1.48,10.8,10.8,0,0,1-7.32-2.19,10.84,10.84,0,0,1-15.13,2.91,13.67,13.67,0,0,1-1.63.16,21.69,21.69,0,0,1-2.93,0,19.23,19.23,0,0,1-9.36,3.78,19.56,19.56,0,0,1-10.37-1.45A16,16,0,0,1,712,44.38c-7.56,1-14.51-3.07-16.67-9.28q-.71-.27-1.41-.58a10.82,10.82,0,0,1-7,.48,10.85,10.85,0,0,1-16.07,1.54,15.75,15.75,0,0,1-26.69.66,15.93,15.93,0,0,1-10.7-1.5c-.3-.2-.7-.4-1-.6,0,.2-.1.3-.1.4C629.2,45.1,618.4,51,607,48.9c-8.2-1.5-14.7-6.7-17.5-13.3a29.16,29.16,0,0,1-15.2,1.3,28.5,28.5,0,0,1-7-2.2,26.65,26.65,0,0,1-5.65-3.46A17.66,17.66,0,0,1,528,35.9a15.07,15.07,0,0,1-15.65-10.62,8.91,8.91,0,0,1-2.07.72L510,26a23.53,23.53,0,0,1-4.73,3.86,28.46,28.46,0,0,1-37.2-7.1c-2.3,9.3-12.6,15-23.2,12.7a21.31,21.31,0,0,1-14-9.9,20,20,0,0,1-9.3,2,14.17,14.17,0,0,1-6.7,11.8l-.05,0A14,14,0,0,1,395.6,34.8a13.84,13.84,0,0,1-10.3-2.1c-2.9,8.6-12.7,13.7-22.9,11.5a20.81,20.81,0,0,1-14.6-11c-.2-.4-.4-.9-.6-1.3a30.38,30.38,0,0,1-6,1.2,25.39,25.39,0,0,1-7.23-.41,13.46,13.46,0,0,1-16.46-2.33,23.88,23.88,0,0,1-14,8.3,24.3,24.3,0,0,1-13-.92,19.93,19.93,0,0,1-14.75,11.58c-9.84,2-19.24-3.1-21.9-11.42a13.43,13.43,0,0,1-10,1.91A13.49,13.49,0,0,1,219,32.56a20.31,20.31,0,0,1-8.94-2.07,20.31,20.31,0,0,1-13.62,9.33c-7.6,1.51-14.95-1.18-19.11-6.32a14.7,14.7,0,0,1-11.15-3.1,14.89,14.89,0,0,1-1.74-.57c-1,8.55-8.28,15.43-17.67,17a32.85,32.85,0,0,1-40.49-2.38,25.19,25.19,0,0,1-20.89,4.31v.09A50,50,0,0,1,0,68.64V86.47H1000V73.07a55,55,0,0,1-83.8-14.54Z" transform="translate(0 -22.8)"></path></svg>';
		break;
		case 'papertorn' :
			var svgpath = '<svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 1000 80" preserveAspectRatio="none"><path d="M0,0V71.07l.22.05c2.83,1,7.45-4.57,7.45-4.57s13.36,6.34,14.9,6.6S30,76.45,30,76.45L36.7,71.5s5.4,6,8,4.95,16.19-8.88,17.73-3.55S64.2,80,65,80s5.4-5.59,6.42-5.59,11,2.79,11.82,2.79,7.71-5.84,9.25-6.6,18.24.51,22.1,2.66,14.13.13,19.79,0,25.18,1.9,25.18,1.9l8.48-1.9s0-5.46,1.8-5.2,9.25.51,9.25.51L180.34,65s20.81,2,22.1,2,2.31-3.81,4.37-3.81,14.13,3.81,15.42,3.81,9.25-3.55,11.56-3.81,8.48,5.08,10.28,5.58,55-6.6,55-6.6-.26-5.33,4.88-3.55,15.16,1.27,19.53,2,3.34,6.34,7.71,2.54,5.65-7.36,8.22-6.09,10.28,2.28,11,2.28,4.37-8.12,7.19-6.6,21.59,12.5,30.06,13.48,13.1-10.44,13.1-10.44l13.1,2.54s7.71,10.15,16.19,11.17,11,6.34,19.27,3.55,17.73-9.39,20-8.38,17.47-6.6,18.24-6.85,8.74.76,16.44.25,9.51-5.52,9.51-5.52,25.69-1.08,28.78,1.21,3.6,4.31,6.68,4.31,12.33-5.84,22.35-3.55,26,6.34,27.49,7.11,10.28-5.58,10.28-5.58,5.14,4.57,6.42,5.84,6.17.76,9.25,0,3.85-9.14,10-5.08,20.3-5.08,25.44,1S667,64,667,64s6.68-11.42,14.39-9.9S710.16,66.2,710.16,66.2l6.42-5.49,27.24-1.27s-1.28-7.11,6.17-5.33,10-.89,11-1.71,5.14-3.49,9,0,25.44,8.31,32.89,8.31,15.93-6.35,22.61-4.57,13.36-1.52,14.39-2-.77-4.65,6.42-3.47,19-.76,20.58-.25,6.17-.76,11.31-1.78,6.34-11.63,12.25-4.27,8.68,5.28,11.51,6.74,7.45,1.33,9.25,0-1.54-7.93,7.19-5.39,4.17,3.48,10.08,3,14.13,4.06,14.13,4.06,10.79-2,13.11-2.28,9.25-4.57,12.59-2.79,6.17,1.52,9,2.28,10-.51,11.82-1.78,2.57-6.35,7.71-3.55a11.91,11.91,0,0,1,3.14,2.18V0Z" style="opacity:0.66"></path><path d="M0,0V59.17c4.84-3,4.08,1,5.36-.23s0,0,2.57-1.27,3.08.51,7.19,1,2.83,2.54,2.83,2.54,8.74,5.08,10.28,4.57,4.88-9.14,4.37-10.15S40,61.22,40,61.22l4.37,3.3s9.76,2,11,1a59.11,59.11,0,0,1,8-4.57c1.8-.76,4.11,2.28,6.68,2.79s8.74,3.81,8.74,3.81S90.92,60.71,94,60.21s16.7,3.55,17.47,2,11.82-3,13.1-2.79,8.48,10.91,8.48,10.91l30.83-.51s6.68-6.09,7.45-7.61.26-1.78.26-1.78,9-4.57,10-4.57S193.18,61,193.18,61s10.54-4.06,14.39-5.08,6.68,1.52,12.85-2,19.79-2.79,20.56-3.3,7.45-6.34,12.85-9.39,11.31.76,13.1.76,7.71,5.33,10.79,4.57,10.28-5.33,13.62-5.33,2.83,2.28,10.28,1.78,5.91.51,12.59,4.32,8-2.29,12.85-3.55,1.8.25,4.63-.51a19,19,0,0,1,5.65-.76c1.29,0,2.57,1.52,8.74,3.55s5.91-.76,9.25-1.78,8.22-3.3,18.76-10.15,2.57,5.33,6.94,6.85,22.87,3.55,24.41,2,4.37-3,7.2-4.57,2.82-.51,3.59-.51,9,2.54,11.31,4.06,3.85,4.57,6.17,4.57,4.62-2.54,11-3.55-.26,4.82,0,5.58,2.06,0,5.4-.25,4.88-2.79,9.76-2.79,5.14,3.3,8.48,4.06,2.83,0,10.54-3.81,6.68-1.78,14.9-3.55,22.61,6.85,24.41,6.09,4.88-1.27,11.3-2.54,11.56,3,16.19,5.58,5.14-1.52,8.74-5.08,12.08.25,14.9.25,9.25-.25,13.36-.51,5.14-3,13.88-5.08,8,4.57,14.65,3.55,14.13-1.27,28-5.08,6.42,3,10.79,5.58,9,1.78,11.56,1.52S676,39.65,679.84,36.35s9.51,4.31,16.19,8.12,9.25,3.81,14.13,1.78,9.51-4.82,14.9-7.87,5.4,5.84,10,2.79,15.42.76,17.21-.25,8.48.76,15.42-1,1.8,2,7.45,6.85,3.08-2.29,15.42,1,28.52-2.29,32.89-3,4.88,5.33,9.25,5.84,5.4-4.82,9.76-7.87,17-.76,20.56-2a17.22,17.22,0,0,0,6.17-4.06s13.36,0,15.16-1.52,10.28-.76,13.36-.76,26,4.57,35.2,2.79,11.82-7.62,16.44-10.91,23.13,2.54,30.32,2.54,20.3-2.54,20.3-2.54V0Z"></path></svg>';
		break;
		case 'bridge' :
			var svgpath = '<svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 283 20.99" preserveAspectRatio="none"><path d="M81.66,18.4C67.67,6.89,32.57.93,18.57,20.33,14.2,14.75,6.34,11.48,0,9.75V0H0V21H143.75C134.52,9.95,107.42,1.35,81.66,18.4Z" transform="translate(0.02 -0.01)"></path><path d="M283,0V11c-3.82.72-6.67,2.21-13.46,9.21-15.71-21-52.38-5.64-55,.58-12.95-12.92-53.74-17.6-70.74.21H283V0Z" transform="translate(0.02 -0.01)"></path></svg>';
		break;
		case 'rockymoutain' :
			var svgpath = '<svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 500 118.77" preserveAspectRatio="none"><defs><style>.cls-2{opacity:0.5;}</style></defs><path class="cls-1" d="M500,200a7.35,7.35,0,0,0-.44-1.4,13,13,0,0,0-1.25-2c-1.45-2.12-1.48-2.21-1.8-2.39-.55-.32-1.19-.45-1.33-1-.1-.32.06-.68-.11-.75s-.45.23-.6.15.1-.5-.11-.72-.87.07-1.2-.19-.09-.54-.14-.94c-.08-.74-.9-1.23-1.46-1.58-3.16-2-4.78-3-5.91-4.38a22.57,22.57,0,0,1-1.51-2,4.47,4.47,0,0,0-1.14-1.49,6.8,6.8,0,0,0-.77-.57c-.36-.18-.67-.26-.74-.49s0-.27-.06-.34-.22,0-.36,0c-.32-.08-.17-.7-.58-.92s-.5,0-.66-.14.14-.78-.05-.88-.24.12-.57.17-.44-.13-.92-.15-.6.15-.93.05-.23-.14-.55-.41a6.83,6.83,0,0,0-.71-.51,5.93,5.93,0,0,0-2-.38,2.44,2.44,0,0,1-1.06-.24c-.23-.14-.18-.2-.49-.46-.51-.41-.77-.37-1-.7s-.11-.45-.28-.6c-.54-.48-2.6.42-3.35.76-1.78.78-2,1.17-2.91,1.22a7.5,7.5,0,0,1-2.33-.51c-2.19-.58-2.07-.41-2.85-.68-2.84-1-3.9-3-5.26-2.64a1.91,1.91,0,0,1-1.05.17c-.56-.13-.66-.61-1.15-.62s-.44.27-.87.26-.62-.32-.91-.24-.22.45-.49.53-.74-.62-1.33-.59-.5.49-1,.51-.91-.68-1.35-.56c-.16,0-.13.13-.45.33a4.22,4.22,0,0,1-.68.31,2.17,2.17,0,0,0-.83.71c-.83.94-2.34.34-3.51,1.36-.44.39-.43.66-1,.93a3.42,3.42,0,0,1-1.49.26c-.53,0-.71.14-2.94,1.13-2.66,1.2-3.16,1.39-3.93,1.34s-.72-.25-1.44-.24c-1.42,0-1.68.82-3,.85-1.08,0-1.45-.55-2.22-.29-.5.18-.59.5-1,1a6.44,6.44,0,0,1-2.93,1.49,14.82,14.82,0,0,1-3.69.47c-2.2.14-1.16.24-7.76,1.32-1,.16-2.83.45-3.31,1.47-.11.24-.1.42-.31.57-.86.59-3.24-.94-3.49-1.1-.67-.44-2.13-1.39-2-2.28,0-.15.11-.42-.07-.6s-.53-.08-.91-.19c-.72-.21-.5-.95-1.34-1.59-.61-.47-.94-.25-1.39-.73s-.31-.95-.74-1.12-.61,0-1.18.19c-.79.27-.78.47-1.24.53-.66.08-.91-.31-1.44-.15s-.59.5-.76.45.11-.7-.29-1.26a1.19,1.19,0,0,0-.91-.53c-.43,0-.63.4-.86.32s-.12-.42-.36-.79a2,2,0,0,0-.87-.63c-1.46-.76-1.44-1.33-2.17-1.35a2.4,2.4,0,0,0-1.43.58c-.84.6-.84,1.17-1.26,1.19s-.49-.33-1.47-1.36-1.36-1.21-1.62-1.21c-.44,0-.65.31-1.16.23s-.45-.27-.68-.26c-.49,0-.67.91-1,1.41-1.17,1.67-5.86,1.27-8.31.47-1.46-.48-2.19-.71-2.6-1.33-.18-.27-.43-.81-.95-.89-.21,0-.32,0-.75,0s-.54-.12-.78-.13a1.88,1.88,0,0,0-1.19.44c-.58.4-.89.31-3,1-2.92,1-5.08,1.68-5.23,2.7,0,.18,0,.69-.42.89-.19.1-.28,0-.58.1-.61.16-.6.61-1.15.72-.3.06-.33-.06-.73,0a2,2,0,0,0-.82.34c-.39.27-.29.38-.73.77s-.32.16-.9.59a1,1,0,0,1-.54.27c-.23,0-.31-.13-.5-.09s-.15.18-.39.4-.35.16-.47.31,0,.43-.08.68-.49.27-1.75.59c-.8.21-.87.26-.92.3-.53.43-.14,1.07-.62,1.37s-.66,0-1.18.2-.28.41-.75.74-.84.2-1.2.58c-.18.19-.15.29-.43.61a5.56,5.56,0,0,1-.49.44c-.72.62-.71.86-1.16,1.06a3.87,3.87,0,0,1-.74.15,3.19,3.19,0,0,0-1.51,1.21c-1.12,1.11-3.68.78-3.83.76-1.54-.21-2.6-1-4.25-2.34l-.87-.7c-.87-.72-1.31-1.08-1.62-1.38a5,5,0,0,1-1.76-2.47c-.12-.68,0-1.36-.35-1.49s-.56.14-.71,0,.05-.34,0-.72a1.53,1.53,0,0,0-.24-.72c-.45-.65-1.31-.44-1.65-1-.23-.39,0-.74-.24-.9s-.67.16-1,0-.07-.81-.53-1.15-.62-.05-.89-.3-.07-.74-.38-.93-.43,0-.76,0c-.84,0-1.49-.86-1.68-1.11-.77-1-.2-1.52-.89-2.15s-1.65-.56-1.69-1,.46-.43.44-.74-.84-.51-1.53-.87c-1.7-.88-2.25-3.07-2.54-4.23a4.61,4.61,0,0,0-.45-1.39c-.42-.75-.78-.79-1.26-1.6s-.24-.85-.69-1.49-1.15-1.3-1.6-1.17c-.25.07-.23.36-.73.66a1.52,1.52,0,0,1-.52.23c-.74.2-1.16-.23-1.65,0-.28.13-.2.31-.55.44a1.36,1.36,0,0,1-.88,0c-.35-.11-.31-.27-.6-.39a1.75,1.75,0,0,0-1.87.5c-.06.11-.09.24-.23.29s-.35-.08-.59-.13-.53.06-1.27.37c-1.2.49-1.35.43-1.41.39-.24-.16,0-.7-.17-.75s-.44.39-.57.34.06-.29-.06-.58-.37-.28-.47-.54.12-.37.15-.68c.06-.61-.71-1.29-1.1-1.21-.2,0-.16.25-.38.32-.41.12-1.2-.46-1.47-1.17-.15-.42,0-.54-.2-.82s-.66-.3-1.24-.68c-.75-.48-.8-1.08-1.13-1s-.5.87-.8.86-.26-.51-.72-.65a.79.79,0,0,0-.47,0c-.35.13-.29.5-.51.56-.41.12-1.11-1-1.37-.87s.06.6-.23.79a.85.85,0,0,1-.63,0c-1.37-.4-1.86-2.54-3.59-3a1.78,1.78,0,0,0-1.56.14c-.4.43.18,1.36,0,1.42s-.25-.3-.65-.57c-.66-.45-1.11-.11-1.71-.44-.85-.46-.4-1.36-1.37-1.89-.3-.17-.63-.23-.75-.52s.08-.55-.12-.71-.39,0-.56-.16,0-.55-.18-.66-.26.05-.41,0,0-.67-.34-1-1-.09-1.13-.37.13-.28.06-.6a.66.66,0,0,0-.3-.45c-.26-.14-.48,0-.65-.07s0-.53,0-1.2a1.63,1.63,0,0,0-.15-.74c-.29-.55-.84-.47-1.73-1.1-.7-.5-.8-.87-1.22-.85s-.41.22-1,.25c-.15,0-.48,0-.61-.13s0-.13-.09-.28-.25-.19-.35-.37,0-.2-.05-.44-.16-.2-.25-.42,0-.35-.08-.61a1.21,1.21,0,0,0-.32-.45c-.51-.57-.78-.87-1.13-.86s-.39.18-.57.11-.15-.36-.33-.41-.35.23-.53.2c-.38-.07-.13-1.33-.82-1.64-.19-.09-.33,0-.53-.19s-.23-.33-.35-.65a2.37,2.37,0,0,0-.65-1.07c-.26-.22-.36-.15-.75-.41a3,3,0,0,1-.78-.76,14.43,14.43,0,0,0-1.39-1.4c-1.59-1.45-4.38-.39-5.4-1.79-.18-.25,0-.2-.36-.91a5.8,5.8,0,0,0-1.49-2.2c-.45-.37-.82-.53-.93-1s.07-.37,0-.68-.48-.67-1.16-1.13-.64-.33-.87-.57c-.38-.4-.29-.65-.68-1a3.08,3.08,0,0,0-.83-.39,8.55,8.55,0,0,1-1.31-.8c-1.2-.74-1.82-.32-2.58-.88-1.12-.82-1-2.57-1.05-2.56s.13.74-.23.93a.94.94,0,0,1-1-.28c-.31-.37.08-.72,0-1.53-.07-.53-.35-1.21-.77-1.27s-.41.29-.71.27c-.61-.05-.54-1.47-1.45-1.76-.35-.1-.51,0-.92-.12s-.47-.51-.8-.6-.73.42-1.4.6c-1.16.3-2.73-.72-3.46-1.79a5.07,5.07,0,0,1-.44-.87,7.16,7.16,0,0,0-3.13-2.86c-2.11-1.14-3.6-.91-4.66-2.35a2.82,2.82,0,0,1-.27-.42c-1.21-2-1.15-3.5-1.86-3.59-.27,0-.47.16-.63,0s.14-.83-.11-1-.51.13-.61,0,.18-.32.08-.51-.47-.07-.61-.25.4-.8.18-1.07-.45,0-.61-.21.14-.51-.07-.79c-.11-.13-.24-.12-.3-.28s0-.21,0-.37a.89.89,0,0,0-.18-.55c-.28-.39-.9-.12-1.26-.47s0-.81-.37-1.14-.51-.1-.77-.33-.18-.55-.23-.86c-.09-.64-.65-.67-1.63-1.69s-.89-1.51-1.65-1.79c-.32-.12-.7-.17-.87-.46s0-.38,0-.73a1.73,1.73,0,0,0-.88-1.36c-.56-.3-1-.11-1.25-.41s.15-.43,0-.75-.56-.3-1.18-.74a4.58,4.58,0,0,1-.6-.52,7.09,7.09,0,0,0-1.91-1.08c-1.13-.55-.86-.94-2.13-1.86a9.49,9.49,0,0,0-3.33-1.51c-1.5-.38-1.43,0-2.89-.32a14.27,14.27,0,0,1-4.47-2,4.45,4.45,0,0,0-1.37-.68,2.89,2.89,0,0,0-1.92-.05c-.4.18-.42.42-1.15.83l-.58.28-1.24.59A8.69,8.69,0,0,0,182,85.25c-.64.69-.43.59-.8.94-1.47,1.37-3.07,1.29-3.54,2.41-.09.23,0,.25-.2.74a6.44,6.44,0,0,1-.7,1.47,10.08,10.08,0,0,1-1.13,1.3c-.92,1-.93,1.07-1.4,1.56s-.79.81-1.27,1.22a13.11,13.11,0,0,1-2.43,1.53c-.83.48-1.53,1.39-2.91,3.22-1,1.28-3.08,4.23-4.17,4.34a1.19,1.19,0,0,0-.56.19c-.29.22-.26.52-.33.81-.14.57-.59,1-1.49,1.74-.59.5-.79.54-.89.85-.16.46.21.68.35,1.28.24,1-.47,1.21-.93,3.15a3.85,3.85,0,0,1-.38,1.13c-.16.3-.28.42-.46.81s-.16.49-.29.78a5.36,5.36,0,0,1-1,1.19,6.93,6.93,0,0,0-.93,1.09,2.62,2.62,0,0,0-.33.67c-.28.88.17,1.34-.24,1.75-.15.14-.34.21-.41.44s0,.27,0,.44-.33.28-.56.43a1.74,1.74,0,0,0-.63.84c-.63,1.19-3.84,4.08-4.69,3.66-.14-.06-.23-.22-.44-.22s-.43.31-.71.29-.32-.3-.54-.29-.35.36-.64.82c-.46.75-.66.6-.86,1.07-.31.71.05,1.26-.37,1.5a2.09,2.09,0,0,0-.44.23c-.24.19-.25.37-.37.37s-.18-.18-.42-.48-.35-.44-.56-.52c-.47-.18-.94.26-1.13.11s.34-.7.29-1.51c0-.5-.31-1-.58-1s-.37.6-.74.64c-.53.05-.82-1.24-1.51-1.27-.39,0-.39.42-1.48,1.31-.71.57-1.12.73-1.18,1.16,0,.21,0,.54-.19.63s-.2,0-.29,0,.07.42-.07.73-.48.21-.91.67c-.16.18-.19.27-.37.36s-.38,0-.64.11-.47.33-.7.65-.21.47-.47.71-.21.12-.38.27c-.4.35-.28.6-.64,1s-.41.25-.63.56-.1.49-.3.84c-.27.5-.69.46-1,.91s-.1.63-.18,1.07c-.12.76-.68.75-1.82,2.12a6.43,6.43,0,0,0-1.35,2.11,1.63,1.63,0,0,1-.23.52c-.32.47-.75.52-1,1a1.39,1.39,0,0,0-.15.53c-.25.95-2.12,1.7-3.67,1.91a6.25,6.25,0,0,0-2.35.4,9.5,9.5,0,0,0-1,.59c-1.07.73-1,.79-1.21.85-1,.25-1.84-1.24-3-1-.42.09-.73.38-1.21.26-.28-.08-.43-.25-.65-.19s-.26.23-.44.45c-.32.42-.57.35-.89.66-.56.56-.12,1.16-.76,1.8a.6.6,0,0,1-.42.23c-.33,0-.43-.41-.78-.45s-.65.47-.92.4-.12-.26-.3-.6-.63-.84-1-.8-.53.68-.76.65-.16-.56-.47-.64-.24.09-.43.08c-.5,0-.71-.91-1-.89s-.16.28-.41.37-.59-.28-1-.31c-.69-.05-.84.84-2,1.22-.6.19-.67,0-1.39.22s-.82.57-1.38.61-.87-.3-1.4-.17a1.58,1.58,0,0,0-.68.48,18.8,18.8,0,0,1-2.27,1.55c-.88.55-1.05.61-1.26.6-.6,0-.85-.45-1.39-.39-.36,0-.58.28-.9.63-.79.88-.73,1.64-1.31,1.77-.24,0-.45,0-.67.12s-.1.09-.29.41-.33.54-.48.62-.23,0-.35.1c-.33.18-.11.7-.2,1.2a2.94,2.94,0,0,1-2.36,1.93,2,2,0,0,0-.7.15,1.86,1.86,0,0,0-.83.7c-.93,1.31-4.47.95-5.74,2.39a1.12,1.12,0,0,1-.76.49c-.48,0-.78-.46-1.1-.35s-.14.29-.42.44-.46,0-.68.1c-.38.2-.15.85-.44.94s-.39-.24-.63-.19-.21.74-.57,1.34a3.91,3.91,0,0,1-3.33,1.5c-.24,0-.75-.11-1,.11s-.06.38-.27.53-.36,0-.6.12-.26.47-.51.81c-.4.53-1,.25-1.75.82-.56.42-.58.83-.94.84s-.36-.31-.93-.53a1.61,1.61,0,0,0-1.2,0c-.22.14-.14.3-.38.51s-.91.38-1,.23.49-.73.26-1-.41-.16-.6-.13-.45.16-.86.78a3.79,3.79,0,0,0-.52.92c-.55,1.46-3.71,3.45-4.63,4-1.49.94-1,.39-2.83,1.47-.56.33-1,.58-1.3.46-.64-.22-.41-1.41-1.12-1.62-.33-.1-.45.15-.87.06-.61-.13-.56-.71-1.35-1.09-.1,0-.7-.33-1-.13a.62.62,0,0,0-.25.46c-.23.75-1.79.65-3.49,1.48a11.13,11.13,0,0,1-2.54,1.27c-.42.08-.93.12-.93.12a3.7,3.7,0,0,1-.89,0c-.64-.11-.71-.46-1.06-.49-.76-.07-.94,1.48-2.18,2s-2.12-.62-3.11-.15c-1.28.6-.93,3-1.91,3.09-.32.05-.63-.17-.93,0s-.21.31-.36.64c-.28.64-.57.61-.76,1.06-.3.74.33,1.19,0,1.51s-1-.16-1.86.19c-.57.22-.57.53-1.11.6s-.65-.12-1.11,0-.32.2-.67.38c-.75.38-1.17.05-1.6.38s-.18.75-.67,1.05c-.28.19-.45.09-.8.19-1.09.34-.79,1.82-2.08,2.53-.4.22-.5.12-.89.38-.58.38-.6.77-1.16,1.24a3.45,3.45,0,0,1-1,.57c-1.94.89-2.42,4.63-5.28,6-1.47.7-1.87,0-3.51.83-1.49.78-2,1.85-3.51,1.92a8.42,8.42,0,0,0-1.42,0A3.49,3.49,0,0,0,0,200Z" transform="translate(0 -81.23)"/><path class="cls-2" d="M490.75,187.83a1.28,1.28,0,0,1-.48-.09c-.25-.15,0-.36-.28-.46s-.31,0-.55,0a2,2,0,0,1-1.23-.54c-.57-.5-.15-.75-.65-1.06s-1.21-.27-1.24-.48.34-.21.33-.36-.62-.25-1.12-.43a3.09,3.09,0,0,1-1.86-2.08,1.71,1.71,0,0,0-.33-.68c-.3-.36-.56-.38-.92-.78s-.17-.42-.5-.73a1.79,1.79,0,0,0-1.17-.58c-.19,0-.17.18-.53.33a1.64,1.64,0,0,1-.38.11c-.54.1-.85-.11-1.21,0-.2.07-.15.15-.4.22a1.39,1.39,0,0,1-.64,0c-.26-.05-.23-.13-.44-.19a1.75,1.75,0,0,0-1.37.24c0,.06-.07.12-.17.15s-.25,0-.43-.07a2.32,2.32,0,0,0-.92.18c-.88.25-1,.22-1,.2s0-.35-.12-.37-.32.19-.42.16.05-.14,0-.28-.27-.14-.34-.26.08-.19.1-.34-.51-.63-.8-.59-.12.12-.27.15a1.29,1.29,0,0,1-1.08-.57c-.11-.21,0-.26-.14-.4s-.48-.15-.91-.33-.59-.53-.83-.52-.36.43-.58.43-.19-.25-.53-.32a.74.74,0,0,0-.34,0c-.25.06-.21.24-.37.27s-.81-.48-1-.43,0,.3-.17.39a.83.83,0,0,1-.47,0c-1-.2-1.35-1.24-2.61-1.48a1.86,1.86,0,0,0-1.15.07c-.29.21.14.66,0,.69s-.18-.15-.47-.28c-.48-.22-.81-.05-1.25-.21-.62-.23-.29-.67-1-.93-.23-.08-.46-.12-.55-.26s.06-.27-.08-.35-.3,0-.42-.08,0-.26-.13-.32-.19,0-.3,0,0-.33-.25-.48-.71-.05-.82-.19.09-.13,0-.29a.34.34,0,0,0-.22-.22c-.19-.07-.35,0-.47,0s0-.26,0-.59a.61.61,0,0,0-.11-.36c-.21-.27-.61-.23-1.26-.54s-.59-.42-.89-.42-.3.11-.69.13a1,1,0,0,1-.45-.06c-.06,0,0-.07-.06-.14s-.19-.09-.26-.18,0-.1,0-.22-.11-.1-.18-.21,0-.17-.06-.29-.05-.09-.23-.23c-.37-.28-.57-.42-.83-.42s-.28.09-.41.06-.11-.18-.25-.21-.25.12-.39.1-.09-.65-.59-.8a2.46,2.46,0,0,1-.39-.09c-.16-.08-.17-.17-.26-.32a1.16,1.16,0,0,0-.47-.53c-.19-.11-.27-.07-.55-.2a2.06,2.06,0,0,1-.57-.37,11,11,0,0,0-1-.69c-1.17-.71-3.21-.19-3.95-.88-.13-.12,0-.1-.27-.45a3,3,0,0,0-1.08-1.07c-.33-.19-.6-.26-.68-.48s0-.18,0-.33-.35-.33-.85-.56a5,5,0,0,1-.63-.27c-.28-.2-.22-.32-.5-.49a3.77,3.77,0,0,0-.61-.19,6.28,6.28,0,0,1-.95-.39c-.88-.36-1.33-.16-1.89-.43-.82-.4-.69-1.26-.77-1.26s.1.37-.17.46a.91.91,0,0,1-.72-.14c-.22-.18.06-.35,0-.75s-.25-.6-.56-.62-.3.14-.52.13c-.45,0-.39-.72-1.06-.86a6.12,6.12,0,0,1-.67-.07c-.35-.1-.35-.24-.59-.29s-.53.21-1,.29a3.39,3.39,0,0,1-2.53-.87,3.19,3.19,0,0,1-.32-.43,5.07,5.07,0,0,0-2.29-1.4c-1.54-.56-2.62-.45-3.4-1.16l-.2-.2c-.88-1-.84-1.72-1.35-1.77-.2,0-.35.09-.47,0s.11-.41-.08-.48-.37.06-.44,0,.13-.16.05-.25-.34,0-.44-.13.29-.39.13-.52-.32,0-.44-.11.1-.25-.06-.38-.17-.07-.22-.14,0-.11,0-.18a.36.36,0,0,0-.12-.27c-.21-.19-.66-.06-.93-.23s0-.4-.27-.57-.37,0-.56-.16-.13-.27-.17-.42c-.07-.31-.47-.33-1.19-.83s-.65-.74-1.21-.88c-.23-.05-.51-.08-.63-.22s0-.19,0-.36a.9.9,0,0,0-.64-.67c-.41-.14-.77-.05-.92-.2s.11-.21,0-.37-.4-.14-.86-.36c-.21-.1-.27-.16-.44-.25a5.61,5.61,0,0,0-1.39-.53c-.82-.28-.63-.46-1.56-.92a8.88,8.88,0,0,0-2.43-.74c-1.1-.18-1,0-2.11-.16a13.44,13.44,0,0,1-3.27-.95,3.62,3.62,0,0,0-1-.33,3,3,0,0,0-1.4,0c-.3.09-.31.21-.84.4l-.42.14-.91.29a6.56,6.56,0,0,0-1.92,1.07c-.46.33-.31.29-.58.46-1.07.67-2.24.63-2.58,1.18-.07.11,0,.12-.15.37a3.21,3.21,0,0,1-.51.72,6.82,6.82,0,0,1-.83.64c-.67.5-.68.52-1,.76s-.58.4-.93.6a11.39,11.39,0,0,1-1.77.75,7.62,7.62,0,0,0-2.13,1.58c-.7.63-2.25,2.08-3.05,2.13a1,1,0,0,0-.41.09c-.21.11-.19.26-.24.4s-.43.47-1.09.85c-.43.25-.57.27-.65.42s.15.33.26.63c.17.47-.35.59-.68,1.55a1.5,1.5,0,0,1-.28.55c-.11.15-.2.21-.34.4s-.11.24-.21.38-.11.15-.7.58a5.32,5.32,0,0,0-.68.54,1.33,1.33,0,0,0-.24.33c-.21.43.13.66-.17.86-.11.07-.25.1-.3.21s0,.14,0,.22-.24.14-.4.21a1,1,0,0,0-.46.41c-.47.59-2.81,2-3.43,1.8-.1,0-.17-.11-.33-.11s-.3.15-.51.15-.24-.15-.39-.15-.26.18-.47.41-.49.29-.63.52,0,.62-.27.74a1,1,0,0,0-.32.11c-.18.09-.18.18-.27.18s-.14-.09-.31-.24a1.08,1.08,0,0,0-.41-.25c-.34-.09-.69.12-.83.05s.26-.34.21-.74c0-.24-.22-.5-.42-.51s-.27.3-.53.32-.61-.61-1.11-.62c-.29,0-.29.2-1.09.64-.51.28-.81.36-.86.57s0,.27-.14.31-.14,0-.2,0,.05.2-.06.36-.34.1-.66.33c-.12.08-.14.13-.27.17a3.55,3.55,0,0,1-.47.06,1,1,0,0,0-.51.32c-.17.16-.16.23-.35.34s-.15.06-.27.14c-.3.17-.21.29-.47.5s-.3.12-.46.27-.08.24-.22.41-.5.23-.72.45-.08.31-.13.53c-.09.37-.5.36-1.33,1a3.45,3.45,0,0,0-1,1,.76.76,0,0,1-.16.26c-.24.23-.56.25-.75.48a.56.56,0,0,0-.11.26c-.18.47-1.55.83-2.68.94a6.28,6.28,0,0,0-1.72.19L345,172c-.79.36-.71.39-.89.42-.76.12-1.34-.61-2.19-.49a2.32,2.32,0,0,1-.88.13c-.21,0-.32-.12-.48-.09s-.19.11-.32.22-.42.17-.65.32c-.41.28-.09.57-.56.89a.55.55,0,0,1-.3.11c-.25,0-.32-.21-.57-.22s-.48.23-.67.19-.09-.13-.22-.29a1.09,1.09,0,0,0-.73-.4c-.3,0-.39.34-.56.32s-.12-.27-.34-.31-.18,0-.32,0c-.36,0-.51-.45-.75-.43s-.12.13-.31.18-.43-.14-.75-.16c-.51,0-.62.42-1.44.6-.43.1-.48,0-1,.11s-.61.28-1,.3-.64-.15-1-.08a1.09,1.09,0,0,0-.5.23,14.68,14.68,0,0,1-1.66.76,2.47,2.47,0,0,1-.92.3c-.44,0-.62-.23-1-.2a1.14,1.14,0,0,0-.66.32c-.58.43-.53.8-1,.86a2,2,0,0,0-.49.06c-.06,0-.07,0-.21.2s-.24.27-.35.31-.17,0-.26.05-.08.34-.14.59c-.12.45-1,.88-1.73.94a2.26,2.26,0,0,0-.51.07,1.47,1.47,0,0,0-.61.35c-.68.64-3.26.46-4.19,1.17a.89.89,0,0,1-.56.24c-.34,0-.57-.22-.8-.17s-.1.15-.31.22-.33,0-.49,0-.11.42-.32.47-.29-.12-.46-.1-.16.37-.42.66a3.53,3.53,0,0,1-2.43.74,1.46,1.46,0,0,0-.72.05c-.12.09,0,.19-.19.26s-.27,0-.44.06-.2.23-.38.4-.73.12-1.28.4-.42.41-.68.41-.26-.15-.68-.25a1.72,1.72,0,0,0-.88,0c-.16.07-.1.15-.28.25a1.09,1.09,0,0,1-.77.12c-.13-.1.37-.36.2-.5a.71.71,0,0,0-.44-.07,1,1,0,0,0-.63.39,1.69,1.69,0,0,0-.38.45c-.4.71-2.71,1.69-3.38,2-1.09.46-.7.19-2.07.72a1.77,1.77,0,0,1-.95.22c-.47-.11-.3-.69-.82-.79-.24,0-.33.07-.63,0s-.41-.35-1-.54a1.43,1.43,0,0,0-.76-.06c-.15.05-.16.16-.19.22-.16.37-1.3.32-2.54.73a10,10,0,0,1-1.86.63l-.68,0a3.46,3.46,0,0,1-.65,0c-.47,0-.52-.22-.78-.24-.54,0-.68.73-1.59,1s-1.55-.3-2.27-.07c-.93.3-.68,1.45-1.39,1.52-.24,0-.46-.09-.69,0s-.15.15-.26.31-.41.3-.55.52.24.59,0,.74-.71-.08-1.36.09c-.42.12-.42.27-.81.3a7.3,7.3,0,0,0-.81,0,2.34,2.34,0,0,0-.49.19c-.55.18-.85,0-1.17.18s-.13.37-.49.52a2.52,2.52,0,0,1-.58.09c-.8.17-.58.9-1.53,1.24-.29.11-.36.06-.65.19s-.43.38-.84.61a3.33,3.33,0,0,1-.71.28c-1.43.44-1.77,2.27-3.87,2.94-1.07.35-1.36,0-2.56.41s-1.49.91-2.57.94a8.85,8.85,0,0,0-1,0,2.85,2.85,0,0,0-1.43.62h233.2Z" transform="translate(0 -81.23)"/><path class="cls-2" d="M242.27,199.15a7.1,7.1,0,0,0-1.13,0c-1.16-.05-1.59-.7-2.76-1.18s-1.61-.08-2.77-.5c-2.26-.84-2.63-3.12-4.16-3.66a3.12,3.12,0,0,1-.77-.35c-.44-.29-.46-.52-.92-.76-.3-.15-.38-.09-.7-.23-1-.43-.78-1.33-1.64-1.54-.27-.06-.4,0-.63-.11s-.19-.45-.53-.65-.66,0-1.26-.23c-.27-.1-.27-.17-.52-.23a8.61,8.61,0,0,0-.88,0c-.42,0-.42-.23-.87-.36-.7-.22-1.21.08-1.47-.12s.24-.47,0-.92c-.15-.27-.38-.26-.6-.64-.11-.21-.11-.32-.28-.4s-.48,0-.73,0c-.78-.08-.51-1.51-1.51-1.88-.78-.29-1.49.38-2.45.09s-1.12-1.24-1.72-1.2c-.27,0-.33.24-.84.3a3.19,3.19,0,0,1-.7,0s-.39,0-.73-.07a9.92,9.92,0,0,1-2-.77c-1.34-.51-2.57-.45-2.75-.91,0-.08,0-.21-.19-.27a1.31,1.31,0,0,0-.83.07c-.62.23-.58.59-1.07.67-.32,0-.41-.1-.68,0-.55.13-.38.85-.88,1-.27.07-.58-.08-1-.28-1.48-.66-1.06-.32-2.23-.9-.73-.35-3.22-1.57-3.65-2.46a2.64,2.64,0,0,0-.41-.56c-.33-.37-.52-.45-.68-.47a.7.7,0,0,0-.48.08c-.18.17.36.5.21.62a1,1,0,0,1-.82-.14c-.2-.13-.13-.23-.31-.31a1.67,1.67,0,0,0-.94,0c-.45.14-.49.33-.74.32s-.29-.25-.73-.51c-.6-.34-1.07-.17-1.38-.5s-.14-.4-.41-.49-.32,0-.47-.08-.08-.21-.21-.31a1.31,1.31,0,0,0-.78-.07,3.53,3.53,0,0,1-2.62-.92c-.28-.37-.18-.78-.45-.82s-.34.17-.5.12,0-.45-.34-.57-.32,0-.54-.07-.16-.22-.32-.26-.5.23-.87.21a1,1,0,0,1-.61-.3c-1-.88-3.78-.66-4.51-1.45a1.48,1.48,0,0,0-.66-.43,1.87,1.87,0,0,0-.55-.09c-.78-.08-1.74-.62-1.86-1.18-.08-.3.1-.62-.16-.73-.09,0-.15,0-.27-.06s-.17-.11-.38-.38-.17-.22-.23-.25a1.58,1.58,0,0,0-.53-.07c-.46-.08-.41-.54-1-1.08a1.33,1.33,0,0,0-.71-.39c-.42,0-.62.24-1.09.24-.17,0-.31,0-1-.37a15,15,0,0,1-1.79-.94,1.24,1.24,0,0,0-.54-.29c-.41-.08-.61.13-1.1.1s-.46-.21-1.09-.37-.62,0-1.09-.14c-.88-.22-1-.77-1.55-.74-.35,0-.54.26-.81.19s-.19-.21-.33-.22-.42.53-.81.54c-.16,0-.21-.08-.35-.05s-.2.37-.36.39-.29-.37-.61-.4a1.06,1.06,0,0,0-.78.49c-.14.21-.11.34-.24.36s-.41-.26-.72-.24-.36.28-.62.28a.5.5,0,0,1-.33-.14c-.5-.39-.16-.76-.6-1.1-.25-.19-.45-.15-.7-.4s-.18-.25-.34-.28-.3.07-.52.12a2.37,2.37,0,0,1-1-.16c-.91-.15-1.54.76-2.36.6-.19,0-.11-.07-.95-.51-.33-.17-.55-.29-.75-.37a6.7,6.7,0,0,0-1.85-.24c-1.22-.12-2.7-.58-2.89-1.16a.89.89,0,0,0-.12-.33c-.21-.27-.55-.3-.81-.59a.91.91,0,0,1-.17-.32,4.28,4.28,0,0,0-1.07-1.29c-.9-.84-1.34-.82-1.44-1.29-.06-.27.07-.42-.14-.65s-.56-.25-.78-.56,0-.3-.23-.51-.28-.14-.5-.34-.19-.41-.51-.62c-.13-.09-.16-.07-.29-.17s-.19-.23-.38-.43a1,1,0,0,0-.55-.4,2.73,2.73,0,0,1-.5-.06,1,1,0,0,1-.29-.22c-.34-.28-.61-.22-.72-.41s.08-.39-.06-.45-.13,0-.22,0-.13-.26-.15-.38c-.05-.27-.37-.36-.93-.71-.86-.55-.86-.81-1.17-.8-.54,0-.77.81-1.19.77s-.35-.39-.58-.39-.43.33-.45.64c0,.49.39.82.22.92s-.52-.18-.89-.07a1.15,1.15,0,0,0-.44.31c-.19.18-.24.3-.33.3s-.11-.11-.29-.23a2.52,2.52,0,0,0-.35-.14c-.33-.14-.05-.48-.29-.91s-.31-.2-.68-.65c-.23-.28-.32-.51-.51-.51s-.21.18-.42.18-.31-.18-.56-.18-.24.1-.34.14c-.68.26-3.2-1.51-3.7-2.24a1.19,1.19,0,0,0-.5-.51c-.18-.08-.37-.12-.44-.26s0-.15,0-.27-.2-.18-.32-.27c-.32-.24,0-.52-.18-1.06a1.46,1.46,0,0,0-.27-.41,4.36,4.36,0,0,0-.73-.67c-.64-.53-.71-.64-.75-.72s-.08-.23-.23-.47-.24-.32-.37-.5a2.09,2.09,0,0,1-.3-.69c-.35-1.18-.92-1.34-.73-1.92.11-.37.4-.5.28-.78s-.24-.21-.7-.52c-.71-.47-1.07-.71-1.18-1.06,0-.18,0-.36-.26-.49a1.16,1.16,0,0,0-.44-.12c-.86-.07-2.53-1.87-3.29-2.64a8.39,8.39,0,0,0-2.29-2,11.09,11.09,0,0,1-1.92-.93c-.38-.25-.7-.51-1-.74s-.37-.33-1.1-1a7,7,0,0,1-.89-.8,3.47,3.47,0,0,1-.55-.89c-.13-.3-.09-.32-.16-.45-.37-.69-1.63-.64-2.78-1.48-.3-.21-.13-.15-.63-.57a6.89,6.89,0,0,0-2.07-1.32l-1-.36-.46-.17c-.57-.25-.58-.39-.91-.51a2.88,2.88,0,0,0-1.5,0,4,4,0,0,0-1.09.41,12.63,12.63,0,0,1-3.52,1.18c-1.15.21-1.09,0-2.27.2a9.08,9.08,0,0,0-2.63.92c-1,.57-.79.8-1.68,1.14a6,6,0,0,0-1.51.66,3.66,3.66,0,0,1-.46.31c-.5.27-.82.25-.94.45s.13.31,0,.46-.54.07-1,.25a1.08,1.08,0,0,0-.7.83c0,.21.11.3,0,.45s-.43.2-.68.27c-.6.17-.49.45-1.3,1.1s-1.21.64-1.29,1c0,.18,0,.37-.18.52s-.4.07-.6.2,0,.49-.29.7-.78.05-1,.28a.51.51,0,0,0-.14.34c0,.1.05.15,0,.23s-.15.09-.23.17.08.34-.06.48-.38,0-.49.13.33.48.15.65-.4,0-.48.15.14.25.06.31-.34-.07-.48,0,.12.49-.09.61-.28-.06-.49,0c-.56.06-.52,1-1.47,2.19a1.66,1.66,0,0,1-.21.26c-.84.87-2,.74-3.67,1.43a5.49,5.49,0,0,0-2.47,1.74,3.77,3.77,0,0,1-.34.54,3.38,3.38,0,0,1-2.73,1.08c-.53-.1-.74-.43-1.11-.36s-.26.24-.63.36-.45,0-.72.08c-.72.17-.66,1-1.14,1.07-.24,0-.32-.19-.57-.16s-.55.45-.6.77c-.08.5.22.71,0,.93a.89.89,0,0,1-.77.18c-.29-.12-.13-.57-.19-.57s.06,1.06-.82,1.56c-.61.34-1.09.08-2,.54a8,8,0,0,1-1,.48,2.82,2.82,0,0,0-.65.24c-.31.2-.24.36-.54.6s-.22.1-.68.35-.83.44-.91.69.06.22,0,.41-.39.36-.74.59a3.67,3.67,0,0,0-1.17,1.34c-.27.43-.15.4-.29.55-.8.85-3,.21-4.26,1.09a11.9,11.9,0,0,0-1.09.86,2.11,2.11,0,0,1-.61.46c-.31.16-.39.12-.6.25a1.46,1.46,0,0,0-.51.65c-.09.2-.1.31-.27.4a1.74,1.74,0,0,1-.42.11c-.54.19-.35,1-.65,1-.14,0-.26-.15-.42-.12s-.1.2-.26.25-.23-.06-.44-.07-.5.18-.9.53a1,1,0,0,0-.25.27c-.06.16,0,.22-.06.38s-.15.12-.2.25,0,.17,0,.27-.2.1-.27.23,0,.12-.07.17-.36.08-.49.08c-.42,0-.5-.15-.74-.16s-.41.21-1,.52c-.7.38-1.14.34-1.37.67a1,1,0,0,0-.12.45c0,.41.17.64,0,.73s-.31,0-.51.05a.4.4,0,0,0-.24.27c-.05.2.11.26.05.37s-.63,0-.89.23,0,.53-.27.59-.22,0-.32,0,0,.29-.14.41-.32,0-.45.09,0,.26-.09.44-.35.21-.59.31c-.77.33-.41.88-1.08,1.16-.48.2-.83,0-1.35.27-.31.16-.44.37-.51.34s.32-.6,0-.86a1.79,1.79,0,0,0-1.24-.09c-1.35.3-1.74,1.6-2.82,1.85a.84.84,0,0,1-.5,0c-.23-.12,0-.44-.18-.48s-.76.6-1.08.53-.13-.27-.41-.34a.69.69,0,0,0-.36,0c-.37.08-.38.39-.57.39s-.32-.5-.63-.52-.3.34-.89.64-.76.17-1,.41,0,.24-.16.5a1.32,1.32,0,0,1-1.15.71c-.17,0-.14-.17-.3-.19-.31-.05-.91.36-.87.74,0,.18.19.26.12.41s-.28.16-.37.33,0,.33-.05.35-.33-.24-.45-.21.06.37-.13.46-.17.06-1.11-.24c-.58-.18-.75-.27-1-.22s-.32.12-.47.08-.13-.11-.18-.18a1.66,1.66,0,0,0-1.47-.3c-.23.07-.2.17-.48.24a1.37,1.37,0,0,1-.69,0c-.27-.08-.21-.19-.44-.27-.38-.14-.71.12-1.3,0a1.7,1.7,0,0,1-.4-.14c-.4-.18-.38-.36-.58-.4-.36-.08-.94.35-1.26.71s-.2.47-.54.91-.67.52-1,1A1.7,1.7,0,0,0,0,179v20.93H243.81A2.89,2.89,0,0,0,242.27,199.15Z" transform="translate(0 -81.23)"/></svg>';
		break;
		case 'singlewave' :
			// var svgpath = '<svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 1000 99" preserveAspectRatio="none"><path d="M768.06,59.54C687,48.21,607.41,28.42,526.35,17.15,347.45-7.73,155.24,13.87.07,99H1000V68.11A1149.19,1149.19,0,0,1,768.06,59.54Z"></path></svg>';
			if (flip == '1') {
				var svgpath = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none" ><path d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z" fill="#ffffff"></path></svg>';
			} else {
				var svgpath = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none" ><path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" fill="#ffffff"></path></svg>';
			}
		break;
		case 'multislope' :
			var svgpath = '<svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none"><path d="M0,22.3V0H1000V100Z" transform="translate(0 0)" style="opacity:0.66"></path><path d="M0,6V0H1000V100Z" transform="translate(0 0)"></path></svg>';
		break;
		case 'slope' :
			var svgpath = '<svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 1000 94" preserveAspectRatio="none"><polygon points="0 94 1000 94 0 0 0 94"></polygon></svg>';
		break;
		case 'waves3' :
			var svgpath = '<svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 1000 84.94" preserveAspectRatio="none"><path d="M0,0V72.94c14.46,5.89,32.38,10.5,54.52.26,110.25-51,120.51,23.71,192.6-4.3,144.73-56.23,154.37,49.44,246.71,4.64C637,4.05,622.19,124.16,757.29,66.21c93-39.91,108.38,54.92,242.71-8.25V0Z" style="fill-rule:evenodd;opacity:0.33"></path><path d="M0,0V52.83c131.11,59.9,147-32.91,239.24,6.65,135.09,58,120.24-62.16,263.46,7.34,92.33,44.8,102-60.88,246.71-4.64,72.1,28,82.35-46.71,192.6,4.3,23.95,11.08,43,4.78,58-1.72V0Z" style="fill-rule:evenodd;opacity:0.66"></path><path d="M0,0V24.26c15.6,6.95,35.77,15.41,61.78,3.38,110.25-51,120.51,23.71,192.6-4.3C399.11-32.89,408.75,72.79,501.08,28,644.3-41.51,629.45,78.6,764.54,20.65,855.87-18.53,872.34,72.12,1000,15.7V0Z" style="fill-rule:evenodd"></path></svg>';
		break;
		case 'drip' :
			var svgpath = '<svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 1000 91.02" preserveAspectRatio="none"><path d="M772,11c-32.4,4-58,9.51-58,9.51C685.3,26.69,659.67,34.32,658,35c-15.34,6.3-25.24,13.11-43,13-27.54-.18-37.37-16.79-56-11-19,5.91-19.53,26.54-35,27-13.47.4-16.5-15.14-36-18-1.32-.19-15.92-2.13-29,6-20.34,12.64-18.82,38.28-28,39-8.62.68-10.8-21.86-26-40-5.44-6.49-24.19-25.34-100-32a429.73,429.73,0,0,0-94,2C165,26.91,96.11,27.3,0,0V91H1000V0C894.78,1.07,813.3,5.92,772,11Z" transform="translate(0 0)"></path></svg>';
		break;
		case 'asymslope' :
			var svgpath = '<svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 1000 89" preserveAspectRatio="none"><polygon points="0 89 741 89 0 0 0 89"></polygon><polygon points="741 89 1000 89 1000 0 741 89"></polygon></svg>';
		break;
		case 'vslope' :
			// var svgpath = '<svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 1000 89" preserveAspectRatio="none" style="width: 100%; max-width: 100%;"><polygon points="0 89 500 89 0 0 0 89"></polygon><polygon points="500 89 1000 89 1000 0 500 89"></polygon></svg>';
			if (flip == '1') {
				var svgpath = '<svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M598.97 114.72L0 0 0 120 1200 120 1200 0 598.97 114.72z" fill="#ffffff"></path></svg>';
			} else {
				var svgpath = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M1200 0L0 0 598.97 114.72 1200 0z" fill="#ffffff"></path></svg>';
			}
		break;
		case 'multivslope' :
			var svgpath = '<svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 1000 89" preserveAspectRatio="none" style="width: 100%; max-width: 100%;"><polygon points="0 89 500 89 0 20 0 89"></polygon><polygon points="500 89 1000 89 1000 20 500 89"></polygon><polygon style="opacity: 0.6;" points="0 20 500 89 0 0 0 89"></polygon><polygon style="opacity: 0.6;" points="500 89 1000 20 1000 0 500 89"></polygon></svg>';
		break;
		case 'multiv3slope' :
			var svgpath = '<svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 1000 89" preserveAspectRatio="none" ><polygon points="0 89 500 89 0 40 0 89"></polygon><polygon points="500 89 1000 89 1000 40 500 89"></polygon><polygon style="opacity: 0.6;" points="0 40 500 89 0 20 0 69"></polygon><polygon style="opacity: 0.6;" points="500 89 1000 20 1000 40 500 89"></polygon><polygon style="opacity: 0.3;" points="0 20 500 89 0 0 0 89" ></polygon><polygon style="opacity: 0.3;" points="500 89 1000 20 1000 0 500 89"></polygon></svg>';
		break;
		case 'triangle' :
			// var svgpath = '<svg xmlns="https://www.w3.org/2000/svg" preserveAspectRatio="none" viewBox="0 0 1000 100"><polygon points="0 100 1000 100 1000 50 550 50 500 0 450 50 0 50 0 100"></polygon></svg>';
			if (flip == '1') {
				var svgpath = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M649.97 0L599.91 54.12 550.03 0 0 0 0 120 1200 120 1200 0 649.97 0z" fill="#ffffff"></path></svg>';
			} else {
				var svgpath = '<svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M649.97 0L550.03 0 599.91 54.12 649.97 0z" fill="#ffffff"></path></svg>';
			}
		break;
		case 'trianglesmall' :
			var svgpath = '<svg xmlns="https://www.w3.org/2000/svg" preserveAspectRatio="none" viewBox="0 0 1000 50"><polygon points="0 50 1000 50 1000 25 520 25 500 0 480 25 0 25 0 50"></polygon></svg>';
		break;
		case 'triangle3' :
			var svgpath = '<svg xmlns="https://www.w3.org/2000/svg" preserveAspectRatio="none" viewBox="0 0 1000 50"><polygon points="0 50 1000 50 1000 25 560 25 540 0 520 25 500 0 480 25 460 0 440 25 0 25 0 50"></polygon></svg>';
		break;
		case 'ellipse' :
			// var svgpath = '<svg xmlns="https://www.w3.org/2000/svg" preserveAspectRatio="none" viewBox="0 0 1000 50"><path d="M0 50 C 200 0 500 0 1000 50 Z"></path></svg>';
			if (flip == '1') {
				var svgpath = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M600,112.77C268.63,112.77,0,65.52,0,7.23V120H1200V7.23C1200,65.52,931.37,112.77,600,112.77Z" fill="#ffffff"></path></svg>';
			} else {
				var svgpath = '<svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M0,0V7.23C0,65.52,268.63,112.77,600,112.77S1200,65.52,1200,7.23V0Z" fill="#ffffff"></path></svg>';
			}
		break;
		
		case 'book' :
			if (flip == '1') {
				var svgpath = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M602.45,3.86h0S572.9,116.24,281.94,120H923C632,116.24,602.45,3.86,602.45,3.86Z" fill="#ffffff"></path></svg>';
			} else {
				var svgpath = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M1200,0H0V120H281.94C572.9,116.24,602.45,3.86,602.45,3.86h0S632,116.24,923,120h277Z" fill="#ffffff"></path></svg>';
			}
		break;
		case 'bubbles' :
			var svgpath = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 143" preserveAspectRatio="none"><defs><style>.cls-2{opacity:0.5;}</style></defs><rect class="cls-1" width="500" height="44" transform="translate(500 44) rotate(-180)"/><circle class="cls-2" cx="240" cy="41" r="30"/><circle class="cls-2" cx="73" cy="55" r="50"/><circle class="cls-2" cx="400.5" cy="66.5" r="14.5"/><circle class="cls-2" cx="471.5" cy="63.5" r="8.5"/><circle class="cls-2" cx="383.97" cy="85.98" r="5"/><circle class="cls-2" cx="392.26" cy="96.59" r="3.33"/><circle class="cls-2" cx="384.33" cy="104.07" r="2.07"/><circle class="cls-2" cx="391.5" cy="111.24" r="1.5"/><circle class="cls-2" cx="464.91" cy="80.28" r="3.33"/><circle class="cls-2" cx="471.02" cy="89.34" r="1.5"/><circle class="cls-2" cx="190.87" cy="82.1" r="7.31"/><circle class="cls-2" cx="199.48" cy="98.6" r="4.51"/><circle class="cls-2" cx="192.75" cy="108.96" r="2.07"/><circle class="cls-2" cx="108.5" cy="128.5" r="14.5"/><path class="cls-1" d="M373.84,56.69A20.76,20.76,0,0,0,385,53.46a20.79,20.79,0,0,0,30.26,11.21,20.81,20.81,0,0,0,37-8.05,20.83,20.83,0,1,0-6.27-19.8,20.74,20.74,0,0,0-24.64-2.62,20.78,20.78,0,0,0-27.74-5.1,20.84,20.84,0,1,0-19.71,27.59Z" transform="translate(0 0)"/><path class="cls-1" d="M46.26,53.78a16,16,0,0,0,8.61-2.5A16.12,16.12,0,0,0,78.33,60,16.14,16.14,0,0,0,107,53.73a16.16,16.16,0,1,0-4.86-15.35,16.08,16.08,0,0,0-19.1-2,16.1,16.1,0,0,0-21.51-4A16.16,16.16,0,1,0,46.26,53.78Z" transform="translate(0 0)"/><circle class="cls-1" cx="230.91" cy="36.36" r="26.36"/><circle class="cls-1" cx="205.45" cy="43.64" r="26.36"/><circle class="cls-1" cx="178.36" cy="36.36" r="26.36"/><circle class="cls-1" cx="260.09" cy="28.94" r="26.36"/></svg>';
		break;
		case 'trees' :
			var svgpath = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 95.32" preserveAspectRatio="none"><path class="cls-1" d="M113.72,166.67l-5-11.14a6.47,6.47,0,0,0,1.33-.18c.78-.19,1.78-.08,1.78-.74s-5.44-11.76-5.44-11.76a15.45,15.45,0,0,0,1.67-.19c.44-.11.26-.89.26-.89s1,1.19,1.44.93-.52-1.52-.52-1.52l-5.14-11.31a5.49,5.49,0,0,0,1.74-.15c.48-.22.15-1.15.15-1.15a11,11,0,0,0,1.73-.26c.71-.18-10.8-22.23-10.8-22.23s-.51-1.4-.88-1.4-10.84,22.26-10.84,22.26-.67.93-.19.93-.25,1.15.52,1.33S87,130.75,87,130.75l-4.67,12.06s-.88,1.26,0,1.44a4.67,4.67,0,0,0,1.67,0l-4.18,8.25s.07.74.59.74-4.7,11-4.7,11-1.55,1.78.15,2.18a3.14,3.14,0,0,0,2.89-.92s-.48,1.74,1.25,1.74,4.11-2.52,4.11-2.37S82.21,168,83.39,168s3.07-3,3.07-3-.88,2.29.12,2.29,1.51,0,1.51,0v7.1h12.32v-7.47s0,1.4.92,1.29,1.37-1.55,1.37-1.55-.41,1.4.56,1.66,1.73-2.47,1.73-2.47.71,2.88,2.11,2.88.82-2.66.82-2.66,1.1,3.07,2.29,2.84.52-2.51.52-2.51,2,1.63,2.55,1.63S113.72,166.67,113.72,166.67Z" transform="translate(0 -104.68)"/><path class="cls-1" d="M145.9,168.05,143.61,163a2.78,2.78,0,0,0,.61-.09c.35-.08.8,0,.8-.33s-2.47-5.35-2.47-5.35a7.18,7.18,0,0,0,.76-.09c.2,0,.12-.4.12-.4s.43.54.65.42-.23-.69-.23-.69l-2.34-5.15a2.24,2.24,0,0,0,.79-.07c.22-.1.07-.52.07-.52a4.71,4.71,0,0,0,.79-.12c.32-.08-4.92-10.11-4.92-10.11s-.23-.64-.4-.64S132.91,150,132.91,150s-.3.42-.08.42-.12.52.23.61.66.71.66.71l-2.12,5.48s-.41.57,0,.66a2.13,2.13,0,0,0,.75,0l-1.9,3.76s0,.33.27.33-2.13,5-2.13,5-.71.81.06,1a1.42,1.42,0,0,0,1.32-.42s-.22.79.57.79,1.87-1.14,1.87-1.08-.85,1.42-.31,1.42,1.4-1.36,1.4-1.36-.41,1,.05,1,.69,0,.69,0v3.23h5.6v-3.4s0,.64.42.59.62-.7.62-.7-.18.63.26.75.79-1.12.79-1.12.32,1.31,1,1.31.37-1.21.37-1.21.5,1.39,1,1.29.24-1.14.24-1.14.9.74,1.16.74S145.9,168.05,145.9,168.05Z" transform="translate(0 -104.68)"/><path class="cls-1" d="M401.66,183.9l-2.28-5.06a2.67,2.67,0,0,0,.6-.09c.35-.08.81,0,.81-.33s-2.47-5.35-2.47-5.35a7.07,7.07,0,0,0,.75-.09c.2,0,.12-.4.12-.4s.44.54.66.42-.24-.69-.24-.69l-2.34-5.15a2.51,2.51,0,0,0,.79-.07c.22-.1.07-.52.07-.52a4.71,4.71,0,0,0,.79-.12c.32-.08-4.91-10.11-4.91-10.11s-.24-.64-.41-.64-4.93,10.13-4.93,10.13-.3.42-.08.42-.12.52.24.61.65.7.65.7l-2.12,5.49s-.4.57,0,.66a2.18,2.18,0,0,0,.76,0l-1.9,3.75s0,.34.27.34-2.14,5-2.14,5-.71.81.07,1a1.41,1.41,0,0,0,1.31-.42s-.22.79.57.79,1.87-1.14,1.87-1.08-.84,1.42-.3,1.42,1.39-1.37,1.39-1.37-.4,1.05.05,1.05.69,0,.69,0v3.23h5.61V184s0,.64.42.59.62-.71.62-.71-.19.64.25.76.79-1.13.79-1.13.32,1.32,1,1.32.37-1.21.37-1.21.51,1.39,1.05,1.29.23-1.14.23-1.14.91.74,1.16.74S401.66,183.9,401.66,183.9Z" transform="translate(0 -104.68)"/><path class="cls-1" d="M377.85,182.32l-3.08-6.81a3.93,3.93,0,0,0,.81-.11c.48-.11,1.09-.05,1.09-.45s-3.33-7.2-3.33-7.2a8.29,8.29,0,0,0,1-.11c.27-.07.16-.54.16-.54s.59.72.88.56-.31-.93-.31-.93l-3.15-6.92a3.16,3.16,0,0,0,1.06-.09c.3-.13.09-.7.09-.7a6.43,6.43,0,0,0,1.07-.16c.43-.11-6.61-13.59-6.61-13.59s-.32-.86-.54-.86S360.38,158,360.38,158s-.41.57-.11.57-.16.7.31.81.89,1,.89,1l-2.85,7.37s-.55.77,0,.88a2.77,2.77,0,0,0,1,0l-2.55,5s0,.45.36.45-2.87,6.72-2.87,6.72-.95,1.09.09,1.34a1.9,1.9,0,0,0,1.76-.57s-.29,1.06.77,1.06,2.51-1.53,2.51-1.44-1.13,1.9-.41,1.9,1.88-1.84,1.88-1.84-.54,1.41.07,1.41.93,0,.93,0V187h7.53v-4.57s0,.86.57.79.83-.95.83-.95-.25.86.34,1,1.07-1.52,1.07-1.52.43,1.77,1.29,1.77.49-1.63.49-1.63.68,1.88,1.41,1.74.31-1.54.31-1.54,1.22,1,1.56,1S377.85,182.32,377.85,182.32Z" transform="translate(0 -104.68)"/><path class="cls-1" d="M436.53,180l-2.29-5.07a3,3,0,0,0,.61-.08c.35-.09.81,0,.81-.34s-2.48-5.35-2.48-5.35a6.5,6.5,0,0,0,.76-.08c.2-.05.12-.41.12-.41s.44.54.65.42-.23-.69-.23-.69l-2.34-5.15a2.42,2.42,0,0,0,.79-.06c.22-.1.07-.52.07-.52a6.21,6.21,0,0,0,.79-.12c.32-.09-4.91-10.11-4.91-10.11s-.24-.64-.41-.64-4.93,10.13-4.93,10.13-.3.42-.08.42-.12.52.23.6.66.71.66.71l-2.12,5.48s-.4.58,0,.66a2.36,2.36,0,0,0,.76,0l-1.9,3.75s0,.34.27.34-2.14,5-2.14,5-.71.81.07,1a1.41,1.41,0,0,0,1.31-.42s-.22.79.57.79,1.87-1.14,1.87-1.07-.84,1.41-.3,1.41,1.39-1.36,1.39-1.36-.4,1,.05,1,.69,0,.69,0v3.24h5.6v-3.4s0,.64.43.59.62-.71.62-.71-.19.64.25.76.79-1.13.79-1.13.32,1.31,1,1.31.37-1.21.37-1.21.5,1.4,1,1.3.24-1.15.24-1.15.91.74,1.16.74S436.53,180,436.53,180Z" transform="translate(0 -104.68)"/><path class="cls-1" d="M469.23,175.45l-3.44-7.64a4.56,4.56,0,0,0,.91-.12c.53-.13,1.22-.05,1.22-.51s-3.73-8.06-3.73-8.06a9.27,9.27,0,0,0,1.14-.13c.3-.08.18-.61.18-.61s.66.81,1,.64-.36-1-.36-1l-3.52-7.76a3.75,3.75,0,0,0,1.19-.1c.33-.16.1-.79.1-.79a7.71,7.71,0,0,0,1.19-.18c.48-.13-7.4-15.24-7.4-15.24s-.36-1-.61-1-7.43,15.26-7.43,15.26-.46.64-.13.64-.18.78.36.91,1,1.07,1,1.07l-3.2,8.26s-.61.86,0,1a3.22,3.22,0,0,0,1.14,0L446,165.76s.05.51.4.51-3.22,7.53-3.22,7.53-1.06,1.22.1,1.5a2.17,2.17,0,0,0,2-.64s-.33,1.19.86,1.19,2.82-1.72,2.82-1.62-1.27,2.13-.46,2.13,2.11-2.05,2.11-2.05-.61,1.57.07,1.57,1,0,1,0v4.87h8.45V175.6s0,1,.63.89.94-1.07.94-1.07-.28,1,.38,1.14,1.19-1.7,1.19-1.7.48,2,1.45,2,.55-1.82.55-1.82.76,2.1,1.58,1.95.35-1.73.35-1.73,1.37,1.12,1.75,1.12S469.23,175.45,469.23,175.45Z" transform="translate(0 -104.68)"/><path class="cls-1" d="M78.52,176.53,75.58,170a3.71,3.71,0,0,0,.78-.11c.45-.11,1,0,1-.43s-3.18-6.88-3.18-6.88.71,0,1-.11.15-.52.15-.52.56.69.85.54-.31-.89-.31-.89l-3-6.62a3.06,3.06,0,0,0,1-.08c.28-.13.09-.67.09-.67a8.19,8.19,0,0,0,1-.15c.41-.11-6.32-13-6.32-13s-.3-.83-.52-.83-6.34,13-6.34,13-.39.54-.11.54-.15.67.31.78.84.9.84.9l-2.73,7.06s-.51.73,0,.84a2.73,2.73,0,0,0,1,0l-2.45,4.83s.05.43.35.43-2.75,6.42-2.75,6.42-.91,1,.09,1.28a1.84,1.84,0,0,0,1.69-.54s-.28,1,.73,1,2.4-1.47,2.4-1.38-1.08,1.82-.39,1.82,1.8-1.75,1.8-1.75-.52,1.34.06,1.34.89,0,.89,0V181h7.2v-4.37s0,.82.55.76.8-.91.8-.91-.24.82.32,1,1-1.45,1-1.45.41,1.69,1.23,1.69.48-1.56.48-1.56.64,1.8,1.34,1.67.3-1.47.3-1.47,1.17.95,1.49.95S78.52,176.53,78.52,176.53Z" transform="translate(0 -104.68)"/><path class="cls-1" d="M160.27,168.05,158,163a2.78,2.78,0,0,0,.61-.09c.35-.08.8,0,.8-.33s-2.47-5.35-2.47-5.35a7.18,7.18,0,0,0,.76-.09c.2,0,.12-.4.12-.4s.43.54.65.42-.23-.69-.23-.69l-2.34-5.15a2.24,2.24,0,0,0,.79-.07c.22-.1.07-.52.07-.52a4.71,4.71,0,0,0,.79-.12c.32-.08-4.92-10.11-4.92-10.11s-.23-.64-.4-.64S147.28,150,147.28,150s-.3.42-.08.42-.12.52.23.61.66.71.66.71L146,157.2s-.41.57,0,.66a2.19,2.19,0,0,0,.76,0l-1.91,3.76s0,.33.27.33-2.13,5-2.13,5-.71.81.06,1a1.42,1.42,0,0,0,1.32-.42s-.22.79.57.79,1.87-1.14,1.87-1.08-.85,1.42-.31,1.42,1.4-1.36,1.4-1.36-.4,1,0,1,.69,0,.69,0v3.23h5.6v-3.4s0,.64.42.59.63-.7.63-.7-.19.63.25.75.79-1.12.79-1.12.32,1.31,1,1.31.37-1.21.37-1.21.5,1.39,1,1.29.24-1.14.24-1.14.91.74,1.16.74S160.27,168.05,160.27,168.05Z" transform="translate(0 -104.68)"/><path class="cls-1" d="M130.39,172.3l-2.29-5.07a3.11,3.11,0,0,0,.61-.08c.35-.09.8,0,.8-.34s-2.47-5.35-2.47-5.35a6.5,6.5,0,0,0,.76-.08c.2,0,.11-.41.11-.41s.44.54.66.42-.23-.68-.23-.68L126,155.56a2.51,2.51,0,0,0,.79-.07c.22-.1.06-.52.06-.52a6.26,6.26,0,0,0,.8-.12c.32-.08-4.92-10.11-4.92-10.11s-.23-.64-.4-.64-4.93,10.13-4.93,10.13-.3.42-.08.42-.12.52.23.6.66.71.66.71l-2.12,5.49s-.41.57,0,.65a2,2,0,0,0,.75,0l-1.9,3.75s0,.34.27.34-2.13,5-2.13,5-.71.81.06,1a1.42,1.42,0,0,0,1.31-.42s-.21.79.58.79,1.86-1.15,1.86-1.08-.84,1.41-.3,1.41,1.4-1.36,1.4-1.36-.41,1,.05,1h.69v3.23h5.6v-3.4s0,.64.42.59.62-.71.62-.71-.18.64.26.76.79-1.13.79-1.13.32,1.31,1,1.31.37-1.21.37-1.21.5,1.4,1,1.3.24-1.15.24-1.15.9.74,1.16.74S130.39,172.3,130.39,172.3Z" transform="translate(0 -104.68)"/><path class="cls-1" d="M195.14,170.56l-2.29-5.07a3,3,0,0,0,.61-.08c.35-.09.81,0,.81-.34s-2.48-5.35-2.48-5.35a6.5,6.5,0,0,0,.76-.08c.2,0,.12-.41.12-.41s.44.54.66.42-.24-.69-.24-.69l-2.34-5.14a2.51,2.51,0,0,0,.79-.07c.22-.1.07-.52.07-.52a6.21,6.21,0,0,0,.79-.12c.32-.08-4.91-10.11-4.91-10.11s-.24-.64-.41-.64-4.93,10.13-4.93,10.13-.3.42-.08.42-.12.52.24.6.65.71.65.71l-2.12,5.48s-.4.58,0,.66a2.34,2.34,0,0,0,.76,0l-1.9,3.75s0,.34.27.34-2.14,5-2.14,5-.71.81.07,1a1.44,1.44,0,0,0,1.31-.42s-.22.79.57.79,1.87-1.15,1.87-1.08-.84,1.41-.3,1.41,1.39-1.36,1.39-1.36-.4,1,0,1h.69v3.23h5.61v-3.4s0,.64.42.59.62-.71.62-.71-.19.64.25.76.79-1.13.79-1.13.32,1.31,1,1.31.37-1.21.37-1.21.51,1.4,1,1.3.24-1.15.24-1.15.91.74,1.16.74S195.14,170.56,195.14,170.56Z" transform="translate(0 -104.68)"/><path class="cls-1" d="M218.06,173.59l-2.79-6.18a3.7,3.7,0,0,0,.74-.1c.43-.11,1-.05,1-.42s-3-6.53-3-6.53a8.49,8.49,0,0,0,.93-.1c.24-.07.14-.5.14-.5s.53.66.8.52-.29-.85-.29-.85l-2.85-6.28a2.84,2.84,0,0,0,1-.09c.27-.12.08-.63.08-.63a5.93,5.93,0,0,0,1-.15c.39-.1-6-12.35-6-12.35s-.29-.78-.49-.78-6,12.37-6,12.37-.37.52-.11.52-.14.63.29.74.8.86.8.86l-2.59,6.7s-.49.7,0,.8a2.73,2.73,0,0,0,.93,0l-2.32,4.58s0,.41.32.41-2.61,6.11-2.61,6.11-.86,1,.09,1.21a1.73,1.73,0,0,0,1.6-.51s-.27,1,.7,1,2.28-1.4,2.28-1.31-1,1.72-.37,1.72,1.7-1.66,1.7-1.66-.49,1.27.07,1.27.84,0,.84,0v4h6.84v-4.15s0,.78.52.72.76-.87.76-.87-.23.78.3.93,1-1.38,1-1.38.39,1.6,1.17,1.6.45-1.48.45-1.48.62,1.71,1.28,1.59.29-1.4.29-1.4,1.11.9,1.41.9S218.06,173.59,218.06,173.59Z" transform="translate(0 -104.68)"/><path class="cls-1" d="M387.27,183.9,385,178.84a2.78,2.78,0,0,0,.61-.09c.35-.08.8,0,.8-.33s-2.47-5.35-2.47-5.35a7.18,7.18,0,0,0,.76-.09c.2,0,.12-.4.12-.4s.43.54.65.42-.23-.69-.23-.69l-2.34-5.15a2.51,2.51,0,0,0,.79-.07c.22-.1.07-.52.07-.52a4.71,4.71,0,0,0,.79-.12c.32-.08-4.92-10.11-4.92-10.11s-.23-.64-.4-.64-4.93,10.13-4.93,10.13-.3.42-.08.42-.12.52.23.61.66.7.66.7L373,173.05s-.41.57,0,.66a2.19,2.19,0,0,0,.76,0l-1.91,3.75s0,.34.27.34-2.13,5-2.13,5-.71.81.06,1a1.42,1.42,0,0,0,1.32-.42s-.22.79.57.79,1.87-1.14,1.87-1.08-.85,1.42-.31,1.42,1.4-1.37,1.4-1.37-.4,1.05.05,1.05.69,0,.69,0v3.23h5.6V184s0,.64.42.59.63-.71.63-.71-.19.64.25.76.79-1.13.79-1.13.32,1.32,1,1.32.37-1.21.37-1.21.5,1.39,1,1.29.24-1.14.24-1.14.91.74,1.16.74S387.27,183.9,387.27,183.9Z" transform="translate(0 -104.68)"/><path class="cls-1" d="M359.35,185,357.06,180a2.78,2.78,0,0,0,.61-.09c.35-.08.8,0,.8-.33S356,174.2,356,174.2a7.18,7.18,0,0,0,.76-.09c.2-.05.12-.4.12-.4s.43.54.65.42-.23-.69-.23-.69L355,168.29a2.42,2.42,0,0,0,.79-.06c.22-.11.07-.53.07-.53a5.47,5.47,0,0,0,.79-.11c.32-.09-4.92-10.12-4.92-10.12s-.23-.64-.4-.64S346.36,167,346.36,167s-.3.42-.08.42-.12.53.23.61.66.71.66.71l-2.12,5.48s-.41.57,0,.66a2.19,2.19,0,0,0,.76,0l-1.91,3.76s0,.33.27.33-2.13,5-2.13,5-.71.81.06,1a1.42,1.42,0,0,0,1.32-.42s-.22.79.57.79,1.87-1.14,1.87-1.07-.85,1.41-.31,1.41,1.4-1.36,1.4-1.36-.4,1,.05,1,.69,0,.69,0v3.23h5.6v-3.4s0,.64.42.59.63-.7.63-.7-.19.64.25.75.79-1.12.79-1.12.32,1.31,1,1.31.37-1.21.37-1.21.5,1.39,1,1.29.24-1.14.24-1.14.91.74,1.16.74S359.35,185,359.35,185Z" transform="translate(0 -104.68)"/><path class="cls-1" d="M429.27,179.79l-4.73-10.47a6.1,6.1,0,0,0,1.25-.17c.73-.18,1.67-.07,1.67-.7s-5.12-11.06-5.12-11.06a14.71,14.71,0,0,0,1.57-.17c.42-.11.24-.84.24-.84s.91,1.11,1.36.87-.49-1.43-.49-1.43l-4.83-10.64a5,5,0,0,0,1.63-.14c.46-.21.14-1.08.14-1.08a11.44,11.44,0,0,0,1.64-.24c.66-.17-10.16-20.91-10.16-20.91s-.49-1.32-.84-1.32-10.19,20.94-10.19,20.94-.62.87-.17.87-.25,1.08.49,1.25,1.35,1.46,1.35,1.46l-4.38,11.35s-.84,1.18,0,1.35a4.33,4.33,0,0,0,1.56,0l-3.93,7.75s.07.7.56.7-4.42,10.33-4.42,10.33-1.46,1.67.14,2.05a2.92,2.92,0,0,0,2.71-.87s-.45,1.64,1.19,1.64,3.86-2.37,3.86-2.23-1.74,2.92-.63,2.92,2.89-2.81,2.89-2.81-.84,2.15.1,2.15,1.43,0,1.43,0V187h11.58v-7s0,1.32.87,1.22,1.29-1.46,1.29-1.46-.38,1.32.52,1.56,1.64-2.33,1.64-2.33.66,2.72,2,2.72.77-2.51.77-2.51,1,2.89,2.15,2.68.49-2.37.49-2.37,1.88,1.53,2.4,1.53S429.27,179.79,429.27,179.79Z" transform="translate(0 -104.68)"/><path class="cls-1" d="M447.88,180.75l-2.8-6.19a3.62,3.62,0,0,0,.74-.1c.43-.1,1,0,1-.41s-3-6.54-3-6.54a8.14,8.14,0,0,0,.92-.1c.25-.06.15-.49.15-.49s.53.66.8.51-.29-.84-.29-.84l-2.86-6.29a3.11,3.11,0,0,0,1-.08c.27-.12.08-.64.08-.64a6.72,6.72,0,0,0,1-.14c.39-.1-6-12.35-6-12.35s-.29-.78-.5-.78-6,12.37-6,12.37-.37.51-.1.51-.14.64.29.74.8.86.8.86l-2.59,6.7s-.49.7,0,.81a2.67,2.67,0,0,0,.92,0L429,172.9s0,.41.33.41-2.61,6.1-2.61,6.1-.86,1,.08,1.22a1.76,1.76,0,0,0,1.61-.52s-.27,1,.69,1,2.29-1.4,2.29-1.32-1,1.73-.37,1.73,1.7-1.67,1.7-1.67-.49,1.28.06,1.28.85,0,.85,0V185h6.84v-4.15s0,.78.51.72.76-.86.76-.86-.22.78.31.92,1-1.37,1-1.37.39,1.6,1.17,1.6.45-1.48.45-1.48.62,1.71,1.27,1.58.29-1.4.29-1.4,1.11.91,1.42.91S447.88,180.75,447.88,180.75Z" transform="translate(0 -104.68)"/><path class="cls-1" d="M60.81,174.51l-2.29-5.07a3,3,0,0,0,.6-.08c.36-.09.81,0,.81-.34s-2.47-5.35-2.47-5.35a6.71,6.71,0,0,0,.76-.08c.2-.05.11-.41.11-.41s.44.54.66.42-.24-.69-.24-.69l-2.33-5.15a2.42,2.42,0,0,0,.79-.06c.22-.1.06-.52.06-.52a6,6,0,0,0,.79-.12C58.38,157,53.15,147,53.15,147s-.23-.64-.4-.64-4.93,10.13-4.93,10.13-.3.42-.09.42-.11.52.24.6.66.71.66.71l-2.12,5.48s-.41.58,0,.66a2.29,2.29,0,0,0,.75,0l-1.9,3.75s0,.33.27.33-2.14,5-2.14,5-.7.81.07,1a1.4,1.4,0,0,0,1.31-.42s-.21.79.58.79,1.86-1.14,1.86-1.07-.84,1.41-.3,1.41,1.4-1.36,1.4-1.36-.41,1,.05,1,.69,0,.69,0V178h5.6v-3.39s0,.64.42.58.62-.7.62-.7-.18.64.26.76.79-1.13.79-1.13.32,1.31,1,1.31.37-1.21.37-1.21.5,1.4,1,1.29.23-1.14.23-1.14.91.74,1.17.74S60.81,174.51,60.81,174.51Z" transform="translate(0 -104.68)"/><path class="cls-1" d="M37,175.82,33.91,169a4.06,4.06,0,0,0,.82-.11c.47-.12,1.08-.05,1.08-.46s-3.32-7.19-3.32-7.19.74-.05,1-.11.16-.55.16-.55.59.73.89.57-.32-.93-.32-.93l-3.15-6.92a3.53,3.53,0,0,0,1.07-.09c.29-.14.09-.7.09-.7a7.78,7.78,0,0,0,1.06-.16c.43-.12-6.6-13.6-6.6-13.6s-.32-.86-.55-.86-6.63,13.62-6.63,13.62-.4.57-.11.57-.16.7.32.81.88.95.88.95l-2.85,7.38s-.54.76,0,.88a3,3,0,0,0,1,0l-2.56,5.05s.05.45.36.45-2.87,6.72-2.87,6.72-1,1.08.09,1.33a1.91,1.91,0,0,0,1.76-.56s-.29,1.06.77,1.06,2.51-1.54,2.51-1.45-1.13,1.9-.4,1.9,1.88-1.83,1.88-1.83-.55,1.4.06,1.4.93,0,.93,0v4.34h7.53V176s0,.86.57.8.84-.95.84-.95-.25.85.34,1,1.06-1.51,1.06-1.51.43,1.76,1.29,1.76.5-1.63.5-1.63.68,1.88,1.4,1.75.32-1.54.32-1.54,1.22,1,1.56,1S37,175.82,37,175.82Z" transform="translate(0 -104.68)"/><path class="cls-1" d="M49.39,174.51l-2.29-5.07a3.11,3.11,0,0,0,.61-.08c.35-.09.81,0,.81-.34S46,163.67,46,163.67a6.5,6.5,0,0,0,.76-.08c.2-.05.12-.41.12-.41s.43.54.65.42-.23-.69-.23-.69L45,157.76a2.42,2.42,0,0,0,.79-.06c.22-.1.07-.52.07-.52a6.21,6.21,0,0,0,.79-.12C47,157,41.74,147,41.74,147s-.24-.64-.41-.64-4.93,10.13-4.93,10.13-.3.42-.08.42-.12.52.23.6.66.71.66.71l-2.12,5.48s-.4.58,0,.66a2.36,2.36,0,0,0,.76,0L34,168.08s0,.33.26.33-2.13,5-2.13,5-.71.81.07,1a1.41,1.41,0,0,0,1.31-.42s-.22.79.57.79,1.87-1.14,1.87-1.07-.84,1.41-.31,1.41,1.4-1.36,1.4-1.36-.4,1,0,1,.69,0,.69,0V178h5.6v-3.39s0,.64.42.58.63-.7.63-.7-.19.64.25.76.79-1.13.79-1.13.32,1.31,1,1.31.37-1.21.37-1.21.5,1.4,1,1.29.24-1.14.24-1.14.91.74,1.16.74S49.39,174.51,49.39,174.51Z" transform="translate(0 -104.68)"/><path class="cls-1" d="M18.49,178.07,16.2,173a3,3,0,0,0,.61-.08c.35-.09.81,0,.81-.34s-2.48-5.35-2.48-5.35a6.5,6.5,0,0,0,.76-.08c.2-.05.12-.41.12-.41s.44.54.66.42-.24-.69-.24-.69l-2.34-5.14a2.51,2.51,0,0,0,.79-.07c.22-.1.07-.52.07-.52a6.21,6.21,0,0,0,.79-.12c.32-.09-4.91-10.11-4.91-10.11s-.24-.64-.41-.64S5.5,160,5.5,160s-.3.42-.08.42-.12.52.24.6.65.71.65.71l-2.12,5.48s-.4.58,0,.66a2.34,2.34,0,0,0,.76,0l-1.9,3.75s0,.34.27.34-2.14,5-2.14,5-.71.81.07,1a1.43,1.43,0,0,0,1.31-.43s-.22.8.57.8S5,177.19,5,177.26s-.84,1.41-.3,1.41,1.39-1.36,1.39-1.36-.4,1,0,1h.69v3.23h5.61v-3.4s0,.64.42.59.62-.71.62-.71-.19.64.25.76.79-1.13.79-1.13.32,1.31,1,1.31.37-1.21.37-1.21.51,1.4,1,1.3.24-1.15.24-1.15.91.74,1.16.74S18.49,178.07,18.49,178.07Z" transform="translate(0 -104.68)"/><path class="cls-1" d="M0,200H500V172.38s-71,13.81-151.36,13.81S216.22,169,130.39,169,0,182.34,0,182.34Z" transform="translate(0 -104.68)"/></svg>';
		break;
		case 'city' :
			var svgpath = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500.03 104.15" preserveAspectRatio="none"><rect class="cls-1" x="88.19" y="72.08" width="36.08" height="32.08"/><rect class="cls-1" x="90.3" y="64.08" width="33.96" height="40.08"/><rect class="cls-1" x="91.06" y="56.53" width="33.21" height="47.62"/><rect class="cls-1" x="124.26" y="82.34" width="38.34" height="21.81"/><rect class="cls-1" x="206.83" y="49.28" width="24.3" height="54.87"/><rect class="cls-1" x="237.62" y="44.91" width="11.77" height="59.25"/><rect class="cls-1" x="265.7" y="39.47" width="17.21" height="64.68"/><rect class="cls-1" x="278.53" y="62.87" width="8.3" height="41.28"/><rect class="cls-1" y="97.43" width="500.03" height="6.72"/><rect class="cls-1" x="163.83" y="71.21" width="28.3" height="26.23"/><rect class="cls-1" x="155.91" y="55.13" width="12.68" height="42.3"/><rect class="cls-1" x="156.64" y="49.81" width="11.15" height="8.72"/><rect class="cls-1" x="157.75" y="44.6" width="9.25" height="9.25"/><rect class="cls-1" x="159.53" y="41.04" width="3.51" height="4.19"/><rect class="cls-1" x="160.04" y="40.09" width="2.21" height="1.78"/><rect class="cls-1" x="171.6" y="67.45" width="3.57" height="5.15"/><rect class="cls-1" x="168.34" y="68.47" width="19.49" height="5.19"/><rect class="cls-1" x="178.08" y="65.94" width="2.76" height="3.21"/><rect class="cls-1" x="178.25" y="65.45" width="1.92" height="1.02"/><rect class="cls-1" x="142.75" y="76" width="13.15" height="10.04"/><rect class="cls-1" x="144.83" y="74.19" width="4.5" height="5.28"/><rect class="cls-1" x="72.81" y="87" width="17.15" height="12"/><rect class="cls-1" y="95.15" width="10.94" height="4.36"/><rect class="cls-1" y="69.68" width="2.06" height="28.36"/><rect class="cls-1" y="65.94" width="1.49" height="3.74"/><rect class="cls-1" x="191.11" y="82.34" width="18.06" height="17.45"/><rect class="cls-1" x="193.6" y="76" width="15.79" height="8.32"/><rect class="cls-1" x="194.91" y="72.23" width="13.42" height="5.77"/><rect class="cls-1" x="198.81" y="69.51" width="9.85" height="4.15"/><rect class="cls-1" x="199.49" y="66.47" width="8.21" height="3.56"/><rect class="cls-1" x="291.96" y="62.87" width="17.43" height="36.38"/><rect class="cls-1" x="306.91" y="73.13" width="21.06" height="28.3"/><rect class="cls-1" x="321.85" y="70.34" width="12.6" height="28.38"/><rect class="cls-1" x="322.91" y="66.47" width="11.55" height="17.85"/><rect class="cls-1" x="423.51" y="69.74" width="5.96" height="28.98"/><rect class="cls-1" x="429.92" y="69.74" width="11.17" height="29.51"/><rect class="cls-1" x="432.11" y="67.77" width="6.64" height="4.23"/><rect class="cls-1" x="464.04" y="32.53" width="18.42" height="65.28"/><rect class="cls-1" x="478.3" y="81.81" width="6.42" height="17.43"/><rect class="cls-1" x="480.42" y="67.77" width="3.62" height="15.7"/><rect class="cls-1" x="484.34" y="96.11" width="6.91" height="3.55"/><rect class="cls-1" x="486.08" y="95.36" width="1.09" height="1.43"/><rect class="cls-1" x="498.05" y="96.79" width="1.95" height="1.64"/><rect class="cls-1" x="499.38" y="95.8" width="0.62" height="0.91"/><rect class="cls-1" x="493.69" y="96.71" width="2.6" height="1.44"/><polyline class="cls-1" points="10.15 83.6 4.77 86.04 4.77 96.62 10.15 96.62 10.15 83.6"/><polyline class="cls-1" points="34.4 36.45 66.17 44.38 66.17 82.92 66.17 98.04 34.4 98.04 34.4 36.45"/><polygon class="cls-1" points="84.64 85.36 84.64 101.04 65.17 101.04 65.17 83.32 84.64 85.36"/><polygon class="cls-1" points="81.13 83.41 81.13 85.44 66.44 83.75 66.44 81.77 78.61 81.77 78.61 83.24 81.13 83.41"/><polygon class="cls-1" points="125.25 73.13 125.25 82.34 124.26 82.34 124.26 73 125.25 73.13"/><polygon class="cls-1" points="135.34 82.34 135.34 79.47 137.66 79.47 137.66 78.36 136.06 78.36 136.06 77.25 139.47 77.25 139.47 76.47 142.07 76.47 142.07 74.62 145 74.62 145 82.34 135.34 82.34"/><polygon class="cls-1" points="151.94 74.98 150.57 76 153.25 76 151.94 74.98"/><polygon class="cls-1" points="169.89 68.47 170.13 66.26 174.04 57 174.57 57.23 170.74 66.25 170.38 68.47 169.89 68.47"/><polygon class="cls-1" points="187.83 70.03 192.13 71.21 187.83 71.21 187.83 70.03"/><polygon class="cls-1" points="207.7 49.23 214.59 42.53 214.59 41.87 214.09 41.87 214.09 41.36 216.15 41.36 216.15 40.64 224.76 40.64 224.76 38.3 225.26 38.3 225.95 30.53 226.14 38.38 226.72 38.38 226.72 43.51 230.24 47.09 236.09 45.59 236.09 39.28 239.3 38.41 239.3 37.77 242.48 37.77 242.48 35.09 244.24 35.09 244.24 31.17 244.78 31.17 244.78 29.06 245.62 29.06 246.76 25.89 246.76 22.94 247.15 22.94 247.15 12.87 247.53 22.79 247.99 22.79 247.99 26.04 249.06 28.94 249.67 28.94 249.67 31.05 250.32 31.05 250.32 35.43 251.01 35.43 251.01 34.64 251.63 34.64 251.63 35.24 251.63 36.6 252.81 36.6 252.81 38.08 253.38 39.24 255.18 39.09 255.18 38.11 258.78 38.11 258.78 40.09 260.81 41.87 260.81 46 263.95 46.19 265.48 47.7 265.48 67.45 266.51 67.45 266.36 97.43 266.36 101.96 209.42 101.96 207.7 49.23"/><rect class="cls-1" x="10.15" y="36.45" width="24.6" height="67.7"/><rect class="cls-1" x="86.53" y="80.38" width="30.64" height="23.77"/><polyline class="cls-1" points="291.96 62.87 286.83 67.77 286.83 97.43 291.96 97.43 291.96 62.87"/><polyline class="cls-1" points="333.62 66.47 333.62 32.53 336.79 31.93 335.81 29.51 341.4 28.23 341.85 0 342.83 27.7 348.26 29.13 348.26 31.25 351.36 32.53 351.36 68.6 353.32 68.6 353.32 66.47 358.07 66.47 360.42 65.17 373.93 65.17 376.79 65.66 380.49 65.66 380.49 67.77 389.55 67.77 389.55 77.74 399.36 77.06 401.25 78.42 401.25 81.58 402 81.58 402 76.08 403.66 76.08 403.13 73.66 407.89 72.53 410.07 72.68 410.07 75.4 411.81 76 411.81 83.51 414.91 82.19 414.91 67.77 414.91 59.92 416.42 59.92 416.42 57.41 416.42 52.08 419.13 51.32 417.09 50.94 418.15 49.66 419.66 50.26 428.87 41.76 420.94 49.81 420.94 51.32 426.98 51.32 426.98 55.55 427.96 55.92 427.96 59.62 428.87 59.62 428.87 65.17 429.93 65.17 429.93 69.74 429.93 100.15 333.62 100.15 333.62 66.47"/><polyline class="cls-1" points="451.32 97.43 451.32 93.96 449.28 93.96 449.28 93.28 447.87 93.28 447.87 94.19 446.4 94.19 446.4 91.25 444.3 91.25 444.3 89.89 442.32 89.89 442.32 79.47 441.09 79.47 441.09 97.43 451.32 97.43"/></svg>';
		break;
		case 'fallleaves' :
			var svgpath = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 138.97" preserveAspectRatio="none"><defs><style>.cls-2{opacity:0.5;}</style></defs><g id="Layer_3" data-name="Layer 3"><path class="cls-1" d="M0,158.87s61.62-36.53,139.21-36.53,116.22,40.45,219.17,40.45S500,150.42,500,150.42V200H0Z" transform="translate(0 -61.03)"/><path class="cls-2" d="M315.33,94.8c-.45,1-.76,1.73-1.13,2.45a17.55,17.55,0,0,0-1.74,9c.09,1.67,0,3.34,0,5,0,.44,0,.89-.07,1.34a6.35,6.35,0,0,1-.55,2.49,5.06,5.06,0,0,0-.38,1.41c-.38,2-.73,4-1.11,6a58.82,58.82,0,0,1-1.64,6.53,19.85,19.85,0,0,1-2.67,5.35,35.23,35.23,0,0,1-9.59,9.34,22.65,22.65,0,0,1-5.65,2.61,39,39,0,0,1-11.77,2,24.57,24.57,0,0,1-6.55-.78c-2.39-.62-2.13-.72-4.85.27a4.29,4.29,0,0,0-1.43.92l-4.27,4-.94.85-.88-.62,7.14-7.22c-.15-.3-.25-.56-.39-.79-2.11-3.58-2.15-3.82-2.83-8.12a13.2,13.2,0,0,1,0-3.66,14.89,14.89,0,0,0,.07-4.27,4.81,4.81,0,0,1,.16-2.06,54.1,54.1,0,0,1,2-6.18c.4-1,.73-2,1.08-3a7.34,7.34,0,0,1,1.12-2,40.11,40.11,0,0,1,8.22-8.34,23.52,23.52,0,0,1,6.49-3.87c1.23-.56,2.45-1.12,3.66-1.7a6.81,6.81,0,0,1,2.83-.72,6.24,6.24,0,0,0,1.2-.19c.52-.12,1-.25,1.54-.41a38.52,38.52,0,0,1,7.7-1.24c3.13-.28,6.24-.75,9.35-1.15a6.34,6.34,0,0,0,2.91-1.2c.73-.52,1.45-1.06,2.18-1.59Z" transform="translate(0 -61.03)"/><path class="cls-1" d="M182.85,61c.59.91,1.05,1.58,1.46,2.27A17.32,17.32,0,0,0,191,69.63c1.45.83,2.81,1.8,4.21,2.72.38.24.74.51,1.1.78a6.21,6.21,0,0,1,1.8,1.81,5,5,0,0,0,1,1.07l4.46,4.19a57.81,57.81,0,0,1,4.62,4.9,19.74,19.74,0,0,1,3.07,5.12,35.41,35.41,0,0,1,2.7,13.12,23,23,0,0,1-.85,6.17,39.22,39.22,0,0,1-4.64,11,25.31,25.31,0,0,1-4.19,5.1c-1.81,1.67-1.76,1.4-2.39,4.23a4.06,4.06,0,0,0,0,1.7c.36,1.93.73,3.85,1.1,5.78.07.39.13.79.21,1.24l-1,.41c-.75-3.34-1.49-6.6-2.24-9.91-.33,0-.6-.09-.87-.1-4.15-.15-4.37-.25-8.37-2a13,13,0,0,1-3.07-2,14.87,14.87,0,0,0-3.55-2.37,4.76,4.76,0,0,1-1.65-1.24,56.57,56.57,0,0,1-4.15-5c-.61-.87-1.28-1.69-1.93-2.52a7.63,7.63,0,0,1-1.11-2,40.32,40.32,0,0,1-2.6-11.42,23.65,23.65,0,0,1,.25-7.55c.19-1.34.37-2.67.53-4a6.75,6.75,0,0,1,.93-2.76,7.4,7.4,0,0,0,.48-1.12c.18-.5.34-1,.49-1.51a37.56,37.56,0,0,1,3.11-7.16c1.44-2.79,2.72-5.66,4.06-8.49a6.39,6.39,0,0,0,.57-3.11c-.05-.89-.12-1.79-.17-2.68C182.87,61.78,182.87,61.55,182.85,61Z" transform="translate(0 -61.03)"/><path class="cls-1" d="M278.54,120.77a2,2,0,0,0,1.18,1.46,1.22,1.22,0,0,0,1.16,0l1.06-.61,1.19-.74a2.3,2.3,0,0,1,.36.54c.12.34.18.71.29,1.13a3.75,3.75,0,0,0,.75,0,39.13,39.13,0,0,1,5.18-.6c.41,0,.81-.09,1.22-.12a4.48,4.48,0,0,1,1.27,0,2.76,2.76,0,0,1-.25.66,20.58,20.58,0,0,1-1.26,1.69A50.92,50.92,0,0,0,287,128.9c-.13.19-.24.38-.36.57a3.22,3.22,0,0,0-.56,2.54c-.29.2-.57.38-.84.58a8.64,8.64,0,0,0-.95.77,1.13,1.13,0,0,0-.2,1.68,11.19,11.19,0,0,0,.91,1.26c.19.26.42.5.66.78a8.84,8.84,0,0,1-1.41,1,8.25,8.25,0,0,0-2.37,2,2,2,0,0,1-.92.58,7.83,7.83,0,0,0-2.08,1.28,4.18,4.18,0,0,0-1,1.3,1.4,1.4,0,0,0,.12,1.58,7.78,7.78,0,0,0,1.26,1.24,14.67,14.67,0,0,0,3.2,1.77,2.43,2.43,0,0,1,1,.66c.32.4.68.78.58,1.35l1.46.75c-1.92.27-3.64.5-5.35.75-2.5.36-5,.74-7.48,1.11a1.91,1.91,0,0,0-.33.06c-.38.11-.57.43-.38.76.31.55.69,1.05,1,1.57.2.3.56.48.66.93-1.14-.19-2.23-.39-3.33-.55-.88-.13-1.76-.23-2.65-.32a5,5,0,0,0-.77,0,.85.85,0,0,0-.87.71c0,.18,0,.37-.08.65a3.22,3.22,0,0,1-.46-.35q-1.81-1.87-3.62-3.77A14,14,0,0,1,261,151c-1.65-2.15-3.31-4.29-4.91-6.47a2.31,2.31,0,0,0-1.65-1l-1.53-.23a2,2,0,0,0-.67.88q-4.09,6.94-8.18,13.91c-.56,1-1.12,1.92-1.72,2.85a1.9,1.9,0,0,0-.36,1.57,2.08,2.08,0,0,1,0,.56.89.89,0,0,1-.85.39c-.06-.05-.14-.09-.15-.15a6.55,6.55,0,0,1-.2-1c-.11-1.43-.09-1.42,1-2.4a4.56,4.56,0,0,0,.8-1.05q3.69-6.15,7.35-12.33l1.65-2.77c.12-.22.23-.45.37-.72-.3-.18-.56-.31-.79-.48a6.4,6.4,0,0,1-.93-.78,1.45,1.45,0,0,0-1.18-.52c-1.33.09-2.67.11-4,.3a15.6,15.6,0,0,0-4.35,1.31,5.19,5.19,0,0,1-2.81.47,10.72,10.72,0,0,0-2.54.2c-.33,0-.66.12-1,.19a3.43,3.43,0,0,1-2.88-.38.3.3,0,0,1,.24-.42c.9-.18,1.08-.8,1-1.58l0-.23a1.44,1.44,0,0,0-.87-.63,13.45,13.45,0,0,0-1.44-.57,1,1,0,0,1-.77-1.06c0-.34,0-.67,0-1-.23-.18-.45-.38-.7-.54-.46-.3-.92-.61-1.41-.88a37.53,37.53,0,0,1-6.18-4.52,1.89,1.89,0,0,1-.24-.33,7.93,7.93,0,0,1,1.23-.13c.36-.06.73-.08,1.1-.15a6.83,6.83,0,0,0,1.49-.4,15.42,15.42,0,0,1,2.72-1,14.79,14.79,0,0,0,3.75-1.66,4.73,4.73,0,0,0,.86-.69.78.78,0,0,0,.13-1,4.87,4.87,0,0,0-.64-.9,13.84,13.84,0,0,1-2.58-4.38,1.72,1.72,0,0,1-.07-.21c-.14-.49.06-.75.54-.65s.78.21,1.18.3a2.11,2.11,0,0,0,.66,0,.49.49,0,0,0,.42-.56,5.1,5.1,0,0,0-.39-.9c-.18-.36-.52-.64-.52-1s.5-.35.76-.46a1.1,1.1,0,0,0,.8-1.29,5.69,5.69,0,0,0-.27-1.08c-.08-.28-.22-.55-.32-.83-.19-.55-.17-1,.57-1.17a1.2,1.2,0,0,0,.31-.13.52.52,0,0,0,.27-.66,5.83,5.83,0,0,0-.23-.66,48.84,48.84,0,0,0,1-5.42c.3-1.57.62-3.13.94-4.69a1.92,1.92,0,0,1,.25-.44c.12.66.21,1.2.31,1.74,0,.22.11.43.17.65a2.33,2.33,0,0,0,2,1.93l.19.65a2.35,2.35,0,0,0,1.48,1.56,1.49,1.49,0,0,0,1.74-.32c.18-.18.33-.38.51-.58.39.23.42.64.52,1a2.29,2.29,0,0,0,.9,1.23c.48.34,1,.69,1.48,1s.64.27,1.14-.22c.07-.07.16-.13.27-.22,1,2.25,1.79,4.59,3.22,6.61a2.33,2.33,0,0,0,.43.38,1.43,1.43,0,0,0,1.15-1.22c.17-.83.28-1.68.42-2.52s.3-1.68.47-2.51a5.79,5.79,0,0,1,.3-.82c.13.34.19.56.29.75a.71.71,0,0,0,1.1.33,2.83,2.83,0,0,0,1.39-1.66c.22-.79.43-1.58.65-2.36.09-.36.2-.71.33-1.15a2.33,2.33,0,0,1,.48.32c.21.21.37.47.58.67a1,1,0,0,0,1.58,0,3.14,3.14,0,0,0,.87-1.28c.18-.44.37-.87.53-1.27.42-.14.62.15.87.28a1,1,0,0,0,1.39-.21,2.22,2.22,0,0,0,.28-.33,2.29,2.29,0,0,1,1.41-1.17c.25-.06.47-.36.63-.6.62-.92,1.2-1.87,1.84-2.78s1.18-1.52,1.78-2.27a3.59,3.59,0,0,1,.5-.42c.46,2.29.75,4.48,1.14,6.66s.7,4.38,1.21,6.48c.48.38.89-.19,1.34.08s.71-.44,1.2-.06c-.06.24-.1.53-.19.79-.2.56-.45,1.1-.64,1.66a4,4,0,0,0-.23,1c-.08.81.28,1.16,1.11,1.12.33,0,.65-.07.94-.1a.44.44,0,0,1,.12.63c-.24.5-.51,1-.76,1.49s-.41.87-.6,1.31a1.37,1.37,0,0,0,.4,1.7l.53.46c-.32.33-.62.6-.89.91-.75.87-1.52,1.73-2.22,2.64a3.91,3.91,0,0,0-.6,1.29.65.65,0,0,0,.72.86,2.45,2.45,0,0,0,.85-.19,10.29,10.29,0,0,0,1.78-.91c1.32-.9,2.59-1.86,3.88-2.79C277.66,121.38,278,121.13,278.54,120.77Z" transform="translate(0 -61.03)"/><path class="cls-1" d="M388.25,137.37a1.24,1.24,0,0,0,1.06.43.75.75,0,0,0,.62-.35c.12-.22.25-.43.38-.64l.4-.75a1.34,1.34,0,0,1,.36.18,6.07,6.07,0,0,1,.49.51,2.11,2.11,0,0,0,.39-.23,23.39,23.39,0,0,1,2.57-1.87l.61-.43a2.88,2.88,0,0,1,.67-.39,1.91,1.91,0,0,1,.07.43c0,.42-.08.85-.16,1.27a29.51,29.51,0,0,0-.53,3.62l0,.41a2,2,0,0,0,.47,1.51c-.1.2-.19.38-.27.56a4.48,4.48,0,0,0-.27.69.69.69,0,0,0,.39,1,7.34,7.34,0,0,0,.86.39c.18.08.37.14.59.22a4.83,4.83,0,0,1-.46.94,5.11,5.11,0,0,0-.65,1.77,1.27,1.27,0,0,1-.32.59,4.8,4.8,0,0,0-.72,1.29,2.65,2.65,0,0,0-.15,1,.85.85,0,0,0,.54.8,4.56,4.56,0,0,0,1,.28,8.68,8.68,0,0,0,2.22,0,1.43,1.43,0,0,1,.74.05c.29.12.59.21.71.54l1,0-2.61,2-3.64,2.83a.93.93,0,0,0-.15.13.31.31,0,0,0,0,.52c.33.2.68.35,1,.52s.45.08.63.29c-.66.25-1.3.47-1.92.71s-1,.41-1.51.62a3.3,3.3,0,0,0-.41.23.51.51,0,0,0-.24.64,2.16,2.16,0,0,0,.15.36,1.61,1.61,0,0,1-.35,0c-1-.3-2-.6-3.05-.92a6.39,6.39,0,0,1-.83-.31c-1.52-.64-3-1.28-4.54-1.95a1.41,1.41,0,0,0-1.17,0l-.88.34a1.3,1.3,0,0,0-.09.67c-.05,3.27-.11,6.55-.16,9.82,0,.68,0,1.36-.06,2a1.14,1.14,0,0,0,.28.94,1.69,1.69,0,0,1,.17.3.55.55,0,0,1-.33.46c-.05,0-.11,0-.13,0a2.88,2.88,0,0,1-.39-.46c-.49-.72-.48-.72-.22-1.56a2.65,2.65,0,0,0,.11-.79c.08-2.92.14-5.83.21-8.74,0-.66,0-1.31,0-2,0-.15,0-.3,0-.49a3.45,3.45,0,0,1-.57,0,4.34,4.34,0,0,1-.72-.13.86.86,0,0,0-.78.08c-.68.44-1.38.86-2,1.35a9.54,9.54,0,0,0-1.91,2,3.17,3.17,0,0,1-1.35,1.09,6.78,6.78,0,0,0-1.29.87c-.16.12-.31.26-.46.39a2.07,2.07,0,0,1-1.64.66.18.18,0,0,1,0-.29c.42-.37.33-.75.06-1.14l-.08-.12a1,1,0,0,0-.65-.07c-.32,0-.63.07-.94.13a.59.59,0,0,1-.73-.33c-.08-.18-.19-.35-.3-.55l-.53-.08c-.34,0-.68,0-1,0a22.53,22.53,0,0,1-4.63-.55,1.21,1.21,0,0,1-.23-.1,3.85,3.85,0,0,1,.62-.43c.17-.15.36-.27.53-.42a3.45,3.45,0,0,0,.67-.65,9.79,9.79,0,0,1,1.16-1.33,9.14,9.14,0,0,0,1.49-2,2.78,2.78,0,0,0,.25-.62.46.46,0,0,0-.24-.58,2.63,2.63,0,0,0-.61-.29,8.31,8.31,0,0,1-2.67-1.55l-.11-.09c-.22-.22-.19-.41.09-.51l.72-.19a1.56,1.56,0,0,0,.37-.17.29.29,0,0,0,.05-.42,2.92,2.92,0,0,0-.48-.37c-.2-.13-.46-.18-.58-.39s.16-.33.26-.47a.66.66,0,0,0,0-.92,3.56,3.56,0,0,0-.46-.49c-.13-.13-.29-.23-.42-.35s-.39-.48-.05-.79a.48.48,0,0,0,.12-.16.31.31,0,0,0-.05-.43,3,3,0,0,0-.32-.28,29.12,29.12,0,0,0-1.09-3.18q-.46-1.38-.9-2.76a1,1,0,0,1,0-.31l.69.83.29.29a1.42,1.42,0,0,0,1.65.42l.29.29a1.47,1.47,0,0,0,1.25.38.91.91,0,0,0,.83-.69c0-.15.06-.31.09-.46.28,0,.42.21.57.36a1.44,1.44,0,0,0,.85.38c.36,0,.72.07,1.08.08s.41-.05.53-.46c0-.06.05-.12.08-.2a24.19,24.19,0,0,0,3.69,2.54,1.14,1.14,0,0,0,.34.07.88.88,0,0,0,.25-1c-.16-.49-.36-1-.53-1.46s-.35-1-.51-1.48a4.83,4.83,0,0,1-.09-.52,4.41,4.41,0,0,0,.38.31.44.44,0,0,0,.69-.15,1.74,1.74,0,0,0,.23-1.3c-.11-.48-.24-1-.36-1.44-.06-.22-.1-.44-.17-.72a1.62,1.62,0,0,1,.35,0c.18.05.34.14.51.18a.64.64,0,0,0,.85-.46,2,2,0,0,0,.07-.94c0-.28-.07-.57-.1-.83.18-.2.37-.11.54-.11a.61.61,0,0,0,.68-.53,1.09,1.09,0,0,0,.05-.26,1.41,1.41,0,0,1,.4-1,.83.83,0,0,0,.15-.51c.06-.67.07-1.35.14-2s.17-1.16.27-1.74a1.85,1.85,0,0,1,.13-.37c.94,1.07,1.75,2.15,2.61,3.18s1.68,2.12,2.58,3.08c.37.05.41-.37.73-.36s.25-.45.62-.39c0,.14.11.31.14.47.06.36.09.72.16,1.08a2.51,2.51,0,0,0,.16.58c.2.45.5.53.93.26l.47-.33c.21,0,.24.16.25.29,0,.34,0,.68.05,1s0,.58.07.87a.83.83,0,0,0,.72.79l.42.08c-.07.27-.15.51-.2.75-.14.69-.29,1.37-.39,2.06a2.34,2.34,0,0,0,.07.87.39.39,0,0,0,.64.24,1.72,1.72,0,0,0,.4-.36,6.8,6.8,0,0,0,.67-1c.43-.87.81-1.76,1.22-2.65Z" transform="translate(0 -61.03)"/><path class="cls-2" d="M335.77,113.84c.41.62.73,1.07,1,1.54a11.94,11.94,0,0,0,4.62,4.27c1,.55,1.95,1.21,2.91,1.82.26.16.51.35.76.53a4.24,4.24,0,0,1,1.25,1.21,3.4,3.4,0,0,0,.69.73l3.1,2.83a40.15,40.15,0,0,1,3.21,3.31,13.57,13.57,0,0,1,2.15,3.48,24.21,24.21,0,0,1,2,9,15.52,15.52,0,0,1-.52,4.23,26.89,26.89,0,0,1-3.07,7.58,16.83,16.83,0,0,1-2.82,3.53c-1.22,1.17-1.19,1-1.59,2.92a2.71,2.71,0,0,0,0,1.17c.27,1.31.54,2.63.8,3.94.06.27.11.54.16.85l-.68.29-1.63-6.76c-.22,0-.41-.06-.6-.06a10.85,10.85,0,0,1-5.74-1.29,8.86,8.86,0,0,1-2.13-1.33A10.37,10.37,0,0,0,337.2,156a3.35,3.35,0,0,1-1.15-.83,38.63,38.63,0,0,1-2.88-3.38c-.43-.59-.9-1.15-1.35-1.71a5.39,5.39,0,0,1-.78-1.38,27.64,27.64,0,0,1-1.89-7.8,16.1,16.1,0,0,1,.1-5.18c.11-.91.22-1.83.32-2.75a4.77,4.77,0,0,1,.61-1.9,4,4,0,0,0,.32-.77c.12-.34.22-.69.32-1a26,26,0,0,1,2.06-4.93c1-1.92,1.81-3.9,2.7-5.86a4.31,4.31,0,0,0,.36-2.13c-.05-.61-.1-1.22-.14-1.84C335.78,114.35,335.78,114.19,335.77,113.84Z" transform="translate(0 -61.03)"/><path class="cls-1" d="M411.4,135.74c.07.37.12.64.16.92a6.07,6.07,0,0,0,1.28,2.91c.35.46.65,1,1,1.43l.24.4a2.12,2.12,0,0,1,.33.82,1.66,1.66,0,0,0,.17.47c.29.65.58,1.29.86,1.94a18.41,18.41,0,0,1,.82,2.18,6.71,6.71,0,0,1,.29,2,12.22,12.22,0,0,1-.9,4.55,7.87,7.87,0,0,1-1.1,1.85,13.53,13.53,0,0,1-3,2.89,8.64,8.64,0,0,1-2,1.06c-.79.29-.74.21-1.32,1a1.42,1.42,0,0,0-.23.54l-.42,2c0,.14-.07.27-.1.43h-.38l.62-3.46-.27-.15a5.46,5.46,0,0,1-2.4-1.75,5,5,0,0,1-.71-1,5.08,5.08,0,0,0-.82-1.23,1.74,1.74,0,0,1-.36-.62,19.57,19.57,0,0,1-.65-2.14c-.08-.36-.18-.71-.28-1.07a2.68,2.68,0,0,1-.07-.8,13.6,13.6,0,0,1,.7-4,8.09,8.09,0,0,1,1.09-2.37c.24-.4.48-.8.71-1.21a2.27,2.27,0,0,1,.66-.75,2.2,2.2,0,0,0,.31-.3l.36-.41a12.79,12.79,0,0,1,1.95-1.87c.83-.69,1.63-1.44,2.44-2.16a2.21,2.21,0,0,0,.59-.91c.11-.3.21-.59.31-.88Z" transform="translate(0 -61.03)"/><path class="cls-1" d="M139.38,88.82a1.69,1.69,0,0,0,1.34.74,1,1,0,0,0,.88-.37c.21-.26.42-.52.61-.79s.43-.59.67-.93a2,2,0,0,1,.44.3c.2.23.36.48.57.77a3.15,3.15,0,0,0,.56-.24,33.21,33.21,0,0,1,3.74-2.07c.29-.14.58-.31.88-.47a3.85,3.85,0,0,1,1-.4,3.85,3.85,0,0,1,0,.58,15.72,15.72,0,0,1-.43,1.67,40.36,40.36,0,0,0-1.31,4.73c0,.17-.07.35-.09.53a2.65,2.65,0,0,0,.36,2.1c-.16.24-.31.46-.45.69a8.4,8.4,0,0,0-.48.88.94.94,0,0,0,.37,1.34,9.79,9.79,0,0,0,1.08.67c.23.13.47.25.74.38a7.49,7.49,0,0,1-.76,1.18,6.86,6.86,0,0,0-1.17,2.24,1.59,1.59,0,0,1-.52.73,6.38,6.38,0,0,0-1.17,1.61,3.29,3.29,0,0,0-.37,1.3,1.12,1.12,0,0,0,.58,1.15,6.43,6.43,0,0,0,1.33.55,11.89,11.89,0,0,0,3,.35,1.92,1.92,0,0,1,1,.19c.37.2.76.37.86.84l1.33.1-3.81,2.23-5.3,3.16a1.82,1.82,0,0,0-.23.15.42.42,0,0,0,0,.7c.4.31.84.58,1.27.86.24.16.57.18.79.49-.93.21-1.81.4-2.69.62-.7.17-1.4.38-2.1.58a3.32,3.32,0,0,0-.59.24.67.67,0,0,0-.43.8,4.6,4.6,0,0,0,.14.51,3.13,3.13,0,0,1-.46-.11l-3.91-1.73a10.33,10.33,0,0,1-1-.55c-1.91-1.12-3.83-2.21-5.72-3.37a1.86,1.86,0,0,0-1.55-.22l-1.23.3a1.63,1.63,0,0,0-.23.88l-1.86,13c-.13.9-.26,1.8-.42,2.69a1.57,1.57,0,0,0,.22,1.3,1.77,1.77,0,0,1,.18.42.73.73,0,0,1-.52.56c-.06,0-.14,0-.17-.07a6.07,6.07,0,0,1-.44-.68c-.53-1-.52-1,0-2.11a4.12,4.12,0,0,0,.29-1q.87-5.8,1.72-11.6c.13-.87.26-1.73.38-2.6,0-.21,0-.41.07-.66-.29,0-.53-.06-.75-.12a6.17,6.17,0,0,1-1-.3,1.18,1.18,0,0,0-1,0c-1,.48-2,.92-2.92,1.47a12.47,12.47,0,0,0-2.88,2.34,4.31,4.31,0,0,1-2,1.23,8.74,8.74,0,0,0-1.86.93c-.23.14-.45.3-.68.45a2.77,2.77,0,0,1-2.3.6.25.25,0,0,1,.06-.39c.62-.41.56-.93.27-1.5l-.09-.17a1.24,1.24,0,0,0-.86-.2c-.42,0-.84,0-1.26,0a.77.77,0,0,1-.91-.56c-.09-.26-.21-.5-.32-.78a6.06,6.06,0,0,0-.7-.19c-.44-.09-.89-.18-1.34-.23a31.05,31.05,0,0,1-6.08-1.5,3.17,3.17,0,0,1-.27-.17,5.12,5.12,0,0,1,.88-.48c.26-.16.54-.29.78-.46a5.5,5.5,0,0,0,1-.77,12.43,12.43,0,0,1,1.76-1.56,11.78,11.78,0,0,0,2.31-2.42,3.4,3.4,0,0,0,.44-.79.63.63,0,0,0-.22-.81,4,4,0,0,0-.76-.48,11.29,11.29,0,0,1-3.31-2.51l-.12-.14c-.25-.33-.18-.58.21-.66s.66-.08,1-.14a1.89,1.89,0,0,0,.51-.16.4.4,0,0,0,.14-.56,4.31,4.31,0,0,0-.57-.56c-.25-.21-.59-.32-.72-.61s.28-.42.44-.59a.9.9,0,0,0,.2-1.22,4.68,4.68,0,0,0-.53-.73c-.16-.19-.34-.35-.5-.53-.32-.36-.44-.71.06-1.06a.69.69,0,0,0,.19-.19.43.43,0,0,0,0-.58,4.88,4.88,0,0,0-.38-.43,40.19,40.19,0,0,0-.92-4.41c-.26-1.28-.5-2.56-.74-3.84a1.77,1.77,0,0,1,0-.41l.78,1.22c.1.15.22.29.33.43a1.89,1.89,0,0,0,2.13.84l.34.44a1.93,1.93,0,0,0,1.6.71,1.19,1.19,0,0,0,1.21-.78,5.89,5.89,0,0,0,.21-.59c.37,0,.52.35.7.57a1.91,1.91,0,0,0,1.06.65c.47.11.94.21,1.42.28s.56,0,.79-.52c0-.08.09-.15.14-.25a33.9,33.9,0,0,0,4.48,4,3,3,0,0,0,.45.15,1.2,1.2,0,0,0,.49-1.28c-.13-.68-.31-1.36-.46-2s-.3-1.36-.43-2a4.67,4.67,0,0,1,0-.71c.21.22.31.36.45.48a.59.59,0,0,0,.94-.09,2.31,2.31,0,0,0,.53-1.69c-.07-.67-.16-1.33-.24-2,0-.29-.07-.59-.11-1a3,3,0,0,1,.47.09c.22.1.42.24.64.33a.85.85,0,0,0,1.2-.48,2.6,2.6,0,0,0,.26-1.23c0-.39,0-.78,0-1.13.27-.24.51-.08.74-.06a.8.8,0,0,0,1-.58,2.24,2.24,0,0,0,.11-.34,1.91,1.91,0,0,1,.7-1.33,1.09,1.09,0,0,0,.29-.64c.19-.89.32-1.79.53-2.67s.41-1.52.64-2.27a3,3,0,0,1,.24-.47c1.06,1.58,2,3.15,2.93,4.67s1.89,3.1,2.93,4.52c.47.14.61-.42,1-.35s.4-.55.89-.41a5.92,5.92,0,0,1,.1.65c0,.48,0,1,0,1.45a3.7,3.7,0,0,0,.13.81c.19.63.57.79,1.19.5.24-.11.47-.26.68-.37.27.07.29.26.28.44,0,.45-.08.91-.11,1.36s0,.79,0,1.18A1.1,1.1,0,0,0,135,89.4l.54.18c-.14.35-.29.65-.39,1-.3.88-.61,1.77-.86,2.68a3.22,3.22,0,0,0-.06,1.16.53.53,0,0,0,.81.42,2,2,0,0,0,.6-.4,9.64,9.64,0,0,0,1.06-1.24c.71-1.09,1.37-2.21,2.06-3.31C138.91,89.55,139.1,89.25,139.38,88.82Z" transform="translate(0 -61.03)"/><path class="cls-2" d="M45.43,84.47a2.17,2.17,0,0,0,2-.3,1.32,1.32,0,0,0,.63-1.1c0-.44,0-.88,0-1.32s0-1,0-1.52a2.87,2.87,0,0,1,.7,0c.39.08.77.21,1.22.35a3.88,3.88,0,0,0,.4-.71,41.19,41.19,0,0,1,2.27-5.19c.2-.39.36-.81.55-1.21a5.44,5.44,0,0,1,.69-1.19,3.78,3.78,0,0,1,.48.59,21.47,21.47,0,0,1,.89,2.11,56.47,56.47,0,0,0,2.44,6c.1.22.22.43.33.65a3.51,3.51,0,0,0,2.08,1.91c0,.39,0,.75.08,1.1A9.41,9.41,0,0,0,60.38,86a1.24,1.24,0,0,0,1.46,1.12,15.67,15.67,0,0,0,1.68-.17c.35,0,.7-.12,1.09-.19a9.36,9.36,0,0,1,.16,1.86,9,9,0,0,0,.57,3.31,2.06,2.06,0,0,1,0,1.18,8.73,8.73,0,0,0,.06,2.65,4.44,4.44,0,0,0,.67,1.66,1.48,1.48,0,0,0,1.54.75,8.59,8.59,0,0,0,1.85-.5,15.29,15.29,0,0,0,3.41-2A2.69,2.69,0,0,1,74.08,95a1.8,1.8,0,0,1,1.58.19l1.5-1c-.8,2-1.52,3.68-2.22,5.43-1,2.53-2,5.07-3,7.61,0,.11-.09.23-.12.34-.1.42.09.77.51.78.68,0,1.36-.07,2-.12.38,0,.75-.27,1.23-.11-.81,1-1.59,1.88-2.34,2.82-.6.75-1.17,1.53-1.75,2.3A6.65,6.65,0,0,0,71,114a.9.9,0,0,0,.2,1.2,6.19,6.19,0,0,0,.56.42,3.26,3.26,0,0,1-.58.25c-1.84.45-3.68.9-5.52,1.33a13.75,13.75,0,0,1-1.54.26c-2.92.37-5.83.77-8.75,1.07a2.5,2.5,0,0,0-1.81,1l-1.06,1.31A2.21,2.21,0,0,0,53,122q4.29,7.63,8.57,15.27c.59,1.05,1.19,2.1,1.74,3.18a2.05,2.05,0,0,0,1.27,1.19,2.17,2.17,0,0,1,.53.3,1,1,0,0,1-.1,1c-.08,0-.17.09-.23.07a7.13,7.13,0,0,1-1-.36c-1.41-.67-1.39-.68-1.74-2.21a5.24,5.24,0,0,0-.54-1.32q-3.75-6.82-7.54-13.63c-.56-1-1.13-2-1.7-3.06-.13-.23-.29-.46-.47-.74-.33.18-.6.36-.88.48a8.11,8.11,0,0,1-1.25.45,1.54,1.54,0,0,0-1.12.82c-.65,1.29-1.36,2.56-1.9,3.9a16.77,16.77,0,0,0-1.15,4.79,5.72,5.72,0,0,1-1.1,2.9,11.34,11.34,0,0,0-1.2,2.48c-.14.33-.24.68-.36,1A3.74,3.74,0,0,1,40.88,141a.34.34,0,0,1-.26-.46c.32-.94-.16-1.44-.93-1.81l-.23-.1a1.63,1.63,0,0,0-1.07.48,16.29,16.29,0,0,0-1.32,1,1.05,1.05,0,0,1-1.42.14c-.3-.2-.62-.37-1-.57-.3.12-.61.22-.89.36-.54.27-1.08.53-1.6.84a40.61,40.61,0,0,1-7.62,3.32,1.86,1.86,0,0,1-.43.05,7.7,7.7,0,0,1,.54-1.22c.14-.38.33-.74.46-1.12a7.28,7.28,0,0,0,.44-1.61,16.92,16.92,0,0,1,.59-3.08,16,16,0,0,0,.5-4.42,5.35,5.35,0,0,0-.18-1.19.84.84,0,0,0-.89-.67,4.8,4.8,0,0,0-1.19.11,15.23,15.23,0,0,1-5.51,0l-.24-.05c-.54-.14-.67-.47-.31-.86s.63-.62.92-1a2.23,2.23,0,0,0,.41-.59.52.52,0,0,0-.29-.69,5.5,5.5,0,0,0-1.06-.14c-.44,0-.88.14-1.26-.07s0-.66,0-1a1.18,1.18,0,0,0-.77-1.45,6,6,0,0,0-1.16-.35c-.31-.07-.63-.09-1-.15-.62-.12-1-.39-.79-1.17a1.12,1.12,0,0,0,0-.36.55.55,0,0,0-.47-.61,6.9,6.9,0,0,0-.74-.15,52.94,52.94,0,0,0-4.53-3.91Q5.76,117,3.83,115.21c-.11-.09-.15-.25-.28-.47l1.8.66c.23.08.47.13.7.19A2.53,2.53,0,0,0,9,114.76l.71.18A2.59,2.59,0,0,0,12,114.4a1.61,1.61,0,0,0,.65-1.8c-.07-.27-.17-.53-.26-.79.43-.25.82,0,1.19,0a2.53,2.53,0,0,0,1.65-.16c.58-.27,1.17-.54,1.72-.86s.6-.45.42-1.19a3,3,0,0,1-.05-.37c2.66.26,5.28.83,8,.6a3.43,3.43,0,0,0,.59-.2,1.55,1.55,0,0,0-.52-1.74c-.69-.62-1.42-1.19-2.13-1.78s-1.41-1.2-2.1-1.81a8.75,8.75,0,0,1-.6-.73,7.08,7.08,0,0,0,.86.14.78.78,0,0,0,.92-.85,3.09,3.09,0,0,0-.81-2.21c-.61-.64-1.24-1.27-1.85-1.9-.28-.28-.55-.58-.9-.94a4.13,4.13,0,0,1,.56-.28c.31-.08.64-.08,1-.18a1.12,1.12,0,0,0,.88-1.46,3.62,3.62,0,0,0-.72-1.52l-.9-1.19c.09-.47.47-.49.73-.66a1.07,1.07,0,0,0,.57-1.41,2.51,2.51,0,0,0-.16-.46,2.49,2.49,0,0,1-.33-2,1.49,1.49,0,0,0-.21-.91c-.52-1.09-1.11-2.14-1.6-3.24-.43-.95-.79-1.94-1.16-2.92a3.52,3.52,0,0,1-.12-.69c2.4.82,4.61,1.75,6.86,2.57s4.49,1.74,6.74,2.41c.62-.24.31-.94.81-1.21s0-.91.6-1.16c.19.19.44.38.63.61.41.49.79,1,1.21,1.51a4.56,4.56,0,0,0,.78.74c.72.52,1.25.38,1.66-.43.17-.31.29-.65.42-.93a.48.48,0,0,1,.66.23c.33.5.64,1,1,1.52s.59.86.9,1.28a1.48,1.48,0,0,0,1.81.56l.72-.24c.13.48.23.91.37,1.32.4,1.18.79,2.37,1.26,3.52a4.12,4.12,0,0,0,.88,1.28.7.7,0,0,0,1.19-.21,2.46,2.46,0,0,0,.3-.9,12.09,12.09,0,0,0,.12-2.16c-.13-1.73-.33-3.45-.5-5.17C45.52,85.63,45.49,85.15,45.43,84.47Z" transform="translate(0 -61.03)"/><path class="cls-1" d="M318.5,127.68c.08.5.15.87.19,1.24a8.13,8.13,0,0,0,1.64,4c.46.63.86,1.3,1.28,2,.11.18.22.36.32.54a2.78,2.78,0,0,1,.42,1.11,2.63,2.63,0,0,0,.22.65l1.11,2.63a28.22,28.22,0,0,1,1,3,9.2,9.2,0,0,1,.33,2.76,16.63,16.63,0,0,1-1.33,6.11,10.75,10.75,0,0,1-1.54,2.47,18.37,18.37,0,0,1-4.06,3.81,11.47,11.47,0,0,1-2.76,1.37c-1.08.38-1,.27-1.82,1.36a2.12,2.12,0,0,0-.32.72c-.21.89-.41,1.78-.62,2.67q-.08.27-.15.57H312l.92-4.65-.35-.21a7.32,7.32,0,0,1-3.19-2.44,5.8,5.8,0,0,1-.93-1.42,7.1,7.1,0,0,0-1.07-1.69,2.17,2.17,0,0,1-.47-.84,26.74,26.74,0,0,1-.82-2.91c-.09-.49-.22-1-.34-1.45a3.5,3.5,0,0,1-.09-1.07,18.82,18.82,0,0,1,1.06-5.36,11.15,11.15,0,0,1,1.54-3.18c.33-.53.67-1.07,1-1.61a3.25,3.25,0,0,1,.92-1,5,5,0,0,0,.42-.38c.17-.18.33-.37.49-.56a17.87,17.87,0,0,1,2.68-2.46c1.15-.91,2.24-1.89,3.35-2.85a3,3,0,0,0,.83-1.21c.15-.39.29-.79.44-1.18C318.36,128,318.41,127.9,318.5,127.68Z" transform="translate(0 -61.03)"/><path class="cls-1" d="M452.44,119.09a1.66,1.66,0,0,0,1.51.29,1,1,0,0,0,.72-.63c.11-.31.23-.62.33-.94s.22-.69.35-1.09a2.83,2.83,0,0,1,.51.15c.26.15.49.34.78.55a3.67,3.67,0,0,0,.46-.4,30.71,30.71,0,0,1,2.9-3.13c.24-.23.45-.49.69-.72a3.74,3.74,0,0,1,.78-.68,2.07,2.07,0,0,1,.19.54,14.06,14.06,0,0,1,.12,1.72,40.1,40.1,0,0,0,.24,4.9c0,.18,0,.36.07.54a2.61,2.61,0,0,0,1,1.87c-.08.28-.16.54-.22.81a8.05,8.05,0,0,0-.18,1,.94.94,0,0,0,.77,1.16,10.46,10.46,0,0,0,1.23.29c.26.06.53.09.83.14a6.66,6.66,0,0,1-.36,1.35,7.09,7.09,0,0,0-.41,2.5,1.6,1.6,0,0,1-.26.85,6.4,6.4,0,0,0-.61,1.9,3.24,3.24,0,0,0,.06,1.35,1.14,1.14,0,0,0,.91.91,6.32,6.32,0,0,0,1.44.11,11.68,11.68,0,0,0,2.92-.61,2,2,0,0,1,1-.12c.42.08.84.12,1.08.53l1.3-.31-2.92,3.3c-1.36,1.55-2.7,3.11-4.05,4.66a1.92,1.92,0,0,0-.18.22c-.17.27-.12.57.17.67.48.18,1,.29,1.48.43.28.07.6,0,.9.22-.81.49-1.59.94-2.35,1.42s-1.22.8-1.82,1.21a3.72,3.72,0,0,0-.48.41.67.67,0,0,0-.16.9,3.59,3.59,0,0,0,.29.44,2.88,2.88,0,0,1-.47,0l-4.25-.43c-.39,0-.78-.11-1.17-.19-2.16-.46-4.33-.9-6.48-1.41a1.89,1.89,0,0,0-1.54.27l-1.07.67a1.65,1.65,0,0,0,0,.91l2.31,13c.16.89.32,1.79.45,2.69a1.51,1.51,0,0,0,.61,1.16,1.66,1.66,0,0,1,.3.34.74.74,0,0,1-.32.7c-.06,0-.14,0-.18,0a5.2,5.2,0,0,1-.63-.51c-.84-.83-.82-.83-.69-2a4,4,0,0,0-.06-1.08c-.65-3.85-1.32-7.7-2-11.55l-.45-2.6c0-.2-.09-.4-.15-.64a6.38,6.38,0,0,1-.75.12,5.47,5.47,0,0,1-1,0,1.19,1.19,0,0,0-1,.31c-.78.76-1.59,1.48-2.31,2.3a12.8,12.8,0,0,0-2,3.12,4.22,4.22,0,0,1-1.49,1.79,9,9,0,0,0-1.48,1.47c-.18.2-.34.42-.51.64a2.78,2.78,0,0,1-2,1.29.24.24,0,0,1-.07-.39c.46-.59.24-1.06-.22-1.51a1.79,1.79,0,0,0-.14-.13,1.19,1.19,0,0,0-.87.07c-.41.13-.81.25-1.2.41a.78.78,0,0,1-1-.25c-.17-.21-.35-.41-.55-.64-.24,0-.48,0-.72,0-.45.06-.9.11-1.35.21a31,31,0,0,1-6.24.47,2.82,2.82,0,0,1-.32-.07,5.56,5.56,0,0,1,.7-.74c.19-.23.41-.44.6-.68a5.17,5.17,0,0,0,.71-1,12.79,12.79,0,0,1,1.18-2,12.24,12.24,0,0,0,1.45-3,4.28,4.28,0,0,0,.17-.88.63.63,0,0,0-.46-.7A3.91,3.91,0,0,0,426,147a11.47,11.47,0,0,1-3.93-1.35l-.15-.1c-.35-.23-.36-.49,0-.68s.6-.29.89-.45a1.71,1.71,0,0,0,.44-.32.4.4,0,0,0,0-.57,4.36,4.36,0,0,0-.72-.35c-.3-.12-.66-.12-.87-.36s.12-.48.22-.69a.9.9,0,0,0-.19-1.23,4.36,4.36,0,0,0-.73-.52c-.2-.13-.43-.22-.64-.35-.41-.24-.64-.54-.27-1a.77.77,0,0,0,.12-.24.41.41,0,0,0-.18-.55,3.73,3.73,0,0,0-.49-.29,40.26,40.26,0,0,0-2.25-3.91c-.65-1.13-1.28-2.26-1.91-3.4a2.1,2.1,0,0,1-.08-.41l1.12.92c.14.11.3.2.45.31a1.91,1.91,0,0,0,2.28.12l.46.31a2,2,0,0,0,1.75.18,1.23,1.23,0,0,0,.91-1.12c0-.21,0-.42,0-.63.37-.07.59.17.83.32a1.9,1.9,0,0,0,1.22.29,14.16,14.16,0,0,0,1.44-.18c.49-.09.53-.17.58-.74,0-.09,0-.17.06-.28a33.45,33.45,0,0,0,5.5,2.4,2.56,2.56,0,0,0,.48,0,1.17,1.17,0,0,0,.06-1.37c-.34-.6-.72-1.19-1.07-1.79s-.71-1.2-1.05-1.81a6,6,0,0,1-.25-.66,6,6,0,0,0,.58.31.58.58,0,0,0,.86-.38,2.31,2.31,0,0,0,0-1.77c-.28-.6-.57-1.2-.85-1.81-.13-.27-.25-.54-.41-.89a1.74,1.74,0,0,1,.47,0c.24,0,.48.09.72.1a.84.84,0,0,0,1-.82,2.7,2.7,0,0,0-.14-1.26c-.12-.37-.24-.74-.34-1.07.18-.31.45-.23.68-.29a.79.79,0,0,0,.75-.86,1.45,1.45,0,0,0,0-.36,1.94,1.94,0,0,1,.26-1.48,1.14,1.14,0,0,0,.07-.7c-.1-.9-.25-1.8-.33-2.7s-.08-1.57-.11-2.36a4.06,4.06,0,0,1,.09-.52c1.5,1.17,2.85,2.38,4.24,3.52s2.77,2.35,4.2,3.38c.49,0,.45-.59.87-.65s.2-.66.72-.68a6.49,6.49,0,0,1,.29.59c.18.45.31.92.49,1.37a4.17,4.17,0,0,0,.37.73c.38.54.79.57,1.29.1.19-.18.37-.39.52-.56a.35.35,0,0,1,.41.33c.12.44.21.88.33,1.32s.21.76.32,1.14a1.11,1.11,0,0,0,1.15.84H449c0,.37-.06.7-.07,1,0,.94,0,1.88,0,2.81a3.17,3.17,0,0,0,.31,1.13.52.52,0,0,0,.9.15,2,2,0,0,0,.43-.57,8.57,8.57,0,0,0,.62-1.51c.34-1.25.62-2.53.93-3.79C452.22,119.94,452.31,119.59,452.44,119.09Z" transform="translate(0 -61.03)"/><path class="cls-1" d="M67.6,85.06c.35.55.62.94.86,1.35a10.35,10.35,0,0,0,4,3.77c.86.5,1.68,1.08,2.51,1.62l.65.47a3.76,3.76,0,0,1,1.07,1.07,3.25,3.25,0,0,0,.59.64c.88.84,1.78,1.66,2.66,2.5a36,36,0,0,1,2.75,2.91,11.72,11.72,0,0,1,1.83,3.06,20.92,20.92,0,0,1,1.6,7.81,13.42,13.42,0,0,1-.5,3.67,23.5,23.5,0,0,1-2.76,6.55,14.83,14.83,0,0,1-2.5,3c-1.07,1-1,.84-1.42,2.53a2.46,2.46,0,0,0,0,1l.66,3.44c0,.23.08.47.12.74l-.59.24-1.33-5.9-.52-.06a9.48,9.48,0,0,1-5-1.18,7.68,7.68,0,0,1-1.83-1.19,9.06,9.06,0,0,0-2.12-1.4,3,3,0,0,1-1-.74,35,35,0,0,1-2.47-3c-.36-.52-.76-1-1.15-1.51a4.43,4.43,0,0,1-.66-1.21,24,24,0,0,1-1.55-6.8,13.89,13.89,0,0,1,.15-4.5c.11-.79.22-1.59.32-2.38a4,4,0,0,1,.55-1.65,4.39,4.39,0,0,0,.29-.66c.1-.3.2-.6.28-.9A22.81,22.81,0,0,1,65,94.15c.86-1.66,1.62-3.36,2.42-5.05a3.81,3.81,0,0,0,.33-1.85c0-.53-.07-1.07-.1-1.6C67.6,85.51,67.61,85.37,67.6,85.06Z" transform="translate(0 -61.03)"/><path class="cls-2" d="M462.08,79.68c.62.89,1.11,1.53,1.54,2.21A17.61,17.61,0,0,0,470.53,88c1.48.76,2.88,1.68,4.32,2.54.38.23.75.49,1.12.74A6.33,6.33,0,0,1,477.84,93a5.34,5.34,0,0,0,1,1c1.53,1.34,3.09,2.66,4.63,4a57.66,57.66,0,0,1,4.8,4.71,20,20,0,0,1,3.27,5,35.51,35.51,0,0,1,3.2,13,22.71,22.71,0,0,1-.6,6.2A39.57,39.57,0,0,1,490,138.12a25.1,25.1,0,0,1-4,5.25c-1.74,1.75-1.7,1.47-2.22,4.32a4.23,4.23,0,0,0,.07,1.7c.44,1.91.89,3.82,1.32,5.73.09.39.17.79.27,1.23l-1,.45L481.8,147c-.33,0-.6-.06-.88-.06-4.15,0-4.37-.08-8.43-1.67a13,13,0,0,1-3.15-1.86,15.27,15.27,0,0,0-3.64-2.23A4.84,4.84,0,0,1,464,140a56.54,56.54,0,0,1-4.33-4.83c-.65-.84-1.35-1.64-2-2.44a7.86,7.86,0,0,1-1.18-2,40.4,40.4,0,0,1-3-11.31,23.38,23.38,0,0,1-.05-7.56c.13-1.34.26-2.68.38-4a6.82,6.82,0,0,1,.81-2.79,6.55,6.55,0,0,0,.44-1.14c.16-.5.3-1,.43-1.53a37.72,37.72,0,0,1,2.83-7.28c1.33-2.83,2.5-5.75,3.73-8.64a6.37,6.37,0,0,0,.44-3.12l-.27-2.68C462.13,80.43,462.12,80.19,462.08,79.68Z" transform="translate(0 -61.03)"/></g></svg>';
		break;
		case 'fire' :
			var svgpath = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 144.36" preserveAspectRatio="none"><defs><style>.cls-2{opacity:0.5;}</style></defs><g id="Layer_3" data-name="Layer 3"><path class="cls-1" d="M497.51,73c-2-7.8-3.5-8.17-3.82-13.39-.27-4.54.66-7.94,0-8.11s-2,2.36-2.67,4.95c-1.12,4.09.27,5.33-.49,9.74-.35,2-1,5.32-3.65,7.7-1.81,1.61-5,3.18-6.09,2.19s0-3.53,3.57-14.52a14.24,14.24,0,0,0,.73-4.14,10.25,10.25,0,0,0-.24-2.92c-.53-2.11-1.63-2.7-2.51-4.22-1.1-1.88-.38-2.68-.65-9.16-.12-2.87-.41-6.08-.73-6.09s.05,2.5-1.3,5.36-2.84,2.78-3.25,4.86c-.45,2.33,1.28,3,1.06,5.93-.15,2-1.2,4.49-2.6,4.62s-3.17-2.24-3.24-4.38c-.05-1.43.68-1.73.65-3.16-.06-2.38-2.14-4.63-3-4.39s.65,6.58-1.13,7.39c-1.08.49-3.31-1.11-4.14-3.17-.94-2.34.38-4.2,1.22-7.54a26,26,0,0,0,.08-11c-.87-5.46-2.61-5.56-4.46-12.49-1.57-5.88-1.37-9.72-2-9.74s-1.71,3.94-1.71,8.12c0,8.79,4.59,11.1,3,16.06-1.28,4-4.6,3.62-6.66,9.17-.78,2.12-.87,3.76-1.05,7.06-.49,8.94,1.72,11.17-.16,14.68-1.56,2.91-5.26,5.46-8.6,4.71-4-.9-5.55-6-5.68-9.09-.11-2.55.71-5.48,1.62-5.52,1.53-.06,2.66,8.16,3.57,8,.58-.08.67-3.49.49-6.17-.28-4.17-1.05-4.08-3-12.17-.56-2.32-1-4.4-2.27-4.7s-2,1.07-3,.73-1.31-1.9-1.54-4.3c-.37-3.87-.64-6.59.24-10.88.82-4,2-6.28,1.38-6.65-.2-.12-.74-.14-2.92,2.11-4.22,4.36-7.73,8-7.54,12.58.12,3.12,1.86,3.92,1.38,7.46A15.59,15.59,0,0,1,424.93,43c-1.75,3.94-3.74,5.91-3.4,6.25.56.58,6-5.17,7.7-4.06,1.39.92-.3,6.06-1.37,8.68-2.67,6.52-6.79,10.26-5.6,11.85.45.6,1.14.18,1.86.89,1.64,1.6.61,6.18-1.94,9-.7.76-3.24,3.58-6.41,2.92a5.66,5.66,0,0,1-4.22-3.9c-.87-3.37,2.95-5.22,3.73-10.3.45-2.94-.38-5.24-1.3-7.79-2.22-6.15-5.47-6.89-9.73-14.44-1.77-3.13-4-7.93-3-8.68,1.25-.94,6.88,4.93,7.55,4.3.41-.39-1.89-2.44-3.08-5.84-1.91-5.46,1.45-7.52.08-13.23-1.15-4.77-3.83-4.74-4.71-9.73s1.13-8.8.73-8.93-3.11,4.39-3.89,10.39c-.84,6.42,1.39,7.71.81,13-.88,7.93-6.19,7.43-7.3,14.93-1.08,7.28,3.44,11.12.57,18.09-.3.72-1.51,3.64-2.93,3.57-1.69-.08-2.73-4.36-3.08-5.76-2-8.25.29-14.85-.32-15-.45-.12-2.57,3.14-2.6,7.3,0,4.43,2.34,6,1.62,9.9-.35,2-1.51,4.68-3,4.79-2.12.15-4.95-5.05-5.76-10.15-1.25-7.82,3-10.68,2-19.63-.5-4.69-2.19-8.78-2.92-8.68s.46,5.93-1.38,13.14c-2.24,8.76-6.29,8.37-7.3,14.77-1.41,8.9,5.88,12.9,3.32,20.77-1.31,4-4.76,7.67-6.81,7.14-2.47-.64-1.38-6.91-4-16.39-2.78-10.16-8.12-17.93-9.16-17.53s3.19,7.07,2.1,16.56c-.94,8.29-5,11.13-5.67,17.1-.05-.21-.1-.4-.15-.62-1.5-5.87-2.64-6.15-2.87-10.08-.21-3.42.49-6,0-6.11s-1.48,1.78-2,3.73c-.84,3.08.21,4-.37,7.33A9.42,9.42,0,0,1,338,78.36c-1.36,1.21-3.75,2.38-4.58,1.65s0-2.67,2.69-10.94a11.06,11.06,0,0,0,.55-3.12,8.12,8.12,0,0,0-.18-2.2c-.4-1.59-1.24-2-1.9-3.18-.82-1.42-.28-2-.49-6.9-.09-2.16-.3-4.58-.55-4.59s0,1.89-1,4-2.13,2.1-2.44,3.66c-.34,1.75,1,2.3.79,4.46-.11,1.5-.9,3.39-1.95,3.49s-2.39-1.69-2.45-3.3c0-1.08.52-1.3.49-2.39,0-1.79-1.61-3.48-2.26-3.3s.49,5-.85,5.57c-.82.37-2.5-.84-3.12-2.39-.71-1.76.28-3.17.92-5.68a19.73,19.73,0,0,0,.06-8.31c-.66-4.12-2-4.19-3.36-9.41-1.19-4.43-1-7.33-1.53-7.34s-1.29,3-1.28,6.11c0,6.63,3.45,8.37,2.26,12.1-1,3-3.46,2.73-5,6.91a16.28,16.28,0,0,0-.8,5.32c-.37,6.73,1.29,8.41-.12,11.06-1.17,2.19-4,4.11-6.48,3.54-3-.67-4.18-4.53-4.28-6.84-.08-1.93.54-4.13,1.23-4.16,1.15,0,2,6.14,2.68,6.05.44-.06.51-2.62.37-4.64-.21-3.15-.78-3.08-2.26-9.17-.42-1.75-.75-3.32-1.71-3.54s-1.5.8-2.26.55-1-1.43-1.16-3.24a24,24,0,0,1,.18-8.19c.62-3,1.51-4.73,1-5-.15-.09-.56-.11-2.2,1.59-3.18,3.28-5.82,6-5.68,9.47.09,2.35,1.4,3,1,5.62a11.43,11.43,0,0,1-1.1,3.36c-1.32,3-2.81,4.45-2.56,4.71.42.43,4.53-3.9,5.8-3.06,1,.69-.23,4.56-1,6.54-2,4.91-5.1,7.73-4.21,8.92.34.46.86.14,1.4.68,1.24,1.2.46,4.65-1.46,6.78-.52.58-2.44,2.7-4.83,2.2a4.28,4.28,0,0,1-3.18-2.93c-.65-2.55,2.22-3.94,2.81-7.77a11.16,11.16,0,0,0-1-5.86c-1.67-4.64-4.11-5.2-7.33-10.88-1.33-2.36-3-6-2.26-6.54.94-.71,5.18,3.72,5.68,3.24.31-.29-1.42-1.83-2.32-4.4-1.44-4.11,1.09-5.66.06-10-.86-3.6-2.88-3.57-3.54-7.34s.85-6.62.55-6.72-2.35,3.31-2.94,7.82c-.63,4.85,1.05,5.81.62,9.78-.66,6-4.67,5.6-5.5,11.25-.82,5.49,2.59,8.37.42,13.63-.22.54-1.13,2.74-2.2,2.68-1.27-.06-2.06-3.28-2.32-4.33-1.52-6.22.22-11.19-.24-11.31s-1.94,2.37-2,5.5c0,3.33,1.76,4.51,1.22,7.46-.27,1.47-1.14,3.52-2.26,3.6-1.6.12-3.72-3.8-4.34-7.64-.94-5.89,2.25-8,1.53-14.79-.38-3.53-1.65-6.61-2.2-6.54s.35,4.47-1,9.9c-1.69,6.6-4.73,6.3-5.5,11.13-1.06,6.7,4.44,9.71,2.51,15.64-1,3-3.59,5.78-5.14,5.38-1.85-.48-1-5.21-3-12.35-2.09-7.65-6.12-13.5-6.91-13.2s2.41,5.32,1.59,12.47a23.09,23.09,0,0,1-2.85,8.38,11.54,11.54,0,0,1-1.47-4c-.91-5.69,2.59-9.7,1.78-9.92s-5.38,4.41-7.72,10.5c-2.18,5.68-1.27,9.44-3.34,9.83-1.73.31-4.63-1.86-5.74-4.28-2.16-4.72,4-7.12,2.8-12.45-.86-3.84-4.26-3.6-6.15-8.85C211.92,53.52,213,50,212.31,50s-2,2.39-2.46,5.2c-.81,5.37,2.76,7.08,1.71,11.77-.69,3.06-3.06,6.17-4.85,6.08-1.25-.07-2.23-1.7-2.53-2.87-.6-2.35,1.39-3.28,1.37-5.93s-1.81-4.45-2.19-4.38,1.43,4-.27,9c-.29.84-1.17,3.4-2.6,3.45-1.19,0-2.2-1.71-2.45-2.14-2.43-4.18,1.38-6.48.47-10.85s-5.41-4.19-6.14-8.94c-.49-3.16,1.38-3.93.68-7.79-.66-3.59-2.91-6.3-3.28-6.22s1.35,2.36.62,5.35-3,3-4,5.84c-1.15,3.42,1.68,4.65.07,7.92-1,2-2.94,3.27-2.59,3.5.56.39,5.3-3.14,6.35-2.57.84.45-1,3.32-2.53,5.2-3.59,4.52-6.32,5-8.19,8.66-.78,1.52-1.48,2.9-1.1,4.67.66,3,3.87,4.15,3.14,6.17-.42,1.18-2,2.1-3.55,2.34a7.35,7.35,0,0,1-5.39-1.75c-2.15-1.7-3-4.44-1.64-5.4.61-.43,1.19-.18,1.57-.54,1-.95-2.47-3.19-4.71-7.1-.91-1.57-2.33-4.66-1.17-5.2,1.43-.67,6,2.78,6.49,2.43.28-.21-1.39-1.39-2.87-3.75a7.54,7.54,0,0,1-1.22-2.67c-.41-2.12,1-2.6,1.16-4.47.15-2.76-2.8-4.93-6.36-7.54-1.83-1.35-2.29-1.34-2.45-1.27-.53.23.47,1.6,1.16,4a13.7,13.7,0,0,1,.2,6.52c-.19,1.44-.49,2.38-1.3,2.58s-1.58-.6-2.52-.44-1.44,1.43-1.92,2.82c-1.64,4.85-2.29,4.79-2.52,7.29-.15,1.61-.08,3.65.41,3.7.76.07,1.71-4.85,3-4.81.76,0,1.46,1.77,1.37,3.3a5.88,5.88,0,0,1-4.78,5.45,8.24,8.24,0,0,1-7.24-2.82c-1.58-2.11.27-3.45-.14-8.8a9.7,9.7,0,0,0-.89-4.24c-1.73-3.32-4.53-3.1-5.6-5.49-1.33-3,2.53-4.36,2.53-9.63,0-2.51-.87-4.87-1.43-4.86s-.39,2.31-1.71,5.83c-1.56,4.16-3,4.22-3.76,7.49a11.23,11.23,0,0,0,.07,6.62c.71,2,1.82,3.11,1,4.52-.69,1.23-2.57,2.19-3.48,1.9-1.5-.49-.07-4.25-1-4.43-.72-.15-2.47,1.2-2.52,2.63,0,.86.58,1,.54,1.89a3,3,0,0,1-2.73,2.63c-1.18-.08-2.06-1.58-2.18-2.77-.19-1.73,1.26-2.16.88-3.55s-1.59-1.21-2.73-2.92-.85-3.21-1.09-3.21-.51,1.93-.62,3.65c-.23,3.88.38,4.36-.54,5.49-.74.91-1.68,1.27-2.12,2.53a4.5,4.5,0,0,0-.2,1.75,6.65,6.65,0,0,0,.61,2.48c3,6.59,3.83,8.18,3,8.71s-3.6-.35-5.13-1.32A6.82,6.82,0,0,1,115,76c-.64-2.64.53-3.38-.41-5.83-.6-1.55-1.76-3.06-2.25-3s.22,2.14,0,4.86c-.27,3.13-1.54,3.35-3.21,8-.26.71-.45,1.32-.6,1.86a59.76,59.76,0,0,0-1.17-6.22c-1.5-5.88-2.64-6.15-2.87-10.08-.21-3.43.49-6,0-6.11s-1.49,1.77-2,3.72c-.85,3.09.21,4-.37,7.34a9.39,9.39,0,0,1-2.75,5.8C98,77.59,95.56,78.77,94.74,78s0-2.66,2.69-10.94A11.35,11.35,0,0,0,98,64a8.12,8.12,0,0,0-.19-2.2c-.39-1.59-1.23-2-1.89-3.18-.82-1.42-.28-2-.49-6.91-.09-2.15-.3-4.58-.55-4.58s0,1.88-1,4-2.14,2.1-2.44,3.67c-.34,1.75,1,2.3.79,4.46-.11,1.5-.9,3.39-2,3.49S88,61.19,88,59.56c0-1.08.52-1.31.49-2.39,0-1.79-1.61-3.49-2.26-3.3s.48,5-.85,5.56c-.82.37-2.5-.83-3.12-2.38-.71-1.77.28-3.17.92-5.68a19.73,19.73,0,0,0,.06-8.31c-.66-4.12-2-4.19-3.36-9.42-1.19-4.42-1-7.32-1.53-7.33s-1.29,3-1.29,6.11c0,6.62,3.46,8.37,2.27,12.1-1,3-3.47,2.73-5,6.91a16.21,16.21,0,0,0-.79,5.31c-.37,6.74,1.29,8.42-.12,11.07-1.18,2.19-4,4.11-6.48,3.54-3-.68-4.18-4.53-4.28-6.84-.08-1.93.54-4.13,1.22-4.16,1.16,0,2,6.14,2.69,6.05.44-.06.51-2.63.37-4.64-.21-3.15-.79-3.08-2.26-9.17-.43-1.75-.76-3.32-1.71-3.55s-1.5.81-2.26.55-1-1.43-1.17-3.23a24.22,24.22,0,0,1,.19-8.19c.62-3,1.5-4.74,1-5-.15-.09-.56-.11-2.2,1.58-3.18,3.29-5.82,6-5.69,9.48.1,2.35,1.41,3,1,5.62a11.73,11.73,0,0,1-1.1,3.36c-1.32,3-2.82,4.45-2.56,4.71.42.43,4.53-3.9,5.8-3.06,1,.69-.23,4.56-1,6.54-2,4.91-5.11,7.73-4.21,8.92.34.46.86.14,1.4.67,1.23,1.21.46,4.66-1.47,6.79-.52.57-2.44,2.7-4.82,2.2A4.29,4.29,0,0,1,42.7,77c-.65-2.54,2.22-3.93,2.81-7.76a11.16,11.16,0,0,0-1-5.86c-1.67-4.64-4.12-5.2-7.33-10.88-1.33-2.36-3-6-2.26-6.54.94-.71,5.18,3.72,5.68,3.24.31-.3-1.42-1.84-2.32-4.4-1.44-4.11,1.09-5.66.06-10-.86-3.6-2.88-3.58-3.54-7.34s.85-6.63.55-6.72S33,24.09,32.41,28.6c-.63,4.84,1.05,5.81.61,9.78-.65,6-4.66,5.6-5.5,11.25-.81,5.48,2.6,8.37.43,13.62-.22.55-1.13,2.74-2.2,2.69-1.27-.06-2.06-3.28-2.32-4.34-1.52-6.21.22-11.18-.25-11.3s-1.93,2.36-1.95,5.5c0,3.33,1.76,4.51,1.22,7.45-.27,1.48-1.14,3.53-2.26,3.61-1.6.11-3.73-3.8-4.34-7.64-.94-5.89,2.25-8,1.53-14.79-.38-3.53-1.65-6.61-2.2-6.54s.35,4.47-1,9.9c-1.69,6.6-4.73,6.3-5.5,11.12-1.06,6.71,4.43,9.72,2.51,15.65-1,3-3.59,5.78-5.14,5.38-1.86-.48-1-5.21-3-12.35a42.36,42.36,0,0,0-3-7.85V91.19H500V84.53C498.63,81.19,499.14,79.44,497.51,73Z" transform="translate(0 53.17)"/></g><g class="cls-2"><path class="cls-1" d="M499,50.88a1.58,1.58,0,0,0,1,.58v-5.2C498.79,48.36,498.22,49.89,499,50.88Z" transform="translate(0 53.17)"/><path class="cls-1" d="M497.81,24.81c.24.25,1.07-.26,2.19-1.08V20.84C498.52,23.19,497.48,24.48,497.81,24.81Z" transform="translate(0 53.17)"/><path class="cls-1" d="M488.69,71.15A9,9,0,0,1,482,65c-1.38-5.34,4.67-8.26,5.91-16.3.71-4.66-.61-8.3-2.06-12.33-3.52-9.74-8.66-10.91-15.4-22.86-2.8-5-6.33-12.55-4.75-13.74,2-1.49,10.89,7.8,12,6.81.65-.62-3-3.87-4.88-9.25-3-8.64,2.3-11.9.13-20.94-1.82-7.55-6.06-7.5-7.45-15.4s1.78-13.93,1.15-14.13-4.92,6.94-6.16,16.44c-1.33,10.16,2.2,12.21,1.29,20.58-1.4,12.55-9.8,11.76-11.56,23.63-1.71,11.52,5.45,17.6.9,28.63-.47,1.14-2.39,5.77-4.63,5.65-2.68-.12-4.33-6.9-4.88-9.11-3.16-13.06.46-23.51-.51-23.75-.71-.19-4.06,5-4.11,11.56,0,7,3.7,9.5,2.56,15.67-.55,3.17-2.39,7.41-4.75,7.58-3.35.24-7.83-8-9.11-16.06-2-12.38,4.75-16.91,3.16-31.08-.79-7.42-3.46-13.89-4.62-13.73s.73,9.38-2.18,20.79c-3.55,13.87-10,13.25-11.56,23.38-2.23,14.09,9.31,20.42,5.26,32.88-2.08,6.33-7.54,12.14-10.78,11.3-3.91-1-2.19-10.94-6.33-25.94-4.4-16.09-12.86-28.38-14.5-27.75s5,11.19,3.32,26.21c-1.49,13.12-7.91,17.62-9,27.07-.08-.33-.16-.64-.24-1-2.37-9.3-4.18-9.74-4.54-16-.33-5.41.77-9.5,0-9.67s-2.35,2.82-3.17,5.9c-1.33,4.88.33,6.34-.58,11.61a14.89,14.89,0,0,1-4.36,9.19c-2.15,1.92-5.93,3.77-7.25,2.61s0-4.22,4.26-17.31a17.33,17.33,0,0,0,.87-4.94,12.7,12.7,0,0,0-.28-3.48c-.64-2.52-2-3.17-3-5-1.3-2.24-.44-3.16-.78-10.92-.14-3.42-.47-7.25-.87-7.26s0,3-1.58,6.33-3.37,3.32-3.86,5.79c-.54,2.77,1.58,3.64,1.25,7.06-.17,2.38-1.42,5.37-3.09,5.53s-3.78-2.68-3.87-5.23c0-1.71.82-2.06.77-3.78,0-2.83-2.55-5.51-3.58-5.22s.78,7.91-1.34,8.81c-1.3.59-4-1.33-4.94-3.78-1.12-2.79.44-5,1.46-9a31,31,0,0,0,.09-13.15c-1-6.53-3.16-6.64-5.32-14.9-1.88-7-1.58-11.6-2.42-11.62s-2,4.75-2,9.67c0,10.5,5.47,13.25,3.58,19.16-1.58,4.74-5.47,4.32-7.91,10.93a25.92,25.92,0,0,0-1.27,8.42c-.58,10.66,2,13.32-.19,17.51-1.85,3.47-6.33,6.51-10.25,5.6-4.75-1.06-6.62-7.17-6.78-10.82-.13-3.06.86-6.54,1.95-6.59,1.82,0,3.16,9.72,4.24,9.58.7-.1.81-4.15.59-7.35-.34-5-1.24-4.87-3.58-14.51-.67-2.77-1.19-5.26-2.71-5.6s-2.37,1.26-3.58.87-1.58-2.27-1.83-5.13a38.11,38.11,0,0,1,.28-13c1-4.75,2.39-7.49,1.59-7.92-.24-.14-.89-.17-3.49,2.52-5,5.19-9.21,9.49-9,15,.15,3.72,2.22,4.75,1.59,8.89A18.17,18.17,0,0,1,291.53,34c-2.08,4.75-4.44,7-4,7.46.67.68,7.17-6.18,9.18-4.85,1.59,1.09-.36,7.22-1.58,10.35-3.17,7.78-8.07,12.24-6.66,14.12.53.73,1.36.22,2.21,1.08,2,1.9.73,7.36-2.31,10.73-.82.92-3.86,4.27-7.64,3.48a6.77,6.77,0,0,1-5-4.63c-1-4,3.52-6.24,4.45-12.3a17.65,17.65,0,0,0-1.58-9.28c-2.65-7.34-6.51-8.23-11.6-17.22-2.11-3.74-4.75-9.5-3.58-10.35,1.49-1.13,8.2,5.89,9,5.13.49-.46-2.25-2.9-3.67-7-2.28-6.5,1.72-9,.09-15.83-1.36-5.69-4.56-5.65-5.6-11.61s1.34-10.48.87-10.64S260.29-12.12,259.36-5c-1,7.67,1.66,9.19,1,15.48-1,9.49-7.39,8.86-8.71,17.8-1.3,8.69,4.1,13.25.67,21.58-.35.85-1.79,4.34-3.49,4.24-2-.09-3.26-5.19-3.67-6.85-2.4-9.85.35-17.72-.38-17.91s-3.07,3.76-3.16,8.71c0,5.27,2.78,7.14,1.93,11.81-.43,2.33-1.81,5.57-3.58,5.7-2.53.19-5.89-6-6.87-12.1-1.49-9.32,3.56-12.66,2.42-23.41-.6-5.58-2.61-10.46-3.48-10.35s.55,7.08-1.58,15.67c-2.68,10.45-7.49,10-8.71,17.62-1.68,10.6,7,15.37,4,24.75-1.58,4.83-5.68,9.15-8.13,8.52-2.93-.76-1.58-8.25-4.75-19.55-3.31-12.11-9.69-21.37-10.94-20.89s3.82,8.42,2.52,19.74a36.74,36.74,0,0,1-4.51,13.26,18.38,18.38,0,0,1-2.33-6.41c-1.44-9,4.1-15.35,2.82-15.7s-8.52,7-12.22,16.62c-3.45,9-2,14.94-5.29,15.56-2.73.49-7.32-3-9.08-6.78-3.42-7.47,6.33-11.27,4.43-19.7-1.36-6.08-6.74-5.7-9.73-14-2.46-6.85-.68-12.36-1.84-12.47s-3.17,3.78-3.89,8.23c-1.29,8.5,4.36,11.2,2.7,18.63-1.09,4.84-4.84,9.76-7.67,9.62-2-.11-3.53-2.69-4-4.54-.95-3.72,2.2-5.19,2.17-9.39s-2.87-7-3.47-6.93,2.27,6.41-.42,14.24c-.46,1.33-1.86,5.39-4.12,5.46-1.88,0-3.48-2.7-3.88-3.38-3.84-6.62,2.19-10.26.75-17.18s-8.57-6.63-9.72-14.15c-.78-5,2.18-6.22,1.07-12.33-1-5.68-4.6-10-5.19-9.84s2.14,3.73,1,8.47-4.74,4.74-6.33,9.24c-1.82,5.41,2.66,7.36.11,12.54-1.58,3.16-4.65,5.17-4.1,5.54.89.61,8.39-5,10.06-4.07,1.32.71-1.59,5.25-4,8.23-5.68,7.15-10,7.91-13,13.71-1.24,2.4-2.35,4.59-1.74,7.39,1,4.75,6.12,6.57,5,9.76-.67,1.87-3.17,3.33-5.62,3.71a11.58,11.58,0,0,1-8.53-2.77c-3.41-2.69-4.75-7-2.6-8.55,1-.68,1.88-.28,2.48-.85,1.59-1.51-3.9-5-7.45-11.24-1.44-2.49-3.69-7.38-1.85-8.23,2.26-1.06,9.49,4.4,10.27,3.84.44-.33-2.2-2.2-4.54-5.93A11.7,11.7,0,0,1,98.18,41c-.65-3.35,1.66-4.11,1.83-7.07.24-4.37-4.43-7.81-10.06-11.94-2.9-2.14-3.63-2.12-3.88-2-.84.37.74,2.53,1.83,6.33a21.59,21.59,0,0,1,.32,10.32c-.3,2.28-.77,3.77-2.06,4.09s-2.5-1-4-.7-2.27,2.26-3,4.46c-2.6,7.68-3.63,7.59-4,11.54-.24,2.55-.13,5.78.65,5.86,1.2.11,2.7-7.68,4.74-7.61,1.21,0,2.31,2.8,2.17,5.22a9.32,9.32,0,0,1-7.56,8.63A13,13,0,0,1,63.69,63.6c-2.5-3.34.42-5.46-.22-13.93A15.38,15.38,0,0,0,62.06,43c-2.74-5.25-7.17-4.91-8.87-8.69-2.1-4.75,4-6.9,4-15.24,0-4-1.38-7.71-2.27-7.69s-.61,3.65-2.7,9.22c-2.47,6.59-4.75,6.68-5.95,11.86a17.67,17.67,0,0,0,.11,10.48c1.12,3.16,2.88,4.92,1.58,7.15-1.09,2-4.07,3.47-5.51,3-2.37-.78-.11-6.73-1.58-7-1.14-.24-3.91,1.9-4,4.16,0,1.36.92,1.58.85,3a4.75,4.75,0,0,1-4.32,4.17C31.56,57.24,30.16,54.86,30,53c-.3-2.74,2-3.42,1.4-5.62S28.85,45.45,27,42.74s-1.34-5.08-1.72-5.08-.81,3.05-1,5.78c-.37,6.14.6,6.9-.86,8.69-1.17,1.44-2.66,2-3.35,4a7,7,0,0,0-.32,2.77,10.34,10.34,0,0,0,1,3.93c4.74,10.43,6.06,12.94,4.74,13.78s-5.69-.55-8.11-2.09a10.76,10.76,0,0,1-4.86-7.31c-1-4.18.83-5.35-.65-9.23-1-2.45-2.79-4.84-3.56-4.75s.34,3.39,0,7.7c-.43,4.95-2.44,5.3-5.09,12.66-.41,1.12-.71,2.09-1,2.94A94.18,94.18,0,0,0,.45,66.69C.3,66.09.15,65.52,0,65v26.2H500V65.06a16.66,16.66,0,0,1-1.16,1.47C497.73,67.73,493.71,72.2,488.69,71.15Z" transform="translate(0 53.17)"/></g></svg>';
		break;
		case 'sphere' :
			var svgpath = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 64" preserveAspectRatio="none"><rect class="cls-1" y="165" width="500" height="36" transform="translate(500 229) rotate(180)"/><circle class="cls-1" cx="249.5" cy="28.5" r="28.5"/></svg>';
		break;
		case 'music' :
			var svgpath = '<svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 1019.61 199.55" preserveAspectRatio="none"><g id="Layer_1-2" data-name="Layer 1"><path class="cls-1" d="M1019.61,100.55v99H1.61v-61s82.09.89,186,12c117.81,12.59,222,30,371,30C758.86,180.55,1019.61,100.55,1019.61,100.55Z" transform="translate(0 0)"/><path class="cls-1" d="M977.91,66.58A1082.81,1082.81,0,0,1,800,129.05a1103.85,1103.85,0,0,1-186.87,31,1087.21,1087.21,0,0,1-189.06-1.43q-46.83-4.51-93.12-13.24c-31.91-6-63.29-14.21-94.79-22.08C173.41,107.66,106,93.15,41.79,108.39a205,205,0,0,0-23.28,7c-1.14.42,5.2,2.07,5.65,2.16,2.7.56,7.64,2.19,10.41,1.17,63.4-23.31,130.23-11,193.72,4.87,32.08,8,64.08,16.22,96.56,22.49q46.74,9,94.09,14.21A1119.76,1119.76,0,0,0,607,164.89a1115.65,1115.65,0,0,0,185.79-26.84,1085,1085,0,0,0,179.32-58.5q11-4.69,21.91-9.63c1.15-.52-5.18-2.07-5.64-2.16-2.49-.52-7.88-2.32-10.42-1.18Z" transform="translate(0 0)"/><path class="cls-1" d="M16.75,82.12c35.91-44.88,100-54,153.37-43,33.33,6.84,65.35,18.93,97.75,29.12a923.4,923.4,0,0,0,95.61,24.51,958.74,958.74,0,0,0,195.38,18.94A918.15,918.15,0,0,0,753.27,89.51C819.46,74.58,882.5,51.05,944.7,24.27q12-5.19,24-10.42c1.16-.5-5.18-2.07-5.64-2.16-2.53-.53-7.83-2.3-10.41-1.18-62.9,27.32-126,53.34-192.65,70.16A906.28,906.28,0,0,1,564.39,107.8c-65.4,1.9-131.13-3.1-195.31-15.92A871.81,871.81,0,0,1,272.75,66.7c-32.34-10.41-64.48-21.77-97.87-28.65-54-11-117.18-11.67-161.21,26.78A110.92,110.92,0,0,0,0,79.17c-.29.35,7.59,2.28,8.21,2.39C9.64,81.82,15.62,83.56,16.75,82.12Z" transform="translate(0 0)"/><path class="cls-1" d="M895.11.48a618.28,618.28,0,0,1-200.39,71c-1,.17,5.13,2,5.65,2.16,3.19.67,7.12,1.73,10.41,1.18A620,620,0,0,0,911.9,3.43c-.26.14-7.26-2.23-8.21-2.4-2.22-.39-6.42-1.75-8.54-.55Z" transform="translate(0 0)"/><path class="cls-1" d="M414.94,140.19c-18.51,9.08-18,25.6-14.65,30.15,3.8,5.16,15.47,11.44,37,2.55s17.57-22.64,12.38-28.47C443.43,137.36,429.11,133.23,414.94,140.19Z" transform="translate(0 0)"/><polyline class="cls-1" points="450.53 155.67 470.46 43.48 470.48 43.39 453.5 156.12"/><path class="cls-1" d="M469.11,52.28s51.56,14.62,36.55,36L502,92.77S538.31,73,471.08,42.62C469.82,42,469.11,52.28,469.11,52.28Z" transform="translate(0 0)"/><path class="cls-1" d="M842,124.84c-14.85,14.3-9.34,29.89-4.75,33.19,5.2,3.75,18.23,6.16,36.05-8.88s9.81-26.92,3.09-30.9C868.28,113.45,853.41,113.88,842,124.84Z" transform="translate(0 0)"/><polyline class="cls-1" points="880.96 130.69 865.8 19.06 865.66 17.68 883.92 130.21"/><path class="cls-1" d="M102.69,156.32c-19,7.87-19.61,24.4-16.55,29.15,3.46,5.39,14.71,12.4,36.79,4.91s19-21.47,14.18-27.62C131.31,155.32,117.3,150.28,102.69,156.32Z" transform="translate(0 0)"/><polyline class="cls-1" points="137.92 173.17 164.64 63.73 165.01 62.39 140.85 173.81"/><path class="cls-1" d="M918.84,53.55a936.61,936.61,0,0,1-169.11,58.29,956.9,956.9,0,0,1-177.09,25.48,929.8,929.8,0,0,1-178.88-8.2,863.78,863.78,0,0,1-86.57-17.07c-30.71-7.71-60.72-17.78-91.23-26.2C160.06,70.41,96,58.56,40.72,82.91A141,141,0,0,0,23.45,92c.16-.1,7.31,2.24,8.21,2.4,2.13.37,6.51,1.79,8.53.55C64.89,79.78,93.8,73.23,122.6,72.68c30.94-.58,60.9,6.47,90.46,15,29.88,8.6,59.45,18.13,89.66,25.56q44.11,10.86,89.1,17.62a983.1,983.1,0,0,0,177.55,10.72A968.33,968.33,0,0,0,745.24,120,939.16,939.16,0,0,0,914.82,65.86Q924.92,61.53,934.9,57c1.15-.53-5.18-2.07-5.65-2.17-2.48-.51-7.88-2.32-10.41-1.17Z" transform="translate(0 0)"/><path class="cls-1" d="M18.9,37.74C50.84,26.1,83.62,14,117.9,12.2s68.68,7.63,101.36,16.85q28.53,8,56.41,18.11a49.1,49.1,0,0,0,11.44,2.3c.82.08,6.27.37,3.22-.73a846.52,846.52,0,0,0-112.17-32c-19.06-4-38.43-7.35-57.93-8.06A207.36,207.36,0,0,0,64.8,14.54C43.67,19.54,23.22,27,2.8,34.4c-1.14.42,5.2,2.07,5.65,2.16,2.71.57,7.63,2.19,10.41,1.18Z" transform="translate(0 0)"/></g></svg>';
		break;
		case 'paint' :
			var svgpath = '<svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 500 78.26" preserveAspectRatio="none"><path class="cls-1" d="M0,181.36c35.21,0,8.49-59.62,32.49-59.62S34.91,174,54.08,174s2.41-44.68,22.79-44.68,1.29,63.8,23.63,64c16.67.15,5.8-58.86,26.33-58.86,23.25,0,.6,36.83,21.43,36.83s9.82-48.91,27.32-48.91-9.2,56.75,17.51,56.75-2.86-51.47,23.1-51.47.9,33.25,23.39,33.25c17.59,0,11.25-16.34,24.23-16.34,22.27,0-.07,45.43,23.62,45.43S283.84,130,309.8,130s1.95,49.66,22,49.66,4.92-41.21,22.62-41.21c22,0-1.28,48.76,21.85,48.76,23.8,0-.88-54.29,27.13-54.29,23.54,0,3.95,53.44,27.38,53.67,28,.29.77-58.35,27.64-58.35s7.66,62.66,29.64,62.66c11.19,0,11.91-14.11,11.91-14.11V200H0Z" transform="translate(0 -121.74)"/></svg>';
		break;
		case 'pyramid' :
			var svgpath = '<svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 500 71" preserveAspectRatio="none"><polygon class="cls-1" points="0 11.01 95.5 55.77 152.5 29.06 236 68.19 381.5 0 500 55.53 500 71 0 71 0 11.01"/></svg>';
		break;
		case 'snowflakes' :
			var svgpath = '<svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 500 133.46" preserveAspectRatio="none"><defs><style>.cls-2{opacity:0.5;}</style></defs><g id="Layer_3" data-name="Layer 3"><path class="cls-1" d="M0,158.87s61.62-36.53,139.21-36.53,116.22,40.45,219.17,40.45S500,150.42,500,150.42V200H0Z" transform="translate(0 -66.54)"/><path class="cls-1" d="M310.1,166.42c-.05-1.25-.11-2.5-.16-3.85,1.86-.23,3.65.11,5.54-.06a22.06,22.06,0,0,0-1.87-3.28c-.61-1-1.26-2.07-1.95-3.21l3.65-2.81c.24.37.46.67.64,1,1.33,2.36,2.67,4.7,3.94,7.09a1.39,1.39,0,0,0,1.57.89,17.16,17.16,0,0,0,3.27-.27L322.11,158l3.55-2.35c.24.32.46.58.64.86q1.6,2.53,3.2,5.08a1.22,1.22,0,0,0,1.09.74c3.41,0,6.83,0,10.24,0l.07-.26a3.57,3.57,0,0,0-.55-.41q-12-6.3-24.09-12.59a1.4,1.4,0,0,0-1.51,0c-2,1.13-4.07,2.21-6.11,3.3-.34.18-.69.34-1.12.56l-1.75-3.22,6-3.58c-1.05-.52-1.94-.88-2.75-1.38a2.89,2.89,0,0,0-3.27-.06,23.52,23.52,0,0,1-2.62,1.1L301.7,143l2.39-1.72-5-2.79c.78-1.27,1.54-2.49,2.35-3.79l5.78,2.45v-3.41l3.4-.2c.05.39.11.72.13,1,.08,1.45.17,2.9.2,4.36a1.22,1.22,0,0,0,.76,1.18c.81.39,1.57.85,2.57,1.4,0-1,0-1.85.05-2.68v-5.42l4.14.29c.09,1.36.19,2.68.27,4,.1,1.8.23,3.6.25,5.41a1.72,1.72,0,0,0,1.06,1.72c6.19,3.31,12.36,6.67,18.53,10l3.73,2a1.88,1.88,0,0,0,1,.25c-1.42-2.94-2.83-5.88-4.29-8.92h-9.59c-.08-1.43-.15-2.74-.23-4.17h6.24c-.73-1.29-1.32-2.43-2-3.49-.16-.24-.7-.29-1.06-.3-2.9-.09-5.81-.15-8.71-.22-.39,0-.77,0-1.31-.08v-3.75c3-.28,5.85-.08,8.94-.34-1-1.79-1.9-3.38-2.86-5.11l5-2.28,2.72,4.54c1.27-2.32,2.44-4.48,3.69-6.77L344,128c-2,3.48-3.87,6.85-5.78,10.25l2.39,3.38,2.93-5.18,3.53,1.65c-1.47,2.57-2.88,5-4.35,7.64l6.19,8.78a1.59,1.59,0,0,0,.25-1.28q-.09-9.76-.21-19.54c0-2.2-.06-4.4,0-6.6a1.36,1.36,0,0,0-.91-1.43c-2.18-1-4.32-2.05-6.48-3.08-.39-.18-.77-.38-1.25-.63.53-1.2,1-2.36,1.59-3.59l7.37,3.21c0-1.36.05-2.52,0-3.67,0-.26-.37-.56-.65-.72-1.63-.93-3.29-1.83-4.94-2.74-.34-.18-.67-.39-1.12-.65.57-1.15,1.13-2.25,1.76-3.52l5,2.86a32.6,32.6,0,0,0,.11-3.63c0-1.13,0-2.26,0-3.48h4.72v7.23l4.14-2.38c.51,1.15,1,2.2,1.47,3.32-1.57,1-3,1.88-4.52,2.72a1.78,1.78,0,0,0-1.12,1.94,21.06,21.06,0,0,1,0,2.41l7.43-2.69c.53,1.18,1,2.29,1.55,3.5-.31.17-.59.36-.89.5-2.42,1.12-4.84,2.25-7.27,3.35a1.26,1.26,0,0,0-.9,1.3q0,13.19-.14,26.38a.86.86,0,0,0,.27.62c1.94-2.77,3.87-5.55,5.86-8.4l-4.31-7.73,3.34-1.52c1.08,1.56,2.13,3.1,3.3,4.78l2.17-3.57c-1.87-3.19-3.74-6.36-5.66-9.62l4.41-2.36c1.18,2.55,2.33,5,3.58,7.73,1-1.67,1.69-3.27,2.62-4.91,1.74.72,3.39,1.56,5.15,2.39l-3.14,5.15,9,.32c.09,1.18.18,2.29.28,3.5l-11.63.51c-.48,1.05-1,2.1-1.53,3.31l5.89.55c.08,1.24,0,2.47-.05,3.79a10.45,10.45,0,0,1-1.07.07c-2.32,0-4.63,0-7-.15a1.65,1.65,0,0,0-1.76,1c-1.22,2.33-2.52,4.61-3.79,6.92-.16.29-.29.6-.54,1.14.52-.25.83-.37,1.12-.53,7.37-4.1,14.73-8.22,22.11-12.29a1.58,1.58,0,0,0,.91-1.58c0-2.55.15-5.1.24-7.65,0-.38.06-.76.1-1.14h4.47v6.86a5.56,5.56,0,0,0,2.53-.94c.17-.1.2-.56.21-.85,0-1.34,0-2.67,0-4v-1.22h4.63v3.1c1.9-.6,3.5-1.51,5.34-2.19l2.43,4.38-5.64,2.83,2.55,1.93c-.44.81-.87,1.59-1.36,2.46a15.27,15.27,0,0,1-2.21-1.18,3.3,3.3,0,0,0-4.18-.16,22,22,0,0,1-2.3,1.07l6.15,4.41c-.61.9-1.2,1.75-1.83,2.66l-2.13-1.15c-1.58-.87-3.18-1.71-4.73-2.63a1.65,1.65,0,0,0-1.84,0c-6.79,3.69-13.6,7.34-20.4,11a15.07,15.07,0,0,0-2.69,1.72c.33,0,.66.11,1,.12,2.51.09,5,.14,7.53.28a1.51,1.51,0,0,0,1.58-.9c.93-1.64,1.93-3.24,2.91-4.84l.67-1.06,4.06,2.17-2.84,4.6c1.44,0,2.68,0,3.92,0,.55,0,.68-.47.87-.84l3.65-7c.18-.34.38-.68.62-1.11l3.85,2.74c-1.08,2.09-2.14,4.12-3.28,6.31,1.65.28,3.17.07,4.76.27a16.5,16.5,0,0,1,0,3.65Z" transform="translate(0 -66.54)"/><path class="cls-1" d="M435.63,166.77c-.17-.85-.34-1.7-.53-2.62,1.26-.36,2.53-.32,3.81-.63a14.84,14.84,0,0,0-1.64-2.06c-.53-.66-1.08-1.29-1.68-2l2.21-2.32c.2.22.39.41.55.61,1.16,1.48,2.33,3,3.46,4.46a1,1,0,0,0,1.18.44,12.37,12.37,0,0,0,2.21-.53L443,159.71l2.19-2c.2.19.38.35.53.52.92,1,1.84,2.1,2.74,3.15a.83.83,0,0,0,.83.39l7-1.09,0-.19a2.82,2.82,0,0,0-.42-.22q-9-3-17.9-6.09a1,1,0,0,0-1,.18c-1.27,1-2.56,1.95-3.85,2.91l-.7.51-1.54-2,3.72-3.1a21.31,21.31,0,0,1-2-.65,2,2,0,0,0-2.26.31,17.67,17.67,0,0,1-1.68,1l-1.27-1.75,1.47-1.43-3.75-1.39,1.21-2.85,4.24,1.07-.36-2.34,2.31-.51c.07.27.15.49.2.72.21,1,.42,2,.6,3a.84.84,0,0,0,.65.73c.6.18,1.17.42,1.92.69-.1-.7-.17-1.28-.26-1.85s-.19-1.24-.28-1.86-.19-1.18-.29-1.86l2.88-.25c.2.93.41,1.82.6,2.72.27,1.23.54,2.46.75,3.69a1.21,1.21,0,0,0,.92,1.08c4.6,1.61,9.2,3.26,13.79,4.9l2.78,1a1.34,1.34,0,0,0,.72.07l-3.9-5.68-6.58,1-.61-2.85,4.29-.66c-.64-.81-1.16-1.53-1.76-2.19-.13-.14-.5-.12-.75-.09-2,.25-4,.52-6,.78l-.91.08L441,146l-.2-1.28c2-.5,4-.68,6.11-1.18-.88-1.12-1.67-2.12-2.51-3.21l3.18-2.1,2.36,2.84c.62-1.74,1.2-3.34,1.81-5.05l3.07.74c-1,2.6-1.92,5.12-2.88,7.66l2,2.07,1.47-3.87,2.6.75c-.74,1.93-1.44,3.78-2.18,5.72l5.19,5.37a1.06,1.06,0,0,0,0-.9c-.73-4.47-1.48-8.94-2.22-13.41-.25-1.51-.51-3-.73-4.53a1,1,0,0,0-.78-.89c-1.6-.45-3.18-.94-4.78-1.42l-.92-.3.71-2.64,5.4,1.42c-.14-.93-.23-1.73-.4-2.52,0-.17-.32-.34-.52-.42-1.22-.47-2.46-.91-3.69-1.36l-.84-.33c.27-.85.54-1.67.84-2.6l3.73,1.43a22.65,22.65,0,0,0-.31-2.51c-.11-.77-.24-1.55-.37-2.39l3.24-.5.77,5,2.59-2.07,1.37,2.12c-1,.82-1.88,1.61-2.82,2.35a1.23,1.23,0,0,0-.56,1.45c.13.49.17,1,.27,1.65l4.82-2.63,1.43,2.24c-.19.15-.36.3-.56.43-1.54,1-3.08,2.07-4.63,3.08a.86.86,0,0,0-.48,1c.91,6,1.8,12.09,2.71,18.14a.57.57,0,0,0,.25.4l3.13-6.4L464,142.47l2.13-1.4,2.77,2.93,1.12-2.68-4.91-6,2.77-2.09,3.29,4.93c.51-1.25.81-2.43,1.27-3.65,1.28.31,2.5.71,3.8,1.09l-1.61,3.87,6.21-.73.56,2.37-7.93,1.59c-.22.77-.45,1.55-.7,2.44l4.11-.25c.18.84.24,1.7.37,2.61-.27.06-.5.13-.73.16-1.6.22-3.19.46-4.79.64a1.14,1.14,0,0,0-1.11.86c-.59,1.73-1.24,3.44-1.87,5.16-.08.21-.13.44-.25.84.33-.23.53-.34.72-.49q6.93-5.4,13.88-10.8a1.1,1.1,0,0,0,.46-1.18c-.25-1.75-.45-3.52-.66-5.28,0-.26,0-.53-.05-.79l3.07-.48.73,4.71a3.66,3.66,0,0,0,1.64-.91c.11-.09.08-.41.06-.61-.14-.92-.29-1.83-.44-2.75l-.13-.84,3.18-.49.33,2.13c1.24-.62,2.25-1.41,3.44-2.08.69.9,1.39,1.79,2.14,2.76l-3.58,2.54,2,1.06-.67,1.84a10.56,10.56,0,0,1-1.65-.59,2.32,2.32,0,0,0-2.89.35,14.43,14.43,0,0,1-1.47,1l4.7,2.38-1,2-1.59-.56c-1.18-.43-2.37-.84-3.54-1.31a1.15,1.15,0,0,0-1.26.19q-6.41,4.89-12.85,9.74a11.21,11.21,0,0,0-1.66,1.46,5.46,5.46,0,0,0,.7,0c1.73-.2,3.47-.44,5.2-.61a1,1,0,0,0,1-.79c.47-1.22,1-2.42,1.49-3.63.1-.25.22-.5.35-.79l3,1-1.47,3.47,2.7-.42c.38-.06.41-.4.51-.67l1.76-5.19c.09-.26.19-.51.31-.83l2.94,1.47c-.53,1.55-1,3.06-1.58,4.69,1.15,0,2.18-.29,3.3-.33a12.23,12.23,0,0,1,.39,2.51Z" transform="translate(0 -66.54)"/><path class="cls-1" d="M178.84,129.7l.36-1.82c.9.1,1.7.46,2.61.59a10.47,10.47,0,0,0-.51-1.75c-.17-.56-.36-1.11-.56-1.72l2-.91c.07.2.14.36.19.54.36,1.25.73,2.51,1,3.77a.69.69,0,0,0,.64.6,8.24,8.24,0,0,0,1.56.23l-.78-2.13,1.93-.71c.07.18.15.33.2.48.31.92.63,1.83.93,2.75.08.22.16.4.43.46l4.81,1.15.06-.11a1.83,1.83,0,0,0-.21-.26l-9.9-8.61a.67.67,0,0,0-.71-.16c-1.08.3-2.16.57-3.24.86l-.59.13-.45-1.7,3.2-1c-.43-.36-.81-.63-1.13-1a1.4,1.4,0,0,0-1.53-.39,11.54,11.54,0,0,1-1.35.22l-.36-1.46,1.32-.53-2-1.88,1.53-1.51,2.44,1.8.38-1.6,1.62.28c0,.19,0,.36-.06.51q-.18,1-.39,2.07a.57.57,0,0,0,.22.64c.34.27.64.58,1.05,1,.12-.48.23-.87.32-1.26s.21-.84.31-1.27l.3-1.27,1.92.6c-.11.65-.22,1.28-.33,1.91-.15.86-.3,1.72-.49,2.56a.84.84,0,0,0,.31.93q3.8,3.38,7.57,6.79l1.52,1.36a.91.91,0,0,0,.45.23c-.33-1.54-.67-3.07-1-4.67l-4.5-1.07.36-2,2.93.7c-.2-.69-.35-1.29-.56-1.86,0-.13-.29-.22-.46-.26-1.35-.37-2.71-.72-4.07-1.08l-.6-.19.21-.89.21-.87c1.42.2,2.76.62,4.23.85-.26-1-.5-1.8-.76-2.72l2.59-.52c.26.81.5,1.58.77,2.44l2.5-2.76,1.77,1.28-3.86,4.17.74,1.85,2-2.1,1.47,1.17-2.9,3.1c.63,1.59,1.27,3.18,1.92,4.81a.77.77,0,0,0,.26-.57l2.1-9.2c.24-1,.46-2.07.72-3.1a.66.66,0,0,0-.27-.77c-.91-.71-1.8-1.45-2.69-2.17l-.52-.44,1.15-1.51,3.1,2.34c.15-.64.31-1.18.4-1.73,0-.12-.11-.31-.22-.41-.66-.62-1.34-1.23-2-1.84-.14-.12-.27-.26-.46-.43l1.22-1.45,2,1.89a16.64,16.64,0,0,0,.46-1.69c.13-.52.25-1.05.39-1.63l2.21.53-.81,3.4,2.21-.65.32,1.72c-.84.27-1.63.54-2.43.77-.43.12-.69.31-.74.78a11,11,0,0,1-.26,1.13l3.79-.42.33,1.81-.47.14-3.79.75a.62.62,0,0,0-.57.52c-1,4.12-2,8.24-3,12.36a.41.41,0,0,0,.06.32l3.69-3.28c-.38-1.35-.76-2.7-1.15-4.11l1.74-.34,1,2.61,1.42-1.43c-.52-1.71-1-3.41-1.58-5.15l2.34-.62c.26,1.33.52,2.62.81,4,.66-.66,1.16-1.34,1.78-2,.74.54,1.42,1.12,2.15,1.71l-2,2.06,4.18,1.16-.26,1.67-5.51-1.06-1.1,1.38,2.71.92c-.1.59-.29,1.16-.45,1.77-.19,0-.35,0-.51-.08-1.09-.28-2.17-.55-3.25-.85a.78.78,0,0,0-.94.26c-.83,1-1.7,1.88-2.55,2.82-.11.12-.2.25-.38.47l.58-.12,11.76-3.29a.75.75,0,0,0,.61-.63c.3-1.2.64-2.38,1-3.57.05-.18.11-.35.17-.53l2.1.51-.77,3.22a2.62,2.62,0,0,0,1.29-.16c.1,0,.16-.24.2-.38.15-.62.29-1.25.44-1.88,0-.18.09-.36.14-.57l2.17.52-.35,1.45c1-.07,1.82-.31,2.76-.42l.65,2.33-3,.69,1,1.19-.91,1a7.35,7.35,0,0,1-.91-.81,1.61,1.61,0,0,0-2-.54,9.39,9.39,0,0,1-1.2.24l2.4,2.77-1.16,1-.87-.78c-.65-.59-1.3-1.16-1.93-1.77a.81.81,0,0,0-.86-.21c-3.6,1-7.21,1.92-10.81,2.88a7.65,7.65,0,0,0-1.46.5,3.61,3.61,0,0,0,.46.17c1.17.33,2.34.63,3.5,1a.72.72,0,0,0,.84-.25c.62-.66,1.28-1.3,1.92-1.94.13-.14.27-.26.43-.42l1.66,1.47L211,135.4l1.84.44c.26.06.37-.14.5-.3.84-.95,1.67-1.91,2.5-2.87l.41-.45,1.51,1.72-2.25,2.6c.74.31,1.48.39,2.2.66a8,8,0,0,1-.4,1.71Z" transform="translate(0 -66.54)"/><path class="cls-1" d="M27.64,148.65l-1.05-2.86c1.35-.62,2.78-.8,4.16-1.38a16.55,16.55,0,0,0-2.2-2c-.71-.65-1.45-1.26-2.25-1.95l2.08-3c.26.22.5.39.72.59,1.57,1.46,3.15,2.9,4.68,4.39a1.11,1.11,0,0,0,1.4.3,13.25,13.25,0,0,0,2.39-1l-2.92-2.32,2.11-2.63c.26.19.49.33.69.5q1.83,1.51,3.64,3.05a1,1,0,0,0,1,.29c2.56-.84,5.14-1.65,7.71-2.48v-.21a3.2,3.2,0,0,0-.52-.18l-21.18-3.66a1.12,1.12,0,0,0-1.13.38c-1.25,1.34-2.54,2.65-3.81,4-.21.22-.43.42-.71.69l-2.09-2L24,133c-.92-.14-1.68-.19-2.4-.38a2.32,2.32,0,0,0-2.48.75,18.75,18.75,0,0,1-1.71,1.46l-1.73-1.74,1.39-1.87-4.45-.9c.28-1.14.56-2.24.85-3.41l4.95.45-.82-2.57,2.51-1c.13.28.26.51.35.76.41,1.07.83,2.14,1.2,3.23a.94.94,0,0,0,.86.7c.7.1,1.39.27,2.27.44-.23-.77-.41-1.4-.61-2s-.43-1.36-.65-2-.41-1.29-.66-2l3.2-.78c.39,1,.79,2,1.16,2.94.51,1.34,1,2.66,1.5,4a1.36,1.36,0,0,0,1.21,1c5.46,1,10.92,2,16.37,3.07l3.3.61a1.4,1.4,0,0,0,.82-.05L45,128l-7.23,2.31c-.4-1.06-.77-2-1.18-3.08l4.7-1.51c-.86-.8-1.58-1.51-2.36-2.14-.17-.14-.59,0-.86,0-2.21.63-4.41,1.29-6.62,1.94l-1,.25L30,124.36,29.58,123c2.16-.93,4.39-1.48,6.65-2.41-1.19-1.11-2.24-2.1-3.39-3.16l3.21-2.93,3.15,2.77c.39-2.06.75-4,1.14-6l3.58.29c-.64,3.1-1.25,6.1-1.88,9.12l2.62,2c.33-1.61.64-3.07,1-4.61l3.05.39c-.48,2.29-.94,4.5-1.43,6.81L54,130.32a1.24,1.24,0,0,0-.13-1Q51.47,122,49,114.63c-.55-1.65-1.11-3.3-1.63-5a1.08,1.08,0,0,0-1-.86c-1.87-.22-3.75-.49-5.62-.75l-1.09-.17c.11-1,.21-2,.33-3.09l6.32.63c-.32-1-.57-1.9-.9-2.75-.07-.19-.41-.34-.66-.39-1.45-.31-2.92-.58-4.38-.87l-1-.22c.16-1,.31-2,.48-3.07l4.45.94a27,27,0,0,0-.79-2.76c-.26-.85-.54-1.69-.84-2.62l3.55-1.14L48,98l2.54-2.79,1.91,2.14c-.95,1.1-1.82,2.15-2.75,3.14a1.41,1.41,0,0,0-.37,1.73,16.8,16.8,0,0,1,.59,1.81l4.95-3.82c.68.77,1.32,1.48,2,2.27-.19.2-.36.41-.55.59-1.55,1.43-3.1,2.86-4.66,4.27a1,1,0,0,0-.37,1.21q3.15,9.93,6.26,19.89a.69.69,0,0,0,.36.41c.79-2.56,1.57-5.12,2.38-7.75l-5.11-4.77,2.15-2,3.64,2.81.77-3.22-6.58-5.88,2.75-2.84,4.56,5c.36-1.5.48-2.87.79-4.33,1.48.12,2.93.35,4.46.56-.38,1.54-.73,3-1.13,4.63l6.85-1.92c.35.86.69,1.67,1,2.56l-8.63,3.19c-.11.91-.22,1.82-.35,2.86l4.57-1c.36.91.57,1.86.88,2.87-.3.11-.54.23-.8.31-1.75.53-3.5,1.08-5.26,1.56a1.31,1.31,0,0,0-1.1,1.17c-.35,2-.78,4.08-1.18,6.12-.05.26-.07.52-.13,1,.33-.31.54-.48.71-.67q6.84-7.3,13.69-14.6a1.23,1.23,0,0,0,.3-1.4c-.59-1.93-1.12-3.88-1.67-5.82-.08-.29-.13-.59-.2-.89l3.37-1.08,1.66,5.17A4.36,4.36,0,0,0,82,109.16c.1-.11,0-.47,0-.69-.32-1-.65-2-1-3-.1-.29-.19-.58-.3-.92l3.49-1.12.75,2.34c1.28-.92,2.27-2,3.49-3l2.89,2.72L87.72,109l2.39.84c-.14.72-.27,1.41-.43,2.18a11.09,11.09,0,0,1-2-.36,2.63,2.63,0,0,0-3.19.9,15.59,15.59,0,0,1-1.47,1.35l5.7,1.85c-.25.82-.48,1.6-.73,2.44l-1.89-.35c-1.4-.28-2.81-.52-4.2-.84a1.29,1.29,0,0,0-1.39.44c-4.22,4.41-8.47,8.81-12.7,13.21a11.37,11.37,0,0,0-1.61,1.94c.26-.05.52-.08.78-.15,1.91-.53,3.81-1.1,5.74-1.61a1.2,1.2,0,0,0,1-1.06c.3-1.45.68-2.9,1-4.34l.26-1,3.58.65c-.35,1.39-.68,2.73-1,4.16l3-.95c.41-.14.39-.52.45-.85q.54-3.06,1.06-6.14c0-.3.12-.6.2-1l3.56,1.14c-.31,1.83-.62,3.62-.94,5.55,1.3-.2,2.4-.72,3.65-.95a13.05,13.05,0,0,1,.88,2.74Z" transform="translate(0 -66.54)"/><g class="cls-2"><path class="cls-1" d="M84,91.93l-.76-.65c.44-.53.83-1,1.26-1.52a.66.66,0,0,0,.11-.81,19,19,0,0,0-1-2,4.74,4.74,0,0,0-.15.5c-.41,2.17-.81,4.34-1.24,6.5a.82.82,0,0,0,.34.94c.57.41,1.11.87,1.69,1.34l-.54,1L81.88,96a3.56,3.56,0,0,0-.33,1.35c0,.13.16.31.29.41.44.36.91.68,1.4,1l-.72.7-1.21-.78L81,99.84l-1.17-.22L80,98.49l-1.37.09-.14-.94,1.76-.3.29-1.52-2.33.27-.25-.91c.81-.26,1.56-.52,2.33-.75a.69.69,0,0,0,.55-.61c.39-2.35.8-4.7,1.21-7.06a2.2,2.2,0,0,0,0-.3,6.67,6.67,0,0,0-1,1c-.32.32-.64.66-1,1l.68,2.39-1,.26c-.1-.36-.17-.71-.29-1-.06-.14-.2-.33-.33-.36a.69.69,0,0,0-.45.21A.89.89,0,0,0,78.4,91c.33.8.59,1.64.92,2.54l-1.25.36-.72-2.13-1,1.07-1.16-.93,1.06-1.11c-.84-.32-1.69-.31-2.52-.59l.2-1.06a3.23,3.23,0,0,1,.44,0c.71.1,1.42.16,2.11.31A.94.94,0,0,0,77.54,89l.26-.35-1.72-.32.11-1a3,3,0,0,1,1.24.06c.5,0,1.06.26,1.48.1s.64-.69.94-1.06l.81-1c-.29,0-.44,0-.57.09-2.12.69-4.24,1.38-6.36,2a.77.77,0,0,0-.61.64c-.12.59-.3,1.16-.46,1.73-.06.22-.14.43-.23.68l-1.12-.28.37-2-.56.1a.67.67,0,0,0-.62.69,8.07,8.07,0,0,1-.19,1l-1.09-.15.07-1-1.67.41-.35-1.14,1.53-.67-.45-.72.54-.61c.63.42,1.21,1,2.13.53l-1.24-1.5.66-.56,1.93,1.51L80,83.85v-.17c-.84-.18-1.68-.38-2.53-.52-.14,0-.36.16-.49.3-.41.45-.81.93-1.24,1.43l-1-.9.91-1.09-1.29-.24L72.65,85l-1-1.06L73,82.4l-1.29-.24a6.62,6.62,0,0,1,.2-1.81l1.57.3-.74-2.06L73.9,78l.39,1.12c.1.28.19.57.28.85.33,1,.33,1,1.5,1l-.47-1.21,1.09-.47c.22.54.45,1,.62,1.54a.89.89,0,0,0,.82.68,23,23,0,0,0,2.41.31c-.1-.1-.2-.22-.31-.31C78.36,80,76.48,78.5,74.62,77a.89.89,0,0,0-.94-.15c-.62.22-1.26.39-1.93.6l-.33-1,1.76-.68a1.7,1.7,0,0,0-2.29-.6l-.25-.8.71-.43-.58-.5c-.19-.16-.38-.32-.62-.54l.82-.9,1.5,1,.23-.89.93.06-.16,1.07c-.1.79,0,.88.8,1.27l.41-2.17,1.13.33a18.72,18.72,0,0,1-.28,2,1.37,1.37,0,0,0,.61,1.57c1.61,1.25,3.17,2.56,4.76,3.85.17.14.35.26.52.38l.14-.08c-.22-.8-.43-1.61-.68-2.41,0-.12-.27-.22-.43-.26-.51-.12-1-.2-1.56-.3l-.7-.17.15-1.13,1.73.32c-.14-.41-.22-.69-.31-1s-.33-.29-.56-.34l-2.56-.48.21-1.09,2.48.34-.5-1.58L80.29,72l.56,1.38,1.4-1.64,1.07.67-.9,1.07-.71.84c-.64.76-.64.78,0,1.68l1.06-1.2.86.62L82,77.34l1.27,2.79c.09-.26.16-.38.19-.52.41-2.28.8-4.57,1.23-6.85a.85.85,0,0,0-.41-1c-.56-.35-1.07-.78-1.68-1.23l.62-.9,1.87,1.24.14-.46a.8.8,0,0,0-.34-1,14.85,14.85,0,0,1-1.21-1l.65-.88,1.23,1,.48-1.93,1.25.23-.38,2,1.3-.39.25,1a9.25,9.25,0,0,1-1.06.39A1,1,0,0,0,86.59,71a18.94,18.94,0,0,0,2.18-.22l.24,1-2.76.72-1.48,7.78a4,4,0,0,0,.41-.28c.43-.42.84-.86,1.29-1.25a.77.77,0,0,0,.26-1c-.24-.57-.4-1.17-.62-1.82l1-.25.7,1.5.81-.88-1.07-3,1.34-.41.6,2.34,1-1.22,1.28.91-1.12,1.3,2.47.57-.09,1-.56,0-2.11-.3c-.73-.1-.82,0-1.11.78l1.52.5-.25,1c-.7-.14-1.36-.23-2-.39a.83.83,0,0,0-1,.36c-.42.57-.89,1.1-1.44,1.77a4.4,4.4,0,0,0,.5-.09c2.18-.72,4.36-1.46,6.54-2.17a.74.74,0,0,0,.58-.66c.15-.73.35-1.46.54-2.23l1.22.23L95.1,78.4l.86-.15.32-1.68,1.22.24,0,.78c.51.08,1-.21,1.54-.21l.45,1.29-1.7.49.54.71-.43.56a1.93,1.93,0,0,1-.41-.26c-.6-.71-1.27-.53-2-.19l1.44,1.52-.63.65c-.56-.47-1.11-.9-1.65-1.36a.64.64,0,0,0-.68-.15l-6.83,2.17-.32.12a4.21,4.21,0,0,0,1.3.36,3.11,3.11,0,0,0,1.4.21c.4-.13.68-.64,1-1L91,82l1,.77-.92,1.06c.7.44,1.1.39,1.57-.2L94,81.86l.91,1L93.7,84.44l1.27.34-.29,1.53-1.32-.19.63,2-1.25.68c-.23-.77-.42-1.48-.63-2.18s0-.71-1-.84a2.42,2.42,0,0,0-.4,0c.08.23.14.42.21.61s.13.34.23.59l-1.22.54c-.23-.62-.45-1.17-.63-1.74a.71.71,0,0,0-.62-.54A15.6,15.6,0,0,0,86.48,85a3,3,0,0,0,.3.32c1.81,1.51,3.64,3,5.45,4.53a.73.73,0,0,0,.87.13c.57-.24,1.16-.43,1.79-.66l.47.81-1.82.84c.62,1,1.43.54,2.22.35l.39.8-1,.44,1.4,1.16-.81,1.08-1.42-.9-.23.73L93,94.38l.18-1.69-.74-.54-.39,2.06L90.88,94c.09-.65.13-1.31.27-1.95a1.15,1.15,0,0,0-.46-1.29C89.07,89.39,87.5,88,85.9,86.59a1.43,1.43,0,0,0-.61-.34l.83,2.54,2.5.61-.1,1.09-1.71-.19c.06.27.12.46.16.65a.62.62,0,0,0,.56.52c.63.11,1.26.25,1.89.39l.78.19L90,93.15l-2.43-.46.5,1.57-1.44.49L86,93.3l-1.48,1.79-1-.64,2.09-2.59-.49-1.19Z" transform="translate(0 -66.54)"/></g><g class="cls-2"><path class="cls-1" d="M180.52,103.53l-.5-.28c.19-.35.36-.68.55-1a.38.38,0,0,0,0-.47,7.54,7.54,0,0,0-.78-1,2.58,2.58,0,0,0,0,.29c0,1.26,0,2.53,0,3.79a.45.45,0,0,0,.29.49c.36.18.72.38,1.09.58l-.2.59-1.16-.46a2.18,2.18,0,0,0,0,.8c0,.07.12.16.21.2.28.15.58.29.9.44l-.33.47-.76-.31-.06.67H179l-.05-.65-.77.19-.17-.51,1-.36v-.89l-1.29.41-.23-.49c.42-.24.82-.46,1.23-.67a.38.38,0,0,0,.24-.4c0-1.37,0-2.74-.07-4.1a.68.68,0,0,0-.05-.17,4.25,4.25,0,0,0-.46.64c-.15.22-.29.44-.44.68l.63,1.28-.53.25c-.1-.19-.18-.38-.28-.56s-.15-.17-.22-.16a.36.36,0,0,0-.23.16.51.51,0,0,0,0,.62c.28.42.51.86.79,1.33l-.66.33-.63-1.12-.48.72-.75-.4.48-.74c-.51-.09-1,0-1.48-.06V103l.24,0c.41,0,.82-.06,1.22,0a.53.53,0,0,0,.58-.36,1.45,1.45,0,0,1,.11-.23h-1l0-.59a1.77,1.77,0,0,1,.71-.1,2.07,2.07,0,0,0,.84-.1c.2-.13.28-.46.41-.7s.21-.39.35-.66a3.07,3.07,0,0,0-.31.11l-3.36,1.84a.42.42,0,0,0-.28.42c0,.34,0,.68-.07,1,0,.13,0,.26-.06.41l-.66,0v-1.16l-.31.12a.37.37,0,0,0-.27.45,6,6,0,0,1,0,.6l-.63,0-.07-.56-.89.41-.32-.61.79-.54-.33-.36.23-.39c.41.16.8.43,1.26.07l-.86-.71.32-.39,1.24.64,4-2.13,0-.09c-.49,0-1,0-1.48,0-.08,0-.18.13-.24.22-.19.3-.36.61-.54.94l-.65-.41.4-.71h-.75l-.7,1.51-.67-.49.6-1h-.76a4,4,0,0,1-.08-1h.92l-.64-1.08.58-.47c.12.22.23.4.34.59s.16.3.25.45c.28.51.29.51.94.38l-.39-.63.56-.38c.18.28.37.53.51.8a.5.5,0,0,0,.54.29,11,11,0,0,0,1.39-.08l-.21-.14c-1.21-.63-2.43-1.26-3.63-1.9a.5.5,0,0,0-.55,0c-.32.18-.66.35-1,.54l-.28-.5.91-.57a1,1,0,0,0-1.35-.1l-.23-.42.36-.31-.38-.22-.41-.24.37-.6.94.38,0-.52.53-.07c0,.24,0,.43,0,.62,0,.46.07.5.58.63V93.51l.68.06a11.81,11.81,0,0,1,.06,1.18.76.76,0,0,0,.5.81c1.05.53,2.07,1.11,3.1,1.66l.33.16.07-.06c-.21-.43-.41-.86-.64-1.29,0-.06-.17-.09-.27-.09-.3,0-.6,0-.91,0l-.41,0,0-.65h1l-.28-.52c-.08-.14-.22-.13-.35-.13h-1.49V94L176,93.9l-.45-.83.79-.36.46.71.61-1.07.68.26-.4.7c-.1.18-.2.37-.31.55-.28.49-.27.5.18.94L178,94l.55.25-.7,1.26,1,1.44c0-.16,0-.24,0-.32,0-1.33,0-2.66,0-4a.49.49,0,0,0-.34-.54c-.35-.14-.69-.33-1.07-.51l.25-.58,1.18.5c0-.12,0-.19,0-.27a.46.46,0,0,0-.31-.56,7.74,7.74,0,0,1-.78-.43l.27-.56.81.44L179,89h.74v1.16l.69-.35.24.51a5.7,5.7,0,0,1-.55.33.56.56,0,0,0-.35.78c.42,0,.77-.29,1.2-.35l.25.54-1.48.7v4.54a1.83,1.83,0,0,0,.2-.21c.2-.28.38-.57.59-.84a.44.44,0,0,0,0-.6c-.19-.29-.35-.61-.54-1l.53-.25.55.77.36-.58-.92-1.58.7-.37.59,1.25.45-.79.81.37-.49.85L184,94l0,.54a2,2,0,0,1-.31,0c-.41,0-.82,0-1.23.06s-.46.07-.54.56l.91.11,0,.6c-.41,0-.79,0-1.16,0a.49.49,0,0,0-.53.31c-.17.36-.38.71-.62,1.15l.28-.1c1.15-.64,2.29-1.29,3.44-1.92a.43.43,0,0,0,.26-.43c0-.44,0-.87.06-1.32h.72v1.13l.47-.18v-1h.71l.06.44c.3,0,.52-.22.85-.28l.39.68-.9.46.37.34-.18.36a.89.89,0,0,1-.26-.11c-.41-.33-.77-.15-1.16.12l1,.7-.28.43c-.37-.2-.73-.39-1.07-.59a.38.38,0,0,0-.41,0c-1.2.66-2.4,1.3-3.61,1.95l-.17.1a2.8,2.8,0,0,0,.77.07c.28,0,.6.08.81,0s.32-.43.47-.66l.24-.37.64.33-.4.7c.44.17.66.09.86-.29s.37-.71.58-1.11l.62.44-.52,1,.75.06v.89l-.76,0,.56,1.06-.63.51-.58-1.16c-.21-.41-.11-.4-.66-.37l-.23,0c.07.13.13.23.19.32l.19.31-.63.44c-.19-.33-.37-.62-.54-.92a.39.39,0,0,0-.41-.23,7.76,7.76,0,0,0-1.26.08c.07,0,.13.11.2.15,1.19.65,2.38,1.3,3.56,2a.44.44,0,0,0,.5,0c.29-.2.61-.37.94-.57l.35.41-.94.66c.45.47.86.16,1.29,0l.3.4-.51.36.91.5-.34.7-.9-.36-.05.44h-.63l-.07-1-.48-.23V104h-.69a10.68,10.68,0,0,1,0-1.13.67.67,0,0,0-.4-.68c-1.05-.59-2.09-1.21-3.14-1.83a.81.81,0,0,0-.38-.12l.74,1.34,1.47.07.06.63-1,.08.16.35a.34.34,0,0,0,.37.22l1.11,0,.45,0v.64H182.6l.45.83-.76.43-.51-.75-.64,1.16-.62-.25.91-1.68-.41-.62Z" transform="translate(0 -66.54)"/></g><g class="cls-2"><path class="cls-1" d="M237.23,105.68l-.38-.76c.52-.27,1-.53,1.48-.77a.55.55,0,0,0,.35-.59,15.74,15.74,0,0,0-.11-1.91,4.19,4.19,0,0,0-.28.34c-1,1.55-2.06,3.11-3.1,4.65a.68.68,0,0,0-.05.84c.31.51.58,1,.88,1.6l-.73.56-1.06-1.51a2.92,2.92,0,0,0-.69,1c-.06.09,0,.29.08.41.23.42.49.83.75,1.28l-.79.31-.68-1-.62.76-.83-.55.46-.84-1.09-.38.21-.78,1.47.35.72-1.09-1.91-.55.11-.8c.72.06,1.39.11,2.06.19a.58.58,0,0,0,.63-.29c1.08-1.71,2.17-3.4,3.26-5.1a2.08,2.08,0,0,0,.08-.24,5.47,5.47,0,0,0-1.1.41l-1.1.47-.26,2.09-.86-.13c0-.32.1-.61.11-.91a.49.49,0,0,0-.13-.39.56.56,0,0,0-.42,0,.74.74,0,0,0-.51.75c0,.73-.07,1.47-.12,2.27l-1.09-.13.14-1.89-1.16.49-.6-1.1,1.19-.52c-.55-.52-1.22-.8-1.77-1.29l.51-.76c.1,0,.23.09.34.15.52.31,1,.59,1.54.93a.8.8,0,0,0,1,0l.31-.19-1.23-.82.42-.76a2.48,2.48,0,0,1,1,.46c.38.2.74.54,1.12.56s.72-.33,1.08-.52l1-.53a4,4,0,0,0-.48-.12l-5.62-.49a.65.65,0,0,0-.69.29c-.28.42-.61.81-.92,1.2-.12.15-.25.28-.4.45l-.78-.59.94-1.42-.47-.11a.56.56,0,0,0-.71.33,7,7,0,0,1-.48.74l-.79-.48.36-.74-1.42-.23.1-1,1.41,0-.11-.71.61-.29c.36.53.62,1.18,1.49,1.11l-.48-1.57.7-.23,1,1.81,6.67.67.05-.13c-.6-.41-1.18-.84-1.8-1.23-.1-.06-.33,0-.48.07-.47.22-.93.46-1.43.71l-.46-1,1.07-.55-.92-.61-2.1,1.27-.41-1.14,1.56-.77-.93-.61a5.25,5.25,0,0,1,.76-1.34l1.12.75.1-1.85,1.09-.1c0,.36,0,.68-.06,1l-.06.76c-.07.86-.07.87.84,1.23l0-1.09h1c0,.49,0,1,0,1.4a.74.74,0,0,0,.43.8,17.72,17.72,0,0,0,1.76,1,1.73,1.73,0,0,0-.14-.34c-1-1.77-1.95-3.53-2.9-5.31a.74.74,0,0,0-.68-.42c-.56,0-1.11-.11-1.7-.17l.06-.85,1.58,0c-.14-.9-.76-1.17-1.57-1.22l.06-.7.69-.1L233,87c-.1-.19-.19-.38-.31-.62l.94-.44.85,1.25.47-.62.7.35-.47.78c-.34.58-.33.67.2,1.25l1-1.55.78.62a16.93,16.93,0,0,1-.89,1.5,1.13,1.13,0,0,0,0,1.41c.84,1.5,1.63,3,2.44,4.56A4.62,4.62,0,0,0,239,96l.13,0c.09-.7.2-1.4.27-2.1,0-.11-.14-.26-.26-.34-.36-.27-.74-.5-1.1-.76-.16-.1-.31-.22-.5-.36l.49-.83,1.24.82.07-.85c0-.24-.15-.34-.31-.45l-1.84-1.22.52-.77,1.82,1.07.13-1.39,1.26.2,0,1.25,1.63-.81.61.86-1.05.54-.83.42c-.75.38-.75.4-.55,1.31l1.22-.58.47.76-1.89,1,.07,2.59a2.22,2.22,0,0,0,.32-.34c1.07-1.64,2.13-3.29,3.21-4.92a.73.73,0,0,0,0-.94c-.32-.46-.58-1-.9-1.51l.78-.5,1,1.58.26-.3a.68.68,0,0,0,.08-.94c-.23-.36-.39-.75-.61-1.17l.79-.47.63,1.19,1-1.34.9.59-.95,1.43,1.14.13-.12.82a7.24,7.24,0,0,1-.95,0,.83.83,0,0,0-1.06.68c.52.32,1.17.27,1.76.55l-.14.86-2.38-.35-3.71,5.57a1.68,1.68,0,0,0,.41-.09c.47-.18.93-.39,1.41-.54a.67.67,0,0,0,.54-.71c0-.51.07-1,.12-1.61L245,94l.05,1.39L246,95l.16-2.7,1.17.12-.3,2,1.2-.61.69,1.13-1.3.64,1.74,1.25-.39.71-.42-.21c-.51-.31-1-.63-1.54-.93s-.63-.29-1.12.24l1,.89-.53.7c-.5-.33-1-.62-1.42-1a.7.7,0,0,0-.89,0c-.52.3-1.06.56-1.7.9.22,0,.32.09.41.1l5.8.46a.64.64,0,0,0,.67-.32c.36-.52.75-1,1.15-1.56l.88.58-.92,1.38L251,99l.8-1.2.88.59-.29.6c.38.22.83.15,1.27.33l-.07,1.16-1.49-.17.19.72-.52.29a1.53,1.53,0,0,1-.23-.33c-.23-.75-.82-.83-1.52-.81l.62,1.65-.7.29c-.29-.54-.57-1.06-.84-1.59a.52.52,0,0,0-.48-.34l-6-.56h-.29a3.81,3.81,0,0,0,.9.71c.33.23.66.59,1,.62s.74-.28,1.11-.44l.59-.26.53.93-1.07.53c.4.57.73.66,1.29.36l1.63-.9.39,1.05-1.49.85.89.69-.73,1.09-1-.58-.17,1.75-1.19.12c.08-.67.16-1.29.23-1.9s.19-.57-.51-1a2.44,2.44,0,0,0-.31-.14c0,.21,0,.38,0,.55s0,.31,0,.53l-1.13,0c0-.57,0-1.07.08-1.57a.58.58,0,0,0-.31-.62,12.54,12.54,0,0,0-1.61-.93,3.42,3.42,0,0,0,.13.34l2.75,5.32a.62.62,0,0,0,.64.39c.52,0,1,0,1.61.08l.1.78-1.7,0c.18.94.93.89,1.61,1l0,.74-.92,0,.71,1.36-1,.58-.81-1.17-.43.49-.77-.51.7-1.25-.39-.67-1,1.48-.85-.56c.29-.48.53-1,.86-1.43a1,1,0,0,0,.06-1.16c-.81-1.59-1.57-3.2-2.35-4.8a1.32,1.32,0,0,0-.37-.47l-.19,2.25,1.74,1.29-.43.82-1.27-.71c0,.23-.06.4-.09.56a.52.52,0,0,0,.27.58c.45.3.9.62,1.34.93l.54.41-.52.78-1.74-1.16-.13,1.39-1.28-.09V107.4l-1.73.9-.55-.82,2.48-1.32v-1.09Z" transform="translate(0 -66.54)"/></g><g class="cls-2"><path class="cls-1" d="M340.79,126.09l-1.2-.69c.46-.84.86-1.64,1.32-2.41a.92.92,0,0,0-.05-1.13,22.28,22.28,0,0,0-1.91-2.52c0,.24-.06.48-.06.72,0,3.07,0,6.15,0,9.22a1.14,1.14,0,0,0,.71,1.2c.89.42,1.75.91,2.67,1.4l-.49,1.44-2.83-1.11a4.91,4.91,0,0,0-.1,1.93c0,.18.29.39.5.5.7.36,1.42.69,2.2,1.07l-.81,1.14-1.85-.75-.15,1.62h-1.66l-.12-1.58-1.85.47-.43-1.25,2.34-.87v-2.16l-3.13,1-.57-1.2c1-.56,2-1.11,3-1.63a.94.94,0,0,0,.6-1c-.07-3.33-.12-6.66-.18-10a2.91,2.91,0,0,0-.11-.4,9.21,9.21,0,0,0-1.13,1.57c-.36.52-.7,1.07-1.08,1.65l1.55,3.1-1.31.62c-.23-.47-.42-.93-.67-1.36a.82.82,0,0,0-.55-.41.84.84,0,0,0-.55.41,1.21,1.21,0,0,0,0,1.5c.66,1,1.24,2.09,1.92,3.23l-1.62.82-1.54-2.73-1.16,1.74-1.83-1,1.17-1.81c-1.23-.22-2.41,0-3.61-.15v-1.51a5,5,0,0,1,.6-.1c1-.06,2-.16,3-.13a1.31,1.31,0,0,0,1.4-.89c.06-.14.13-.27.26-.54H329l-.11-1.44a4.32,4.32,0,0,1,1.72-.24,5.19,5.19,0,0,0,2.05-.25c.49-.31.69-1.11,1-1.7s.5-1,.84-1.61a6.4,6.4,0,0,0-.76.27c-2.73,1.49-5.44,3-8.18,4.46a1.08,1.08,0,0,0-.67,1c0,.83-.11,1.66-.18,2.49,0,.31-.08.61-.13,1l-1.62-.09v-2.82l-.74.28a.92.92,0,0,0-.68,1.11,11.31,11.31,0,0,1,0,1.44l-1.53.08-.17-1.36-2.17,1-.78-1.48,1.93-1.31-.8-.87.57-1c1,.41,1.93,1.06,3.06.17l-2.1-1.73.77-.95,3,1.57,9.78-5.18-.05-.23c-1.19,0-2.39-.07-3.59,0-.2,0-.45.32-.59.54-.46.73-.87,1.48-1.33,2.28l-1.56-1,1-1.73h-1.83l-1.71,3.67-1.62-1.2,1.44-2.47h-1.83a8.53,8.53,0,0,1-.19-2.53h2.23l-1.55-2.63,1.4-1.14.82,1.43c.21.36.41.73.61,1.09.7,1.25.7,1.25,2.29.92l-.94-1.53,1.36-.92c.44.67.89,1.28,1.25,1.94a1.22,1.22,0,0,0,1.31.71,30.1,30.1,0,0,0,3.38-.19,4.11,4.11,0,0,0-.51-.34c-2.95-1.54-5.91-3.07-8.85-4.63a1.19,1.19,0,0,0-1.32,0c-.79.46-1.62.86-2.49,1.32l-.69-1.22,2.23-1.38c-1-1.11-2.13-.91-3.29-.23l-.55-1,.86-.77-.93-.54-1-.57.9-1.46,2.3.93.09-1.28,1.28-.15c0,.56,0,1,.07,1.5.06,1.11.15,1.21,1.41,1.53V101.7l1.64.14a28.17,28.17,0,0,1,.15,2.87,1.87,1.87,0,0,0,1.23,2c2.54,1.29,5,2.69,7.53,4,.26.15.54.27.81.4l.17-.15c-.51-1-1-2.11-1.55-3.13-.09-.16-.43-.23-.66-.24-.74,0-1.48,0-2.21,0-.32,0-.63,0-1,0l-.08-1.6h2.45c-.29-.53-.48-.89-.68-1.24s-.53-.33-.85-.33h-3.63v-1.53l3.48-.19-1.09-2,1.91-.88,1.13,1.74c.52-.9,1-1.72,1.49-2.61l1.64.63-1,1.7-.75,1.34c-.68,1.2-.68,1.23.44,2.3l1.14-1.92,1.34.62-1.71,3.07,2.47,3.49a5,5,0,0,0,.13-.76c0-3.23-.09-6.47-.09-9.7a1.2,1.2,0,0,0-.83-1.31c-.86-.34-1.67-.79-2.62-1.25l.62-1.4,2.87,1.22c0-.29.06-.48.08-.66a1.12,1.12,0,0,0-.75-1.36c-.64-.28-1.22-.67-1.9-1l.65-1.37,2,1.07c.05-1,.1-1.87.14-2.77h1.79V93.6l1.67-.87.59,1.25a12.26,12.26,0,0,1-1.34.81,1.39,1.39,0,0,0-.84,1.9c1,0,1.87-.7,2.93-.86l.6,1.32-3.6,1.7V109.9a5.16,5.16,0,0,0,.48-.5c.49-.68.92-1.39,1.44-2a1.08,1.08,0,0,0,.09-1.46c-.47-.72-.85-1.49-1.31-2.33l1.28-.6,1.34,1.88.88-1.41c-.73-1.26-1.46-2.52-2.25-3.86l1.73-.9,1.43,3L345,99.79l2,.92-1.19,2.07,3.53.13.12,1.33c-.29,0-.53.08-.77.09-1,0-2,.08-3,.14s-1.13.17-1.31,1.36l2.21.28c0,.52-.05,1-.08,1.45-1,0-1.92,0-2.83,0a1.15,1.15,0,0,0-1.27.76c-.43.88-.94,1.73-1.51,2.79a6.29,6.29,0,0,0,.66-.24c2.8-1.56,5.59-3.13,8.39-4.67a1.07,1.07,0,0,0,.63-1.06c0-1.05.1-2.1.15-3.2h1.74v2.75l1.14-.43v-2.37h1.73l.16,1.08c.72,0,1.27-.54,2.05-.69l1,1.65-2.2,1.12.92.83-.45.87a2.36,2.36,0,0,1-.62-.25c-1-.81-1.88-.38-2.83.28l2.36,1.71-.69,1c-.89-.49-1.76-.94-2.61-1.43a.89.89,0,0,0-1,0c-2.92,1.59-5.86,3.16-8.79,4.74l-.41.25a6.48,6.48,0,0,0,1.87.16,4.32,4.32,0,0,0,2-.09c.52-.28.77-1.05,1.13-1.61l.58-.9,1.57.8-1,1.7c1.06.42,1.6.24,2.09-.69s.91-1.75,1.42-2.71l1.5,1.06-1.26,2.55,1.84.13v2.17l-1.87.09,1.38,2.56-1.54,1.25c-.5-1-.95-1.91-1.42-2.82s-.25-1-1.61-.89c-.15,0-.3,0-.55.09l.45.78c.13.22.27.43.47.75l-1.52,1.06c-.48-.8-.93-1.5-1.32-2.22a1,1,0,0,0-1-.57,21.5,21.5,0,0,0-3.07.18,5.25,5.25,0,0,0,.49.37c2.89,1.6,5.78,3.18,8.65,4.79a1,1,0,0,0,1.23,0c.71-.48,1.47-.89,2.28-1.38l.86,1-2.29,1.61c1.11,1.14,2.1.38,3.14-.09l.74,1-1.26.86,2.22,1.23-.82,1.7-2.19-.88-.12,1.07h-1.53c-.06-.78-.12-1.53-.19-2.36l-1.15-.55v2.93h-1.68c-.05-.91-.17-1.83-.13-2.74a1.62,1.62,0,0,0-1-1.65c-2.57-1.44-5.09-3-7.64-4.45a1.93,1.93,0,0,0-.93-.3l1.8,3.26,3.58.18.14,1.52-2.39.19c.16.35.29.6.39.85a.85.85,0,0,0,.91.55c.89,0,1.79,0,2.69,0,.35,0,.69,0,1.11.06v1.56h-3.44l1.1,2-1.86,1-1.23-1.82-1.55,2.83-1.51-.63,2.2-4.08-1-1.51Z" transform="translate(0 -66.54)"/></g><g class="cls-2"><path class="cls-1" d="M470.82,110.72l-.85-.24c.16-.59.28-1.14.45-1.68a.58.58,0,0,0-.21-.69,13.93,13.93,0,0,0-1.55-1.26,3.5,3.5,0,0,0,.07.45c.46,1.89.94,3.78,1.39,5.68a.72.72,0,0,0,.62.63c.61.12,1.21.29,1.85.45l-.08,1-1.91-.25a3.15,3.15,0,0,0,.24,1.21c0,.11.24.19.38.23.49.12,1,.21,1.52.32l-.32.82-1.26-.17.16,1-1,.26-.31-1-1.07.57-.46-.71,1.31-.89-.33-1.33-1.77,1.09-.54-.65c.55-.51,1.07-1,1.6-1.46a.61.61,0,0,0,.22-.69c-.55-2-1.09-4.08-1.64-6.12a1.28,1.28,0,0,0-.12-.23,5.84,5.84,0,0,0-.46,1.13c-.14.38-.27.77-.41,1.19l1.42,1.67-.71.58a9.13,9.13,0,0,0-.62-.73.52.52,0,0,0-.4-.17.58.58,0,0,0-.28.33.79.79,0,0,0,.23.93c.56.52,1.08,1.09,1.67,1.7l-.87.75L465.39,113l-.45,1.25-1.28-.32.45-1.29c-.79.06-1.48.38-2.24.46l-.23-.93A2.3,2.3,0,0,1,462,112c.6-.19,1.2-.4,1.81-.54a.83.83,0,0,0,.73-.76c0-.09,0-.19.08-.37l-1.5.37-.29-.87a2.88,2.88,0,0,1,1-.41c.42-.15.94-.18,1.22-.46s.26-.79.37-1.2l.27-1.12a4.07,4.07,0,0,0-.43.28c-1.45,1.33-2.89,2.67-4.35,4a.69.69,0,0,0-.26.74c.12.51.19,1,.27,1.56,0,.2,0,.39.07.63l-1,.19-.43-1.74-.42.29a.58.58,0,0,0-.24.78,8.71,8.71,0,0,1,.23.89l-.94.28-.31-.81-1.18.94-.71-.79,1-1.1-.63-.41.21-.68c.66.1,1.35.36,1.91-.37l-1.56-.74.33-.7,2.11.5q2.61-2.34,5.23-4.67l-.07-.14c-.74.17-1.49.32-2.22.52-.12,0-.23.26-.28.42-.17.51-.31,1-.47,1.6l-1.11-.36.33-1.21-1.13.28-.49,2.52-1.17-.5.51-1.74-1.14.28a6,6,0,0,1-.5-1.53l1.38-.34-1.36-1.38.69-.91.73.75.54.58c.62.66.62.66,1.55.22l-.82-.8.7-.78c.37.35.74.66,1.06,1a.79.79,0,0,0,.92.24,19.24,19.24,0,0,0,2-.64c-.12,0-.24-.1-.37-.13-2.05-.5-4.1-1-6.14-1.5a.76.76,0,0,0-.81.23c-.42.4-.87.78-1.33,1.19L455,103l1.16-1.19a1.5,1.5,0,0,0-2.05.36l-.5-.54L454,101l-.65-.19-.7-.2.33-1,1.56.22-.14-.8.76-.29c.11.35.19.63.28.92.2.67.28.72,1.1.72l-.47-1.89,1-.16a17.23,17.23,0,0,1,.53,1.74,1.19,1.19,0,0,0,1.06,1c1.76.41,3.5.89,5.25,1.34a5,5,0,0,0,.56.11l.08-.11c-.47-.57-.94-1.14-1.43-1.69-.08-.09-.3-.08-.45,0l-1.36.33-.63.12-.29-1,1.5-.38-.6-.66c-.17-.18-.38-.12-.57-.07l-2.24.55-.23-.94,2.11-.65-1-1.08,1-.84,1,.91.52-1.84,1.11.14c-.12.43-.23.81-.33,1.19s-.18.63-.26.94c-.24.85-.23.86.62,1.35l.41-1.35.92.17-.59,2.15,2.06,1.78a3.77,3.77,0,0,0,0-.49c-.51-2-1-4-1.54-6a.75.75,0,0,0-.71-.68c-.58-.08-1.15-.23-1.8-.37l.17-1,1.95.31c0-.19,0-.31-.05-.42a.73.73,0,0,0-.67-.73c-.43-.07-.85-.22-1.33-.35l.19-.94,1.37.36-.33-1.73,1.1-.27.43,1.74.9-.79.55.68a7.7,7.7,0,0,1-.7.7.88.88,0,0,0-.23,1.3c.62-.18,1-.71,1.67-1l.57.71-1.95,1.6,1.68,6.8a3.48,3.48,0,0,0,.22-.38c.19-.49.35-1,.58-1.48a.69.69,0,0,0-.17-.91c-.4-.37-.75-.79-1.17-1.23l.7-.57,1.11,1,.33-1-2-2,.92-.81L469,95.25l.38-1.36,1.35.27-.42,1.45,2.2-.45.27.79a3.93,3.93,0,0,1-.46.18c-.6.18-1.21.35-1.81.54s-.66.27-.6,1l1.41-.17.17.9c-.61.16-1.17.32-1.75.43a.73.73,0,0,0-.66.65c-.13.62-.31,1.22-.51,2a2.72,2.72,0,0,0,.37-.25c1.49-1.38,3-2.78,4.46-4.15a.66.66,0,0,0,.22-.74c-.15-.65-.26-1.31-.39-2l1.07-.26.41,1.69.64-.44L475,93.86l1.07-.26.26.64c.44-.13.7-.53,1.16-.74l.84.87-1.19,1,.69.37-.13.6a1.78,1.78,0,0,1-.43-.06c-.74-.35-1.21,0-1.7.6l1.72.69-.27.75c-.62-.16-1.23-.31-1.82-.48a.56.56,0,0,0-.61.14l-4.68,4.26-.22.21a3.83,3.83,0,0,0,1.18-.19,2.77,2.77,0,0,0,1.2-.35c.27-.26.31-.77.45-1.17.07-.19.13-.38.22-.64l1.09.25-.35,1.2c.72.09,1-.1,1.18-.74s.3-1.22.46-1.89l1.09.43-.39,1.75,1.15-.19.33,1.33-1.13.34,1.24,1.37-.76,1-1.3-1.52c-.47-.53-.31-.55-1.13-.3l-.32.14.39.41.4.39-.77.89c-.42-.42-.8-.78-1.15-1.17a.62.62,0,0,0-.7-.2,13.27,13.27,0,0,0-1.86.58,2.28,2.28,0,0,0,.36.15l6.05,1.63a.65.65,0,0,0,.75-.21c.37-.4.77-.77,1.19-1.19l.68.48-1.16,1.34c.85.53,1.35-.09,1.92-.53l.6.49-.64.72,1.56.42-.25,1.17-1.48-.21.09.68-.95.24-.47-1.43-.8-.17.45,1.81-1,.26c-.17-.56-.38-1.1-.5-1.67a1,1,0,0,0-.85-.87c-1.8-.49-3.58-1-5.38-1.57a1.13,1.13,0,0,0-.62,0l1.61,1.74,2.23-.44.32.92-1.44.48.37.46a.53.53,0,0,0,.64.2c.55-.14,1.1-.26,1.66-.38l.7-.13.23,1L474,110l1,1.08-1,.93-1-.94-.52,2-1-.16.74-2.85-.84-.78Z" transform="translate(0 -66.54)"/></g></g></svg>';
		break;
		case 'butterfly' :
			var svgpath = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 87" preserveAspectRatio="none"><defs><style>.cls-1{opacity:0.5;}</style></defs><g id="Layer_2" data-name="Layer 2"><polygon class="cls-1" points="500 69 500 0 250 31 0 0 0 69 500 69"/></g><g id="Layer_1" data-name="Layer 1"><polygon class="cls-2" points="500 69 500 87 0 87 0 69 250 31 500 69"/></g></svg>';
		break;
		case 'animated_waves' :
			var svgpath = '<svg xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" viewBox="0 0 1417 794"><g transform="translate(708.5,397) scale(1,1) translate(-708.5,-397)"><linearGradient id="lg-0.47933337739525805" x1="0" x2="1" y1="0" y2="0"><stop stop-color="#ff00ff" offset="0"></stop><stop stop-color="#00ffff" offset="1"></stop></linearGradient><path d="" fill="url(#lg-0.47933337739525805)" opacity="0.4"><animate attributeName="d" dur="16.666666666666664s" repeatCount="indefinite" keyTimes="0;0.333;0.667;1" calcmod="spline" keySplines="0.2 0 0.2 1;0.2 0 0.2 1;0.2 0 0.2 1" begin="0s" values="M0 0L 0 324.5015185187671Q 177.125 298.8138829521863  354.25 260.63120664633277T 708.5 421.84239744185305T 1062.75 299.5855871657385T 1417 235.48542325129495L 1417 0 Z;M0 0L 0 556.0570965244332Q 177.125 579.7599181261096  354.25 548.8915090487861T 708.5 395.7607383039141T 1062.75 443.3170210284975T 1417 256.3449578716147L 1417 0 Z;M0 0L 0 334.3647287988679Q 177.125 481.3057865872413  354.25 454.2751592079807T 708.5 222.1385345469165T 1062.75 311.5931733565468T 1417 207.7608312901822L 1417 0 Z;M0 0L 0 324.5015185187671Q 177.125 298.8138829521863  354.25 260.63120664633277T 708.5 421.84239744185305T 1062.75 299.5855871657385T 1417 235.48542325129495L 1417 0 Z"></animate></path><path d="" fill="url(#lg-0.47933337739525805)" opacity="0.4"><animate attributeName="d" dur="16.666666666666664s" repeatCount="indefinite" keyTimes="0;0.333;0.667;1" calcmod="spline" keySplines="0.2 0 0.2 1;0.2 0 0.2 1;0.2 0 0.2 1" begin="-5.5555555555555545s" values="M0 0L 0 367.984770106225Q 177.125 324.01361362793216  354.25 306.28929020884766T 708.5 261.4361931670915T 1062.75 281.9991979465649T 1417 330.4049835349433L 1417 0 Z;M0 0L 0 509.4734329285319Q 177.125 540.4994363377859  354.25 517.4158015508332T 708.5 208.80779803904636T 1062.75 403.9369293490124T 1417 230.0060597212573L 1417 0 Z;M0 0L 0 457.76392017006003Q 177.125 542.0993611405761  354.25 524.1995210781041T 708.5 462.4877002854464T 1062.75 375.4312262098515T 1417 120.38246779772896L 1417 0 Z;M0 0L 0 367.984770106225Q 177.125 324.01361362793216  354.25 306.28929020884766T 708.5 261.4361931670915T 1062.75 281.9991979465649T 1417 330.4049835349433L 1417 0 Z"></animate></path><path d="" fill="url(#lg-0.47933337739525805)" opacity="0.4"><animate attributeName="d" dur="16.666666666666664s" repeatCount="indefinite" keyTimes="0;0.333;0.667;1" calcmod="spline" keySplines="0.2 0 0.2 1;0.2 0 0.2 1;0.2 0 0.2 1" begin="-11.111111111111109s" values="M0 0L 0 445.3038054314494Q 177.125 534.232193249191  354.25 485.43500150394334T 708.5 276.5172429814809T 1062.75 323.75793608780236T 1417 207.78966179439004L 1417 0 Z;M0 0L 0 604.6134986658767Q 177.125 588.8800511232508  354.25 558.8422971616096T 708.5 237.8922322513478T 1062.75 377.97839431525256T 1417 175.7858492654541L 1417 0 Z;M0 0L 0 617.8357128464033Q 177.125 457.79207391362837  354.25 431.84403152530945T 708.5 314.67826764223526T 1062.75 410.99068403607623T 1417 277.9132981120613L 1417 0 Z;M0 0L 0 445.3038054314494Q 177.125 534.232193249191  354.25 485.43500150394334T 708.5 276.5172429814809T 1062.75 323.75793608780236T 1417 207.78966179439004L 1417 0 Z"></animate></path></g></svg>';
		break;
		
		case '0' :
		default :
			if (prefix == 'divider') focus.find('.pbck-divider1-container').remove();
			if (prefix == 'divider-2') focus.find('.pbck-divider2-container').remove();
			return;
		break;
	}

	// remove all divider for B/C
	focus.find('.pbck-divider-container:not(.pbck-divider2-container):not(.pbck-divider1-container)').remove();

	if (prefix == 'divider') {
		focus.find('.pbck-divider1-container').remove();
		// focus.find('.pbck-divider-container').remove();
		if (! focus.find('.pbck-divider1-container').length) focus.prepend('<div class="pbck-divider-container pbck-divider1-container">' + svgpath + '</div>');

		var divider = focus.find('.pbck-divider1-container');

		// position
		if ($ck('#' + prefix + 'position').val() == 'top') {
			divider.removeClass('pbck-divider-bottom').addClass('pbck-divider-top');
		} else {
			divider.removeClass('pbck-divider-top').addClass('pbck-divider-bottom');
		}
		// placement
		if ($ck('#' + prefix + 'placement').val() == 'over') {
			divider.removeClass('pbck-divider-under').addClass('pbck-divider-over');
		} else {
			divider.removeClass('pbck-divider-over').addClass('pbck-divider-under');
		}

		// flip
		// if ($ck('#' + prefix + 'fliphorizontal').val() == '1') {
		if ($ck('[name="' + prefix + 'fliphorizontal"]:checked').val() == '1') {
			divider.addClass('ckflip-horizontal');
		} else {
			divider.removeClass('ckflip-horizontal');
		}
		// if ($ck('#' + prefix + 'flipvertical').val() == '1') {
		if ($ck('[name="' + prefix + 'flipvertical"]:checked').val() == '1') {
			divider.addClass('ckflip-vertical');
		} else {
			divider.removeClass('ckflip-vertical');
		}

		divider.find('path, polygon, polyline, rect, circle').attr('fill', $ck('#' + prefix + 'color').val());
		divider.css('background-color', $ck('#' + prefix + 'bgcolor').val());
		divider.find('svg').css('height', ckTestUnit($ck('#' + prefix + 'height').val()));
		divider.find('svg').css('width', ckTestUnit($ck('#' + prefix + 'width').val(), '%'));
		var widthVal = parseInt(ckTestUnit($ck('#' + prefix + 'width').val(), '%'));
		var offsetHRange = (widthVal - 100) / 2;
		var offsetVal = parseInt($ck('#' + prefix + 'offseth').val());
		divider.find('svg').css('margin-left', (offsetVal * offsetHRange / 100) + '%');
		divider.find('svg').css('margin-top', ckTestUnit($ck('#' + prefix + 'offsetv').val()));
		// divider.find('svg').css('width', ckTestUnit($ck('#' + prefix + 'width').val()));
		// divider.find('svg').css('max-width', ckTestUnit($ck('#' + prefix + 'width').val()));

	} else if (prefix == 'divider-2') {
		focus.find('.pbck-divider2-container').remove();
		// focus.find('.pbck-divider-container').remove();
		
		if ($ck('#divider-2shape').val()) {
			if (! focus.find('.pbck-divider2-container').length) focus.prepend('<div class="pbck-divider-container pbck-divider2-container">' + svgpath + '</div>');
		} else {
			return;
		}
		var divider2 = focus.find('.pbck-divider2-container');

		// position
		if ($ck('#' + prefix + 'position').val() == 'top') {
			divider2.removeClass('pbck-divider-bottom').addClass('pbck-divider-top');
		} else {
			divider2.removeClass('pbck-divider-top').addClass('pbck-divider-bottom');
		}
		// placement
		if ($ck('#' + prefix + 'placement').val() == 'over') {
			divider2.removeClass('pbck-divider-under').addClass('pbck-divider-over');
		} else {
			divider2.removeClass('pbck-divider-over').addClass('pbck-divider-under');
		}

		// if ($ck('#' + prefix + 'fliphorizontal').val() == '1') {
		if ($ck('[name="' + prefix + 'fliphorizontal"]:checked').val() == '1') {
			divider2.addClass('ckflip-horizontal');
		} else {
			divider2.removeClass('ckflip-horizontal');
		}
		// if ($ck('#' + prefix + 'flipvertical').val() == '1') {
		if ($ck('[name="' + prefix + 'flipvertical"]:checked').val() == '1') {
			divider2.addClass('ckflip-vertical');
		} else {
			divider2.removeClass('ckflip-vertical');
		}

		divider2.find('path, polygon, polyline, rect, circle').attr('fill', $ck('#' + prefix + 'color').val());
		divider2.css('background-color', $ck('#' + prefix + 'bgcolor').val());
		divider2.find('svg').css('height', ckTestUnit($ck('#' + prefix + 'height').val()));
		divider2.find('svg').css('width', ckTestUnit($ck('#' + prefix + 'width').val(), '%'));
		var widthVal = parseInt(ckTestUnit($ck('#' + prefix + 'width').val(), '%'));
		var offsetHRange = (widthVal - 100) / 2;
		var offsetVal = parseInt($ck('#' + prefix + 'offseth').val());
		divider2.find('svg').css('margin-left', (offsetVal * offsetHRange / 100) + '%');
		divider2.find('svg').css('margin-bottom', ckTestUnit($ck('#' + prefix + 'offsetv').val()));

	}
}

function ckShowResponsiveCssEdition(blocid) {
	blocid = '#' + blocid;
	bloc = $ck(blocid);
	$ck('.editfocus').removeClass('editfocus');
	bloc.addClass('editfocus');
	var focus = $ck('.editfocus');
	$ck('#popup_editionck').empty().fadeIn().addClass('ckwait');
	var range = ckGetResponsiveRange();

	var ckprops = $ck('> .tab_blocstyles', focus);
	var fields = new Object();
	fieldslist = ckprops.attr('fieldslist') ? ckprops.attr('fieldslist').split(',') : Array();
	for (j=0;j<fieldslist.length;j++) {
		fieldname = fieldslist[j];
		fields[fieldname] = ckprops.attr(fieldname);

	}
	fields = JSON.stringify(fields);

	var myurl = PAGEBUILDERCK.URIPBCK + "&task=interface.load&layout=responsivecssedition&" + PAGEBUILDERCK.TOKEN;
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			ckobjid: bloc.prop('id'),
			fields: fields,
			responsiverange: range
		}
	}).done(function(code) {
		$ck('#popup_editionck').append(code).removeClass('ckwait');
		$ck('#ckwaitoverlay').remove();
		ckFillEditionPopup(blocid, $ck('.workspaceck'), range);
		ckAddEventOnResponsiveFields($ck('#popup_editionck'), blocid);
		ckMakeTooltip($ck('#popup_editionck'));
	}).fail(function() {
		alert(Joomla.JText._('CK_FAILED', 'Failed'));
	});
}

function ckAddEventOnResponsiveFields(editionarea, blocid) {
	$ck('.inputbox:not(.colorPicker):not(.cknoupdate)', editionarea).change(function() {
		ckRenderResponsiveCss();
	});
	$ck('.colorPicker,.inputbox[type=radio]', editionarea).blur(function() {
		ckRenderResponsiveCss();
	});
}

function ckRenderResponsiveCss() {
	ckAddSpinnerIcon($ck('.headerckicon.cksave'));
	var editionarea = document.body;
	var focus = $ck('.editfocus');
	var blocid = focus.attr('id');
	var fieldslist = new Array();
	var fields = new Object();
	var rangeNumber = ckGetResponsiveRangeNumber();
	$ck('.inputbox', editionarea).each(function(i, el) {
		el = $ck(el);
		fields[el.attr('name')] = el.val();
		if (el.attr('type') == 'radio') {
			fields[el.attr('name')] = $ck('[name="' + el.attr('name') + '"]:checked').val();
			if (el.prop('checked')) {
				fields[el.attr('id')] = 'checked';
			} else {
				fields[el.attr('id')] = '';
			}
		}
	});

	$ck('> .ckprops.ckresponsiverange' + rangeNumber, focus).each(function(i, ckprops) {
		ckprops = $ck(ckprops);
		fieldslist = ckprops.attr('fieldslist') ? ckprops.attr('fieldslist').split(',') : Array();
		// fieldslist.each(function(fieldname) {
		// for (var fieldname of fieldslist) {
		for (j=0;j<fieldslist.length;j++) {
			fieldname = fieldslist[j];
			if (typeof(fields[fieldname]) == 'null') 
				fields[fieldname] = ckprops.attr(fieldname);
		// });
		}
	});
	fields = JSON.stringify(fields);
	var customstyles = new Object();
	$ck('.menustylescustom').each(function() {
		$this = $ck(this);
		customstyles[$this.attr('data-prefix')] = $this.attr('data-rule');
	});
	customstyles = JSON.stringify(customstyles);
	ckSaveResponsiveEdition(); // save fields before ajax to keep sequential/logical steps
	var myurl = PAGEBUILDERCK.URIPBCK + "&task=interface.load&layout=renderresponsivecss&" + PAGEBUILDERCK.TOKEN;
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			objclass: focus.prop('class'),
			ckobjid: blocid,
			responsiverange: rangeNumber,
			customstyles: customstyles,
			fields: fields
		}
	}).done(function(code) {
		if (! $ck('> .ckstyleresponsive.ckresponsiverange' + rangeNumber, $ck('.workspaceck #' + blocid)).length) {
			$ck('.workspaceck #' + blocid).append('<div class="ckstyleresponsive ckresponsiverange' + rangeNumber + '"></div>')
		}
		$ck('> .ckstyleresponsive.ckresponsiverange' + rangeNumber, $ck('.workspaceck #' + blocid)).empty().append(code);
		ckOrderStylesResponsive(blocid);
		ckRemoveSpinnerIcon($ck('.headerckicon.cksave'));
		ckSaveAction();
	}).fail(function() {
		alert(Joomla.JText._('CK_FAILED', 'Failed'));
	});
}

function ckOrderStylesResponsive(blocid) {
	for (var i = 4; i > 0; i--) {
		$ck('.workspaceck #' + blocid).append($ck('.ckstyleresponsive.ckresponsiverange' + i, $ck('.workspaceck #' + blocid)));
	}
}

function ckSaveResponsiveEdition() {
	var focus = $ck('.editfocus');
	var rangeNumber = ckGetResponsiveRangeNumber();
	var editionarea = $ck('#popup_editionck');
	$ck('> .ckprops.ckresponsiverange' + rangeNumber, focus).remove();
	$ck('.ckproperty', editionarea).each(function(i, tab) {
		tab = $ck(tab);
		tabid = tab.attr('id');
		(! $ck('> .' + tabid + '_ckresponsiverange' + rangeNumber, focus).length) ? focus.prepend('<div class="' + tabid + '_ckresponsiverange' + rangeNumber + ' ckprops ckresponsive ckresponsive' + rangeNumber + '" />') : $ck('> .' + tabid + '_ckresponsiverange' + rangeNumber, focus).empty();
		focusprop = $ck('> .' + tabid + '_ckresponsiverange' + rangeNumber, focus);
		ckSavePopupfields(focusprop, tabid);
		fieldslist = ckGetPopupFieldslist(focus, tabid);
		focusprop.attr('fieldslist', fieldslist);
	});
}

function ckSearchAddon(field) {
	var cont = $ck($ck(field).parents('.menuck')[0]);
	var s = cont.find('.cksearchleftpanel').val().toLowerCase();
	cont.find('.menuitemck').each(function() {
		var addon = $ck(this);
		if (addon.attr('data-type').toLowerCase().indexOf(s) != -1
			|| addon.find('.menuitemck_title').text().toLowerCase().indexOf(s) != -1 ) {
			addon.show();
		} else {
			addon.hide();
		}
	});
}

function ckSearchAddonClear() {
	$ck('#menuck .menuitemck').show();
	$ck('#ckaddonsearch input').val('');
}

function ckActivatePanel(target) {
	switch (target) {
		case 'responsive':
			ckShowResponsiveSettings();
		break;
		case 'addons':
		default:
			ckShowResponsiveSettings(1);
		break;
	}

	$ck('.menuckpanel').removeClass('active');
	$ck('.menuckpanel[data-target="' + target + '"]').addClass('active');
	$ck('.menuckpaneltarget').hide();
	$ck('.menuckpaneltarget[data-target="' + target + '"]').show();
}

function ckOpenCustomCssEditor() {
	// article, page, module, element, library
	var customcss = $ck('.ckcustomcssfield', $ck('.workspaceck')).length ? $ck('.ckcustomcssfield', $ck('.workspaceck')).html() : '';
	var myurl = PAGEBUILDERCK.URIPBCK + "&task=interface.load&layout=customcss&" + PAGEBUILDERCK.TOKEN;
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			customcss : customcss
		}
	}).done(function(code) {
		$ck('#ckcustomcssedition').empty().append(code);
		CKBox.open({handler: 'inline', content: 'ckcustomcssedition', style: {padding: '10px'},
			footerHtml: '<a class="ckboxmodal-button" href="javascript:void(0)" onclick="ckSaveCustomCss()">' + Joomla.JText._('CK_SAVE_CLOSE') + '</a>'
		});
	}).fail(function() {
		alert(Joomla.JText._('CK_FAILED', 'Failed'));
	});
}

function ckSaveCustomCss() {
	if (!$ck('#ckcustomcsseditor').length) return;
	ckcustomcsseditor.save(); // save the editor to the textarea
	var customcss = $ck('#ckcustomcsseditor').val();
	if (! $ck('.ckcustomcssfield', $ck('.workspaceck')).length) {
		$ck('.workspaceck').prepend('<div class="ckcustomcssfield" style="display: none;"></div>');
	}
	$ck('.ckcustomcssfield', $ck('.workspaceck')).text(customcss);
	CKBox.close();
}

function ckSetAddonsDisplaytypeState(type) {
	$ck('.ckaddonsdisplaytype').hide();
	$ck('#ckaddonsdisplaytype' + type).show();
	$ck('.headerckdisplaytype').removeClass('active');
	$ck('.headerckdisplaytype[data-type="' + type + '"]').addClass('active');
	ckSetUserState('pagebuilderck.addons.displaytype', type)
}

function ckSetUserState(key, value) {
	var myurl = PAGEBUILDERCK_ADMIN_URL + "&task=ajaxSetUserState&" + PAGEBUILDERCK.TOKEN;
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			key : key,
			value : value
		}
	}).done(function(code) {
		
	}).fail(function() {
		alert(Joomla.JText._('CK_FAILED', 'Failed'));
	});
}

function ckAddDataOnImages() {
	$ck('.imageck').each(function() {
		var imgwrap = $ck(this);
		ckAddDataOnImage(imgwrap);
	});
}

/* imgwrap must be the wrapper .imageck */
function ckAddDataOnImage(imgwrap) {
		var img = imgwrap.find('img');
		if (! imgwrap.find('.ckimagedata').length) {
			imgwrap.append('<div class="ckimagedata"></div>');
		}
		var imgdata = imgwrap.find('.ckimagedata');
		var imglink = imgwrap.find('> a').length ? imgwrap.find('> a') : false;
		var imgtitle = imgwrap.find('img').attr('title');
		if (imgtitle) {
			if (! imgdata.find('.ckimagedata-title').length) {
				imgdata.append('<div class="ckimagedata-title cktip" title="' + imgtitle + '"><span class="fa fa-font"></span></div>');
			} else {
				imgdata.find('.ckimagedata-title').attr('title', imgtitle);
			}
		} else {
			imgdata.find('.ckimagedata-title').remove();
		}
		if (imglink) {
			if (! imgdata.find('.ckimagedata-link').length) {
				imgdata.append('<div class="ckimagedata-link cktip" title="' + imglink.attr('href') + '"><span class="fa fa-link"></span></div>');
			} else {
				imgdata.find('.ckimagedata-link').attr('title', imglink.attr('href'));
			}
			
		} else {
			imgdata.find('.ckimagedata-link').remove();
		}
		ckMakeTooltip(imgdata);
}

function ckApplyParallax() {
	var doParallax = $ck('#elementscontainer #rowbgparallax').val();
	var focus = $ck('.editfocus');
	if (doParallax == '1') {
		var speed = $ck('#elementscontainer #rowbgparallaxspeed').val();
		speed = speed ? speed : '50';
		focus.attr('data-parallax', speed);
	} else {
		focus.removeAttr('data-parallax');
	}
}

function ckApplyLinkWrap() {
	var link = $ck('#elementscontainer #wraplinkurl').val();
	var linktext = $ck('#elementscontainer #wraplinktext').val();
	var linkclass = $ck('#elementscontainer #wraplinkclass').val();
	var linktarget = $ck('#elementscontainer #wraplinktarget').val();
	var linkicon = $ck('#elementscontainer [name="wraplinkicon"]:checked').val();
	var dataAttrs = linktext ? ' data-custom-text="1"' : '';
	dataAttrs += linkicon == '1' ? ' data-link-icon="1"' : '';
	dataAttrs += linktarget !== 'default' ? ' target="' + linktarget + '"' : '';

	linktext = linktext ? linktext : 'Link to ' + link;
	var focus = $ck('.editfocus');
	focus.removeClass('pbck-has-link-wrap');
	focus.find('> .inner a.pbck-link-wrap').remove();
	if (link) {
		focus.addClass('pbck-has-link-wrap');
		focus.find('> .inner').prepend('<a href="' + link + '" class="pbck-link-wrap' + (linkclass !== '' ? ' ' + linkclass : '') + '"' + dataAttrs + '>' + linktext + '</a>');
		ckAddFakeLinkEvent();
	}
}

function ckMakePagesDraggable(iframe) {
	var frame = $ck(iframe);
	var framehtml = frame.contents();
}

function ckStartDragPage(row) {

}

function ckShowAclEdition(blocid) {
	blocid = '#' + blocid;
	bloc = $ck(blocid);
	$ck('.editfocus').removeClass('editfocus');
	bloc.addClass('editfocus');
	$ck('#popup_editionck').empty().fadeIn().addClass('ckwait');
	var acl = bloc.attr('data-acl-edit') ? bloc.attr('data-acl-edit') : '';

	var myurl = PAGEBUILDERCK.URIPBCK + "&task=interface.load&layout=setacl&" + PAGEBUILDERCK.TOKEN;
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			ckobjid: bloc.prop('id'),
			acl: acl
		}
	}).done(function(code) {
		$ck('#popup_editionck').append(code).removeClass('ckwait');
		$ck('#ckwaitoverlay').remove();
		ckMakeTooltip($ck('#popup_editionck'));
		// set ACL settings
		var aclview = bloc.attr('data-acl-view');
		var acledit = bloc.attr('data-acl-edit');
		if (aclview) {
			aclview = aclview.split(',');
			for (i=0; i<aclview.length; i++) {
				$ck('#popup_editionck').find('.ckaclrow[data-group="' + aclview[i] + '"]').find('.ckaclfieldview').removeAttr('checked');
			}
		}
		if (acledit) {
			acledit = acledit.split(',');
			for (i=0; i<acledit.length; i++) {
				$ck('#popup_editionck').find('.ckaclrow[data-group="' + acledit[i] + '"]').find('.ckaclfieldedit').removeAttr('checked');
			}
		}
	}).fail(function() {
		alert(Joomla.JText._('CK_FAILED', 'Failed'));
		$ck('#ckwaitoverlay').remove();
	});
}

function ckSetAcl() {
	ckAddSpinnerIcon($ck('.headerckicon.cksave'));
	var aclview = new Array();
	var acledit = new Array();
	$ck('#popup_editionck').find('.ckaclrow').each(function() {
		$this = $ck(this);
		var groupid = $this.attr('data-group');
		var viewauth = $this.find('.ckaclfieldview:checked').length;
		var editauth = $this.find('.ckaclfieldedit:checked').length;
		if (!viewauth) aclview.push(groupid);
		if (!editauth) acledit.push(groupid);
	});
	var focus = $ck('.editfocus');
	aclview = aclview.join(',');
	acledit = acledit.join(',');
	if (aclview) {
		focus.attr('data-acl-view', aclview);
	} else {
		focus.removeAttr('data-acl-view');
	}
	if (acledit) {
		focus.attr('data-acl-edit', acledit);
	} else {
		focus.removeAttr('data-acl-edit');
	}
	ckRemoveSpinnerIcon($ck('.headerckicon.cksave'));
}

function ckCheckUserRightsFromAcl(restrictedGroups) {
	userGroups = PAGEBUILDERCK.USERGROUPS.split(',');
	// special check to allow super users in any case
	if (userGroups.includes('8')) return true;

	restrictedGroups = restrictedGroups.split(',');

	for (var i=0; i<restrictedGroups.length; i++) {
		if (userGroups.includes(restrictedGroups[i])) {
			return false;
		}
	}
	return true;
}

/*-------------------------------
 * ---		Copy paste styles ---
 --------------------------------*/

function ckCopyStyles(blocid) {
	var item = ckGetObjectAnyway(blocid);

	PAGEBUILDERCK.CLIPBOARD = {"ID" : item.attr('id'), "PROPS" : item.find('> .ckprops').clone(), "STYLE" : item.find('> .ckstyle').html()};

//	alert(TCK.TexJoomla.JText._('CK_COPYTOCLIPBOARD', 'Current styles copied to clipboard !'));
}

function ckPasteStyles(blocid) {
	var item = ckGetObjectAnyway(blocid);

	if (PAGEBUILDERCK.CLIPBOARD) {
		if (!confirm(Joomla.JText._('CK_COPYFROMCLIPBOARD', 'Apply styles from Clipboard ? This will replace all current existing styles.')))
			return;
		item.find('> .ckprops').remove();
		item.prepend(PAGEBUILDERCK.CLIPBOARD.PROPS);
		item.find('> .ckstyle').empty().append(PAGEBUILDERCK.CLIPBOARD.STYLE);
		var re = new RegExp(PAGEBUILDERCK.CLIPBOARD.ID, 'g');
		if (item.find('> .ckstyle').length) item.find('> .ckstyle').html(item.find('> .ckstyle').html().replace(re,item.attr('id')));
	} else {
		alert(Joomla.JText._('CK_CLIPBOARDEMPTY', 'Clipboard is empty'));
	}
}


function ckSwitchResponsiveSmart(responsiverange, force) {
	$ck('#popup_editionck').attr('data-responsiverange', responsiverange);
	if (! responsiverange) responsiverange = ckGetResponsiveRangeNumber();
	if (! force) force = false;
	var button = $ck('.ckresponsivebutton[data-range="' + responsiverange + '"]');
	var focus = $ck('.editfocus');
	var blocid = focus.attr('id');

	// do nothing if click on the active button
	if (button.hasClass('active')) return;
	if (button.hasClass('active') && !force) {
		ckRemoveWorkspaceWidth();
	} else {
		$ck('.ckresponsivebutton').removeClass('active').removeClass('ckbutton-warning');
		// $ck('#popup_editionck .ckresponsivebutton').removeClass('active').removeClass('ckbutton-warning');
		button.addClass('active').addClass('ckbutton-warning');
		ckSetWorkspaceWidth(responsiverange);
	}

	var editionarea = $ck('#popup_editionck');
	$ck('#popup_editionck input.ckresponsivable:not([type="radio"])').val('');
	$ck('#popup_editionck textarea.ckresponsivable').val('');
	$ck('#popup_editionck [type="radio"].ckresponsivable').removeProp('checked');

	if (responsiverange == '5' || ! responsiverange) {
		ckRemoveWorkspaceWidth();
		ckFillEditionPopup(blocid, $ck('.workspaceck'));
		ckAddEventOnFields(editionarea, blocid);
	} else {
		// update the css responsive values in the panel
		ckFillEditionPopup($ck('.editfocus').attr('id'), $ck('.workspaceck'), responsiverange);
		ckAddEventOnFields(editionarea, blocid);
	}
}

function ckSaveEditionPanel(close) {
	if (! close) close = false;
	var rangeNumber = ckGetResponsiveRangeNumber();

	if (! rangeNumber || rangeNumber == '5') {
		if (close) {
			ckGetPreviewAreastylescss(false, false, false, false, false, true);
			$ck('.menuckpanel[data-target="addons"]').trigger('click');
		} else {
			ckGetPreviewAreastylescss();
		}
	} else {
		ckRenderResponsiveCss();
		ckBeforeSaveEditionPopup();
		if (close) {
			ckCloseEdition();
			ckRemoveWorkspaceWidth();
			$ck('.menuckpanel[data-target="addons"]').trigger('click');
		}
	}
}

/**
 * for message plugin only
 */
function ckMessageCloseButtonDisable() {
	$ck('.workspaceck .messageck button.close').on('click', function(e) {
		e.preventDefault();
		return false;
	});
}

/**
 * Trim only on the left for a given character
 * 
 * @param {type} str
 * @param {type} charlist
 * @returns {unresolved}
 */
function ckTrimLeft(str, charlist) {
	if (! str) return str;
	if (charlist === undefined)
		charlist = "\s";

	return str.replace(new RegExp("^[" + charlist + "]+"), "");
};


/**
 * Trim only on the right for a given character
 * 
 * @param {type} str
 * @param {type} charlist
 * @returns {unresolved}
 */
function ckTrimRight(str, charlist) {
	if (! str) return str;
	if (charlist === undefined)
		charlist = "\s";

	return str.replace(new RegExp("[" + charlist + "]+$"), "");
};

function ckInitRangeInputs() {
	$ck('.ckrangeinputupdate').on('input', function() {
		ckSetRrangeInputText(this);
		$ck(this).trigger('change');
	});
}

function ckSetRrangeInputText(field) {
	var $textfield = $ck(field).next();
	$textfield.text(field.value);
	$textfield.val(field.value);
}

function ckUpdateRangeInputs(field) {
	var $field = $ck(field);
	var rangeField = $field.prev('.ckrangeinputupdate');
	rangeField.val($field.val());
	rangeField.trigger('change');
}

function ckFocusOnField(id) {
	$ck('.ckfieldfocus').removeClass('ckfieldfocus');
	$ck(id).addClass('ckfieldfocus');
}

function ckGetYoutubeCode(url) {
	var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/;
	var match = url.match(regExp);
	return (match&&match[7].length==11)? match[7] : false;
}

/* empty function because only used in frontend */
function ckLoadYoutube(thumb) {
	return;
}

function ckIsVideoLocal(videoSrc) {
	var isLocal = !(/^(f|ht)tps?:\/\//i.test(videoSrc));
	return isLocal;
}

/*-----------------------------------------
---		for SVG icon selection 			---
-------------------------------------------*/

function ckSelectSvgIcon(code, _path) {
	if (! code) return;

	var iconposition = $ck('select[name="iconiconposition"]').val();
	var icon = '<span class="pbckicon" data-path="' + _path + '" data-pos="' + iconposition + '" style="display:inline-block;">' + code + '</span>';
	// get the focus from the function because it can be anything 
	var focusIcon = ckSelectIcon(icon);
	var svg = focusIcon.find('svg');

	if (! svg[0]) {
		alert('Error : No SVG found');
	}
	// hack needed to remove square border on Tabler icons
	if (svg[0].classList.contains('icon-tabler')) {
		svg.find('path').first().remove();
	}
	svg.find('title').remove();

	CKBox.close();
	$ck('#fontawesomefieldset').hide();
	$ck('#fontawesomefieldsetoptions').hide();
	$ck('#iconicon-class').hide();
	$ck('#iconsvgfieldset').show();
	ckUpdateSvgIconSettings('1');
	$ck('#iconicontype').val('svg');
	ckSaveEditionPanel();
}

function ckUpdateSvgIconSettings(ignoreColor) {
	if (! ignoreColor) ignoreColor = false;
	if (! $ck('.editfocus').find('.pbckicon svg').length) return;
	ckSetSvgIconSize();
	ckSetSvgIconStroke();
	ckSetSvgIconStrokeColor(ignoreColor);
	ckSetSvgIconFillColor(ignoreColor);
	ckSetSvgIconPosition();
	ckSetSvgIconVPosition();
}

function ckGetSvgStyleScoped(id) {
	var focusStyle = $ck('.editfocus > .ckstyle');
	var scopedStyle = focusStyle.find('style.scopedsvg#scopedsvg' + id);
	if (! scopedStyle.length) {
		focusStyle.append('<style id="scopedsvg' + id + '" class="scopedsvg" scoped></style>');
		scopedStyle = focusStyle.find('style#scopedsvg' + id);
	}
	return scopedStyle;
}

function ckSetSvgIconSize() {
	var focus = $ck('.editfocus');
	if (! focus.find('.pbckicon svg').length) return;
	var icon = focus.find('.pbckicon svg');
	var iconsize = $ck('#popup_editionck input[name$="iconsvgsize"]').val();
	var scopedStyle = ckGetSvgStyleScoped('svgsize');
	scopedStyle.empty().append('#' + focus.attr('id') + ' svg { width: ' + iconsize + 'px;height: ' + iconsize + 'px; }');
//	icon.css({'width': iconsize, 'height': iconsize});
	// focus.find('.iconck').css({'width': iconsize});
}

function ckSetSvgIconStroke() {
	var focus = $ck('.editfocus');
	if (! focus.find('.pbckicon svg').length) return;
	var icon = focus.find('.pbckicon svg');
	var strokesize = $ck('#popup_editionck input[name$="iconsvgstrokewidth"]').val();

	var svg = focus.find('.pbckicon svg');

	// hack needed for Ionicons that are 512px
	if (svg[0].classList.contains('ionicon')) {
		icon.addClass('svgicon-ionicon')
		strokesize = strokesize * 16;
	}
	// hack needed for fontawesome that are 512px
	if (svg[0].innerHTML.indexOf('fontawesome') !== -1) {
		icon.addClass('svgicon-fontawesome')
		strokesize = strokesize * 16;
	}

//	icon.find('svg, path, circle, rect, line').each(function() {
//		$ck(this).attr('stroke-width', strokesize);
//	});
	var scopedStyle = ckGetSvgStyleScoped('svgstrokewidth');
	scopedStyle.empty().append('#' + focus.attr('id') + ' svg *, #' + focus.attr('id') + ' svg.svgicon-ionicon *, #' + focus.attr('id') + ' svg.svgicon-fontawesome * { stroke-width: ' + strokesize + 'px; }');
}

function ckSetSvgIconStrokeColor(ignoreColor) {
	if (! ignoreColor) ignoreColor = false;
	var strokecolor = $ck('#popup_editionck input[name$="iconsvgstroke"]').val();
//	if (! strokecolor) return;
	var focus = $ck('.editfocus');
	if (! focus.find('.pbckicon svg').length) return;
//	var icon = focus.find('.pbckicon svg');
//	icon.find('svg, path, circle, rect, line').each(function() {
//		if (! strokecolor) {
//			if (! ignoreColor) $ck(this).attr('stroke', 'transparent');
//		} else {
//			$ck(this).attr('stroke', strokecolor);
//		}
//	});
	if (! strokecolor) {
		if (! ignoreColor) strokecolor = 'transparent';
	}
	var scopedStyle = ckGetSvgStyleScoped('svgstrokecolor');
	scopedStyle.empty().append('#' + focus.attr('id') + ' svg * { stroke: ' + strokecolor + '; }');

}

function ckSetSvgIconFillColor(ignoreColor) {
	if (! ignoreColor) ignoreColor = false;
	var color = $ck('#popup_editionck input[name$="iconsvgfill"]').val();
	
	var focus = $ck('.editfocus');
	if (! focus.find('.pbckicon svg').length) return;
//	var icon = focus.find('.pbckicon svg');
//	icon.find('svg, path, circle').each(function() {
//		if (! color) {
//			if (! ignoreColor) $ck(this).attr('fill', 'transparent');
//		} else {
//			$ck(this).attr('fill', color);
//		}
//	});
	if (! color) {
		if (! ignoreColor) color = 'transparent';
	}
	var scopedStyle = ckGetSvgStyleScoped('svgfillcolor');
	scopedStyle.empty().append('#' + focus.attr('id') + ' svg * { fill: ' + color + '; }');
}

function ckSetSvgIconPosition() {
	var focus = $ck('.editfocus');
	if (! focus.find('.pbckicon svg').length) return;

	var pbckicon = focus.find('.pbckicon svg').first();
	var title = pbckicon.parent();
	title.find('span.pbckicon').remove();

	var iconposition = $ck('#popup_editionck select[name*="iconsvgposition"]').val();

	switch (iconposition) {
		default:
		case 'left':
			title.prepend(pbckicon);
			pbckicon.css('display', 'inline-block');
			break;
		case 'top':
			title.prepend(pbckicon);
			pbckicon.css('display', 'block');
			break;
		case 'right':
			title.append(pbckicon);
			pbckicon.css('display', 'inline-block');
			break;
		case 'bottom':
			title.append(pbckicon);
			pbckicon.css('display', 'block');
			break;
	}

	pbckicon = title.find('.pbckicon svg');
	pbckicon.attr('data-pos', iconposition);
}

function ckSetSvgIconVPosition() {
	var focus = $ck('.editfocus');
	if (! focus.find('.pbckicon svg').length) return;
	var icon = focus.find('.pbckicon svg');
//	var iconVPos = $ck('input[name="iconiconvposition"]:checked').val();
	var iconVPos = $ck('#popup_editionck select[name*="iconiconsvgvposition"]').val();
	icon.css('vertical-align', '');
	if (iconVPos != 'default') {
		icon.css('vertical-align', iconVPos);
		focus.find('.pbckicon svg').attr('data-vpos', iconVPos);
	} else {
		focus.find('.pbckicon svg').attr('data-vpos', '');
	}
	
}

function ckClearStyles(el) {
	el = ckGetObjectAnyway(el);
	if (!confirm(Joomla.JText._('CK_CONFIRM_CLEAR_STYLES','CK_CONFIRM_CLEAR_STYLES'))) return;
	el.find('> .ckprops').remove();
	el.find('> .ckstyle').empty();
}

function ckTogglePanel() {
	const body = $ck(document.body);
	if (body.hasClass('hidepanel')) {
		body.removeClass('hidepanel');
	} else {
		body.addClass('hidepanel');
	}
}

function ckMakeExpandable() {
	$ck('#items_edition_list .item_overlay').on('click', function() {
		$ck(this).parent().addClass('item_expanded');
	});
	$ck('#items_edition_list .item_close').on('click', function() {
		$ck(this).parent().removeClass('item_expanded');
	});
}

function ckReturnLoadStyle(id, state, title) {
	var params = $ck('.pagebuilderckparams');
	var styles = params.attr('data-styles');
	if (styles) {
		styles = styles.split(',');
	} else {
		styles  = new Array();
	}
	if (! styles.includes(id) && state == true) {
		styles.push(id);
	} else if (state == false) {
		styles = styles.filter(data => data != id && data != '-1');
	}

	params.attr('data-styles', styles.join(','));

	var myurl = PAGEBUILDERCK.URIPBCK + "&task=styles.load&" + PAGEBUILDERCK.TOKEN;
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			styles: styles
		}
	}).done(function(code) {
		$ck('style#pagebuilderckstylesloaded').remove();
		$ck(document.head).append('<style id="pagebuilderckstylesloaded"></style>');
		$ck('style#pagebuilderckstylesloaded').text(code);
	}).fail(function() {
		alert(Joomla.JText._('CK_FAILED', 'Failed'));
//		$ck('#cktoolbarLoadPageOptions .ckwait').removeClass('ckwait');
	});
}

function ckLoadStylesFromParams() {
	var params = $ck('.pagebuilderckparams');
	var styles = params.attr('data-styles');

	if (styles) {
		styles = styles.split(',');
	} else if (styles === undefined) {
		styles = PAGEBUILDERCK.STYLESTOLOAD.split(',');
		params.attr('data-styles', styles.filter(data => data != 0 && data != '-1'))
	} else {
		return;
	}

	var frame = $ck('#pbckiframestyles');
	frame.load(function() {
		var framehtml = frame.contents();
		framehtml.find('.ckrowstyle').each(function() {
			if (styles.includes($ck(this).attr('data-id'))) {
				$ck(this).find('input').prop('checked', true);
			} else {
				$ck(this).find('input').prop('checked', false);
			}
		});
		ckReturnLoadStyle(0, false, '')
	});
}

function ckClearStylesFromPanel() {
	if (!confirm(Joomla.JText._('CK_CONFIRM_CLEAR_STYLES','CK_CONFIRM_CLEAR_STYLES'))) return;
	$ck('#popup_editionck .tab[id$="styles"] input').val('');
	ckGetPreviewAreastylescss();
}

function ckAddEditionForNestedAddons(id) {
	if (id) ckMakeItemsSortable($ck(id));
	if (! id) id = '.cktype';
	$ck(id).find('.innercontent').on('mouseover', function() {
		// add feature to have a direct button to add
		if (PAGEBUILDERCK.FASTEDITION === '1' && !$ck(this).find('.cktype-button-add').length) {
			// bloc.append('<div class="blockck-button-add blockck-button-add-right"><div class="blockck-button-add-icon" onclick="ckAddColumnFromButton(this)">+</div></div>');
			if (! $ck(this).find('.cktype').length) {
				$ck(this).append('<div class="cktype-button-add cktype-button-add-center"><div class="cktype-button-add-icon" onclick="ckAddItemFromButton(this)">+</div></div>');
			}
		}
	}).on('mouseleave', function() {
		$ck(this).find('.cktype-button-add').remove();
	});
}