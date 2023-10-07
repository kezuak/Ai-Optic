<?php
/**
 * @copyright	Copyright (C) 2016 Cédric KEIFLIN alias ced1870
 * https://www.joomlack.fr
 * @license		GNU/GPL
 * */

defined('_JEXEC') or die;
//if (! defined('CK_LOADED')) define('CK_LOADED', 1);

require_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/ckfof.php';
use Pagebuilderck\CKFof;

/**
 * Editor Pagebuilderckeditor button
 *
 */
class PlgButtonPagebuilderckeditor extends JPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;

	/**
	 * Pagebuilderckeditor button
	 *
	 * @param   string  $name  The name of the button to add
	 *
	 */
	public function onDisplay($name)
	{

		// check the name of the editor, if ckeditor then we must not load this button because we are already in the pagebuilder
		if ($name == 'ckeditor') return;

		$doc = JFactory::getDocument();
		$app = JFactory::getApplication();
		$input = $app->input;
		$conf = JFactory::getConfig();
		$css = "";

		$user = JFactory::getUser();

		// authorize only in article edition admin and front, if the page builder ck editor has been allowed, comes from the system plugin
		if ($conf->get('pagebuilderck_allowed') != '1')
			return;

		// loads the language files from the component frontend
		$lang = JFactory::getLanguage();
		$lang->load('com_pagebuilderck', JPATH_SITE . '/components/com_pagebuilderck', $lang->getTag(), false);
		$lang->load('com_pagebuilderck', JPATH_SITE, $lang->getTag(), false);

		// for 3rd party integration
		$thirdPartyIntegrations = PagebuilderckHelper::getThirdPartyIntegrations();
		$attribsVar = $thirdPartyIntegrations['attribs'];
		$adminForm = $thirdPartyIntegrations['adminForm'];
		$fieldsname = $thirdPartyIntegrations['fieldsname'];

		if ($input->get('option', '') !== 'com_content'
			&& $input->get('option', '') !== 'com_flexicontent'
			) {
			// if the field is not allowed in the list, don't show the button
			if ($name !== 'jform_' . $fieldsname) return;
			// if the integration params of PBCK does not allow, don't show the button
			if ($conf->get('pagebuilderck_allowed_' . $fieldsname, '1') == '0') return;
		}

		// if the page builder ck editor must be used
		if ($input->get('pbck', '0') == '1') {
			$short_name = str_replace('jform_', '', $name);
			if ($conf->get('pagebuilderck_allowed_' . $short_name) != '1')
				return;

			$conf = JFactory::getConfig();
			// Get the text filter data
			$params          = JComponentHelper::getParams('com_config');
			$filters = CKFof::convertObjectToArray($params->get('filters'));
			// check if the user has the correct rights on the filters to save the article
			if (! in_array('8', $user->groups)) { // checks for super user group
				foreach($user->groups as $g) {
					if (isset($filters[$g])) {
						$filters[$g] = CKFof::convertObjectToArray($filters[$g]);
						if ($filters[$g]['filter_type'] === 'BL') $app->enqueueMessage('PAGE BUILDER CK  : ' . JText::_('CK_WARNING_USERGROUP_FILTERTYPE_BLACKLIST'), 'error');
					}
//					if ($filters[$g]['filter_type'] === 'WL' && strpos($filters[$g]['filter_tags'], 'style') === false) $app->enqueueMessage('PAGE BUILDER CK  : ' . JText::_('CK_WARNING_USERGROUP_FILTERTYPE_WHITELIST_NOSTYLE'), 'error');
				}
			}

			include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/menustyles.php';
			include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/stylescss.php';
//			include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/ckeditor.php';
			include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/pagebuilderck.php';
			include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/ckmodel.php';

			// get instance of the editor to load the css / js in the page
			// $this->ckeditor = PagebuilderckHelper::loadEditor();
			// need the tinymce instance for the items edition
			// Load the editor Tinymce or JCE
			$editor = $conf->get('pagebuilderck_replaced_editor') == 'jce' ? 'jce' : 'tinymce';
			$editor = JEditor::getInstance($editor);
			$editor->display('ckeditor', $html = '', $width = '', $height = '200px', $col='', $row='', $buttons = true, $id = 'ckeditor');
			// Get an instance of the model
//			JModelLegacy::addIncludePath(JPATH_SITE . '/administrator/components/com_pagebuilderck/models', 'PagebuilderckModel');
			$model = Pagebuilderck\CKModel::getInstance('Elements', 'Pagebuilderck');
			// $model = $this->getModel('Elements', '', array());
			$this->elements = $model->getItems();

			str_replace('"', '\"', include_once(JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/views/page/tmpl/include.php'));
			str_replace('"', '\"', include_once(JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/views/page/tmpl/submitform.php'));
			str_replace('"', '\"', include_once(JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/views/page/tmpl/menu.php'));
			str_replace('"', '\"', include_once(JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/views/page/tmpl/toolbar.php'));
			str_replace('"', '\"', include_once(JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/views/page/tmpl/contextmenu.php'));

			// force the inclusion of the field with the value 1
			echo '<input id="jform_attribs_pagebuilderck_editor" type="hidden" value="1" name="jform[attribs][pagebuilderck_editor]">';

			// section specific to content types
			$iscontenttype = $input->get('iscontenttype', 0, 'int');
			// force state with url variable, for debugging purposes
			$iscontenttype = isset($_GET['iscontenttype']) ? $_GET['iscontenttype'] : $iscontenttype;

			echo '<input id="jform_attribs_pagebuilderck_iscontenttype" type="hidden" value="' . $iscontenttype . '" name="jform[attribs][pagebuilderck_iscontenttype]">';
			$contenttype = $input->get('contenttype', '', 'string');
			/*if ($iscontenttype === 1) {
				JPluginHelper::importPlugin( 'pagebuilderck' );
				ob_start();
				Pagebuilderck\CKFof::triggerEvent( 'onPagebuilderckLoadItemContent' . ucfirst($contenttype) );
				$contenttypehtml = ob_get_contents();
				ob_end_clean();
				$contenttypehtml = str_replace("'", "\'", $contenttypehtml);
				$contenttypehtml = str_replace("\n", "", $contenttypehtml);
				$contenttypehtml = str_replace("\r", "", $contenttypehtml);
			} else {
				$contenttypehtml = '';
			}*/

			$js1 = "
			function pbckeditorLoadEditor(name) {
				var cont = jQuery(name).parent();
				// cont.css('display', 'none');
				var html = '<div id=\"workspaceparentck\">'
								+'<div id=\"workspaceck\" class=\"pagebuilderck workspaceck" . ($input->get('iscontenttype', 0, 'int') === 1 ? ' ckiscontenttype' : '') ."\">'
								+'</div>'
							+'</div>';
				// load the page builder workspace and hide the textarea
				//cont.children().hide();
				cont.append(html);

				// fix for RSFirewall
				jQuery(name).val(jQuery(name).val().replace(/<s-tyle/g, '<style'));
				jQuery(name).val(jQuery(name).val().replace(/s-tyle>/g, 'style>'));

				var injectContentToPbck = false;
				// test if this is a new content or already created with page builder
				if (jQuery(name).length && ! jQuery(name).val().includes('rowck')) {
					injectContentToPbck = true;
				}

				jQuery('#workspaceparentck').prepend(jQuery('#context-menu-ck'));
				if (jQuery(name).length && ! injectContentToPbck) {
					jQuery('#workspaceck').html(jQuery(name).val().replace(/\|URIROOT\|/g, '" . JUri::root(true) . "'));
				}

				ckInitWorkspace();
				// manage content creation if is a content type
				if (PAGEBUILDERCK.ISCONTENTTYPE == '1' && injectContentToPbck) {
					var workspace = ckGetWorkspace();
					var newrowid = ckGetUniqueId('row_');
					var newrow = ckHtmlRow(newrowid);
					workspace.append(newrow);
					var newblockid = ckGetUniqueId('block_');
					var newblock = ckHtmlBlock(newblockid);
					jQuery('> .inner', newrow).append(newblock);
					ckInitBlocksSize(newrow);

					jQuery('#workspaceck .blockck > .inner > .innercontent').addClass('ckfocus');
					ckAddItem('" . $contenttype . "');
				}
				// manage content creation for a standard article
				else if (injectContentToPbck) {
					jQuery('#workspaceck .blockck > .inner > .innercontent').addClass('ckfocus');
					ckAddItem('text');
					
					// Override to get the appended text ID and update the data
					/*function ckTriggerAfterAdditem(id) {
						var content = jQuery('#" . $name . "').val();
						content = ckEditorToContent(content);
						jQuery('#'+id+' > .inner').html(content);
					}*/
				}
				
				cont.css('display', '');
				
				// adds the settings in JS to be sure that it is at the end of the front end form
				jQuery('#" . $adminForm . "').append('<input id=\"jform_" . $attribsVar . "_pagebuilderck_editor\" type=\"hidden\" value=\"1\" name=\"jform[" . $attribsVar . "][pagebuilderck_editor]\">');
				jQuery('#adminForm').append('<input id=\"jform_attribs_pagebuilderck_editor\" type=\"hidden\" value=\"1\" name=\"jform[attribs][pagebuilderck_editor]\">');
				jQuery('#adminForm').append('<input id=\"jform_attribs_pagebuilderck_iscontenttype\" type=\"hidden\" value=\"" . $iscontenttype . "\" name=\"jform[attribs][pagebuilderck_iscontenttype]\">');
			}
			
			
			// Override to get the appended text ID and update the data
			function ckTriggerAfterAdditem(id) {
				var content = jQuery('#" . $name . "').val();
				content = ckEditorToContent(content);
				jQuery('#'+id+' > .inner').html(content);
			}

			JoomlaCK.beforesubmitbutton = function(task) {
				// check if the function exists, loads it
				if (typeof ckBeforeSaveWorkspace == 'function') { ckBeforeSaveWorkspace(); }

				var workspace = jQuery('#workspaceck');
			
				jQuery('#" . $name . "').val(workspace.html());

				// JoomlaCK.submitbutton(task);
			}
			";

			echo "<script>" . $js1 . "</script>";
			
			$css .= "#" . $name . ",
#" . $name . " + #editor-xtd-buttons,
#editor-xtd-buttons,
.editor-xtd-buttons
 {
	display: none;
}";
		}

		// construct the JS code to manage the operations
		$js2 = "
			jQuery(document).ready(function (){
				if (" . $input->get('pbck', '0') . " != '1') pbckeditorLoadEditorButton('#" . $name . "');
				if (" . $input->get('pbck', '0') . " == '1') pbckeditorLoadEditor('#" . $name . "');
			});

			function pbckeditorLoadEditorButton(name) {
				var cont = jQuery(name).parent();
				cont.before('<a class=\"btn pbckswitch btn-secondary\" onclick=\"pbckLoadPagebuilderckEditor()\"><i class=\"icon icon-loop\"></i>&nbsp;" . JText::_('CK_LOAD_PAGEBUILDERCK_EDITOR', true) . "</a>');
			}

			function pbckLoadPagebuilderckEditor() {				
				var beSure = confirm('" . JText::_('CK_CONFIRM_PAGEBUILDERCK_EDITOR', true) . "');
				if (!beSure) return;

				window.location.search += '&pbck=1';
			}
			";
		$doc->addScriptDeclaration($js2);

		$css .= ".pbckswitch {
	margin: 5px 0;
}";
		$doc->addStyleDeclaration($css);

if ($input->get('option', '') == 'com_flexicontent') $editor = $conf->set('editor', $conf->get('pagebuilderck_replaced_editor'));
		return;
	}
}
