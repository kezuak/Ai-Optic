<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */


// No direct access
defined('_JEXEC') or die;

use Pagebuilderck\CKController;
use Pagebuilderck\CKFof;

class PagebuilderckController extends CKController
{
	static $releaseNotes;

	static $currentVersion;

	public function display($cachable = false, $urlparams = false)
	{
		// load the views only in backend
		if (CKFof::isAdmin()) {
			$view	= $this->input->get('view', 'pages');
			$this->input->set('view', $view);
		}
		parent::display();

		return $this;
	}

	/**
	 * Load the backup file
	 * 
	 * @return string, the html content
	 */
	public function ajaxDoRestoration() {
		// security check
		CKFof::checkAjaxToken();

		$id = $this->input->get('id', 0, 'int');
		$name = $this->input->get('name','', 'string');
		$isLocked = $this->input->get('isLocked', 0, 'int');
		$filename = ($isLocked ? 'locked' : 'backup') . '_' . $id . '_' . $name . '.pbck';
		$path = JPATH_ROOT . '/administrator/components/com_pagebuilderck/backup/' . $id . '_bak';
		$content = file_get_contents($path . '/' . $filename);
		$backup = json_decode($content);

		echo str_replace('|URIROOT|', JUri::root(true), $backup->htmlcode);
		exit();
	}

	/**
	 * Load a method from the Page Builder CK Params
	 * 
	 * @return mixed, the method return
	 */
	public function ajaxParamsCall() {
		// security check
		CKFof::checkAjaxToken();

		$class = $this->input->get('class', '', 'cmd');
		$method = $this->input->get('method', '', 'cmd');
		$params = $this->input->get('params', '', 'array');
		if ($paramsClass = PagebuilderckHelper::getParams($class)) {
			echo $paramsClass->$method($params);
		}
		exit();
	}

	/**
	 * Switch between lock / unlock state for the backup file
	 * 
	 * @return string, the html content
	 */
	public function ajaxToggleLockBackup() {
		// security check
		CKFof::checkAjaxToken();

		$id = $this->input->get('id', 0, 'int');
		$isLocked = $this->input->get('isLocked', 0, 'int');
		$filename = $this->input->get('filename', '', 'string');
		// locked_38_09-02-2016-11-30-13
		$filename = ($isLocked ? 'locked' : 'backup') . '_' . $id . '_' . $filename . '.pbck';
		$file = JPATH_ROOT . '/administrator/components/com_pagebuilderck/backup/' . $id . '_bak/' . $filename;
		// $isLocked = stristr($file, 'locked_');
		if ($isLocked) {
			$newFilename = str_replace('locked_', 'backup_', $filename);
		} else {
			$newFilename = str_replace('backup_', 'locked_', $filename);
		}
		$toFile = JPATH_ROOT . '/administrator/components/com_pagebuilderck/backup/' . $id . '_bak/' . $newFilename;

		if (@JFile::move($file, $toFile)) {
			echo '1';
		} else {
			echo '0';
		}
		exit();
	}

	/**
	 * Get the page html code from it id
	 * 
	 * @return string, the html code
	 */
	public function ajaxLoadPageHtml() {
		// security check
		CKFof::checkAjaxToken();

		PagebuilderckHelper::ajaxLoadPageHtml();
	}

	/**
	 * Get the page html code from its id
	 * 
	 * @return string, the html code
	 */
	public static function ajaxLoadLibraryHtml() {
		// security check
		CKFof::checkAjaxToken();

		$id = $this->input->get('id', 0, 'string');
//		$page = PagebuilderckHelper::getPage($id);
		$url = 'https://media.joomlack.fr/api/pagebuilderck/page/' . $id;

		try {
			$file = file_get_contents($url);
		} catch (Exception $e) {
			echo 'ERROR : ',  $e->getMessage(), "\n";
			exit();
		}
		$file = json_decode($file);
		if (isset($file->htmlcode)) {
			echo trim($file->htmlcode);
		} else {
			echo 'error';
		}
		exit();
	}

	/*
	 * Check that the user can do
	 */
	public function checkAjaxUserEditRight() {
		$user		= JFactory::getUser();
		$canEdit    = $user->authorise('core.edit', 'com_pagebuilderck');
		if (! $canEdit) {
			echo '{"status": "0", "msg": "' . JText::_('CK_ERROR_USER_NO_AUTH') . '"}';
			exit();
		}
	}

