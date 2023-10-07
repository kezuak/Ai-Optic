<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
$app = JFactory::getApplication();
$objclass = $app->input->get('objclass', '');
//$saveFunc = $app->input->get('savefunc', 'ckSaveEditionPopup', 'cmd');
// get global component params
$params = JComponentHelper::getParams('com_pagebuilderck');
?>

<div class="menuck clearfix fixedck">
	<div class="inner clearfix">
		<div class="headerck">
			<span class="headerckicon cktip" title="<?php echo JText::_('CK_SAVE_CLOSE'); ?>" onclick="ckCloseEdition();">×</span>
			<span class="headercktext"><?php echo JText::_('CK_ROWWIDTH_EDIT'); ?></span>
		</div>
		<div id="elementscontainer">
			<div class="clr"></div>
			<div id="elementscontent" class="ckinterface" style="padding: 5px;">
				<p><a href="https://www.joomlack.fr/en/documentation/page-builder-ck/236-full-with-rows" target="_blank"><?php echo JText::_('CK_READ_DOCUMENTATION') ?></a></p>
				<div class="ckbutton-group" style="margin-top: 5px;">
					<input id="fullwidth" name="fullwidth" value="1" type="radio" onchange="ckSwitchFullwidthState('fullwidth');" />
					<label class="ckbutton btn" for="fullwidth" style="width:auto;margin-left:5px;" ><?php echo JText::_('CK_FULLWIDTH') ?></label>
					<input id="fixedwidth" name="fullwidth" value="0" type="radio" onchange="ckSwitchFullwidthState('fixedwidth');" />
					<label class="ckbutton btn" for="fixedwidth" style="width:auto;"><?php echo JText::_('CK_FIXEDWIDTH') ?></label>
				</div>
				<div id="fullwidthoptions">
					<div class="ckbutton-group" style="margin-top: 5px;">
						<input id="fullwidthoptionstd" name="fullwidthoption" value="std" type="radio" onchange="ckSwitchFullwidthType('standard');" />
						<label class="ckbutton btn" for="fullwidthoptionstd" style="width:auto;margin-left:5px;" ><?php echo JText::_('CK_FULLWIDTH_STANDARD') ?></label>
						<input id="fullwidthoptionjs" name="fullwidthoption" value="js" type="radio" onchange="ckSwitchFullwidthType('javascript');" />
						<label class="ckbutton btn" for="fullwidthoptionjs" style="width:auto;"><?php echo JText::_('CK_FULLWIDTH_JAVASCRIPT') ?></label>
					</div>
					<div id="fullwidthoptionstandard">
						<div class="ckalert ckalert-info"><?php echo JText::_('CK_FULLWIDTH_STANDARD_INFO') ?></div>
					</div>
					<div id="fullwidthoptionjavascript">
						<div class="ckalert ckalert-danger"><?php echo JText::_('CK_FULLWIDTH_JAVASCRIPT_ALERT') ?></div>
					</div>
				</div>
				<div id="fixedwidthoptions">
					<div class="ckbutton-group" style="margin-top: 5px;">
						<input id="fixedwidthoptionresolution" name="fixedwidthoption" value="resolution" type="radio" onchange="ckSwitchFixedwidthType('resolution');" />
						<label class="ckbutton btn" for="fixedwidthoptionresolution" style="width:auto;margin-left:5px;" ><?php echo JText::_('CK_FIXEDWIDTH_RESOLUTION') ?></label>
						<input id="fixedwidthoptiontck" name="fixedwidthoption" value="tck" type="radio" onchange="ckSwitchFixedwidthType('templatecreator');" />
						<label class="ckbutton btn" for="fixedwidthoptiontck" style="width:auto;"><?php echo JText::_('CK_FIXEDWIDTH_TEMPLATECREATOR') ?></label>
					</div>
					<div id="fixedwidthoptionresolutionvalue">
						<div class="ckalert ckalert-info"><?php echo JText::_('CK_FIXEDWIDTH_INFO') ?></div>
						<div class="menuckinfos"><?php echo JText::_('CK_RESOLUTION') ?></div>
						<input id="fixedwidthresolution" type="text" onchange="ckSwitchFixedwidthType();" style="margin-left:5px;" placeholder="<?php echo $params->get('fixedwidthresolution', '1000') ?>" />
					</div>
				</div>
			</div>
		</div>
<div class="clr"></div>
</div>
	</div>
