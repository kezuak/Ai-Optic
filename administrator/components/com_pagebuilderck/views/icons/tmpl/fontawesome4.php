<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */

defined('_JEXEC') or die;

use Pagebuilderck\CKFof;
?>
<div class="container-fluid ckinterface">
	<div id="search" class="filter-parent">
		<label for="filter-by"></label>
		<div style="position: relative; height: 46px; display: block;" class="">
			<input type="text" tabindex="1" class="" id="filter-by" placeholder="Search icons" style="height:auto;margin:0;background-color: transparent; position: relative;">
			<span class="ckbutton-group">
				<button class="ckbutton fa fa-search" id="filter-submit" onclick="searchiconsck()"></button>
				<button class="ckbutton fa fa-times" id="filter-clear" onclick="clearsearchiconsck()"></button>
			</span>
		</div>
		<div>
			<h3><?php echo JText::_('CK_FILTER_BY_GROUP') ?></h3>
			<button class="ckbutton filter-category" id="filter-submit" onclick="filtericonsbycatck('web-application')">Web Application Icons</button>
			<button class="ckbutton filter-category" id="filter-submit" onclick="filtericonsbycatck('transportation')">Transportation Icons</button>
			<button class="ckbutton filter-category" id="filter-submit" onclick="filtericonsbycatck('gender')">Gender Icons</button>
			<button class="ckbutton filter-category" id="filter-submit" onclick="filtericonsbycatck('file-type')">File Type Icons</button>
			<button class="ckbutton filter-category" id="filter-submit" onclick="filtericonsbycatck('spinner')">Spinner Icons</button>
			<button class="ckbutton filter-category" id="filter-submit" onclick="filtericonsbycatck('form-control')">Form Control Icons</button>
			<button class="ckbutton filter-category" id="filter-submit" onclick="filtericonsbycatck('payment')">Payment Icons</button>
			<button class="ckbutton filter-category" id="filter-submit" onclick="filtericonsbycatck('chart')">Chart Icons</button>
			<button class="ckbutton filter-category" id="filter-submit" onclick="filtericonsbycatck('currency')">Currency Icons</button>
			<button class="ckbutton filter-category" id="filter-submit" onclick="filtericonsbycatck('text-editor')">Text Editor Icons</button>
			<button class="ckbutton filter-category" id="filter-submit" onclick="filtericonsbycatck('directional')">Directional Icons</button>
			<button class="ckbutton filter-category" id="filter-submit" onclick="filtericonsbycatck('video-player')">Video Player Icons</button>
			<button class="ckbutton filter-category" id="filter-submit" onclick="filtericonsbycatck('brand')">Brand Icons</button>
			<button class="ckbutton filter-category" id="filter-submit" onclick="filtericonsbycatck('medical')">Medical Icons</button>
		</div>
	</div>
<script>
function searchiconsck() {
	jQuery('section').show();
	jQuery('i.fa').parent().parent().hide();
	jQuery('i[class*=' + jQuery('#filter-by').val() + ']').parent().parent().show();
}

function clearsearchiconsck() {
	jQuery('section').show();
	jQuery('i.fa').parent().parent().show();
	jQuery('#filter-by').val('');
}

function filtericonsbycatck (cat) {
	jQuery('i.fa').parent().parent().show();
	jQuery('section').hide();
	jQuery('section#' + cat).show();
	jQuery('#filter-by').val('');
}

