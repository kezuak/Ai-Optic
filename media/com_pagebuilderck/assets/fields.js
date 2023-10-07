/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2018. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */
 
(function($) {
	// "use strict";

	var CKField = function (type, opts) {
		if (! type) return false;

		if (!(this instanceof CKField)) return new CKField(type, opts);
//		var ckfields = window.ckfields || [];

		var defaults = {
			optionList			: {},
			id				: '',
			name				: '',
			cssClass				: '',
			disabled				: '',
			required				: '',
			labelText				: '',
			rowWrapperTag			: '',		// any html tag, if set the wrapper is added
			rowWrapperClass			: '',
			labelWrapperTag			: '',		// any html tag, if set the wrapper is added
			labelWrapperClass		: '',
			fieldWrapperTag			: '',		// any html tag, if set the wrapper is added
			fieldWrapperClass		: '',
		};

		opts = $.extend(defaults, opts);

		this.text = function() {
			var html = '<input type="text" ' + this.getRequired() + ' id="' + this.getId() + '" data-id="' + this.getId() + '" data-name="' + opts.name + '" name="' + this.getName() + '" ' + this.getClassAttribute() + this.getDisabled() + ' />';
			return html;
		}

		this.textarea = function() {
			var html = '<textarea type="textarea" ' + this.getRequired() + ' id="' + this.getId() + '" data-id="' + this.getId() + '" data-name="' + opts.name + '" name="' + this.getName() + '" ' + this.getClassAttribute() + this.getDisabled() + ' ></textarea>';
			return html;
		}

		this.list = function() {
			var html = '<select type="list" ' + this.getRequired() + ' id="' + this.getId() + '" data-id="' + this.getId() + '" data-name="' + opts.name + '" name="' + this.getName() + '" ' + this.getClassAttribute() + this.getDisabled() + '>';
			for (var opt in opts.optionList) {
				html += '<option value="' + opts.optionList[opt] + '">' + opt + '</option>';
			}
			html += '</select>';
			return html;
		}

		this.radio = function() {
			var html = '';
			for (var opt in opts.optionList) {
				html += '<div><label class="contactck-label-radio" style="display: inline-block;float: none;" for="' + this.getId() + opts.optionList[opt] + '">' + opt + this.getRequiredStar() + '</label>';
				html += '<input type="radio" data-name="' + opts.name + '" name="' + this.getName() + '[]" ' + this.getRequired() + ' id="' + this.getId() + opts.optionList[opt] + '" data-id="' + this.getId() + '" ' + this.getClassAttribute() + this.getDisabled() + ' value="' + opts.optionList[opt] + '"></div>';
			}
			if (opts.optionList.length == 0) {
				html += '<label class="contactck-label-radio" style="display: inline-block;float: none;" for="' + this.getId() + opts.optionList[opt] + '">' + opt + this.getRequiredStar() + '</label>';
				html += '<input type="radio" data-name="' + opts.name + '" name="' + this.getName() + '[]" ' + this.getRequired() + ' id="' + this.getId() + opts.optionList[opt] + '" data-id="' + this.getId() + '" ' + this.getClassAttribute() + this.getDisabled() + ' value="' + opts.optionList[opt] + '">';
			}
			return html;
		}

		this.checkbox = function() {
			var html = '';
			for (var opt in opts.optionList) {
				html += '<div><label class="contactck-label-checkbox" style="display: inline-block;float: none;" for="' + this.cleanName(this.getId() + opts.optionList[opt]) + '">' + opt + this.getRequiredStar() + '</label>';
				html += '<input type="checkbox" data-name="' + opts.name + '" data-id="' + this.getId() + '" name="' + this.getName() + '[]" ' + this.getRequired() + ' id="' + this.cleanName(this.getId() + opts.optionList[opt]) + '" ' + this.getClassAttribute() + this.getDisabled() + ' value="' + opts.optionList[opt] + '"></div>';
			}
			if (opts.optionList.length == 0) {
				html += '<label class="contactck-label-checkbox" style="display: inline-block;float: none;" for="' + this.cleanName(this.getId() + opts.optionList[opt]) + '">' + opt + this.getRequiredStar() + '</label>';
				html += '<input type="checkbox" data-name="' + opts.name + '" data-id="' + this.getId() + '" name="' + this.getName() + '[]" ' + this.getRequired() + ' id="' + this.cleanName(this.getId() + opts.optionList[opt]) + '" ' + this.getClassAttribute() + this.getDisabled() + ' value="' + opts.optionList[opt] + '">';
			}
			return html;
		}

		this.getClassAttribute = function() {
			return opts.cssClass ? ' class="' + opts.cssClass + '"' : '';
		}

		this.getId = function(suffix) {
			if (! suffix) suffix = '';
			var cleanedName = this.cleanName(opts.name);
			var id = opts.id ? opts.id + '_' + cleanedName + suffix : cleanedName + suffix;
			return id;
		}

		this.getName = function(suffix) {
			if (! suffix) suffix = '';
			return opts.id ? opts.id + '[' + opts.name + suffix + ']': opts.name + suffix;
		}

		this.cleanName = function(name) {
			return name.replace(/\W/g, '_').replace(/^_+|_+$/g, '');
		}

		this.getDisabled = function() {
			return (opts.disabled) ? ' disabled' : '';
		}

		this.getRequired = function() {
			return (opts.required == '1') ? ' data-required="1"' : '';
		}

		this.getRequiredStar = function() {
			return (opts.required == '1') ? '<span class="star"> *</span>' : '';
		}

		this.render = function() {
			var wrapperStart = opts.rowWrapperTag ? '<' + opts.rowWrapperTag + ' class="' + opts.rowWrapperClass + '">' : '';
			var wrapperEnd = opts.rowWrapperTag ? '</' + opts.rowWrapperTag + '">' : '';
			var labelStart = opts.labelWrapperTag ? '<' + opts.labelWrapperTag + ' class="' + opts.labelWrapperClass + '">' : '';
			var labelEnd = opts.labelWrapperTag ? '</' + opts.labelWrapperTag + '>' : '';
			if (type != 'radio' && type != 'checkbox') {
				var label = (opts.labelText) ? labelStart + '<label for="' + this.getId() + '">' + opts.labelText + this.getRequiredStar() + '</label>' + labelEnd : '';
			} else  {
				var label = (opts.labelText) ? labelStart + '<label>' + opts.labelText + '</label>' + labelEnd : '';
			}
			var fieldStart = opts.fieldWrapperTag ? '<' + opts.fieldWrapperTag + ' class="' + opts.fieldWrapperClass + '" data-placeholder="' + opts.labelText + '">' : '';
			var fieldEnd = opts.fieldWrapperTag ? '</' + opts.fieldWrapperTag + '">' : '';

			return wrapperStart + label + fieldStart + this[type]() + fieldEnd + wrapperEnd;
		}

		return this;
	}

	window.CKField = CKField;

	CKApi = window.CKApi || {};

	CKApi.CKFieldInterface = {
		opts: {},

		showManager: function(fieldid, opts, returnFunc) {
			// init options
			var defaults = {
				optionList				: {},
				name					: '',
				cssClass				: '',
				disabled				: '',
				labelText				: '',
				required				: '0'
				};

			this.opts = $.extend(defaults, opts);

			// get infos from selected field
			if (fieldid && $('#' + fieldid).length) {
				var field = $('#' + fieldid);
				this.opts.name = field.attr('data-name');
				this.opts.labelText = $(field.parents('.contactck-field')[0]).attr('data-placeholder');
				switch (field.attr('type')) {
					case 'radio' :
					case 'checkbox' :
						this.getOptionListFromRadio(field.attr('data-name'));
						break;
					case 'list' :
						this.getOptionListFromList(field.attr('data-name'));
						break;
					default:
					break;
				}
				this.opts.required = (field.attr('data-required') && field.attr('data-required') == '1') ? '1' : '0';
			}

			if (! returnFunc) returnFunc = 'ckGetFiedFromInterface';
			$('#ckfieldmanager').remove();
			$(document.body).append('<div id="ckfieldmanager"><h3>' + Joomla.JText._('CK_FIELD_MANAGER', 'Field manager') + '</h3>'
			+ '<p>'
			+ '<a class="ckbutton cktypeselection" data-type="text" onclick="CKApi.CKFieldInterface.create(\'text\')">' + CKApi.Text._('CK_FIELD_TEXT', 'Text') + '</a>'
			+ '<a class="ckbutton cktypeselection" data-type="textarea" onclick="CKApi.CKFieldInterface.create(\'textarea\')">' + CKApi.Text._('CK_FIELD_TEXTAREA', 'Textarea') + '</a>'
			+ '<a class="ckbutton cktypeselection" data-type="list" onclick="CKApi.CKFieldInterface.create(\'list\')">' + CKApi.Text._('CK_FIELD_LIST', 'List') + '</a>'
			+ '<a class="ckbutton cktypeselection" data-type="radio" onclick="CKApi.CKFieldInterface.create(\'radio\')">' + CKApi.Text._('CK_FIELD_RADIO', 'Radio') + '</a>'
			+ '<a class="ckbutton cktypeselection" data-type="checkbox" onclick="CKApi.CKFieldInterface.create(\'checkbox\')">' + CKApi.Text._('CK_FIELD_CHECKBOX', 'Checkbox') + '</a>'
			+ '</p>'
			+ '<div style="height:100px;"><h3>' + CKApi.Text._('CK_PREVIEW', 'Preview') + '</h3><div id="ckfieldpreview"></div></div>'
			+ '<h3>Options</h3><div id="ckfieldinterface"></div></div>');

			CKBox.open({handler: 'inline', content: 'ckfieldmanager', style: {padding: '10px'}, size: {x: '600px', y: '400px'}, footerHtml: '<a class="ckboxmodal-button" href="javascript:void(0)" onclick="CKApi.CKFieldInterface.returnField(\'' + returnFunc + '\')">'+Joomla.JText._('CK_SAVE_CLOSE')+'</a>'});

			// trigger action
			if (fieldid && $('#' + fieldid).length) {
				var field = $('#' + fieldid);
				$('#ckfieldmanager').find('.cktypeselection[data-type="' + field.attr('type') + '"]').trigger('click');
			}
		},

		getOptionListFromRadio: function(name) {
			var opts = this.opts;
			$('.editfocus [data-name="' + name + '"]').each(function() {
				var label = $('.editfocus [for="' + this.id + '"]');
				opts.optionList[label.text()] = this.value.trim();
			});
		},

		getOptionListFromList: function(name) {
			var opts = this.opts;
			$('.editfocus [data-name="' + name + '"] option').each(function() {
				opts.optionList[$(this).text()] = this.value.trim();
			});
		},

		create: function(type) {
			var fieldinterface = CKApi.CKFieldInterface.getInterface(type);

			var opts = this.opts;

			if (! $('#ckfieldinterface').find('#ckfieldname').length) {
				$('#ckfieldinterface').empty().append(fieldinterface);
			} else {
				var optionList = $('#ckfieldoptionlist').val().split(',');
				opts.optionList = {};
				for (var i=0; i<optionList.length; i++) {
					var line = optionList[i].split('::');
					var name = line[0];
					opts.optionList[name] = line[1];
				}
				opts.name = $('#ckfieldname').val();
				opts.required = ($('#ckfieldrequired').prop('checked') ? '1' : '0');
				opts.labelText = $('#ckfieldinterface').find('#ckfieldlabel').val();
			}
			if (type == 'list' || type == 'radio' || type == 'checkbox') {
				$('#ckfieldoptionlistwrapper').show();
			} else {
				$('#ckfieldoptionlistwrapper').hide();
			}
			
			// if (type == 'radio' || type == 'checkbox') {
				// $('#ckfieldlabelwrapper').hide();
			// } else {
				// $('#ckfieldlabelwrapper').show();
			// }
			
			var field = new CKField(type, opts).render();
			$('#ckfieldpreview').empty().append(field);
			
			$('#ckfieldmanager .cktypeselection').removeClass('active');
			$('#ckfieldmanager .cktypeselection[data-type="' + type + '"]').addClass('active');
			$('#ckfieldinterface .ckinputbox').change(function() {
				CKApi.CKFieldInterface.updateInterface();
			});
		},

		getInterface: function(type) {
			var optionList = new Array();
			for (var opt in this.opts.optionList) {
				optionList.push(opt + '::' + this.opts.optionList[opt]);
			}
			// optionList = optionList.join(",\n");
			optionList = optionList.join(",");
			var html = '<div style="float:left;width:50%;">';
			html += (this.opts.name != 'subject' && this.opts.name != 'email') ? '<div><label for="ckfieldname">Field Name</label><input type="text" name="ckfieldname" id="ckfieldname" class="ckinputbox" value="' + this.opts.name + '"/></div>' : '<input type="hidden" name="ckfieldname" id="ckfieldname" value="' + this.opts.name + '" />';
			html += '<div id="ckfieldlabelwrapper"><label for="ckfieldlabel">Label</label><input type="text" name="ckfieldlabel" id="ckfieldlabel" class="ckinputbox" value="' + this.opts.labelText + '"/></div>';
			html += '<div><label for="ckfieldrequired">Required <input type="checkbox" name="ckfieldrequired" id="ckfieldrequired" class="ckinputbox" style="vertical-align:top;" ' + (this.opts.required =='1' ? 'checked'  : '') + ' /></label></div>';
			html += '</div><div style="float:left;width:50%;">';
			html += '<div id="ckfieldoptionlistwrapper"><label for="ckfieldoptionlist">Options List</label><p><small>Write the list of values to put in the list field.</small></p><textarea type="textarea" name="ckfieldoptionlist" id="ckfieldoptionlist" placeholder="First value::value1,\nSecond value::value2" class="ckinputbox"  value="">' + optionList + '</textarea></div>';
			html += '</div>';
			html += '<div style="clear:both;"></div>';

			return html;
		},

		updateInterface: function() {
			var type = $('#ckfieldmanager .cktypeselection.active').attr('data-type');
			CKApi.CKFieldInterface.create(type);
		},

		returnField: function(returnFunc) {
			if (! $('#ckfieldname').val()) {
				alert('Please give a name');
				$('#ckfieldname').addClass('invalid').focus();
				return;
			}
			var fieldHtml = $('#ckfieldpreview').html();
			if (typeof(window[returnFunc]) == 'function') window[returnFunc](fieldHtml);
			CKBox.close();
		},
		
	};

	CKApi.Text = CKApi.Text || Joomla.JText;
})(jQuery);