<script language="javascript" type="text/javascript">
var focus = $ck('.editfocus');
function ckBeforeSaveEditionPopup() {
	// empty to avoid function from items to be called
}

function ckLoadEdition() {
	var maxwidth = focus.attr('data-fixedwidth');
	if (maxwidth) $ck('#elementscontainer #fixedwidthresolution').val(maxwidth);
	var focusinner = focus.find('> .inner');
	// fullwidth JS
	if (focus.hasClass('rowckfullwidth')) {
		$ck('#elementscontainer #fullwidth').click();
		$ck('#elementscontainer #fullwidthoptionjs').click();
//		ckSwitchFullwidthState('fullwidth');
//		ckSwitchFullwidthType('javascript');
	// fixed width component option
	} else if (focusinner.hasClass('pbck-container')) {
		$ck('#elementscontainer #fixedwidth').click();
		$ck('#elementscontainer #fixedwidthoptionresolution').click();
//		ckSwitchFullwidthState('fixedwidth');
//		ckSwitchFixedwidthType('resolution');
	// fixed width automatic with Template Creator CK
	} else if (focusinner.hasClass('tck-container')) {
		$ck('#elementscontainer #fixedwidth').click();
		$ck('#elementscontainer #fixedwidthoptiontck').click();
//		ckSwitchFullwidthState('fixedwidth');
//		ckSwitchFixedwidthType('templatecreator');
	// fixed width by value
	} else if (focus.attr('data-fixedwidth')) {
		$ck('#elementscontainer #fixedwidth').click();
		$ck('#elementscontainer #fixedwidthoptionresolution').click();
		ckSwitchFullwidthState('fixedwidth');
		ckSwitchFixedwidthType('resolution');
	// standard
	} else {
		$ck('#elementscontainer #fullwidth').click();
		$ck('#elementscontainer #fullwidthoptionstd').click();
		ckSwitchFullwidthState('fullwidth');
		ckSwitchFullwidthType('standard');
	}

	
}

function ckSwitchFullwidthState(type) {
	if (type == 'fixedwidth') {
		$ck('#fullwidthoptions').hide();
		$ck('#fixedwidthoptions').show();
		ckSwitchFixedwidthType();
	} else {
		$ck('#fullwidthoptions').show();
		$ck('#fixedwidthoptions').hide();
		ckSwitchFullwidthType();
	}
}

function ckSwitchFullwidthType(type) {
	if (type == 'javascript') {
		$ck('#fullwidthoptionstandard').hide();
		$ck('#fullwidthoptionjavascript').show();
		ckAddFullWidthJs();
	} else {
		$ck('#fullwidthoptionstandard').show();
		$ck('#fullwidthoptionjavascript').hide();
		ckremoveAllWidths();
	}
}
function ckSwitchFixedwidthType(type) {
	$ck('#elementscontainer #fullwidthoptionstd').click();
	if (type == 'templatecreator') {
		$ck('#fixedwidthoptionresolutionvalue').hide();
		ckAddFixedWidthTemplatecreator();
	} else {
		$ck('#fixedwidthoptionresolutionvalue').show();
		$ck('#elementscontainer #fixedwidthoptionresolution').click();
		var resolutionvalue = $ck('#elementscontainer #fixedwidthresolution').val();
		if (resolutionvalue) {
			ckAddFixedWidthResolutionValue();
		} else {
			ckAddFixedWidthResolutionDefault();
		}
	}
}

function ckAddFixedWidthResolutionValue() {
	ckremoveAllWidths();
	var resolutionvalue = $ck('#elementscontainer #fixedwidthresolution').val();
	focus.attr('data-fixedwidth', resolutionvalue);
	focus.find('> .inner').css('max-width', ckTestUnit(resolutionvalue));
}

function ckAddFixedWidthResolutionDefault() {
	ckremoveAllWidths();
	focus.find('> .inner').addClass('pbck-container');
}

function ckAddFixedWidthTemplatecreator() {
	ckremoveAllWidths();
	focus.find('> .inner').addClass('tck-container');
}

function ckAddFullWidthJs() {
	ckToggleFullwidthRow(focus.attr('id'), 1);
}

function ckremoveAllWidths() {
	focus
		.removeAttr('data-fixedwidth');
	focus.find('> .inner')
		.removeClass('pbck-container')
		.removeClass('tck-container')
		.css('max-width', '');
	ckToggleFullwidthRow(focus.attr('id'), 0);
}

ckLoadEdition();
ckInitColorPickers();
ckInitOptionsTabs();
ckInitAccordions();
</script>