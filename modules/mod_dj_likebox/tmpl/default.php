<?php 
/**
* @version 2.0
* @package DJ Like Box
* @copyright Copyright (C) 2010 Blue Constant Media LTD, All rights reserved.
* @license http://www.gnu.org/licenses GNU/GPL
* @author url: http://design-joomla.eu
* @author email contact@design-joomla.eu
* @developer Szymon Woronowski - szymon.woronowski@design-joomla.eu
*
*
* DJ Like Box is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* DJ Like Box is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with DJ Like Box. If not, see <http://www.gnu.org/licenses/>.
*
*/

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<?php if($params->get('facebook_sdk')) { ?>
<div id="fb-root"></div>
<script>(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = 'https://connect.facebook.net/<?php echo $lang ?>/sdk.js#xfbml=1&version=v2.10&appId=<?php echo $params->get('appid') ?>';
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<?php } ?>
	
<div class="dj-likebox">

	<div class="fb-page"
		data-href="<?php echo $params->get('href'); ?>"
		<?php if($params->get('width')) { ?>
		data-width="<?php echo $params->get('width'); ?>"
		<?php } ?>
		<?php if($params->get('height')) { ?>
		data-height="<?php echo $params->get('height'); ?>"
		<?php } ?>
		data-tabs="<?php echo $params->get('tabs'); ?>"
		data-hide-cover="<?php echo $params->get('hide_cover'); ?>"
		data-show-facepile="<?php echo $params->get('show_facepile'); ?>" 
		data-hide-cta="<?php echo $params->get('hide_cta'); ?>" 
		data-small-header="<?php echo $params->get('small_header'); ?>"
		data-adapt-container-width="<?php echo $params->get('adapt'); ?>">
	</div>
</div>
