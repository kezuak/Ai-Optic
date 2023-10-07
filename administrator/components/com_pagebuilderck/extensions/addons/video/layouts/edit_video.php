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
<div id="elementscontainer">
	<div class="menulink current" tab="tab_edition"><?php echo JText::_('CK_EDITION'); ?></div>
	<div class="tab menustyles current ckproperty ckoption" id="tab_edition">
		<div class="menupanetitle"><?php echo JText::_('CK_VIDEO'); ?></div>
		<div style="text-align:left;">
			<input class="inputbox" type="text" value="" name="videourl" id="videourl" size="7" style="width:90%; min-width: 200px; clear:both;" onchange="ckUpdateVideoPreview()" />
			<a class="ckbuttonstyle" href="javascript:void(0)" onclick="CKBox.open({handler: 'iframe', url: 'index.php?option=com_pagebuilderck&view=browse&type=video&func=ckSelectVideoFile&tmpl=component'})"><?php echo JText::_('CK_SELECT') ?></a>
		</div>
		<div class="ckfilterhtml5">
			<div class="menupanetitle"><?php echo JText::_('PLG_PAGEBUILDERCK_VIDEO_POSTER_IMAGE'); ?></div>
			<div style="text-align:left;">
				<input class="inputbox" type="text" value="" name="posterurl" id="posterurl" size="7" style="width:90%; min-width: 200px; clear:both;" onchange="ckUpdateVideoPreview()" />
				<a class="ckbuttonstyle" href="javascript:void(0)" onclick="CKBox.open({handler: 'iframe', url: 'index.php?option=com_pagebuilderck&view=links&type=image&func=ckSelectPosterFile&tmpl=component'})"><?php echo JText::_('CK_SELECT') ?></a>
			</div>
		</div>
		<div class="ckfilteryoutube">
			<span class="ckoption-label">
				<?php echo JText::_('PLG_PAGEBUILDERCK_VIDEO_AUTO_YOUTUBE_THUMB'); ?></span>
			<span class="ckoption-field">
			<span class="ckoption-field ckbutton-group">
				<input id="blocyoutubethumb1" class="inputbox" name="blocyoutubethumb" value="1" type="radio">
				<label class="ckbutton" for="blocyoutubethumb1"><?php echo JText::_('JYES'); ?></label>
				<input id="blocyoutubethumb0" class="inputbox" name="blocyoutubethumb" value="0" type="radio">
				<label class="ckbutton" for="blocyoutubethumb0"><?php echo JText::_('JNO'); ?></label>
			</span>
		</div>
		<div class="ckfilteriframe">
			<span class="ckoption-label">
				<img class="ckoption-icon" src="<?php echo $this->imagespath; ?>text_signature.png" width="16" height="16" />
				<?php echo JText::_('CK_TITLE'); ?>
			</span>
			<span class="ckoption-field">
				<input class="inputbox" type="text" name="iframetitle" id="iframetitle" value="" style="" onchange="ckUpdateVideoAttribute('title', this.value)" />
			</span>
		</div>
		<div style="text-align:left;clear:both;">
			<div style="float:left;text-align:right;margin:5px 5px 0 0;line-height: 15px;;"><?php echo JText::_('CK_WIDTH'); ?></div><div style="float:left;text-align:right;margin:5px 5px 0 0;"><img src="<?php echo $this->imagespath; ?>width.png" width="15" height="15" align="top" /></div><div style="float:left;"><input class="inputbox" type="text" name="videowidth" id="videowidth" size="2" value="" style="" onchange="ckUpdateVideoPreview()" /></div><div style="float:left;text-align:left;margin-left:3px;width:50px;"></div>							
			<div style="float:left;text-align:right;margin:5px 5px 0 10px;line-height: 15px;;"><?php echo JText::_('CK_HEIGHT'); ?></div><div style="float:left;text-align:right;margin:5px 5px 0 0;"><img src="<?php echo $this->imagespath; ?>height.png" width="15" height="15" align="top" /></div><div style="float:left;"><input class="inputbox" type="text" name="videoheight" id="videoheight" size="2" value="" style="" onchange="ckUpdateVideoPreview()" /></div><div style="float:left;text-align:left;margin-left:3px;width:50px;"></div>
			<div class="clr"></div>
		</div>
		<div class="">
			<span class="ckoption-label">
				<?php echo JText::_('CK_RATIO'); ?></span>
			<span class="ckoption-field">
			<span class="ckoption-field ckbutton-group">
				<input id="blocratio169" class="inputbox" name="blocratio" value="169" type="radio" onclick="ckSetActiveButton('blocratio');ckSetVideoRatio(this);">
				<label class="ckbutton" for="blocratio169">
					16:9
				</label>
				<input id="blocratio43" class="inputbox" name="blocratio" value="43" type="radio" onclick="ckSetActiveButton('blocratio');ckSetVideoRatio(this);">
				<label class="ckbutton" for="blocratio43">
					4:3
				</label>
			</span>
		</div>
		<div class="">
			<span class="ckoption-label">
				<?php echo JText::_('CK_ALIGN'); ?></span>
			<span class="ckoption-field">
			<span class="ckoption-field ckbutton-group">
				<input id="blocalignementleft" class="inputbox" name="blocalignement" value="left" type="radio" onclick="ckSetActiveAlignmentButton()">
				<label class="ckbutton" for="blocalignementleft">
					<img src="<?php echo $this->imagespath; ?>text_align_left.png" width="16" height="16" />
				</label>
				<input id="blocalignementcenter" class="inputbox" name="blocalignement" value="center" type="radio" onclick="ckSetActiveAlignmentButton()">
				<label class="ckbutton" for="blocalignementcenter">
					<img src="<?php echo $this->imagespath; ?>text_align_center.png" width="16" height="16" />
				</label>
				<input id="blocalignementright" class="inputbox" name="blocalignement" value="right" type="radio" onclick="ckSetActiveAlignmentButton()">
				<label class="ckbutton" for="blocalignementright">
					<img src="<?php echo $this->imagespath; ?>text_align_right.png" width="16" height="16" />
				</label>
			</span>
		</div>
		<div class="">
			<span class="ckoption-label">
				<img class="ckoption-icon" src="<?php echo $this->imagespath; ?>control_play.png" width="16" height="16" />
				<?php echo JText::_('CK_AUTOPLAY'); ?>
			</span>
			<span class="ckoption-field ckbutton-group">
				<input type="radio" class="inputbox" name="autoplay" id="autoplayyes" onclick="ckUpdateVideoPreview()" value="1" />
				<label for="autoplayyes" class="ckbutton"><?php echo JText::_('JYES') ?></label>
				<input type="radio" class="inputbox" name="autoplay" id="autoplayno" onclick="ckUpdateVideoPreview()" value="0" checked />
				<label for="autoplayno" class="ckbutton"><?php echo JText::_('JNO') ?></label>
			</span>
		</div>
		<div class="">
			<span class="ckoption-label">
				<img class="ckoption-icon" src="<?php echo $this->imagespath; ?>sound.png" width="16" height="16" />
				<?php echo JText::_('CK_MUTED'); ?>
			</span>
			<span class="ckoption-field ckbutton-group">
				<input type="radio" class="inputbox" name="muted" id="mutedyes" onclick="ckUpdateVideoPreview()" value="1" />
				<label for="mutedyes" class="ckbutton"><?php echo JText::_('JYES') ?></label>
				<input type="radio" class="inputbox" name="muted" id="mutedno" onclick="ckUpdateVideoPreview()" value="0" checked />
				<label for="mutedno" class="ckbutton"><?php echo JText::_('JNO') ?></label>
			</span>
		</div>
		<div class="">
			<span class="ckoption-label">
				<img class="ckoption-icon" src="<?php echo $this->imagespath; ?>control_repeat.png" width="16" height="16" />
				<?php echo JText::_('CK_LOOP'); ?>
			</span>
			<span class="ckoption-field ckbutton-group">
				<input type="radio" class="inputbox" name="loop" id="loopyes" onclick="ckUpdateVideoPreview()" value="1" />
				<label for="loopyes" class="ckbutton"><?php echo JText::_('JYES') ?></label>
				<input type="radio" class="inputbox" name="loop" id="loopno" onclick="ckUpdateVideoPreview()" value="0" checked />
				<label for="loopno" class="ckbutton"><?php echo JText::_('JNO') ?></label>
			</span>
		</div>
		<div class="">
			<span class="ckoption-label">
				<img class="ckoption-icon" src="<?php echo $this->imagespath; ?>control_pause.png" width="16" height="16" />
				<?php echo JText::_('CK_CONTROLS'); ?>
			</span>
			<span class="ckoption-field ckbutton-group">
				<input type="radio" class="inputbox" name="controls" id="controlsyes" onclick="ckUpdateVideoPreview()" value="1" checked />
				<label for="controlsyes" class="ckbutton"><?php echo JText::_('JYES') ?></label>
				<input type="radio" class="inputbox" name="controls" id="controlsno" onclick="ckUpdateVideoPreview()" value="0"  />
				<label for="controlsno" class="ckbutton"><?php echo JText::_('JNO') ?></label>
			</span>
		</div>
		<div class="">
			<span class="ckoption-label">
				<img class="ckoption-icon" src="<?php echo $this->imagespath; ?>text_signature.png" width="16" height="16" />
				<?php echo JText::_('CK_CSS_CLASS'); ?>
			</span>
			<span class="ckoption-field">
				<input class="inputbox" type="text" name="videocssclass" id="videocssclass" value="" style="" onchange="ckUpdateVideoAttribute('class', this.value)" />
			</span>
		</div>
	</div>
	<div class="menulink" tab="tab_blocstyles"><?php echo JText::_('CK_STYLES'); ?></div>
	<div class="tab menustyles ckproperty" id="tab_blocstyles">
		<?php echo $this->menustyles->createBlocStyles('bloc', 'video', '') ?>
	</div>
