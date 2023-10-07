<?php
/**
 * @copyright	Copyright (C) 2011 Cedric KEIFLIN alias ced1870
 * http://www.joomlack.fr
 * @license		GNU/GPL
 * */

defined('JPATH_PLATFORM') or die;

use Pagebuilderck\CKFof;

if (!defined('PAGEBUILDERCK_MEDIA_URI'))
{
	define('PAGEBUILDERCK_MEDIA_URI', JUri::root(true) . '/media/com_pagebuilderck');
}

class JFormFieldPagebuilderck extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 *
	 * @since  11.1
	 */
	protected $type = 'Pagebuilderck';

	protected function getPathToElements() {
		$localpath = dirname(__FILE__);
		$rootpath = JPATH_ROOT;
		$httppath = trim(JURI::root(), "/");
		$pathtoelements = str_replace("\\", "/", str_replace($rootpath, $httppath, $localpath));
		return $pathtoelements;
	}

	/* 
	 * Render the input only if we are in frontend editing because the renderField method does not work
	 */
	protected function getInput() {
		$app = JFactory::getApplication();
		$doc = JFactory::getDocument();
		if (CKFof::isSite()) {
			$doc->addStyleDeclaration('
				.pagebuilderckfrontend #workspaceparentck:not(.collapsedck) #workspaceck {
					margin-left: 20px;
				}
				
				.pagebuilderckfrontend #workspaceck {
					margin-left: -120px;
				}

				#options .accordion-body.collapse:not(.in) #menuck {
					display: none;
				}

				#options .accordion-body.collapse.in {
					height: auto !important;
				}
			');
			echo '<div class="pagebuilderckfrontend">';
			$this->renderField();
			echo '</div>';
		}
	}

	public function renderField($options = array()) {
		include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/ckfof.php';
		$app = JFactory::getApplication();
		$this->input = CKFof::getInput();

		// loads the language files from the frontend
		// loads the language files from the component frontend
		$lang = JFactory::getLanguage();
		$lang->load('com_pagebuilderck', JPATH_SITE . '/components/com_pagebuilderck', $lang->getTag(), false);
		$lang->load('com_pagebuilderck', JPATH_SITE, $lang->getTag(), false);
?>
		<input type="hidden" name="<?php echo $this->name ?>" id="<?php echo $this->id ?>" />
		<style>
			iframe {border:none;}
			.pagebuilderck_container {resize:vertical;overflow:auto;}
			/* for joomla 4 */
			#fieldset-edition [class*="column-count-"] { column-count: initial; }
			#fieldset-edition [class*=" column-"] > div, #fieldset-edition [class^="column-"] > div { display: block; }
			#fieldset-edition [class*=" column-"] .modal { display: none; }
		</style>

		<?php if (CKFof::isSite()) { ?>
		<div class="ckbutton ckbutton-primary" style="display: block;" onclick="ckModuleEditFullScreen()"><?php echo JText::_('CK_EDIT_FULLSCREEN') ?></div>
		<?php } ?>
		<div class="pagebuilderck_container"></div>
		<?php
		include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/menustyles.php';
		include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/stylescss.php';
		include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/ckeditor.php';
		include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/pagebuilderck.php';

		$this->value = str_replace("|URIROOT|", JUri::root(true), $this->value);
		// get instance of the editor to load the css / js in the page
//		$this->ckeditor = PagebuilderckHelper::loadEditor();
		// need the tinymce instance for the items edition
		$editor = JFactory::getConfig()->get('editor') == 'jce' ? 'jce' : 'tinymce';
		$editor = JEditor::getInstance($editor);
		$editor->display('ckeditor', $html = '', $width = '', $height = '200px', $col='', $row='', $buttons = true, $id = 'ckeditor');
		// Get an instance of the model
//		JModelLegacy::addIncludePath(JPATH_SITE . '/administrator/components/com_pagebuilderck/models', 'PagebuilderckModel');
		$model = CKFof::getModel('Elements');

		// $model = $this->getModel('Elements', '', array());
		$this->elements = $model->getItems();
		include_once(JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/views/page/tmpl/include.php');
		include_once(JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/views/page/tmpl/submitform.php');
		?>
		<script src="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/mod_pagebuilderck_edition.js" type="text/javascript"></script>
		<script type="text/javascript">
			JoomlaCK.beforesubmitbutton = function() {
				var workspace = $ck('#workspaceck');
				$ck('#<?php echo $this->id ?>').attr('value', workspace.html());
			}
		</script>
		<div id="workspaceparentck">
			<?php include_once(JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/views/page/tmpl/menu.php'); ?>
			<?php include_once(JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/views/page/tmpl/toolbar.php'); ?>
			<div id="workspaceck" class="pagebuilderck workspaceck">
				<?php
				if ($this->value) {
					echo $this->value;
				} else { ?>
					<div class="googlefontscall"></div>
				<?php }
				?>
			</div>
		</div>
		<?php
		include PAGEBUILDERCK_PATH . '/views/page/tmpl/contextmenu.php';
		return;
	}
}
