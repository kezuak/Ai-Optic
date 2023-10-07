<?php
/**
 * @name		Page Builder CK
 * @copyright	Copyright (C) 2019. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
$tables = array('colorpalette', 'colorpalettefromsettings', 'colorpalettefromtemplate')
?>
<div id="ckcolorspalette" class="ckinterface">
	<h3><?php echo JText::_('CK_COLOR_PALETTE') ?></h3>
	<p><?php echo JText::_('CK_COLOR_PALETTE_DESC') ?></p>
	<?php
	foreach ($tables as $t) {
	?>
	<div style="width: 30%;float: left; margin-right: 2%;">
		<h4><?php echo JText::_('CK_COLOR_PALETTE_' . strtoupper($t)) ?></h4>
		<table data-selector="<?php echo $t ?>" style="width: 100%;vertical-align: middle;" class="ckedition cktable cktable-striped cktable-bordered cktable-hover">
		<?php
		for ($i=1;$i<=5;$i++) {
			?>
			<tr>
				<td style="width:5%"><span class="label"><?php echo $i ?></span></td>
				<td class="ckbutton-group">
					<?php if ($t == 'colorpalette') { ?>
					<input class="inputbox colorPicker" type="text" value="" name="palettecolor<?php echo $i ?>" id="palettecolor<?php echo $i ?>" size="6" style="width:70px;padding:5px;"/>
					<?php } else { ?>
					<input class="inputbox" type="text" value="" name="<?php echo $t . $i ?>" id="<?php echo $t . $i ?>" size="6" style="width:70px;padding:5px;" disabled/>
					<span class="ckbutton" onclick="ckCopyPaletteFrom(this)"><span class="fa fa-copy"></span></span>
					<?php } ?>
				</td>
			</tr>
		<?php
		}
		?>
		</table>
	</div>
	<?php
	}
	?>
</div>
<script>
	ckInitColorPickers();
</script>