</div>
<div class="clr"></div>
<script language="javascript" type="text/javascript">
var focus = $ck('.editfocus');
function ckLoadEditionPopup() {
	ckFillEditionPopup(focus.attr('id'));
	var isLocal = ckIsVideoLocal($ck('#videourl').val());
	if (isLocal) {
		$ck('.ckfilteriframe').hide();
		$ck('.ckfilterhtml5').show();
	} else {
		$ck('.ckfilterhtml5').hide();
		$ck('.ckfilteriframe').show();
	}
}

function ckBeforeSaveEditionPopup() {
//	var focus = $ck('.editfocus');
	ckUpdateVideoPreview();
//	ckSaveEditionPopup(focus.attr('id'));
//	ckCloseEditionPopup();
}

function ckUpdateVideoPreview() {
	ckAutoDetectVideoEmbedurl();
	var videoSrc = $ck('#videourl').val();
	var posterSrc = $ck('#posterurl').val();
	if (videoSrc.substr(0,1) == '/') {
		videoSrc = videoSrc.slice(1,videoSrc.length);
	}
	if (posterSrc.substr(0,1) == '/') {
		posterSrc = posterSrc.slice(1,posterSrc.length);
	}
	var isLocal = ckIsVideoLocal(videoSrc);
//	var isLocal = !(/^(f|ht)tps?:\/\//i.test(videoSrc));
	videoSrc = isLocal ? PAGEBUILDERCK.URIROOT + '/' + videoSrc : videoSrc;
	var isLocalPoster = !(/^(f|ht)tps?:\/\//i.test(posterSrc));
	posterSrc = isLocalPoster ? PAGEBUILDERCK.URIROOT + '/' + posterSrc : posterSrc;
	var posterAttr = $ck('#posterurl').val() ? ' poster="' + posterSrc + '"' : '';

	var videoiframe = isLocal ? $ck('.editfocus .videock video') : $ck('.editfocus .videock iframe');

	var videow = $ck('#videowidth').val() ? $ck('#videowidth').val() : '';
	var videoh = $ck('#videoheight').val() ? $ck('#videoheight').val() : '';
	var autoplay = $ck('#elementscontainer [name="autoplay"]:checked').val() == '1' ? 'autoplay playsinline' : '';
	var muted = $ck('#elementscontainer [name="muted"]:checked').val() == '1' ? ' muted' : '';
	var loop = $ck('#elementscontainer [name="loop"]:checked').val() == '1' ? ' loop' : '';
	var controls = $ck('#elementscontainer [name="controls"]:checked').val() == '0' ? '' : ' controls';

	if (videoSrc) {
		var appendChar = videoSrc.indexOf('?') != '-1' ? '&' : '?';
		$ck('.editfocus .videock').empty();
		if (isLocal) {
			$ck('.ckfilteriframe').hide();
			$ck('.ckfilterhtml5').show();
			$ck('.editfocus .videock').append(
				'<video src="'+videoSrc+'" ' + autoplay + muted + loop + controls + posterAttr + ' >'
				+ '</video>');
		} else {
			$ck('.ckfilterhtml5').hide();
			$ck('.ckfilteriframe').show();
			var iframetitle = $ck('#iframetitle').val();
			$ck('.editfocus .videock').append(
				'<iframe src="' + videoSrc 
					+ (autoplay ? appendChar + 'autoplay=1' : '') + '"'
					+ (iframetitle ? ' title="' + iframetitle + '"' : '')
					+ ' frameborder="0" allowfullscreen>'
				+ '</iframe>');
			ckSetYoutubeThumb();
		}
		$ck('.editfocus .videock').css('width', videow).css('padding-bottom', videoh);
	} else {
		$ck('.editfocus .videock').css('width', videow).css('padding-bottom', videoh);
	}
	
}

function ckAutoDetectVideoEmbedurl() {
	var url = $ck('#videourl').val();
	// for youtube
	// https://www.youtube.com/watch?v=code
	url = url.replace('youtu.be', 'www.youtube.com/embed');
	url = url.replace('youtube.com/watch?v=', 'youtube.com/embed/');

	$ck('#videourl').val(url);
}

// set active class for radio buttons
function ckSetActiveButton(type) {
	$ck('#elementscontainer .inputbox[name="'+type+'"]').each(function() {
		if ($ck(this).prop('checked')) {
			$ck(this).next('label').addClass('active');
		} else {
			$ck(this).next('label').removeClass('active');
		}
	});
}

function ckUpdateVideoAttribute(attribute, value) {
	var videopreview = $ck('.editfocus iframe');
	if (value) {
		videopreview.attr(attribute, value);
	} else {
		videopreview.removeAttr(attribute);
	}
	
}

function ckSetVideoRatio(button) {
	if (button.value == '169') {
		$ck('#videoheight').val('56.25%');
		$ck('#videowidth').val('100%');
	} else if (button.value == '43') {
		$ck('#videoheight').val('75%');
		$ck('#videowidth').val('100%');
	}
	ckUpdateVideoPreview();
}

function ckSelectPosterFile(file) {
	$ck('#posterurl').val(file);
	CKBox.close();
	ckUpdateVideoPreview();
}

function ckSelectVideoFile(file) {
	$ck('#videourl').val(file);
	CKBox.close();
	ckUpdateVideoPreview();
}

// set active class for radio buttons
function ckSetActiveAlignmentButton() {
	$ck('#elementscontainer .inputbox[name="blocalignement"]').each(function() {
		if ($ck(this).prop('checked')) {
			$ck(this).next('label').addClass('active');
		} else {
			$ck(this).next('label').removeClass('active');
		}
	});
}

function ckSetYoutubeThumb() {
	var usethumb = $ck('[name="blocyoutubethumb"]:checked').val();
	if (usethumb == 1) {
		var youtubecode = ckGetYoutubeCode($ck('#videourl').val());
		if (! youtubecode) return;
		focus.find('.videock img').remove();
		focus.find('.videock').prepend('<img class="videock-youtube-thumb" alt="youtube" src="https://img.youtube.com/vi/' + youtubecode + '/mqdefault.jpg" onclick="ckLoadYoutube(this)" width="320" height="180" />');
		var framecode = focus.find('.videock iframe').get(0).outerHTML;
		framecode = framecode.replace('<iframe', '<i-frame');
		framecode = framecode.replace('</iframe>', '</i-frame>');
		focus.find('.videock iframe').get(0).outerHTML = framecode;
	}
}

ckSetActiveButton('blocalignement');
</script>
<style type="text/css">
#video_preview {
padding: 5px;
background: #f5f5f5;
border: 1px solid #ddd;
margin: 10px 10px 10px 0;
max-width: 600px;
/*height: 200px;*/
overflow: hidden;
}

#video_preview > iframe {
	max-width: 100%;
}
</style>