jQuery(document).ready(function() {
	jQuery('.fa-hover a').click(function(e) {
		e.preventDefault();
		window.parent.ckSelectFaIcon(jQuery(this).find('i').attr('class'));
		window.parent.CKBox.close();
	});
});
</script>

  <section id="web-application">
  <h2 class="page-header">Web Application Icons</h2>

  <div class="row fontawesome-icon-list">
    

    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/adjust"><i class="fa fa-adjust"></i> adjust</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/anchor"><i class="fa fa-anchor"></i> anchor</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/archive"><i class="fa fa-archive"></i> archive</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/area-chart"><i class="fa fa-area-chart"></i> area-chart</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/arrows"><i class="fa fa-arrows"></i> arrows</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/arrows-h"><i class="fa fa-arrows-h"></i> arrows-h</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/arrows-v"><i class="fa fa-arrows-v"></i> arrows-v</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/asterisk"><i class="fa fa-asterisk"></i> asterisk</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/at"><i class="fa fa-at"></i> at</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/car"><i class="fa fa-automobile"></i> automobile <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/ban"><i class="fa fa-ban"></i> ban</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/university"><i class="fa fa-bank"></i> bank <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/bar-chart"><i class="fa fa-bar-chart"></i> bar-chart</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/bar-chart"><i class="fa fa-bar-chart-o"></i> bar-chart-o <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/barcode"><i class="fa fa-barcode"></i> barcode</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/bars"><i class="fa fa-bars"></i> bars</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/bed"><i class="fa fa-bed"></i> bed</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/beer"><i class="fa fa-beer"></i> beer</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/bell"><i class="fa fa-bell"></i> bell</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/bell-o"><i class="fa fa-bell-o"></i> bell-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/bell-slash"><i class="fa fa-bell-slash"></i> bell-slash</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/bell-slash-o"><i class="fa fa-bell-slash-o"></i> bell-slash-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/bicycle"><i class="fa fa-bicycle"></i> bicycle</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/binoculars"><i class="fa fa-binoculars"></i> binoculars</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/birthday-cake"><i class="fa fa-birthday-cake"></i> birthday-cake</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/bolt"><i class="fa fa-bolt"></i> bolt</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/bomb"><i class="fa fa-bomb"></i> bomb</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/book"><i class="fa fa-book"></i> book</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/bookmark"><i class="fa fa-bookmark"></i> bookmark</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/bookmark-o"><i class="fa fa-bookmark-o"></i> bookmark-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/briefcase"><i class="fa fa-briefcase"></i> briefcase</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/bug"><i class="fa fa-bug"></i> bug</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/building"><i class="fa fa-building"></i> building</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/building-o"><i class="fa fa-building-o"></i> building-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/bullhorn"><i class="fa fa-bullhorn"></i> bullhorn</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/bullseye"><i class="fa fa-bullseye"></i> bullseye</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/bus"><i class="fa fa-bus"></i> bus</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/taxi"><i class="fa fa-cab"></i> cab <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/calculator"><i class="fa fa-calculator"></i> calculator</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/calendar"><i class="fa fa-calendar"></i> calendar</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/calendar-o"><i class="fa fa-calendar-o"></i> calendar-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/camera"><i class="fa fa-camera"></i> camera</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/camera-retro"><i class="fa fa-camera-retro"></i> camera-retro</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/car"><i class="fa fa-car"></i> car</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/caret-square-o-down"><i class="fa fa-caret-square-o-down"></i> caret-square-o-down</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/caret-square-o-left"><i class="fa fa-caret-square-o-left"></i> caret-square-o-left</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/caret-square-o-right"><i class="fa fa-caret-square-o-right"></i> caret-square-o-right</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/caret-square-o-up"><i class="fa fa-caret-square-o-up"></i> caret-square-o-up</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/cart-arrow-down"><i class="fa fa-cart-arrow-down"></i> cart-arrow-down</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/cart-plus"><i class="fa fa-cart-plus"></i> cart-plus</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/cc"><i class="fa fa-cc"></i> cc</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/certificate"><i class="fa fa-certificate"></i> certificate</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/check"><i class="fa fa-check"></i> check</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/check-circle"><i class="fa fa-check-circle"></i> check-circle</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/check-circle-o"><i class="fa fa-check-circle-o"></i> check-circle-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/check-square"><i class="fa fa-check-square"></i> check-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/check-square-o"><i class="fa fa-check-square-o"></i> check-square-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/child"><i class="fa fa-child"></i> child</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/circle"><i class="fa fa-circle"></i> circle</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/circle-o"><i class="fa fa-circle-o"></i> circle-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/circle-o-notch"><i class="fa fa-circle-o-notch"></i> circle-o-notch</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/circle-thin"><i class="fa fa-circle-thin"></i> circle-thin</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/clock-o"><i class="fa fa-clock-o"></i> clock-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/times"><i class="fa fa-close"></i> close <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/cloud"><i class="fa fa-cloud"></i> cloud</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/cloud-download"><i class="fa fa-cloud-download"></i> cloud-download</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/cloud-upload"><i class="fa fa-cloud-upload"></i> cloud-upload</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/code"><i class="fa fa-code"></i> code</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/code-fork"><i class="fa fa-code-fork"></i> code-fork</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/coffee"><i class="fa fa-coffee"></i> coffee</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/cog"><i class="fa fa-cog"></i> cog</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/cogs"><i class="fa fa-cogs"></i> cogs</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/comment"><i class="fa fa-comment"></i> comment</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/comment-o"><i class="fa fa-comment-o"></i> comment-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/comments"><i class="fa fa-comments"></i> comments</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/comments-o"><i class="fa fa-comments-o"></i> comments-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/compass"><i class="fa fa-compass"></i> compass</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/copyright"><i class="fa fa-copyright"></i> copyright</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/credit-card"><i class="fa fa-credit-card"></i> credit-card</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/crop"><i class="fa fa-crop"></i> crop</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/crosshairs"><i class="fa fa-crosshairs"></i> crosshairs</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/cube"><i class="fa fa-cube"></i> cube</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/cubes"><i class="fa fa-cubes"></i> cubes</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/cutlery"><i class="fa fa-cutlery"></i> cutlery</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/tachometer"><i class="fa fa-dashboard"></i> dashboard <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/database"><i class="fa fa-database"></i> database</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/desktop"><i class="fa fa-desktop"></i> desktop</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/diamond"><i class="fa fa-diamond"></i> diamond</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/dot-circle-o"><i class="fa fa-dot-circle-o"></i> dot-circle-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/download"><i class="fa fa-download"></i> download</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/pencil-square-o"><i class="fa fa-edit"></i> edit <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/ellipsis-h"><i class="fa fa-ellipsis-h"></i> ellipsis-h</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/ellipsis-v"><i class="fa fa-ellipsis-v"></i> ellipsis-v</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/envelope"><i class="fa fa-envelope"></i> envelope</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/envelope-o"><i class="fa fa-envelope-o"></i> envelope-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/envelope-square"><i class="fa fa-envelope-square"></i> envelope-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/eraser"><i class="fa fa-eraser"></i> eraser</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/exchange"><i class="fa fa-exchange"></i> exchange</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/exclamation"><i class="fa fa-exclamation"></i> exclamation</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/exclamation-circle"><i class="fa fa-exclamation-circle"></i> exclamation-circle</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/exclamation-triangle"><i class="fa fa-exclamation-triangle"></i> exclamation-triangle</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/external-link"><i class="fa fa-external-link"></i> external-link</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/external-link-square"><i class="fa fa-external-link-square"></i> external-link-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/eye"><i class="fa fa-eye"></i> eye</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/eye-slash"><i class="fa fa-eye-slash"></i> eye-slash</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/eyedropper"><i class="fa fa-eyedropper"></i> eyedropper</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/fax"><i class="fa fa-fax"></i> fax</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/female"><i class="fa fa-female"></i> female</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/fighter-jet"><i class="fa fa-fighter-jet"></i> fighter-jet</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/file-archive-o"><i class="fa fa-file-archive-o"></i> file-archive-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/file-audio-o"><i class="fa fa-file-audio-o"></i> file-audio-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/file-code-o"><i class="fa fa-file-code-o"></i> file-code-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/file-excel-o"><i class="fa fa-file-excel-o"></i> file-excel-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/file-image-o"><i class="fa fa-file-image-o"></i> file-image-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/file-video-o"><i class="fa fa-file-movie-o"></i> file-movie-o <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/file-pdf-o"><i class="fa fa-file-pdf-o"></i> file-pdf-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/file-image-o"><i class="fa fa-file-photo-o"></i> file-photo-o <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/file-image-o"><i class="fa fa-file-picture-o"></i> file-picture-o <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/file-powerpoint-o"><i class="fa fa-file-powerpoint-o"></i> file-powerpoint-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/file-audio-o"><i class="fa fa-file-sound-o"></i> file-sound-o <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/file-video-o"><i class="fa fa-file-video-o"></i> file-video-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/file-word-o"><i class="fa fa-file-word-o"></i> file-word-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/file-archive-o"><i class="fa fa-file-zip-o"></i> file-zip-o <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/film"><i class="fa fa-film"></i> film</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/filter"><i class="fa fa-filter"></i> filter</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/fire"><i class="fa fa-fire"></i> fire</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/fire-extinguisher"><i class="fa fa-fire-extinguisher"></i> fire-extinguisher</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/flag"><i class="fa fa-flag"></i> flag</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/flag-checkered"><i class="fa fa-flag-checkered"></i> flag-checkered</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/flag-o"><i class="fa fa-flag-o"></i> flag-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/bolt"><i class="fa fa-flash"></i> flash <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/flask"><i class="fa fa-flask"></i> flask</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/folder"><i class="fa fa-folder"></i> folder</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/folder-o"><i class="fa fa-folder-o"></i> folder-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/folder-open"><i class="fa fa-folder-open"></i> folder-open</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/folder-open-o"><i class="fa fa-folder-open-o"></i> folder-open-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/frown-o"><i class="fa fa-frown-o"></i> frown-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/futbol-o"><i class="fa fa-futbol-o"></i> futbol-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/gamepad"><i class="fa fa-gamepad"></i> gamepad</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/gavel"><i class="fa fa-gavel"></i> gavel</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/cog"><i class="fa fa-gear"></i> gear <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/cogs"><i class="fa fa-gears"></i> gears <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/circle-thin"><i class="fa fa-genderless"></i> genderless <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/gift"><i class="fa fa-gift"></i> gift</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/glass"><i class="fa fa-glass"></i> glass</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/globe"><i class="fa fa-globe"></i> globe</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/graduation-cap"><i class="fa fa-graduation-cap"></i> graduation-cap</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/users"><i class="fa fa-group"></i> group <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/hdd-o"><i class="fa fa-hdd-o"></i> hdd-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/headphones"><i class="fa fa-headphones"></i> headphones</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/heart"><i class="fa fa-heart"></i> heart</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/heart-o"><i class="fa fa-heart-o"></i> heart-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/heartbeat"><i class="fa fa-heartbeat"></i> heartbeat</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/history"><i class="fa fa-history"></i> history</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/home"><i class="fa fa-home"></i> home</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/bed"><i class="fa fa-hotel"></i> hotel <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/picture-o"><i class="fa fa-image"></i> image <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/inbox"><i class="fa fa-inbox"></i> inbox</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/info"><i class="fa fa-info"></i> info</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/info-circle"><i class="fa fa-info-circle"></i> info-circle</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/university"><i class="fa fa-institution"></i> institution <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/key"><i class="fa fa-key"></i> key</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/keyboard-o"><i class="fa fa-keyboard-o"></i> keyboard-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/language"><i class="fa fa-language"></i> language</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/laptop"><i class="fa fa-laptop"></i> laptop</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/leaf"><i class="fa fa-leaf"></i> leaf</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/gavel"><i class="fa fa-legal"></i> legal <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/lemon-o"><i class="fa fa-lemon-o"></i> lemon-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/level-down"><i class="fa fa-level-down"></i> level-down</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/level-up"><i class="fa fa-level-up"></i> level-up</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/life-ring"><i class="fa fa-life-bouy"></i> life-bouy <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/life-ring"><i class="fa fa-life-buoy"></i> life-buoy <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/life-ring"><i class="fa fa-life-ring"></i> life-ring</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/life-ring"><i class="fa fa-life-saver"></i> life-saver <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/lightbulb-o"><i class="fa fa-lightbulb-o"></i> lightbulb-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/line-chart"><i class="fa fa-line-chart"></i> line-chart</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/location-arrow"><i class="fa fa-location-arrow"></i> location-arrow</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/lock"><i class="fa fa-lock"></i> lock</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/magic"><i class="fa fa-magic"></i> magic</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/magnet"><i class="fa fa-magnet"></i> magnet</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/share"><i class="fa fa-mail-forward"></i> mail-forward <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/reply"><i class="fa fa-mail-reply"></i> mail-reply <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/reply-all"><i class="fa fa-mail-reply-all"></i> mail-reply-all <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/male"><i class="fa fa-male"></i> male</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/map-marker"><i class="fa fa-map-marker"></i> map-marker</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/meh-o"><i class="fa fa-meh-o"></i> meh-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/microphone"><i class="fa fa-microphone"></i> microphone</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/microphone-slash"><i class="fa fa-microphone-slash"></i> microphone-slash</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/minus"><i class="fa fa-minus"></i> minus</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/minus-circle"><i class="fa fa-minus-circle"></i> minus-circle</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/minus-square"><i class="fa fa-minus-square"></i> minus-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/minus-square-o"><i class="fa fa-minus-square-o"></i> minus-square-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/mobile"><i class="fa fa-mobile"></i> mobile</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/mobile"><i class="fa fa-mobile-phone"></i> mobile-phone <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/money"><i class="fa fa-money"></i> money</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/moon-o"><i class="fa fa-moon-o"></i> moon-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/graduation-cap"><i class="fa fa-mortar-board"></i> mortar-board <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/motorcycle"><i class="fa fa-motorcycle"></i> motorcycle</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/music"><i class="fa fa-music"></i> music</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/bars"><i class="fa fa-navicon"></i> navicon <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/newspaper-o"><i class="fa fa-newspaper-o"></i> newspaper-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/paint-brush"><i class="fa fa-paint-brush"></i> paint-brush</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/paper-plane"><i class="fa fa-paper-plane"></i> paper-plane</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/paper-plane-o"><i class="fa fa-paper-plane-o"></i> paper-plane-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/paw"><i class="fa fa-paw"></i> paw</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/pencil"><i class="fa fa-pencil"></i> pencil</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/pencil-square"><i class="fa fa-pencil-square"></i> pencil-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/pencil-square-o"><i class="fa fa-pencil-square-o"></i> pencil-square-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/phone"><i class="fa fa-phone"></i> phone</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/phone-square"><i class="fa fa-phone-square"></i> phone-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/picture-o"><i class="fa fa-photo"></i> photo <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/picture-o"><i class="fa fa-picture-o"></i> picture-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/pie-chart"><i class="fa fa-pie-chart"></i> pie-chart</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/plane"><i class="fa fa-plane"></i> plane</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/plug"><i class="fa fa-plug"></i> plug</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/plus"><i class="fa fa-plus"></i> plus</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/plus-circle"><i class="fa fa-plus-circle"></i> plus-circle</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/plus-square"><i class="fa fa-plus-square"></i> plus-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/plus-square-o"><i class="fa fa-plus-square-o"></i> plus-square-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/power-off"><i class="fa fa-power-off"></i> power-off</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/print"><i class="fa fa-print"></i> print</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/puzzle-piece"><i class="fa fa-puzzle-piece"></i> puzzle-piece</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/qrcode"><i class="fa fa-qrcode"></i> qrcode</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/question"><i class="fa fa-question"></i> question</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/question-circle"><i class="fa fa-question-circle"></i> question-circle</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/quote-left"><i class="fa fa-quote-left"></i> quote-left</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/quote-right"><i class="fa fa-quote-right"></i> quote-right</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/random"><i class="fa fa-random"></i> random</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/recycle"><i class="fa fa-recycle"></i> recycle</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/refresh"><i class="fa fa-refresh"></i> refresh</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/times"><i class="fa fa-remove"></i> remove <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/bars"><i class="fa fa-reorder"></i> reorder <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/reply"><i class="fa fa-reply"></i> reply</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/reply-all"><i class="fa fa-reply-all"></i> reply-all</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/retweet"><i class="fa fa-retweet"></i> retweet</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/road"><i class="fa fa-road"></i> road</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/rocket"><i class="fa fa-rocket"></i> rocket</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/rss"><i class="fa fa-rss"></i> rss</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/rss-square"><i class="fa fa-rss-square"></i> rss-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/search"><i class="fa fa-search"></i> search</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/search-minus"><i class="fa fa-search-minus"></i> search-minus</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/search-plus"><i class="fa fa-search-plus"></i> search-plus</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/paper-plane"><i class="fa fa-send"></i> send <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/paper-plane-o"><i class="fa fa-send-o"></i> send-o <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/server"><i class="fa fa-server"></i> server</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/share"><i class="fa fa-share"></i> share</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/share-alt"><i class="fa fa-share-alt"></i> share-alt</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/share-alt-square"><i class="fa fa-share-alt-square"></i> share-alt-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/share-square"><i class="fa fa-share-square"></i> share-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/share-square-o"><i class="fa fa-share-square-o"></i> share-square-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/shield"><i class="fa fa-shield"></i> shield</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/ship"><i class="fa fa-ship"></i> ship</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/shopping-cart"><i class="fa fa-shopping-cart"></i> shopping-cart</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/sign-in"><i class="fa fa-sign-in"></i> sign-in</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/sign-out"><i class="fa fa-sign-out"></i> sign-out</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/signal"><i class="fa fa-signal"></i> signal</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/sitemap"><i class="fa fa-sitemap"></i> sitemap</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/sliders"><i class="fa fa-sliders"></i> sliders</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/smile-o"><i class="fa fa-smile-o"></i> smile-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/futbol-o"><i class="fa fa-soccer-ball-o"></i> soccer-ball-o <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/sort"><i class="fa fa-sort"></i> sort</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/sort-alpha-asc"><i class="fa fa-sort-alpha-asc"></i> sort-alpha-asc</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/sort-alpha-desc"><i class="fa fa-sort-alpha-desc"></i> sort-alpha-desc</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/sort-amount-asc"><i class="fa fa-sort-amount-asc"></i> sort-amount-asc</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/sort-amount-desc"><i class="fa fa-sort-amount-desc"></i> sort-amount-desc</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/sort-asc"><i class="fa fa-sort-asc"></i> sort-asc</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/sort-desc"><i class="fa fa-sort-desc"></i> sort-desc</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/sort-desc"><i class="fa fa-sort-down"></i> sort-down <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/sort-numeric-asc"><i class="fa fa-sort-numeric-asc"></i> sort-numeric-asc</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/sort-numeric-desc"><i class="fa fa-sort-numeric-desc"></i> sort-numeric-desc</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/sort-asc"><i class="fa fa-sort-up"></i> sort-up <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/space-shuttle"><i class="fa fa-space-shuttle"></i> space-shuttle</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/spinner"><i class="fa fa-spinner"></i> spinner</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/spoon"><i class="fa fa-spoon"></i> spoon</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/square"><i class="fa fa-square"></i> square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/square-o"><i class="fa fa-square-o"></i> square-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/star"><i class="fa fa-star"></i> star</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/star-half"><i class="fa fa-star-half"></i> star-half</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/star-half-o"><i class="fa fa-star-half-empty"></i> star-half-empty <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/star-half-o"><i class="fa fa-star-half-full"></i> star-half-full <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/star-half-o"><i class="fa fa-star-half-o"></i> star-half-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/star-o"><i class="fa fa-star-o"></i> star-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/street-view"><i class="fa fa-street-view"></i> street-view</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/suitcase"><i class="fa fa-suitcase"></i> suitcase</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/sun-o"><i class="fa fa-sun-o"></i> sun-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/life-ring"><i class="fa fa-support"></i> support <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/tablet"><i class="fa fa-tablet"></i> tablet</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/tachometer"><i class="fa fa-tachometer"></i> tachometer</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/tag"><i class="fa fa-tag"></i> tag</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/tags"><i class="fa fa-tags"></i> tags</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/tasks"><i class="fa fa-tasks"></i> tasks</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/taxi"><i class="fa fa-taxi"></i> taxi</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/terminal"><i class="fa fa-terminal"></i> terminal</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/thumb-tack"><i class="fa fa-thumb-tack"></i> thumb-tack</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/thumbs-down"><i class="fa fa-thumbs-down"></i> thumbs-down</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/thumbs-o-down"><i class="fa fa-thumbs-o-down"></i> thumbs-o-down</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/thumbs-o-up"><i class="fa fa-thumbs-o-up"></i> thumbs-o-up</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/thumbs-up"><i class="fa fa-thumbs-up"></i> thumbs-up</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/ticket"><i class="fa fa-ticket"></i> ticket</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/times"><i class="fa fa-times"></i> times</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/times-circle"><i class="fa fa-times-circle"></i> times-circle</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/times-circle-o"><i class="fa fa-times-circle-o"></i> times-circle-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/tint"><i class="fa fa-tint"></i> tint</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/caret-square-o-down"><i class="fa fa-toggle-down"></i> toggle-down <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/caret-square-o-left"><i class="fa fa-toggle-left"></i> toggle-left <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/toggle-off"><i class="fa fa-toggle-off"></i> toggle-off</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/toggle-on"><i class="fa fa-toggle-on"></i> toggle-on</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/caret-square-o-right"><i class="fa fa-toggle-right"></i> toggle-right <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/caret-square-o-up"><i class="fa fa-toggle-up"></i> toggle-up <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/trash"><i class="fa fa-trash"></i> trash</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/trash-o"><i class="fa fa-trash-o"></i> trash-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/tree"><i class="fa fa-tree"></i> tree</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/trophy"><i class="fa fa-trophy"></i> trophy</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/truck"><i class="fa fa-truck"></i> truck</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/tty"><i class="fa fa-tty"></i> tty</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/umbrella"><i class="fa fa-umbrella"></i> umbrella</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/university"><i class="fa fa-university"></i> university</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/unlock"><i class="fa fa-unlock"></i> unlock</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/unlock-alt"><i class="fa fa-unlock-alt"></i> unlock-alt</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/sort"><i class="fa fa-unsorted"></i> unsorted <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/upload"><i class="fa fa-upload"></i> upload</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/user"><i class="fa fa-user"></i> user</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/user-plus"><i class="fa fa-user-plus"></i> user-plus</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/user-secret"><i class="fa fa-user-secret"></i> user-secret</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/user-times"><i class="fa fa-user-times"></i> user-times</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/users"><i class="fa fa-users"></i> users</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/video-camera"><i class="fa fa-video-camera"></i> video-camera</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/volume-down"><i class="fa fa-volume-down"></i> volume-down</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/volume-off"><i class="fa fa-volume-off"></i> volume-off</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/volume-up"><i class="fa fa-volume-up"></i> volume-up</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/exclamation-triangle"><i class="fa fa-warning"></i> warning <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/wheelchair"><i class="fa fa-wheelchair"></i> wheelchair</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/wifi"><i class="fa fa-wifi"></i> wifi</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/wrench"><i class="fa fa-wrench"></i> wrench</a></div>
    
  </div>