	/*
	 * Save the current styles into a favorite file
	 */
	public function ajaxSaveFavorite() {
		// security check
		CKFof::checkAjaxToken();

		$this->checkAjaxUserEditRight();

		$blocs = $this->input->get('favorite', null, null);
		$id = $this->input->get('id', -1, 'int');
		$path = PAGEBUILDERCK_PARAMS_PATH . '/favorites';

		$error = 0;
		if (is_numeric($id) && (int) $id > -1) {
			$i = (int) $id;
		} else {
			$i = count(JFolder::files($path, '.fck3'));
			$j = 0;
			while (file_exists(PAGEBUILDERCK_PARAMS_PATH . '/favorites/favorite'.$i.'.fck3') && $j < 1000) {
				$i++;
				$j++;
			}
			if ($j >= 1000) {
				echo 'ERROR reach loop of 1000 files';
				$error = 1;
			}
		}

		$exportfiledest = PAGEBUILDERCK_PARAMS_PATH . '/favorites/favorite'.$i.'.fck3';
		$exportfiletext = $blocs;

		if (!file_put_contents($exportfiledest, $exportfiletext) || $error == 1) {
			$msg = JText::_('CK_ERROR_CREATING_FAVORITEFILE');
			$status = 0;
		} else {
			$msg = $i;
			$status = 1;
		}

		echo '{"status": "' . $status . '", "msg": "' . $msg . '"}';
		// echo $msg;
		exit();
	}

	/*
	 * Load the favorite file
	 */
	public function ajaxLoadFavorite() {
		// security check
		CKFof::checkAjaxToken();

		$name = $this->input->get('name', '', 'string');
		$folder = $this->input->get('folder', '', 'string');

		$path = PAGEBUILDERCK_PARAMS_PATH . '/'.$folder.'/';

		$content = file_get_contents($path . $name . '.fck3');
		echo $content;
		exit();
	}

	/*
	 * Remove the favorite file from the folder
	 */
	public function ajaxRemoveFavorite() {
		// security check
		CKFof::checkAjaxToken();

		$this->checkAjaxUserEditRight();

		$name = $this->input->get('name', '', 'string');

		$msg = '';
		if (!JFile::delete(PAGEBUILDERCK_PARAMS_PATH . '/favorites/' . $name . '.fck3')) {
			$msg = JText::_('CK_ERROR_DELETING_FAVORITEFILE');
			$status = 0;
		} else {
			$status = 1;
		}

		echo '{"status": "' . $status . '", "msg": "' . $msg . '"}';
		exit();
	}

	function ajaxShowMenuItems() {
		// security check
		CKFof::checkAjaxToken();

		$parentId = $this->input->get('parentid', 0, 'int');
		$menutype = $this->input->get('menutype', '', 'string');

		$model = $this->getModel('Menus', '', array());
		$items = $model->getChildrenItems($menutype, $parentId);

		$links = array();
		$imagespath = PAGEBUILDERCK_MEDIA_URI .'/images/';
		?>
		<div class="cksubfolder">
		<?php
		foreach ($items as $item) {
			$aliasId = $item->id;
			if ($item->type == 'alias') {
				$itemParams = new JRegistry($item->params);
				$aliasId = $itemParams->get('aliasoptions', 0);
			}
			$Itemid = substr($item->link,-7,7) == 'Itemid=' ? $aliasId : '&Itemid=' . $aliasId;
			$hasChild = (int)$item->rgt - (int)$item->lft > 1 ? true : false;
		?>
			<div class="ckfoldertree parent">
				<div class="ckfoldertreetoggler <?php if (! $hasChild) { echo 'empty'; } ?>" onclick="ckMenusToggleTreeSub(this, <?php echo $item->id ?>)" data-menutype="<?php echo $item->menutype; ?>"></div>
				<div class="ckfoldertreename hasTip" title="<?php echo $item->link . $Itemid ?>" onclick="ckSetMenuItemUrl('<?php echo $item->link . $Itemid ?>')"><span class="icon-link"></span><?php echo $item->title; ?></div>
			</div>
		<?php
		}
		?>
		</div>
		<?php
		exit;
	}

	public function ajaxSaveElement() {
		// security check
		CKFof::checkAjaxToken();

		$name = $this->input->get('name', '', 'string');
		$type = $this->input->get('type', '', 'string');
		$html = $this->input->get('html', '', 'raw');
		// $html = json_encode($this->input->get('html', '', 'raw'));

		$model = $this->getModel('Elements', '', array());
		$id = $model->ajaxSave($name, $type, $html);
		$pluginsType = PagebuilderckHelper::getPluginsMenuItemType();
		$image = $pluginsType[$type]->image;
		$returncode = '<div data-type=\"' . $type . '\" data-id=\"' . $id . '\" class=\"menuitemck ckmyelement\" >'
						. '<div>'
							. '<div class=\"menuitemck_title\">' . $name . '</div>'
						. '</div>'
						. '<img src=\"' . $image . '\" />'
					. '</div>';
		echo '{"status" : "' . ($id == false ? '0' : '1') . '", "code" : "' . $returncode . '"}';
		exit;
	}

