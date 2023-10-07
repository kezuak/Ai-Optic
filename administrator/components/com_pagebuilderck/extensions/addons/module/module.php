<?php
/**
 * @copyright	Copyright (C) 2015 Cédric KEIFLIN alias ced1870
 * https://www.template-creator.com
 * https://www.joomlack.fr
 * @license		GNU/GPL
 * */
 
defined('_JEXEC') or die('Restricted access');
jimport('joomla.event.plugin');

class plgPagebuilderckModule extends JPlugin {

	private $context = 'PLG_PAGEBUILDERCK_MODULE';

	private $type = 'module';

	function __construct(&$subject, $params) {

		parent::__construct($subject, $params);
	}

	/* 
	 * Construct the Menu Item to drag into the interface
	 *
	 * Return Object with item data
	 */
	public function onPagebuilderckAddItemToMenu() {
		// load the language files of the plugin
		$this->loadLanguage();
		// create the menu item
		$menuitem = new stdClass();
		$menuitem->type = $this->type;
		$menuitem->group = 'other';
		$menuitem->title = JText::_($this->context . '_MENUITEM_TITLE');
		$menuitem->description = JText::_($this->context . '_MENUITEM_DESC');
		$menuitem->image = JUri::root(true) . '/plugins/pagebuilderck/module/assets/images/module.png';

		return $menuitem;
	}

	/* 
	 * Display the html code for the item to be used into the interface
	 *
	 * Return String the html code
	 */
	public function onPagebuilderckLoadItemContentModule() {
		$input = JFactory::getApplication()->input;
		$id = $input->get('ckid', '', 'string');
		// ckstyle and inner classes are needed to get the styles from the interface
		?>
		<div id="<?php echo $id; ?>" class="cktype" data-type="module" >
			<div class="ckstyle">
			</div>
			<div class="moduleck inner">
				<div class="moduleck_content">
					<svg class="moduleck_icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M488.6 250.2L392 214V105.5c0-15-9.3-28.4-23.4-33.7l-100-37.5c-8.1-3.1-17.1-3.1-25.3 0l-100 37.5c-14.1 5.3-23.4 18.7-23.4 33.7V214l-96.6 36.2C9.3 255.5 0 268.9 0 283.9V394c0 13.6 7.7 26.1 19.9 32.2l100 50c10.1 5.1 22.1 5.1 32.2 0l103.9-52 103.9 52c10.1 5.1 22.1 5.1 32.2 0l100-50c12.2-6.1 19.9-18.6 19.9-32.2V283.9c0-15-9.3-28.4-23.4-33.7zM358 214.8l-85 31.9v-68.2l85-37v73.3zM154 104.1l102-38.2 102 38.2v.6l-102 41.4-102-41.4v-.6zm84 291.1l-85 42.5v-79.1l85-38.8v75.4zm0-112l-102 41.4-102-41.4v-.6l102-38.2 102 38.2v.6zm240 112l-85 42.5v-79.1l85-38.8v75.4zm0-112l-102 41.4-102-41.4v-.6l102-38.2 102 38.2v.6z"/></svg>
					<h4 class="modulerow_title"><?php echo JText::_('CK_MODULE') ?></h4>
					<?php echo JText::_('CK_MODULE_NOT_SELECTED') ?>
				</div>
			</div>
		</div>
		<?php
	}

	/* 
	 * Load the interface for the item edition
	 *
	 * Return String the html code
	 */
	public function onPagebuilderckLoadItemOptionsModule() {
		// load the language files of the plugin
		$this->loadLanguage();
		// load the interface for the options
		$tpl = JPATH_SITE . '/plugins/pagebuilderck/module/layouts/edit_module.php';
		return $tpl;
	}

	/* 
	 * Display the html code for the item to be used into the frontend page
	 * @param string the item object from simple_html_dom
	 * 
	 * Return String the html code
	 */
	public function onPagebuilderckRenderItemModule($item) {
		return $item->innertext;
	}
}