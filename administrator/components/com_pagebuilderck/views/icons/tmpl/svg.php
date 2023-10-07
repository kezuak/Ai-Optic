<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */

defined('_JEXEC') or die;

?>
<div id="svgicon-search">
	<label for="svgicon-filter-by"></label>
	<div style="position: relative; height: 46px; display: block;">
		<input type="text" tabindex="1" class="" id="svgicon-filter-by" placeholder="<?php echo JText::_('CK_SEARCH') ?>" style="height:auto;margin:0;background-color: transparent; position: relative;">
		<span class="ckbutton-group">
			<button class="ckbutton fa fa-search" id="svgicon-filter-submit" onclick="ckSearchSvgIcon()"></button>
			<button class="ckbutton fa fa-times" id="svgicon-filter-clear" onclick="ckClearSearchSvgIcon()"></button>
		</span>
	</div>
</div>

<button class="ckbutton ckbuttonlink" data-target="ckheroiconsoutline" data-set="heroicons/outline"><?php echo JText::_('Heroicons - Outline'); ?></button>
<button class="ckbutton ckbuttonlink" data-target="ckherosolidiconslist" data-set="heroicons/solid"><?php echo JText::_('Heroicons - Solid'); ?></button>
<button class="ckbutton ckbuttonlink" data-target="cktablericonslist" data-set="tabler"><?php echo JText::_('Tabler icons'); ?></button>
<button class="ckbutton ckbuttonlink" data-target="ckioniconsnormaliconslist" data-set="ionicons/normal"><?php echo JText::_('Ionicons icons - Normal'); ?></button>
<button class="ckbutton ckbuttonlink" data-target="ckioniconsoutlineiconslist" data-set="ionicons/outline"><?php echo JText::_('Ionicons icons - Outline'); ?></button>
<button class="ckbutton ckbuttonlink" data-target="ckioniconssharpiconslist" data-set="ionicons/sharp"><?php echo JText::_('Ionicons icons - Sharp'); ?></button>
<button class="ckbutton ckbuttonlink" data-target="ckfontawesomebrandsiconslist" data-set="fontawesome/brands"><?php echo JText::_('Fontawesome icons - Brands'); ?></button>
<button class="ckbutton ckbuttonlink" data-target="ckfontawesomeregulariconslist" data-set="fontawesome/regular"><?php echo JText::_('Fontawesome icons - Regular'); ?></button>
<button class="ckbutton ckbuttonlink" data-target="ckfontawesomesolidiconslist" data-set="fontawesome/solid"><?php echo JText::_('Fontawesome icons - Solid'); ?></button>

<div class="ckuttontab" data-tab="ckheroiconsoutline">
	<a href="https://heroicons.com" target="_blank">Heroicons website</a>
</div>
<div class="ckuttontab" data-tab="ckherosolidiconslist">
	<a href="https://heroicons.com" target="_blank">Heroicons website</a>
</div>
<div class="ckuttontab" data-tab="cktablericonslist">
	<a href="https://tablericons.com" target="_blank">Tabler icons website</a>
</div>
<div class="ckuttontab" data-tab="ckioniconsnormaliconslist">
	<a href="https://ionicons.com/" target="_blank">Ionicons website</a>
</div>
<div class="ckuttontab" data-tab="ckioniconsoutlineiconslist">
	<a href="https://ionicons.com/" target="_blank">Ionicons website</a>
</div>
<div class="ckuttontab" data-tab="ckioniconssharpiconslist">
	<a href="https://ionicons.com/" target="_blank">Ionicons website</a>
</div>
<div class="ckuttontab" data-tab="ckfontawesomebrandsiconslist">
	<a href="https://fontawesome.com/" target="_blank">Fontawesome website</a>
</div>
<div class="ckuttontab" data-tab="ckfontawesomeregulariconslist">
	<a href="https://fontawesome.com/" target="_blank">Fontawesome website</a>
</div>
<div class="ckuttontab" data-tab="ckfontawesomesolidiconslist">
	<a href="https://fontawesome.com/" target="_blank">Fontawesome website</a>