</section>

  <section id="transportation">
  <h2 class="page-header">Transportation Icons</h2>

  <div class="row fontawesome-icon-list">
    

    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/ambulance"><i class="fa fa-ambulance"></i> ambulance</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/car"><i class="fa fa-automobile"></i> automobile <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/bicycle"><i class="fa fa-bicycle"></i> bicycle</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/bus"><i class="fa fa-bus"></i> bus</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/taxi"><i class="fa fa-cab"></i> cab <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/car"><i class="fa fa-car"></i> car</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/fighter-jet"><i class="fa fa-fighter-jet"></i> fighter-jet</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/motorcycle"><i class="fa fa-motorcycle"></i> motorcycle</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/plane"><i class="fa fa-plane"></i> plane</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/rocket"><i class="fa fa-rocket"></i> rocket</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/ship"><i class="fa fa-ship"></i> ship</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/space-shuttle"><i class="fa fa-space-shuttle"></i> space-shuttle</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/subway"><i class="fa fa-subway"></i> subway</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/taxi"><i class="fa fa-taxi"></i> taxi</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/train"><i class="fa fa-train"></i> train</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/truck"><i class="fa fa-truck"></i> truck</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/wheelchair"><i class="fa fa-wheelchair"></i> wheelchair</a></div>
    
  </div>

