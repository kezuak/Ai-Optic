/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */


//var $ck = jQuery.noConflict();

$ck(document).ready(function(){
	ckInitTemplateFrontEdition();
	// var workspaceparent = $ck('#workspaceparentck');
	// $ck(workspaceparent.parents('.controls')[0]).css('margin-left', '0');
});

// function ckModuleEditFullScreen() {
	// $ck('.pagebuilderckfrontend').toggleClass('ckfrontendfullwidth');
// }

function ckInitTemplateFrontEdition() {
	$ck('div.tck-edition').each(function() {
		var nbpbckmodules = $ck(this).find('.tck-module-container[data-type="mod_pagebuilderck"]').length;
		if (nbpbckmodules === 0) {
			$ck(this).addClass('tck-edition-empty');
			ckAddNewModuleButton($ck(this));
			ckMakeTooltip($ck(this));
		}
	});
	$ck('.tck-module[data-type="mod_pagebuilderck"]').each(function() {
		$module = $ck(this);
		if (! $module.find('.tck-module-toolbar').length) {
			$module.addClass('tck-module-pbck');
			$module.prepend('<div class="tck-module-toolbar tck-module-toolbar-pbck"><span class="tck-module-toolbar-id">' + $module.attr('data-id') + '</span><span class="tck-module-toolbar-type">[mod_pagebuilderck]</span></div>');
		}
	});
	
}

function ckAddNewModuleButton(bloc) {
	bloc.append('<div class="tck-more cktip" onclick="ckAddNewPagebuilderModule(this)" title="' + CKApi.Text._('CK_ADD_NEW_PAGEBUILDER_MODULE', '') + ' [' + bloc.attr('data-position') + ']">+</div>');
}

function ckAddNewPagebuilderModule(btn) {
	var container = $ck($ck(btn).parents('div.tck-edition')[0]);
	var position = container.attr('data-position');
	container.append('<div class="tck-module moduletable"><div class="tck-module-text"><div class="workspaceck pagebuilderck pbck-module-edition pbck-module-edition-new"></div></div></div>');
	var module = container.find('.pbck-module-edition-new');
	module.removeClass('pbck-module-edition-new');
	ckAddRow(false, module);
	ckInitWorkspace(module);
	var pagedition = module.clone();
	ckCleanInterfaceBeforeSave(pagedition);

	var myurl = 'index.php?option=com_pagebuilderck&task=frontedition.createmodule&' + PAGEBUILDERCK.TOKEN;
	$ck.ajax({
	type: "POST",
	url: myurl,
	dataType: 'json',
	data: {
		position: position,
		pagedition: pagedition[0].innerHTML
		}
	}).done(function(result) {
		if (result.id) {
//			container.append('<div class="workspaceck pagebuilderck pbck-module-edition" data-id="' + result.id + '"></div>');
//			var module = container.find('[data-id="' + result.id + '"]');
//			ckAddRow(false, module);
//			ckInitWorkspace(module);
			btn.remove();
			container.attr('data-id', result.id);
			module.attr('data-id', result.id);
			container.removeClass('tck-edition-empty');
		} else {
			alert('A problem occured when trying to create the module. Please retry.');
		}
	}).fail(function() {
		alert('A problem occured when trying to create the module. Please retry.');
	});
}

function ckPagebuilderFrontEditionSave() {
	ckAddSpinnerIcon($ck('.ckheadermenuitem.cksave'));
	try {
		var modules = new Object();
		$ck('.workspaceck').each(function(i) {
			var moduleId = $ck(this).attr('data-id');
			if (moduleId) {
				var pagedition = $ck(this).clone();
				ckCleanInterfaceBeforeSave(pagedition);
				modules[i] = {'id' : moduleId, 'code' : pagedition[0].innerHTML};
			}
		});
	} catch(error) {
		alert('A problem occured duging the save method. Page not saved.')
		console.error(error);
	}
	var myurl = 'index.php?option=com_pagebuilderck&task=frontedition.savemodules&' + PAGEBUILDERCK.TOKEN;
	$ck.ajax({
	type: "POST",
	url: myurl,
	data: {
		modules: modules
		}
	}).done(function(result) {
		ckRemoveSpinnerIcon($ck('.ckheadermenuitem.cksave'));
	}).fail(function() {
//		alert('A problem occured when trying to save the module ID ' + moduleid + '. Please retry.');
		// $ck(currentbloc).remove();
	});
}