</div>
<script>
$ck('.ckbuttonlink').on('click', function() {
	var target = $ck(this).attr('data-target');
	$ck('.ckuttontab').hide();
	$ck('.ckbuttonlink').removeClass('active');
	var $tab = $ck('.ckuttontab[data-tab="' + target + '"]');
	$tab.show();
	$ck(this).addClass('active');
	if (! $ck('#' + target).length) {
		$tab.append('<div id="' + target + '" class="ckiconslist"></div>')
		ckgetIconsLibrary(target, $ck(this).attr('data-set'));
	}
});

function ckgetIconsLibrary(targetId, set) {
	var setVar = set.replace('/', '_');
	$ck('#' + targetId).addClass('ckwait');
	var debug = false;
	sessionStorage.setItem('icons_library_' + setVar,'');
	if (!debug && window.sessionStorage && sessionStorage.getItem('icons_library_' + setVar)) {
		var html = sessionStorage.getItem('icons_library_' + setVar);
		$ck('#' + targetId).empty().append(html);
		$ck('#' + targetId).removeClass('ckwait');
		ckAddEventOnIcons();
	} else {
		var url = 'https://media.joomlack.fr/api/icons/set/' + set;
		jQuery.ajax({
			url: url,
			dataType: 'jsonp',
			cache: true,
			jsonpCallback: "joomlack_jsonpcallback",
			timeout: 20000,
			success: function (response) {
				$ck('#' + targetId).removeClass('ckwait');
				var html = '';

				for (var i=0;i<response.length;i++) {
					var name = response[i];
					html += '<img class="ckiconfile" loading="lazy" title="' + name + '" data-set="' + set  + '" data-name="' + name + '" src="https://media.joomlack.fr/icons/' + set + '/' + name + '" />';
				}
				html = '<p class="ckiconslistnumber">' + i + ' icons found</p>' + html;
				$ck('#' + targetId).empty().append(html);
				ckAddEventOnIcons();
				sessionStorage.setItem('icons_library_' + setVar, html);
			},
			fail: function() {
				alert('Error : Unable to connect to the library. Please contact an administrator');
			},
			complete: function() {

			},
			error: function(request, status, error) {

			}
		});
	}
}

$ck('#svgicon-filter-by').keydown(function (e) {
	if (e.which == 13) {
		$ck("#svgicon-filter-submit").click();
	}
});

function ckSearchSvgIcon() {
	$ck('.ckiconfile').hide();
	$ck('.ckiconfile[data-name*=' + $ck('#svgicon-filter-by').val() + ']').show();
}

function ckClearSearchSvgIcon() {
	jQuery('.ckiconfile').show();
	jQuery('#svgicon-filter-by').val('');
}

function ckAddEventOnIcons() {
	$ck('.ckiconfile').on('click', function() {
		ckGetIconSvgCode($ck(this).attr('data-set') + '/' + $ck(this).attr('data-name'));
	});
}

function ckGetIconSvgCode(path) {
	var regEx = new RegExp('/', "ig");
	var setVar = path.replace(regEx, '_');
	var debug = true;
	if (debug === true) sessionStorage.setItem('icons_library_' + setVar,'');
	if (!debug && window.sessionStorage && sessionStorage.getItem('icons_library_' + setVar)) {
		var code = sessionStorage.getItem('icons_library_' + setVar);
		window.parent.ckSelectSvgIcon(response, setVar);
	} else {
		var url = 'https://media.joomlack.fr/api/icons/get/' + path;
		jQuery.ajax({
			url: url,
			dataType: 'jsonp',
			cache: true,
			jsonpCallback: "joomlack_jsonpcallback",
			timeout: 20000,
			success: function (response) {
				window.parent.ckSelectSvgIcon(response, setVar);

				sessionStorage.setItem('icons_library_' + setVar, response);
			},
			fail: function() {
				alert('Error : Unable to connect to the library. Please contact an administrator');
			},
			complete: function() {

			},
			error: function(request, status, error) {

			}
		});
	}
}
</script>
<style>
.ckiconslistnumber {
	flex: 1 1 100%;
}

.ckuttontab {
	display: none;
}

.ckiconslist {
	display: flex;
	flex-wrap: wrap;
}
.ckiconslist img {
	width: 30px;
	height: 30px;
	margin: 10px;
	padding: 10px;
	cursor: pointer;
}
.ckiconslist img:hover {
	transform: scale(1.5);
	background: #eee;
}
</style>