</section>

  <section id="gender">
  <h2 class="page-header">Gender Icons</h2>

  <div class="row fontawesome-icon-list">
    

    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/circle-thin"><i class="fa fa-circle-thin"></i> circle-thin</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/circle-thin"><i class="fa fa-genderless"></i> genderless <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/mars"><i class="fa fa-mars"></i> mars</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/mars-double"><i class="fa fa-mars-double"></i> mars-double</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/mars-stroke"><i class="fa fa-mars-stroke"></i> mars-stroke</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/mars-stroke-h"><i class="fa fa-mars-stroke-h"></i> mars-stroke-h</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/mars-stroke-v"><i class="fa fa-mars-stroke-v"></i> mars-stroke-v</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/mercury"><i class="fa fa-mercury"></i> mercury</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/neuter"><i class="fa fa-neuter"></i> neuter</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/transgender"><i class="fa fa-transgender"></i> transgender</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/transgender-alt"><i class="fa fa-transgender-alt"></i> transgender-alt</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/venus"><i class="fa fa-venus"></i> venus</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/venus-double"><i class="fa fa-venus-double"></i> venus-double</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/venus-mars"><i class="fa fa-venus-mars"></i> venus-mars</a></div>
    
  </div>

</section>

  <section id="file-type">
  <h2 class="page-header">File Type Icons</h2>

  <div class="row fontawesome-icon-list">
    

    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/file"><i class="fa fa-file"></i> file</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/file-archive-o"><i class="fa fa-file-archive-o"></i> file-archive-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/file-audio-o"><i class="fa fa-file-audio-o"></i> file-audio-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/file-code-o"><i class="fa fa-file-code-o"></i> file-code-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/file-excel-o"><i class="fa fa-file-excel-o"></i> file-excel-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/file-image-o"><i class="fa fa-file-image-o"></i> file-image-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/file-video-o"><i class="fa fa-file-movie-o"></i> file-movie-o <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/file-o"><i class="fa fa-file-o"></i> file-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/file-pdf-o"><i class="fa fa-file-pdf-o"></i> file-pdf-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/file-image-o"><i class="fa fa-file-photo-o"></i> file-photo-o <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/file-image-o"><i class="fa fa-file-picture-o"></i> file-picture-o <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/file-powerpoint-o"><i class="fa fa-file-powerpoint-o"></i> file-powerpoint-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/file-audio-o"><i class="fa fa-file-sound-o"></i> file-sound-o <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/file-text"><i class="fa fa-file-text"></i> file-text</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/file-text-o"><i class="fa fa-file-text-o"></i> file-text-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/file-video-o"><i class="fa fa-file-video-o"></i> file-video-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/file-word-o"><i class="fa fa-file-word-o"></i> file-word-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/file-archive-o"><i class="fa fa-file-zip-o"></i> file-zip-o <span class="text-muted">(alias)</span></a></div>
    
  </div>

