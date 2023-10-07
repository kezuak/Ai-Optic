<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */

defined('_JEXEC') or die;
$input = new JInput();
$id = $input->get('ckid', '', 'string');
$componentParams = JComponentHelper::getParams('com_pagebuilderck');
require_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/menustyles.php';
$menustyles = new MenuStyles();
?>
<div class="menuck clearfix fixedck">
	<div class="inner clearfix">
		<div class="headerck">
			<span class="headerckicon cktip" title="<?php echo JText::_('CK_SAVE_CLOSE'); ?>" onclick="ckSaveEditionPanel(1);">×</span>
			<span class="headerckicon cksave cktip" title="<?php echo JText::_('CK_APPLY'); ?>"  onclick="ckSaveEditionPanel();"><span class="fa fa-check"></span></span>
			<span class="headercktext"><?php echo JText::_('CK_CSS_EDIT'); ?></span>
		</div>
		<div class="ckinterface">
			<div class="cktoolbarResponsive clearfix ckinterface" style="overflow:visible;background:none;padding:5px;text-align:center;">
				<span class="ckbutton-group">
					<span id="ckresponsive1button" class="ckbutton ckresponsivebutton cktip" style="width: auto;" onclick="ckSwitchResponsiveSmart(1)" data-range="1" title="<?php echo JText::_('CK_PHONE') ?> / <?php echo JText::_('CK_PORTRAIT') ?>"><span class="fa fa-mobile" ></span> </span>
					<input id="ckresponsive1value" type="hidden" value="<?php echo $componentParams->get('responsive1value', '320') ?>" data-default="" disabled="disabled" title="<?php echo JText::_('CK_SET_RESPONSIVE_VALUE_IN_OPTIONS') ?>" style="width:40px;"/>

					<span id="ckresponsive2button" class="ckbutton ckresponsivebutton cktip" style="width: auto;" onclick="ckSwitchResponsiveSmart(2)" data-range="2" title="<?php echo JText::_('CK_PHONE') ?> / <?php echo JText::_('CK_LANDSCAPE') ?>"><span class="fa fa-mobile" style="font-size: 1.4em;vertical-align: bottom;transform:rotate(90deg);"></span> </span>
					<input id="ckresponsive2value" type="hidden" value="<?php echo $componentParams->get('responsive2value', '480') ?>" class="cktip" data-default="" disabled="disabled" title="<?php echo JText::_('CK_SET_RESPONSIVE_VALUE_IN_OPTIONS') ?>" style="width:40px;"/>
				</span>
				<span class="ckbutton-group">
					<span id="ckresponsive3button" class="ckbutton ckresponsivebutton cktip" style="width: auto;" onclick="ckSwitchResponsiveSmart(3)" data-range="3" title="<?php echo JText::_('CK_TABLET') ?> / <?php echo JText::_('CK_PORTRAIT') ?>"><span class="fa fa-tablet" ></span> </span>
					<input id="ckresponsive3value" type="hidden" value="<?php echo $componentParams->get('responsive3value', '640') ?>" class="cktip" data-default="" disabled="disabled" title="<?php echo JText::_('CK_SET_RESPONSIVE_VALUE_IN_OPTIONS') ?>" style="width:40px;"/>
					<span id="ckresponsive4button" class="ckbutton ckresponsivebutton cktip" style="width: auto;" onclick="ckSwitchResponsiveSmart(4)" data-range="4" title="<?php echo JText::_('CK_TABLET') ?> / <?php echo JText::_('CK_LANDSCAPE') ?>"><span class="fa fa-tablet" style="font-size: 1.4em;vertical-align: bottom;transform:rotate(90deg);"></span> </span>
					<input id="ckresponsive4value" type="hidden" value="<?php echo $componentParams->get('responsive4value', '800') ?>" class="cktip" data-default="" disabled="disabled" title="<?php echo JText::_('CK_SET_RESPONSIVE_VALUE_IN_OPTIONS') ?>" style="width:40px;"/>
				</span>
				<span class="ckbutton-group">
					<span id="ckresponsive5button" class="ckbutton ckresponsivebutton ckbutton-warning active cktip" style="width: auto;" onclick="ckSwitchResponsiveSmart(5)" data-range="5" title="<?php echo JText::_('CK_COMPUTER') ?>"><span class="fa fa-desktop" ></span></span>
				</span>
			</div>
<?php
	$this->input = $input;
	// load the custom plugins
	JPluginHelper::importPlugin( 'pagebuilderck' );
//	$dispatcher = JEventDispatcher::getInstance();
	$otheritems = Pagebuilderck\CKFof::triggerEvent( 'onPagebuilderckLoadItemOptions' .  ucfirst(str_replace('.', '_', $this->cktype)) );

	if (count($otheritems) == 1) {
		// load only the first instance found, because each plugin type must be unique
		$layout = $otheritems[0];
		include_once($layout);
	} else {
		echo '<p style="text-align:center;color:red;font-size:14px;">' . JText::_('CK_EDITION_NOT_FOUND') . ' : ' . $this->cktype . '. Number of instances found : ' . count($otheritems) . '</p>';
	}
?>
			<div class="menulink" tab="tab_effects"><?php echo JText::_('CK_EFFECTS'); ?></div>
			<div class="tab menustyles ckproperty" id="tab_effects">
				<?php echo PagebuilderckHelper::showParamsMessage(true); ?>
				<div class="clr"></div>
			</div>
		</div>
	</div>
	</div>
<script>
	ckInitRangeInputs();
</script>