	public function ajaxAddElementItem() {
		// security check
		CKFof::checkAjaxToken();
	
		$id = $this->input->get('id', '', 'int');

		$model = $this->getModel('Element');
		$result = $model->getHtml($id);
		echo ($result == false ? 'ERROR' : $result);
		exit;
	}

	public function fixDb() {
		$this->searchTable('elements');
	}

	private function searchTable($tableName) {
		$db = JFactory::getDbo();

		$tablesList = $db->getTableList();
		$tableExists = in_array($db->getPrefix() . 'pagebuilderck_' . $tableName, $tablesList);
		// test if the table not exists

		if (! $tableExists) {
			$query = $this->getSqlQueryElements();
			$db->setQuery($query);
//			// add the SQL field to the main table
//			$query = 'ALTER TABLE `' . $table . '` ADD `' . $name . '` text NOT NULL;';
			if (! $db->execute($query)) {
				echo '<p class="alert alert-danger">Error during table ' . $tableName . ' creation process !</p>';
			} else {
				echo '<p class="alert alert-success">Table ' . $tableName . ' created with success !</p>';
			}
		} 
	}

	private function getSqlQueryElements() {
		$query = "CREATE TABLE IF NOT EXISTS `#__pagebuilderck_elements` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` tinytext NOT NULL,
  `type` varchar(50) NOT NULL,
  `ordering` int(11) NOT NULL,
  `state` int(10) NOT NULL DEFAULT '1',
  `catid` varchar(255) NOT NULL,
  `htmlcode` longtext NOT NULL,
  `checked_out` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;";
		return $query;
	}

	/*
	 * DO NOT USE, use ajaxSetPluginOption
	 */
	public function ajaxSavePluginCustomData() {
		// security check
		CKFof::checkAjaxToken();

		$customdata = $this->input->get('customdata', array(), 'array');
		$plugin = $this->input->get('plugin', '', 'string');

		$query = "SELECT custom_data FROM #__extensions WHERE type='plugin' AND element ='" . $plugin . "' AND folder='pagebuilderck'";
		$db = JFactory::getDbo();
		$db->setQuery($query);
		$data = $db->loadResult();

//		var_dump($customdata);
		$options = unserialize($data);
//		var_dump($options);
		$newdata = serialize($customdata);

		$query = "UPDATE #__extensions "
				. "SET custom_data = '" . $newdata . "'"
				. "WHERE type='plugin' AND element ='" . $plugin . "' AND folder='pagebuilderck' ";
		$db->setQuery($query);
		$result = $db->execute();
	}

	/*
	 * DO NOT USE, use ajaxGetPluginOption
	 */
	public function ajaxLoadPluginCustomData() {
		// security check
		CKFof::checkAjaxToken();

		$plugin = $this->input->get('plugin', '', 'string');
		$key = $this->input->get('key', '', 'string');

		$query = "SELECT custom_data FROM #__extensions WHERE type='plugin' AND element ='" . $plugin . "' AND folder='pagebuilderck'";
		$db = JFactory::getDbo();
		$db->setQuery($query);
		$data = $db->loadResult();

//		var_dump($data);
		$options = unserialize($data);
//		var_dump($options);
		echo isset($options[$key]) ? $options[$key] : '0';
		exit;
	}

	public function ajaxSaveCustomCss() {
		// security check
		CKFof::checkAjaxToken();

		$id = $this->input->get('id', 0, 'int');
		$name = $this->input->get('name', '', 'string');
		$customcss = $this->input->get('customcss', '', 'raw');

		$data = array();
//		$data['id'] = 0;
		$data['name'] = $name;
		$data['value'] = $customcss;

		$query = "SELECT id FROM #__pagebuilderck_options WHERE name='" . $name . "'";
		$data['id'] = (int) \Pagebuilderck\CKFof::dbLoadResult($query);

		$id = \Pagebuilderck\CKFof::dbStore('#__pagebuilderck_options', $data);

		echo '{"status" : "' . ($id == false ? '0' : '1') . '", "code" : "' . $id . '"}';
		exit;
	}

	public function ajaxSetUserState() {
		// security check
		CKFof::checkAjaxToken();

		$key = $this->input->get('key', '', 'string');
		$value = $this->input->get('value', '', 'string');

		$app = JFactory::getApplication();
		$app->setUserState($key, $value);
	}

	public function ajaxSetPluginOption() {
		PagebuilderckHelper::ajaxSetPluginOption();
	}

	public function ajaxGetPluginOption() {
		PagebuilderckHelper::ajaxGetPluginOption();
	}
}