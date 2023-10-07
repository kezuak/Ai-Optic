<?php
// no direct access
defined('_JEXEC') or die;

use Pagebuilderck\CKfof;
use Pagebuilderck\CKFramework;

require_once PAGEBUILDERCK_PATH . '/helpers/defines.js.php';

CKFramework::load();

if (!defined('PAGEBUILDERCK_MEDIA_URI')) {
	define('PAGEBUILDERCK_MEDIA_URI', JUri::root(true) . '/media/com_pagebuilderck');
}
$imagespath = PAGEBUILDERCK_MEDIA_URI . '/images/';
$input = JFactory::getApplication()->input;
$fieldid = $input->get('fieldid', '', 'string');
$lang = JFactory::getLanguage();
$langs = ['cs', 'da', 'de', 'en', 'es', 'fr', 'id', 'it', 'hu', 'nl', 'no', 'pl', 'pt', 'ro', 'sk', 'fi', 'sv', 'tr', 'vi', 'th', 'bg', 'ru', 'el', 'ja', 'ko', 'zh'];
?>

<style scope>
	.flex-images { overflow: hidden; }
	.flex-images .pixabay-item { float: left; margin: 4px; background: #f3f3f3; box-sizing: content-box; overflow: hidden; position: relative; }
	.flex-images .pixabay-item > img { display: block; width: auto; height: 100%; }

	.flex-images .download {
		opacity: 0; transition: opacity .3s; position: absolute; top: 0; right: 0; bottom: 0; left: 0;
		cursor: pointer; background: rgba(0,0,0,.65); color: #eee;
		text-align: center; font-size: 14px; line-height: 1.5;
	}
	.flex-images .pixabay-item:hover .download, .flex-images .pixabay-item.uploading .download { opacity: 1; }
	.flex-images .download img { position: absolute; top: 0; left: 0; right: 0; bottom: 0; margin: auto; height: 32px; opacity: .7; }
	.flex-images .download div { position: absolute; left: 0; right: 0; bottom: 15px; padding: 0 5px; }
	.flex-images .download a { color: #eee; }
</style>
<div id="ckpixabay" class="ckinterface">
	<form id="pixabay_images_form" style="margin:0">
		<a href="https://pixabay.com/" target="_blank" style="margin:3px 15px 5px 0;font-size:12px;line-height:1.7;color:#555;display:inline-block;padding:9px 12px 6px;border:1px solid #ccc">
			<i style="display:block;width:68px;height:18px;overflow:hidden"><img src="<?php echo $imagespath ?>/pixabay/logo.png" style="width:94px"></i> Free Images
		</a>
		<div style="line-height:1.5;margin:0;max-width:500px;position:relative; display:inline-block;width:100%;vertical-align: bottom;">
			<input id="q" type="text" value="" style="width:100%;padding:7px 32px 7px 9px; box-sizing: border-box; height:40px;" autofocus placeholder="<?php echo htmlspecialchars(JText::_('Search for "red roses", "flowers -red", "city OR town", etc.')); ?>">
			<button type="submit" style="background:#fff;border:0;cursor:pointer;position:absolute;right:5px;top:10px;outline:0" title="<?php echo JText::_('Search'); ?>"><img src="<?= PAGEBUILDERCK_MEDIA_URI . '/images/pixabay/search.png' ?>"></button>
		</div>
		<select id="pixabaylang" style="display: inline-block;width: 60px;vertical-align: bottom;height: 40px;">
			<?php foreach ($langs as $l) {
				$selected = substr($lang->getTag(), 0, 2) === $l;
				echo '<option value="' . $l . '" ' . ($selected ? 'selected' : '') .'>' . $l . '</option>';
			}
			?>
		</select>
		<div style="margin:1em 0;padding-left:2px;line-height:2;">
			<?php /* <label style="margin-right:15px;white-space:nowrap"><input type="checkbox" id="filter_photos"><?php echo JText::_('Photos'); ?></label>
			  <label style="margin-right:20px;white-space:nowrap"><input type="checkbox" id="filter_cliparts"><?php echo JText::_('Cliparts'); ?></label>
			 */ ?>
			<label style="margin-right:15px;white-space:nowrap"><input type="checkbox" id="filter_horizontal" ><?php echo JText::_('Horizontal'); ?></label>
			<label style="margin-right:25px;white-space:nowrap"><input type="checkbox" id="filter_vertical"><?php echo JText::_('Vertical'); ?></label>
		</div>
	</form>
	<div id="pixabay_results" class="flex-images" style="margin-top:20px;padding-top:25px;border-top:1px solid #ddd"></div>
</div>
<script>
		// flexImages
		!function(t){function e(t,a,r,n){function o(t){r.maxRows&&d>r.maxRows||r.truncate&&t&&d>1?w[g][0].style.display="none":(w[g][4]&&(w[g][3].attr("src",w[g][4]),w[g][4]=""),w[g][0].style.width=l+"px",w[g][0].style.height=u+"px",w[g][0].style.display="block")}var g,l,s=1,d=1,f=t.width()-2,w=[],c=0,u=r.rowHeight;for(f||(f=t.width()-2),i=0;i<a.length;i++)if(w.push(a[i]),c+=a[i][2]+r.margin,c>=f){var m=w.length*r.margin;for(s=(f-m)/(c-m),u=Math.ceil(r.rowHeight*s),exact_w=0,l,g=0;g<w.length;g++)l=Math.ceil(w[g][2]*s),exact_w+=l+r.margin,exact_w>f&&(l-=exact_w-f),o();w=[],c=0,d++}for(g=0;g<w.length;g++)l=Math.floor(w[g][2]*s),h=Math.floor(r.rowHeight*s),o(!0);n||f==t.width()||e(t,a,r,!0)}t.fn.flexImages=function(a){var i=t.extend({container:".pixabay-item",object:"img",rowHeight:180,maxRows:0,truncate:0},a);return this.each(function(){var a=t(this),r=t(a).find(i.container),n=[],o=(new Date).getTime(),h=window.getComputedStyle?getComputedStyle(r[0],null):r[0].currentStyle;for(i.margin=(parseInt(h.marginLeft)||0)+(parseInt(h.marginRight)||0)+(Math.round(parseFloat(h.borderLeftWidth))||0)+(Math.round(parseFloat(h.borderRightWidth))||0),j=0;j<r.length;j++){var g=r[j],l=parseInt(g.getAttribute("data-w")),s=l*(i.rowHeight/parseInt(g.getAttribute("data-h"))),d=t(g).find(i.object);n.push([g,l,s,d,d.data("src")])}e(a,n,i),t(window).off("resize.flexImages"+a.data("flex-t")),t(window).on("resize.flexImages"+o,function(){e(a,n,i)}),a.data("flex-t",o)})}}(jQuery);
		function getCookie(k){return(document.cookie.match('(^|; )'+k+'=([^;]*)')||0)[2]}
		function setCookie(n,v,d,s){var o=new Date;o.setTime(o.getTime()+864e5*d+1000*(s||0)),document.cookie=n+"="+v+";path=/;expires="+o.toGMTString()}
		function escapejs(s){return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,"\\'");}

		// set checkbox filters
		jQuery("input[id^='filter_']").each(function(){
			if (getCookie('px_'+this.id) != '0') this.checked = true;
			jQuery(this).change(function(){ setCookie('px_'+this.id, this.checked ? 1 : 0, 365); });
		});

		var post_id = 
			lang = jQuery('#pixabaylang').val(),
			safesearch = true,
			per_page = 30, form = jQuery('#pixabay_images_form'), hits, q, image_type, orientation;

		form.submit(function(e){
			jQuery(window).off('scroll');
			e.preventDefault();
			q = jQuery('#q', form).val();

			if (jQuery('#filter_photos', form).is(':checked') && !jQuery('#filter_cliparts', form).is(':checked')) image_type = 'photo';
			else if (!jQuery('#filter_photos', form).is(':checked') && jQuery('#filter_cliparts', form).is(':checked')) image_type = 'clipart';
			else image_type = 'all';
			if (jQuery('#filter_horizontal', form).is(':checked') && !jQuery('#filter_vertical', form).is(':checked')) orientation = 'horizontal';
			else if (!jQuery('#filter_horizontal', form).is(':checked') && jQuery('#filter_vertical', form).is(':checked')) orientation = 'vertical';
			else orientation = 'all';
			jQuery('#pixabay_results').html('');

			call_api(q, 1);
		});

		function call_api(q, p){
			lang = jQuery('#pixabaylang').val();
			$ck.ajax({
				type: "GET",
				url: 'https://pixabay.com/api/?key=27347-23fd1708b1c4f768195a5093b&lang='+lang+'&safesearch='+safesearch+'&image_type='+image_type+'&orientation='+orientation+'&per_page='+per_page+'&page='+p+'&search_term='+encodeURIComponent(q),
				cache: true,
				data: {

				}
			}).done(function(code) {
				// var data = JSON.parse(code);
				var data = code;
				if (!(data.totalHits > 0)) {
					jQuery('#pixabay_results').html('<div style="color:#bbb;font-size:24px;text-align:center;margin:40px 0">—— <?php echo JText::_('No matches', 'pixabay_images') ?> ——</div>');
					return false;
				}
				render_px_results(q, p, data);
			}).fail(function() {
				alert(CKApi.Text._('CK_FAILED', 'Failed'));
			});
			return false;
		}

		function render_px_results(q, p, data){
			hits = data['hits']; // store for upload click
			pages = Math.ceil(data.totalHits/per_page);
			var s = '';
			jQuery.each(data.hits, function(k, v) {
				s += '<div class="pixabay-item pixabay-upload" data-url="'+v.largeImageURL+'" data-user="'+v.user+'" data-w="'+v.webformatWidth+'" data-h="'+v.webformatHeight+'"><img src="'+v.previewURL.replace('_150', v.previewURL.indexOf('cdn.') > -1 ? '__340' : '_340')+'"><div class="download"><img src="<?= PAGEBUILDERCK_MEDIA_URI.'/images/pixabay/download.png' ?>"><div>'+(v.webformatWidth*2)+'Ã—'+(v.webformatHeight*2)+'<br><a href="https://pixabay.com/users/'+v.user+'/" target="_blank"">'+v.user+'</a> @ <a href="'+v.pageURL+'" target="_blank">Pixabay</a></div></div></div>';
			});
			jQuery('#pixabay_results').html(jQuery('#pixabay_results').html()+s);
			jQuery('#load_animation').remove();
			if (p < pages) {
				jQuery('#pixabay_results').after('<div id="load_animation" style="clear:both;padding:15px 0 0;text-align:center"><img style="width:60px" src="<?= PAGEBUILDERCK_MEDIA_URI.'/images/pixabay/loading.png' ?>"></div>');
				jQuery(window).scroll(function() {
					if(jQuery(window).scrollTop() + jQuery(window).height() > jQuery(document).height() - 400) {
						jQuery(window).off('scroll');
						call_api(jQuery('#q', form).val(), p+1);
					}
				});
			}

			jQuery('.flex-images').flexImages({rowHeight: 260});
		}

		jQuery(document).on('click', '.pixabay-upload', function(e) {
			if (jQuery(e.target).is('a')) return;
			jQuery(document).off('click', '.pixabay-upload');
			// loading animation
			jQuery(this).addClass('uploading').find('.download img').replaceWith('<img src="<?= PAGEBUILDERCK_MEDIA_URI.'/images/pixabay/loading.png' ?>" style="height:80px !important">');
			var myurl = PAGEBUILDERCK.URIPBCK + "&task=pixabay.upload&" + PAGEBUILDERCK.TOKEN;
			$ck.ajax({
				type: "POST",
				url: myurl,
				data: {
					image_url : jQuery(this).data('url')
				}
			}).done(function(code) {
				var result = JSON.parse(code);
				if (result.status == '1') {
					window.parent.ckSelectFile(result.file, '<?php echo $fieldid ?>');
					window.parent.CKBox.close('#ckfilesmodal .ckboxmodal-button');
				} else {
					alert(CKApi.Text._('CK_FAILED', 'Failed') + ' : ' + result.message);
				}
			}).fail(function() {
				alert(CKApi.Text._('CK_FAILED', 'Failed'));
			});
			return false;
		});
	</script>
</div>