</section>

  <section id="spinner">
  <h2 class="page-header">Spinner Icons</h2>

  <div class="row fontawesome-icon-list">
    

    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/circle-o-notch"><i class="fa fa-circle-o-notch"></i> circle-o-notch</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/cog"><i class="fa fa-cog"></i> cog</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/cog"><i class="fa fa-gear"></i> gear <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/refresh"><i class="fa fa-refresh"></i> refresh</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/spinner"><i class="fa fa-spinner"></i> spinner</a></div>
    
  </div>
</section>

  <section id="form-control">
  <h2 class="page-header">Form Control Icons</h2>

  <div class="row fontawesome-icon-list">
    

    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/check-square"><i class="fa fa-check-square"></i> check-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/check-square-o"><i class="fa fa-check-square-o"></i> check-square-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/circle"><i class="fa fa-circle"></i> circle</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/circle-o"><i class="fa fa-circle-o"></i> circle-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/dot-circle-o"><i class="fa fa-dot-circle-o"></i> dot-circle-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/minus-square"><i class="fa fa-minus-square"></i> minus-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/minus-square-o"><i class="fa fa-minus-square-o"></i> minus-square-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/plus-square"><i class="fa fa-plus-square"></i> plus-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/plus-square-o"><i class="fa fa-plus-square-o"></i> plus-square-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/square"><i class="fa fa-square"></i> square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/square-o"><i class="fa fa-square-o"></i> square-o</a></div>
    
  </div>
</section>

  <section id="payment">
  <h2 class="page-header">Payment Icons</h2>

  <div class="row fontawesome-icon-list">
    

    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/cc-amex"><i class="fa fa-cc-amex"></i> cc-amex</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/cc-discover"><i class="fa fa-cc-discover"></i> cc-discover</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/cc-mastercard"><i class="fa fa-cc-mastercard"></i> cc-mastercard</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/cc-paypal"><i class="fa fa-cc-paypal"></i> cc-paypal</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/cc-stripe"><i class="fa fa-cc-stripe"></i> cc-stripe</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/cc-visa"><i class="fa fa-cc-visa"></i> cc-visa</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/credit-card"><i class="fa fa-credit-card"></i> credit-card</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/google-wallet"><i class="fa fa-google-wallet"></i> google-wallet</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/paypal"><i class="fa fa-paypal"></i> paypal</a></div>
    
  </div>

</section>

  <section id="chart">
  <h2 class="page-header">Chart Icons</h2>

  <div class="row fontawesome-icon-list">
    

    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/area-chart"><i class="fa fa-area-chart"></i> area-chart</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/bar-chart"><i class="fa fa-bar-chart"></i> bar-chart</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/bar-chart"><i class="fa fa-bar-chart-o"></i> bar-chart-o <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/line-chart"><i class="fa fa-line-chart"></i> line-chart</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/pie-chart"><i class="fa fa-pie-chart"></i> pie-chart</a></div>
    
  </div>

</section>

  <section id="currency">
  <h2 class="page-header">Currency Icons</h2>

  <div class="row fontawesome-icon-list">
    

    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/btc"><i class="fa fa-bitcoin"></i> bitcoin <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/btc"><i class="fa fa-btc"></i> btc</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/jpy"><i class="fa fa-cny"></i> cny <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/usd"><i class="fa fa-dollar"></i> dollar <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/eur"><i class="fa fa-eur"></i> eur</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/eur"><i class="fa fa-euro"></i> euro <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/gbp"><i class="fa fa-gbp"></i> gbp</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/ils"><i class="fa fa-ils"></i> ils</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/inr"><i class="fa fa-inr"></i> inr</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/jpy"><i class="fa fa-jpy"></i> jpy</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/krw"><i class="fa fa-krw"></i> krw</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/money"><i class="fa fa-money"></i> money</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/jpy"><i class="fa fa-rmb"></i> rmb <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/rub"><i class="fa fa-rouble"></i> rouble <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/rub"><i class="fa fa-rub"></i> rub</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/rub"><i class="fa fa-ruble"></i> ruble <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/inr"><i class="fa fa-rupee"></i> rupee <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/ils"><i class="fa fa-shekel"></i> shekel <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/ils"><i class="fa fa-sheqel"></i> sheqel <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/try"><i class="fa fa-try"></i> try</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/try"><i class="fa fa-turkish-lira"></i> turkish-lira <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/usd"><i class="fa fa-usd"></i> usd</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/krw"><i class="fa fa-won"></i> won <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/jpy"><i class="fa fa-yen"></i> yen <span class="text-muted">(alias)</span></a></div>
    
  </div>

