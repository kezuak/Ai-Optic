/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */

var $ck = window.$ck || jQuery.noConflict();

$ck(document).ready(function(){
	if ($ck('#ckfolderupload').length) ckAddDndForImageUpload(document.getElementById('ckfolderupload'));
	// display the images in the root folder
//	if ($ck('.ckfoldertreename').length) ckBrowseShowFiles($ck('.ckfoldertreename').first()[0]);
});

if (typeof(ckInitTooltips) != 'function') {
	function ckInitTooltips() {
		CKApi.Tooltip('.cktip');
	}
}

if (typeof(ckAddWaitIcon) != 'function') {
	/**
	 * Add the spinner icon
	 */
	function ckAddWaitIcon(button) {
		$ck(button).addClass('ckwait');
	}
}
if (typeof(ckRemoveWaitIcon) != 'function') {
	/**
	 * Remove the spinner icon
	 */
	function ckRemoveWaitIcon(button) {
		$ck(button).removeClass('ckwait');
	}
}
/*------------------------------------------------------
 * Functions for the image drag and drop upload
 *-----------------------------------------------------*/

function ckReadDndImages(holder, files) {
	// empty the place if there is already an image -> no !!
	// if ($ck(holder).find('img').length) $ck(holder).find('img').remove();
	var formData = !!window.FormData ? new FormData() : null;
    for (var i = 0; i < files.length; i++) {
		if (!files[i].type.match(/^image\//) && !files[i].type.match(/^video\//) 
		&& !files[i].type.match(/^audio\//)
		&& !files[i].type.match(/\/pdf$/)
		) {
			alert('The file must be an image or a pdf : ' + files[i].name) ;
			continue ;
		}
		if (!!window.FormData) formData.append('file', files[i]);
		if ($ck('.ckfoldertree.ckcurrent').length) formData.append('path', $ck('.ckfoldertree.ckcurrent').attr('data-path'));

	if (!!window.FormData) {
		$ck(holder).append('<progress max="100" value="0" class="progress"></progress>');
		var holderProgress = $ck(holder).find('.progress');
		var myurl = PAGEBUILDERCK.URIPBCK + '&task=browse.ajaxAddPicture&' + PAGEBUILDERCK.TOKEN;
		$ck.ajax({
			type: "POST",
			url: myurl,
			// async: false,
			data: formData,
			dataType: 'json',
			processData: false,  // indique � jQuery de ne pas traiter les donn�es
			contentType: false,  // indique � jQuery de ne pas configurer le contentType
			xhr: function () {
				var xhr = new window.XMLHttpRequest();
				xhr.upload.addEventListener("progress", function (evt) {
					if (evt.lengthComputable) {
						var percentComplete = evt.loaded / evt.total;
						holderProgress.val(
							percentComplete * 100
						);
						if (percentComplete === 1) {
							holderProgress.addClass('hide');
						}
					}
				}, false);
				xhr.addEventListener("progress", function (evt) {
					if (evt.lengthComputable) {
						var percentComplete = evt.loaded / evt.total;
						holderProgress.val(
							percentComplete * 100
						);
					}
				}, false);
				return xhr;
			}
		}).done(function(response) {
			if(typeof response.error === 'undefined')
			{
				// Success
				if(typeof response.img !== 'undefined') {
					holderProgress.remove();
					if ($ck('.ckfoldertree').length) {
					// if the image already exists, return
						if ($ck('.ckfoldertreelistfiles-loaded.ckcurrent').find('[data-filename="' + response.filename + '"]').length) return;
						// $ck('.ckfoldertree.ckcurrent').find('> .ckfoldertreename > .ckfoldertreecount').text(parseInt($ck('.ckfoldertree.ckcurrent').find('> .ckfoldertreename > .ckfoldertreecount').text())+1);
						$ck('.ckfoldertreelistfiles-loaded.ckcurrent')
							.append('<div class="ckfoldertreefile" data-filename="' + response.filename + '" data-path="'+ $ck('.ckfoldertreelistfiles-loaded.ckcurrent').attr('data-path') +'" onclick="ckBrowseSelectFile(this)" data-type="image">'
						+ '<img title="' + response.filename + '" data-src="' + response.img + '" src="' + PAGEBUILDERCK.URIROOT + response.img+'" />'
						+ '</div>');
					$ck('#ckfileupload').val('');
					} else {
						holderProgress.remove();
						if ($ck(holder).find('img').length) {
							$ck(holder).find('img').attr('src', PAGEBUILDERCK.URIROOT + response.img).attr('data-src', response.img);
						} else {
							$ck(holder).find('.imageck').append('<img data-src="'+response.img+'" src="'+PAGEBUILDERCK.URIROOT + response.img+'" />');
						}
					}
				}
			} else {
				alert('ERROR: ' + response.error);
			}
		}).fail(function() {
			// alert(Joomla.JText._('CK_FAILED', 'Failed'));
		});
    }
	}
}

// B/C only
function addDndForImageUpload(holder) {
	ckAddDndForImageUpload(holder);
}

function ckAddDndForImageUpload(holder) {
	if (typeof FileReader == 'undefined') return;
		if ('draggable' in document.createElement('span')) {
			holder.ondragover = function () { $ck(holder).addClass('ckdndhover'); return false; };
			holder.ondragleave = function () { $ck(holder).removeClass('ckdndhover'); return false; };
			holder.ondragend = function () { $ck(holder).removeClass('ckdndhover'); return false; };
			holder.ondrop = function (e) {
				$ck(holder).removeClass('ckdndhover');
				e.preventDefault();
				ckReadDndImages(holder, e.dataTransfer.files);
			}
		} else {
			alert('Message : Drag and drop for images not supported');
			// fileupload.className = 'hidden';
		}
		$ck('#ckfileupload').on('change', function () {
			ckReadDndImages(holder, this.files);
		});
}

/*------------------------------------------------------
 * END of image drag and drop 
 *-----------------------------------------------------*/


function ckAddFolder() {
	$ck('.ckcurrent .ckfoldertreepathwayaddfolder').hide();
	$ck('.ckcurrent .ckfoldertreepathwayfoldername, .ckcurrent .ckfoldertreepathwaycreatefolder').addClass('ckshow');
}

function ckCreateFolder(btn, path) {
	var folderName = $ck(btn).parent().find('input').val();
	var myurl = PAGEBUILDERCK.URIPBCK + "&task=browse.ajaxCreateFolder&" + PAGEBUILDERCK.TOKEN;
	$ck.ajax({
		type: "POST",
		url: myurl,
		data: {
			path: path,
			name: folderName
		}
	}).done(function(code) {
		var result = JSON.parse(code);
		if (result.status == '1') {
			alert(result.message);
			var currentpath = $ck('.ckcurrent');
			var code = '';
			if (! currentpath.find('.cksubfolder').length) code += '<div class="cksubfolder">';
			
			code += '<div class="ckfoldertree" data-level="' + (parseInt(currentpath.attr('data-level'))+1) + '" data-path="' + path + '/' + folderName + '">'
			+'<div class="ckfoldertreetoggler" onclick="ckToggleTreeSub(this)"></div>'
			+'<div class="ckfoldertreename" onclick="ckLoadFiles(this, \'\', \'' + path + '/' + folderName + '\')"><span class="icon-folder"></span>' + folderName
			+'</div>'
			+'<div class="ckfoldertreefiles">'
				+ ckBrowseGetPathway(path, folderName)
								+'</div>'
				+'</div>';

			// do sorting to find the correct folder position
			var foldersList = $ck('.ckfoldertree.ckcurrent').find('> .cksubfolder > .ckfoldertree')
				.map(function() {
					var text = $ck( this ).find('> .ckfoldertreename').clone();
					text.find('.ckfoldertreecount').remove();

					return text.text().trim();
			});
			foldersList.push(folderName);
			foldersList.sort();

			var foldersListArray = Object.values(foldersList);
			var position = foldersListArray.indexOf(folderName);

			// add the html to create the folder
			if (! currentpath.find('.cksubfolder').length) code += '</div>';
//			if (! currentpath.find('.cksubfolder').length) {
				$ck('.ckcurrent').append(code);
//			} else {
//				if (position == 0) {
//					$ck('.ckcurrent > .cksubfolder').prepend(code);
//				} else {
//					$ck('.ckcurrent > .cksubfolder > .ckfoldertree').eq(position-1).before(code);
//				}
//			}
		} else if (result.status == '2') {
			alert(result.message);
		} else {
			alert(CKApi.Text._('CK_FAILED', 'Failed'));
		}
	}).fail(function() {
		alert(CKApi.Text._('CK_FAILED', 'Failed'));
	});
}

function ckLoadFiles(btn, type, path, folderName) {
	if ($ck('.ckfoldertreelistfiles-loaded[data-path="' + path + '"]').length) {
		ckFocusFolder(path);
		return;
	}
	var myurl = PAGEBUILDERCK.URIPBCK + "&task=browse.getFiles&" + PAGEBUILDERCK.TOKEN;
	$ck.ajax({
	type: "POST",
	url: myurl,
	data: {
		folder: path,
		type: type
		}
	}).done(function(result) {
		if (result) {
			let html = '<div class="ckfoldertreelistfiles-loaded" data-path="' + path + '">' + ckBrowseGetPathway(path, folderName) + result + '</div>';
			$ck('#ckfoldertreelistfiles').append(html);
			$ck(btn).addClass('ckdone');
		}
		ckFocusFolder(path);
	}).fail(function() {
		alert('A problem occured when trying to create the module. Please retry.');
	});
}

function ckFocusFolder(path) {
	var item = $ck('.ckfoldertree[data-path="' + path + '"]');
	$ck('.ckfoldertreelistfiles-loaded').removeClass('ckcurrent').hide();
	// set the current state on the folder

	$ck('.ckfoldertree.ckcurrent').removeClass('ckcurrent');
	if (item.hasClass('ckcurrent')) {
		item.removeClass('ckcurrent');
	} else {
		item.addClass('ckcurrent')
	}
	$ck('.ckfoldertreelistfiles-loaded[data-path="' + path + '"]').addClass('ckcurrent').show();
	sessionStorage.setItem('pagebuilderck_browse_folder.' + PAGEBUILDERCK.USERID, path);
}

function ckBrowseGetPathway(path, folderName) {
	let breadcrumb = path.replace(/\//g, '</span><span class="ckfoldertreepath">');
	let html = '<div class="ckfoldertreepathway ckinterface">'
		+'<span class="ckfoldertreepath">'+breadcrumb+'</span>'
		+'<span class="ckfoldertreepathwayactions">'
			+'<span class="ckfoldertreepathwayaddfolder ckbutton" onclick="ckAddFolder()" style="display: none;">Add a subfolder</span>'
			+'<span class="ckfoldertreepathwayfoldername ckshow"><input type="text" class="ckfoldertreepathwayaddfoldername"></span>'
			+'<span class="ckfoldertreepathwaycreatefolder ckbutton ckshow" onclick="ckCreateFolder(this, \'' + path + '\')">Create folder</span>'
		+'</span>'
	+'</div>';

	return html;
}