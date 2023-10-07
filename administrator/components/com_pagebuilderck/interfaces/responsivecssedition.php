<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

$objid = $this->input->get('ckobjid', '');
$fields = $this->input->get('fields', '', 'string');
$fields = json_decode($fields);

$prefix = 'bloc';
$blocfontsize = isset($fields->blocfontsize) ? $fields->blocfontsize : '';
$blocmarginleft = isset($fields->blocmarginleft) ? $fields->blocmarginleft : '';
$blocmarginright = isset($fields->blocmarginright) ? $fields->blocmarginright : '';
$blocmargintop = isset($fields->blocmargintop) ? $fields->blocmargintop : '';
$blocmarginbottom = isset($fields->blocmarginbottom) ? $fields->blocmarginbottom : '';
$blocpaddingleft = isset($fields->blocpaddingleft) ? $fields->blocpaddingleft : '';
$blocpaddingright = isset($fields->blocpaddingright) ? $fields->blocpaddingright : '';
$blocpaddingtop = isset($fields->blocpaddingtop) ? $fields->blocpaddingtop : '';
$blocpaddingbottom = isset($fields->blocpaddingbottom) ? $fields->blocpaddingbottom : '';
?>
<div class="menuck clearfix fixedck">
	<div class="inner clearfix">
		<div class="headerck">
			<span class="headerckicon cktip" title="<?php echo JText::_('CK_SAVE_CLOSE'); ?>" onclick="ckRenderResponsiveCss();ckCloseEdition();">×</span>
			<span class="headerckicon cksave cktip" title="<?php echo JText::_('CK_APPLY'); ?>" onclick="ckRenderResponsiveCss();"><span class="fa fa-check"></span></span>
			<span class="headercktext"><?php echo JText::_('CK_CSS_EDIT'); ?></span>
		</div>
		<div id="elementscontainer">
			<div class="clr"></div>
			<div id="elementscontent" class="ckinterface">
				<div class="ckproperty" id="tab_blocstyles">
					<div class="menustylesblock" style="height: calc(100vh - 73px);padding: 10px 5px;">
						<div class="menupaneblock">
							<div class="menupanetitle" style="float:left;width: 78px;"><?php echo JText::_('CK_POLICE'); ?></div>
							<span>
								<input class="inputbox" style="width: 50px;" name="<?php echo $prefix; ?>fontsize" id="<?php echo $prefix; ?>fontsize" type="text" placeholder="<?php echo $blocfontsize ?>"/>
								<div style="text-align:left;display:inline;"><?php echo JText::_('CK_SIZE'); ?></div>
							</span>
						</div>
						<div class="menupaneblock">
								<div class="menupanetitle"><?php echo JText::_('CK_MARGINS'); ?></div>
								<div class="menupaneblock">
									<div><div style="width:45px;float:left;text-align:right;margin-right:10px;"><?php echo JText::_('CK_TOP'); ?></div><div style="float:left;text-align:right;margin-right:5px;"><img src="<?php echo $this->imagespath; ?>margintop.png" width="23" height="23" align="top" /></div><input class="inputbox" type="text" name="<?php echo $prefix; ?>margintop" id="<?php echo $prefix; ?>margintop" size="1" value="" placeholder="<?php echo $blocmargintop ?>" /></div>
									<div><div style="width:45px;float:left;text-align:right;margin-right:10px;"><?php echo JText::_('CK_RIGHT'); ?></div><div style="float:left;text-align:right;margin-right:5px;"><img src="<?php echo $this->imagespath; ?>marginright.png" width="23" height="23" align="top" /></div><input class="inputbox" type="text" name="<?php echo $prefix; ?>marginright" id="<?php echo $prefix; ?>marginright" size="1" value="" placeholder="<?php echo $blocmarginright ?>"/></div>
									<div><div style="width:45px;float:left;text-align:right;margin-right:10px;"><?php echo JText::_('CK_BOTTOM'); ?></div><div style="float:left;text-align:right;margin-right:5px;"><img src="<?php echo $this->imagespath; ?>marginbottom.png" width="23" height="23" align="top" /></div><input class="inputbox" type="text" name="<?php echo $prefix; ?>marginbottom" id="<?php echo $prefix; ?>marginbottom" size="1" value=""  placeholder="<?php echo $blocmarginbottom ?>"/></div>
									<div><div style="width:45px;float:left;text-align:right;margin-right:10px;"><?php echo JText::_('CK_LEFT'); ?></div><div style="float:left;text-align:right;margin-right:5px;"><img src="<?php echo $this->imagespath; ?>marginleft.png" width="23" height="23" align="top" /></div><input class="inputbox" type="text" name="<?php echo $prefix; ?>marginleft" id="<?php echo $prefix; ?>marginleft" size="1" value="" placeholder="<?php echo $blocmarginleft ?>"/></div>
								</div>
								<div class="menupaneblock">
									<div style="width:21px;float:left;text-align:right;margin:7px 0 0 0;"><img src="<?php echo $this->imagespath; ?>all_margins.png" width="21" height="98" /></div>
									<div style="float:left;text-align:left;margin:42px 0 0 5px;"><input class="inputbox" type="text" name="<?php echo $prefix; ?>margins" id="<?php echo $prefix; ?>margins" size="1" value="" /><div style="width:25px;float:right;text-align:left;margin-left:3px;"></div></div>
								</div>
							</div>
							<div class="menupaneblock">
								<div class="menupanetitle"><?php echo JText::_('CK_PADDINGS'); ?></div>
								<div class="menupaneblock">
									<div><div style="width:45px;float:left;text-align:right;margin-right:5px;"><?php echo JText::_('CK_TOP'); ?></div><div style="float:left;text-align:right;margin:5px 8px 0 11px;"><img src="<?php echo $this->imagespath; ?>paddingtop.png" width="15" height="15" align="top" /></div><input class="inputbox" type="text" name="<?php echo $prefix; ?>paddingtop" id="<?php echo $prefix; ?>paddingtop" size="1" value="" placeholder="<?php echo $blocpaddingtop ?>"/></div>
									<div><div style="width:45px;float:left;text-align:right;margin-right:5px;"><?php echo JText::_('CK_RIGHT'); ?></div><div style="float:left;text-align:right;margin:5px 8px 0 11px;"><img src="<?php echo $this->imagespath; ?>paddingright.png" width="15" height="15" align="top" /></div><input class="inputbox" type="text" name="<?php echo $prefix; ?>paddingright" id="<?php echo $prefix; ?>paddingright" size="1" value="" placeholder="<?php echo $blocpaddingright ?>"/></div>
									<div><div style="width:45px;float:left;text-align:right;margin-right:5px;"><?php echo JText::_('CK_BOTTOM'); ?></div><div style="float:left;text-align:right;margin:5px 8px 0 11px;"><img src="<?php echo $this->imagespath; ?>paddingbottom.png" width="15" height="15" align="top" /></div><input class="inputbox" type="text" name="<?php echo $prefix; ?>paddingbottom" id="<?php echo $prefix; ?>paddingbottom" size="1" value=""  placeholder="<?php echo $blocpaddingbottom ?>"/></div>
									<div><div style="width:45px;float:left;text-align:right;margin-right:5px;"><?php echo JText::_('CK_LEFT'); ?></div><div style="float:left;text-align:right;margin:5px 8px 0 11px;"><img src="<?php echo $this->imagespath; ?>paddingleft.png" width="15" height="15" align="top" /></div><input class="inputbox" type="text" name="<?php echo $prefix; ?>paddingleft" id="<?php echo $prefix; ?>paddingleft" size="1" value="" placeholder="<?php echo $blocpaddingleft ?>" /></div>
								</div>
								<div class="menupaneblock">
									<div style="width:21px;float:left;text-align:right;margin:7px 0 0 0;"><img src="<?php echo $this->imagespath; ?>all_paddings.png" width="15" height="98" /></div>
									<div style="float:left;text-align:left;margin:42px 0 0 5px;"><input class="inputbox" type="text" name="<?php echo $prefix; ?>paddings" id="<?php echo $prefix; ?>paddings" size="1" value="" /><div style="width:20px;float:right;text-align:left;margin-left:3px;"></div></div>
								</div>
							</div>
							<div class="clr"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="clr"></div>
	</div>
</div>
<script language="javascript" type="text/javascript">
ckInitColorPickers();
ckInitOptionsTabs();
ckInitAccordions();
function ckBeforeSaveEditionPopup() {
	// empty to avoid function from items to be called
}
</script>
<?php
exit();