</section>

  <section id="text-editor">
  <h2 class="page-header">Text Editor Icons</h2>

  <div class="row fontawesome-icon-list">
    

    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/align-center"><i class="fa fa-align-center"></i> align-center</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/align-justify"><i class="fa fa-align-justify"></i> align-justify</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/align-left"><i class="fa fa-align-left"></i> align-left</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/align-right"><i class="fa fa-align-right"></i> align-right</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/bold"><i class="fa fa-bold"></i> bold</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/link"><i class="fa fa-chain"></i> chain <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/chain-broken"><i class="fa fa-chain-broken"></i> chain-broken</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/clipboard"><i class="fa fa-clipboard"></i> clipboard</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/columns"><i class="fa fa-columns"></i> columns</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/files-o"><i class="fa fa-copy"></i> copy <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/scissors"><i class="fa fa-cut"></i> cut <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/outdent"><i class="fa fa-dedent"></i> dedent <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/eraser"><i class="fa fa-eraser"></i> eraser</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/file"><i class="fa fa-file"></i> file</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/file-o"><i class="fa fa-file-o"></i> file-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/file-text"><i class="fa fa-file-text"></i> file-text</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/file-text-o"><i class="fa fa-file-text-o"></i> file-text-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/files-o"><i class="fa fa-files-o"></i> files-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/floppy-o"><i class="fa fa-floppy-o"></i> floppy-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/font"><i class="fa fa-font"></i> font</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/header"><i class="fa fa-header"></i> header</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/indent"><i class="fa fa-indent"></i> indent</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/italic"><i class="fa fa-italic"></i> italic</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/link"><i class="fa fa-link"></i> link</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/list"><i class="fa fa-list"></i> list</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/list-alt"><i class="fa fa-list-alt"></i> list-alt</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/list-ol"><i class="fa fa-list-ol"></i> list-ol</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/list-ul"><i class="fa fa-list-ul"></i> list-ul</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/outdent"><i class="fa fa-outdent"></i> outdent</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/paperclip"><i class="fa fa-paperclip"></i> paperclip</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/paragraph"><i class="fa fa-paragraph"></i> paragraph</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/clipboard"><i class="fa fa-paste"></i> paste <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/repeat"><i class="fa fa-repeat"></i> repeat</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/undo"><i class="fa fa-rotate-left"></i> rotate-left <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/repeat"><i class="fa fa-rotate-right"></i> rotate-right <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/floppy-o"><i class="fa fa-save"></i> save <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/scissors"><i class="fa fa-scissors"></i> scissors</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/strikethrough"><i class="fa fa-strikethrough"></i> strikethrough</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/subscript"><i class="fa fa-subscript"></i> subscript</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/superscript"><i class="fa fa-superscript"></i> superscript</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/table"><i class="fa fa-table"></i> table</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/text-height"><i class="fa fa-text-height"></i> text-height</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/text-width"><i class="fa fa-text-width"></i> text-width</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/th"><i class="fa fa-th"></i> th</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/th-large"><i class="fa fa-th-large"></i> th-large</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/th-list"><i class="fa fa-th-list"></i> th-list</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/underline"><i class="fa fa-underline"></i> underline</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/undo"><i class="fa fa-undo"></i> undo</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/chain-broken"><i class="fa fa-unlink"></i> unlink <span class="text-muted">(alias)</span></a></div>
    
  </div>

</section>

  <section id="directional">
  <h2 class="page-header">Directional Icons</h2>

  <div class="row fontawesome-icon-list">
    

    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/angle-double-down"><i class="fa fa-angle-double-down"></i> angle-double-down</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/angle-double-left"><i class="fa fa-angle-double-left"></i> angle-double-left</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/angle-double-right"><i class="fa fa-angle-double-right"></i> angle-double-right</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/angle-double-up"><i class="fa fa-angle-double-up"></i> angle-double-up</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/angle-down"><i class="fa fa-angle-down"></i> angle-down</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/angle-left"><i class="fa fa-angle-left"></i> angle-left</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/angle-right"><i class="fa fa-angle-right"></i> angle-right</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/angle-up"><i class="fa fa-angle-up"></i> angle-up</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/arrow-circle-down"><i class="fa fa-arrow-circle-down"></i> arrow-circle-down</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/arrow-circle-left"><i class="fa fa-arrow-circle-left"></i> arrow-circle-left</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/arrow-circle-o-down"><i class="fa fa-arrow-circle-o-down"></i> arrow-circle-o-down</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/arrow-circle-o-left"><i class="fa fa-arrow-circle-o-left"></i> arrow-circle-o-left</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/arrow-circle-o-right"><i class="fa fa-arrow-circle-o-right"></i> arrow-circle-o-right</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/arrow-circle-o-up"><i class="fa fa-arrow-circle-o-up"></i> arrow-circle-o-up</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/arrow-circle-right"><i class="fa fa-arrow-circle-right"></i> arrow-circle-right</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/arrow-circle-up"><i class="fa fa-arrow-circle-up"></i> arrow-circle-up</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/arrow-down"><i class="fa fa-arrow-down"></i> arrow-down</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/arrow-left"><i class="fa fa-arrow-left"></i> arrow-left</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/arrow-right"><i class="fa fa-arrow-right"></i> arrow-right</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/arrow-up"><i class="fa fa-arrow-up"></i> arrow-up</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/arrows"><i class="fa fa-arrows"></i> arrows</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/arrows-alt"><i class="fa fa-arrows-alt"></i> arrows-alt</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/arrows-h"><i class="fa fa-arrows-h"></i> arrows-h</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/arrows-v"><i class="fa fa-arrows-v"></i> arrows-v</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/caret-down"><i class="fa fa-caret-down"></i> caret-down</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/caret-left"><i class="fa fa-caret-left"></i> caret-left</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/caret-right"><i class="fa fa-caret-right"></i> caret-right</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/caret-square-o-down"><i class="fa fa-caret-square-o-down"></i> caret-square-o-down</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/caret-square-o-left"><i class="fa fa-caret-square-o-left"></i> caret-square-o-left</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/caret-square-o-right"><i class="fa fa-caret-square-o-right"></i> caret-square-o-right</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/caret-square-o-up"><i class="fa fa-caret-square-o-up"></i> caret-square-o-up</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/caret-up"><i class="fa fa-caret-up"></i> caret-up</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/chevron-circle-down"><i class="fa fa-chevron-circle-down"></i> chevron-circle-down</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/chevron-circle-left"><i class="fa fa-chevron-circle-left"></i> chevron-circle-left</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/chevron-circle-right"><i class="fa fa-chevron-circle-right"></i> chevron-circle-right</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/chevron-circle-up"><i class="fa fa-chevron-circle-up"></i> chevron-circle-up</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/chevron-down"><i class="fa fa-chevron-down"></i> chevron-down</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/chevron-left"><i class="fa fa-chevron-left"></i> chevron-left</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/chevron-right"><i class="fa fa-chevron-right"></i> chevron-right</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/chevron-up"><i class="fa fa-chevron-up"></i> chevron-up</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/hand-o-down"><i class="fa fa-hand-o-down"></i> hand-o-down</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/hand-o-left"><i class="fa fa-hand-o-left"></i> hand-o-left</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/hand-o-right"><i class="fa fa-hand-o-right"></i> hand-o-right</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/hand-o-up"><i class="fa fa-hand-o-up"></i> hand-o-up</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/long-arrow-down"><i class="fa fa-long-arrow-down"></i> long-arrow-down</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/long-arrow-left"><i class="fa fa-long-arrow-left"></i> long-arrow-left</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/long-arrow-right"><i class="fa fa-long-arrow-right"></i> long-arrow-right</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/long-arrow-up"><i class="fa fa-long-arrow-up"></i> long-arrow-up</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/caret-square-o-down"><i class="fa fa-toggle-down"></i> toggle-down <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/caret-square-o-left"><i class="fa fa-toggle-left"></i> toggle-left <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/caret-square-o-right"><i class="fa fa-toggle-right"></i> toggle-right <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/caret-square-o-up"><i class="fa fa-toggle-up"></i> toggle-up <span class="text-muted">(alias)</span></a></div>
    
  </div>

</section>

  <section id="video-player">
  <h2 class="page-header">Video Player Icons</h2>

  <div class="row fontawesome-icon-list">
    

    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/arrows-alt"><i class="fa fa-arrows-alt"></i> arrows-alt</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/backward"><i class="fa fa-backward"></i> backward</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/compress"><i class="fa fa-compress"></i> compress</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/eject"><i class="fa fa-eject"></i> eject</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/expand"><i class="fa fa-expand"></i> expand</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/fast-backward"><i class="fa fa-fast-backward"></i> fast-backward</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/fast-forward"><i class="fa fa-fast-forward"></i> fast-forward</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/forward"><i class="fa fa-forward"></i> forward</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/pause"><i class="fa fa-pause"></i> pause</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/play"><i class="fa fa-play"></i> play</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/play-circle"><i class="fa fa-play-circle"></i> play-circle</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/play-circle-o"><i class="fa fa-play-circle-o"></i> play-circle-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/step-backward"><i class="fa fa-step-backward"></i> step-backward</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/step-forward"><i class="fa fa-step-forward"></i> step-forward</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/stop"><i class="fa fa-stop"></i> stop</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/youtube-play"><i class="fa fa-youtube-play"></i> youtube-play</a></div>
    
  </div>

</section>

  <section id="brand">
  <h2 class="page-header">Brand Icons</h2>

  <div class="alert alert-warning">
    <h4><i class="fa fa-warning"></i> Warning!</h4>
Apparently, Adblock Plus can remove Font Awesome brand icons with their "Remove Social
Media Buttons" setting. We will not use hacks to force them to display. Please
<a class="alert-link" href="https://adblockplus.org/en/bugs">report an issue with Adblock Plus</a> if you believe this to be
an error. To work around this, you'll need to modify the social icon class names.

  </div>

  <div class="row fontawesome-icon-list margin-bottom-lg">
    

    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/adn"><i class="fa fa-adn"></i> adn</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/android"><i class="fa fa-android"></i> android</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/angellist"><i class="fa fa-angellist"></i> angellist</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/apple"><i class="fa fa-apple"></i> apple</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/behance"><i class="fa fa-behance"></i> behance</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/behance-square"><i class="fa fa-behance-square"></i> behance-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/bitbucket"><i class="fa fa-bitbucket"></i> bitbucket</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/bitbucket-square"><i class="fa fa-bitbucket-square"></i> bitbucket-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/btc"><i class="fa fa-bitcoin"></i> bitcoin <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/btc"><i class="fa fa-btc"></i> btc</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/buysellads"><i class="fa fa-buysellads"></i> buysellads</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/cc-amex"><i class="fa fa-cc-amex"></i> cc-amex</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/cc-discover"><i class="fa fa-cc-discover"></i> cc-discover</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/cc-mastercard"><i class="fa fa-cc-mastercard"></i> cc-mastercard</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/cc-paypal"><i class="fa fa-cc-paypal"></i> cc-paypal</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/cc-stripe"><i class="fa fa-cc-stripe"></i> cc-stripe</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/cc-visa"><i class="fa fa-cc-visa"></i> cc-visa</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/codepen"><i class="fa fa-codepen"></i> codepen</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/connectdevelop"><i class="fa fa-connectdevelop"></i> connectdevelop</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/css3"><i class="fa fa-css3"></i> css3</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/dashcube"><i class="fa fa-dashcube"></i> dashcube</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/delicious"><i class="fa fa-delicious"></i> delicious</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/deviantart"><i class="fa fa-deviantart"></i> deviantart</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/digg"><i class="fa fa-digg"></i> digg</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/dribbble"><i class="fa fa-dribbble"></i> dribbble</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/dropbox"><i class="fa fa-dropbox"></i> dropbox</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/drupal"><i class="fa fa-drupal"></i> drupal</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/empire"><i class="fa fa-empire"></i> empire</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/facebook"><i class="fa fa-facebook"></i> facebook</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/facebook"><i class="fa fa-facebook-f"></i> facebook-f <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/facebook-official"><i class="fa fa-facebook-official"></i> facebook-official</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/facebook-square"><i class="fa fa-facebook-square"></i> facebook-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/flickr"><i class="fa fa-flickr"></i> flickr</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/forumbee"><i class="fa fa-forumbee"></i> forumbee</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/foursquare"><i class="fa fa-foursquare"></i> foursquare</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/empire"><i class="fa fa-ge"></i> ge <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/git"><i class="fa fa-git"></i> git</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/git-square"><i class="fa fa-git-square"></i> git-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/github"><i class="fa fa-github"></i> github</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/github-alt"><i class="fa fa-github-alt"></i> github-alt</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/github-square"><i class="fa fa-github-square"></i> github-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/gratipay"><i class="fa fa-gittip"></i> gittip <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/google"><i class="fa fa-google"></i> google</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/google-plus"><i class="fa fa-google-plus"></i> google-plus</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/google-plus-square"><i class="fa fa-google-plus-square"></i> google-plus-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/google-wallet"><i class="fa fa-google-wallet"></i> google-wallet</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/gratipay"><i class="fa fa-gratipay"></i> gratipay</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/hacker-news"><i class="fa fa-hacker-news"></i> hacker-news</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/html5"><i class="fa fa-html5"></i> html5</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/instagram"><i class="fa fa-instagram"></i> instagram</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/ioxhost"><i class="fa fa-ioxhost"></i> ioxhost</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/joomla"><i class="fa fa-joomla"></i> joomla</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/jsfiddle"><i class="fa fa-jsfiddle"></i> jsfiddle</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/lastfm"><i class="fa fa-lastfm"></i> lastfm</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/lastfm-square"><i class="fa fa-lastfm-square"></i> lastfm-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/leanpub"><i class="fa fa-leanpub"></i> leanpub</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/linkedin"><i class="fa fa-linkedin"></i> linkedin</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/linkedin-square"><i class="fa fa-linkedin-square"></i> linkedin-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/linux"><i class="fa fa-linux"></i> linux</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/maxcdn"><i class="fa fa-maxcdn"></i> maxcdn</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/meanpath"><i class="fa fa-meanpath"></i> meanpath</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/medium"><i class="fa fa-medium"></i> medium</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/openid"><i class="fa fa-openid"></i> openid</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/pagelines"><i class="fa fa-pagelines"></i> pagelines</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/paypal"><i class="fa fa-paypal"></i> paypal</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/pied-piper"><i class="fa fa-pied-piper"></i> pied-piper</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/pied-piper-alt"><i class="fa fa-pied-piper-alt"></i> pied-piper-alt</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/pinterest"><i class="fa fa-pinterest"></i> pinterest</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/pinterest-p"><i class="fa fa-pinterest-p"></i> pinterest-p</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/pinterest-square"><i class="fa fa-pinterest-square"></i> pinterest-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/qq"><i class="fa fa-qq"></i> qq</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/rebel"><i class="fa fa-ra"></i> ra <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/rebel"><i class="fa fa-rebel"></i> rebel</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/reddit"><i class="fa fa-reddit"></i> reddit</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/reddit-square"><i class="fa fa-reddit-square"></i> reddit-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/renren"><i class="fa fa-renren"></i> renren</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/sellsy"><i class="fa fa-sellsy"></i> sellsy</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/share-alt"><i class="fa fa-share-alt"></i> share-alt</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/share-alt-square"><i class="fa fa-share-alt-square"></i> share-alt-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/shirtsinbulk"><i class="fa fa-shirtsinbulk"></i> shirtsinbulk</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/simplybuilt"><i class="fa fa-simplybuilt"></i> simplybuilt</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/skyatlas"><i class="fa fa-skyatlas"></i> skyatlas</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/skype"><i class="fa fa-skype"></i> skype</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/slack"><i class="fa fa-slack"></i> slack</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/slideshare"><i class="fa fa-slideshare"></i> slideshare</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/soundcloud"><i class="fa fa-soundcloud"></i> soundcloud</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/spotify"><i class="fa fa-spotify"></i> spotify</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/stack-exchange"><i class="fa fa-stack-exchange"></i> stack-exchange</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/stack-overflow"><i class="fa fa-stack-overflow"></i> stack-overflow</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/steam"><i class="fa fa-steam"></i> steam</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/steam-square"><i class="fa fa-steam-square"></i> steam-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/stumbleupon"><i class="fa fa-stumbleupon"></i> stumbleupon</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/stumbleupon-circle"><i class="fa fa-stumbleupon-circle"></i> stumbleupon-circle</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/tencent-weibo"><i class="fa fa-tencent-weibo"></i> tencent-weibo</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/trello"><i class="fa fa-trello"></i> trello</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/tumblr"><i class="fa fa-tumblr"></i> tumblr</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/tumblr-square"><i class="fa fa-tumblr-square"></i> tumblr-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/twitch"><i class="fa fa-twitch"></i> twitch</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/twitter"><i class="fa fa-twitter"></i> twitter</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/twitter-square"><i class="fa fa-twitter-square"></i> twitter-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/viacoin"><i class="fa fa-viacoin"></i> viacoin</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/vimeo-square"><i class="fa fa-vimeo-square"></i> vimeo-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/vine"><i class="fa fa-vine"></i> vine</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/vk"><i class="fa fa-vk"></i> vk</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/weixin"><i class="fa fa-wechat"></i> wechat <span class="text-muted">(alias)</span></a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/weibo"><i class="fa fa-weibo"></i> weibo</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/weixin"><i class="fa fa-weixin"></i> weixin</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/whatsapp"><i class="fa fa-whatsapp"></i> whatsapp</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/windows"><i class="fa fa-windows"></i> windows</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/wordpress"><i class="fa fa-wordpress"></i> wordpress</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/xing"><i class="fa fa-xing"></i> xing</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/xing-square"><i class="fa fa-xing-square"></i> xing-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/yahoo"><i class="fa fa-yahoo"></i> yahoo</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/yelp"><i class="fa fa-yelp"></i> yelp</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/youtube"><i class="fa fa-youtube"></i> youtube</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/youtube-play"><i class="fa fa-youtube-play"></i> youtube-play</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/youtube-square"><i class="fa fa-youtube-square"></i> youtube-square</a></div>
    
  </div>

  <div class="alert alert-success">
    <ul class="margin-bottom-none padding-left-lg">
  <li>All brand icons are trademarks of their respective owners.</li>
  <li>The use of these trademarks does not indicate endorsement of the trademark holder by Font Awesome, nor vice versa.</li>
</ul>

  </div>
</section>

  <section id="medical">
  <h2 class="page-header">Medical Icons</h2>

  <div class="row fontawesome-icon-list">
    

    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/ambulance"><i class="fa fa-ambulance"></i> ambulance</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/h-square"><i class="fa fa-h-square"></i> h-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/heart"><i class="fa fa-heart"></i> heart</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/heart-o"><i class="fa fa-heart-o"></i> heart-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/heartbeat"><i class="fa fa-heartbeat"></i> heartbeat</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/hospital-o"><i class="fa fa-hospital-o"></i> hospital-o</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/medkit"><i class="fa fa-medkit"></i> medkit</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/plus-square"><i class="fa fa-plus-square"></i> plus-square</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/stethoscope"><i class="fa fa-stethoscope"></i> stethoscope</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/user-md"><i class="fa fa-user-md"></i> user-md</a></div>
    
      <div class="fa-hover col-md-3 col-sm-4"><a href="../icon/wheelchair"><i class="fa fa-wheelchair"></i> wheelchair</a></div>
    
  </div>

</section>

</div>

<script>
var $ck = window.$ck || jQuery.noConflict();

$ck('#maincktabcontent div.maintab:not(.current)').hide();
$ck('.mainmenulink', $ck('#maincktabcontent')).each(function(i, tab) {
	$ck(tab).click(function() {
		$ck('#maincktabcontent div.maintab').hide();
		$ck('.mainmenulink', $ck('#maincktabcontent')).removeClass('current');
		if ($ck('#' + $ck(tab).attr('tab')).length)
			$ck('#' + $ck(tab).attr('tab')).show();
		$ck(this).addClass('current');
	});
});
</script>