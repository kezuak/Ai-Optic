<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */
 
defined('_JEXEC') or die('Restricted access');
/*
	preflight which is executed before install and update
	install
	update
	uninstall
	postflight which is executed after install and update
	*/

class com_pagebuilderckInstallerScript {

	function install($parent) {
		
	}
	
	function update($parent) {
		
	}

	/*
	 * get a variable from the manifest file (actually, from the manifest cache).
	 */
	function getParam( $name ) {
		$db = JFactory::getDbo();
		$db->setQuery('SELECT manifest_cache FROM #__extensions WHERE element = "com_pagebuilderck"');
		$manifest = json_decode( $db->loadResult(), true );
		return $manifest[ $name ];
	}

	/*
	* update the table
	*/
	function updateTable($version) {
		$sqlsrc = dirname(__FILE__).'/administrator/sql/updates/' . $version . '.sql';
		$query = file_get_contents($sqlsrc);
		$db = JFactory::getDbo();
		$db->setQuery($query);
		if (!$db->execute()) {
			echo '<p class="alert alert-danger">Error during table update for version ' . $version . '</p>';
		} else {
			echo '<p class="alert alert-success">Table successfully updated for version ' . $version . '</p>';
		}
	}

	/*
	* update the table
	*/
	function updateTableFieldCategory() {
		$db = JFactory::getDbo();
		// test for order column title depending on virtuemart version

		// test if the columns not exists
		$query = "SHOW COLUMNS FROM #__pagebuilderck_pages LIKE 'categories'";
		$db->setQuery($query);
		try {
		if ($db->execute()) {
			if ( $db->loadResult()) {
				//echo 'existe deja!';return;
			} else {
				// add the SQL field to the main table
				$db->setQuery('ALTER TABLE `#__pagebuilderck_pages` ADD `categories` varchar(255) NOT NULL;');
				if (!$db->execute()) {
					echo '<p class="alert alert-danger">Error during table pagebuilderck_pages/categories update process !</p>';
				} else {
					echo '<p class="alert alert-success">Table pagebuilderck_pages/categories updated !</p>';
				}
			}
		} else {
			echo 'Erreur de données SQL - Test si champ  pagebuilderck_pages/categories existe';
			return false;
		}
		} catch (Exception $e) {
			echo 'Problem during sql update : ',  $e->getMessage(), "\n";
		}
	}


	function uninstall($parent) {
		// jimport('joomla.installer.installer');
		// Latest Module
		// Check first that the module exist
		/*$db->setQuery('SELECT `extension_id` FROM #__extensions WHERE `element` = "mod_test" AND `type` = "module"');
		$id = $db->loadResult();
		if($id)
		{
			$installer = new JInstaller;
			$result = $installer->uninstall('module',$id,1);
		}*/

		// disable all plugins and modules
		// module
		$db = JFactory::getDbo();
		$db->setQuery("UPDATE `#__modules` SET `published` = 0 WHERE `module` LIKE '%pagebuilderck%'");
		$db->execute();
		// system, editor button, search plugins
		$db->setQuery("UPDATE `#__extensions` SET `enabled` = 0 WHERE `type` = 'plugin' AND `element` LIKE '%pagebuilderck%' AND `folder` NOT LIKE '%pagebuilderck%'");
		$db->execute();
		return true;
	}

	function preflight($type, $parent) {
//		// check if a pro version already installed
		$xmlPath = JPATH_ROOT . '/administrator/components/com_pagebuilderck/pagebuilderck.xml';

		// if no file already exists
		if (! file_exists($xmlPath)) return true;

		$xmlData = $this->getXmlData($xmlPath);
		$isProInstalled = ((int)$xmlData->ckpro);

		if ($isProInstalled) {
			throw new RuntimeException('Page Builder CK Light cannot be installed over Page Builder CK Pro. Please install Page Builder CK Pro. To downgrade, please first uninstall Page Builder CK Pro.');
			// return false;
		}

		$installedVersion = ((string)$xmlData->version );

		// if the installed version is the old one
		if(version_compare($installedVersion, '2.5.0', '<')) {
			// if the params is also installed
			if (file_exists(JPATH_ROOT . '/plugins/system/pagebuilderckparams/pagebuilderckparams.xml')) {
				throw new RuntimeException('Page Builder CK Light cannot be installed over Page Builder CK Params. Please install Page Builder CK Pro to get the same features as previously, else you may loose your existing settings. To downgrade, please first uninstall Page Builder CK Params.<a href="https://forum.joomlack.fr/index.php/page-builder-ck/16402-how-to-update-page-builder-ck-to-2-5-0" target="_blank">Read more</a>');
				// return false;
			}
		}

		return true;
	}

	// run on install and update
	function postflight($type, $parent) {
		// update the tables
		$db = JFactory::getDbo();
		$tablesList = $db->getTableList();
		// test if the table not exists
		$tableExists = in_array($db->getPrefix() . 'pagebuilderck_elements', $tablesList);
		if (! $tableExists) {
			$this->updateTable('2.2.0');
		}

		$tableCategoriesExists = in_array($db->getPrefix() . 'pagebuilderck_categories', $tablesList);
		if (! $tableCategoriesExists) {
			$this->updateTable('2.14.0');
		}
		$this->updateTableFieldCategory();

		// install modules and plugins
		jimport('joomla.installer.installer');
		$db = JFactory::getDbo();
		$status = array();
		$src_ext = dirname(__FILE__).'/administrator/extensions';
		$installer = new JInstaller;

		// extensions to install
		// system plugin
		$result = $installer->install($src_ext.'/system_pagebuilderck');
		$status[] = array('name'=>'System - Pagebuilder CK','type'=>'plugin', 'result'=>$result);
		// system plugin must be enabled for user group limits and private areas
		$db->setQuery("UPDATE #__extensions SET enabled = '1' WHERE `element` = 'pagebuilderck' AND `type` = 'plugin'");
		$db->execute();

		// editor button plugin
		$result = $installer->install($src_ext.'/pagebuilderckbutton');
		$status[] = array('name'=>'Button - Pagebuilder CK','type'=>'plugin', 'result'=>$result);
		// auto enable the plugin
		$db->setQuery("UPDATE #__extensions SET enabled = '1' WHERE `element` = 'pagebuilderckbutton' AND `type` = 'plugin'");
		$db->execute();

		// editor plugin (editor button type)
		$result = $installer->install($src_ext.'/pagebuilderckeditor');
		$status[] = array('name'=>'Editor - Pagebuilder CK','type'=>'plugin', 'result'=>$result);
		// auto enable the plugin
		$db->setQuery("UPDATE #__extensions SET enabled = '1' WHERE `element` = 'pagebuilderckeditor' AND `type` = 'plugin'");
		$db->execute();

		// search plugin
		/*$result = $installer->install($src_ext.'/pagebuildercksearch');
		$status[] = array('name'=>'Search - Pagebuilder CK','type'=>'plugin', 'result'=>$result);*/
		// auto enable the plugin
		$db->setQuery("UPDATE #__extensions SET enabled = '0' WHERE `element` = 'pagebuildercksearch' AND `type` = 'plugin'");
		$db->execute();

		// module
		$result = $installer->install($src_ext.'/mod_pagebuilderck');
		$status[] = array('name'=>'Page Builder CK - Module','type'=>'module', 'result'=>$result);
		// auto enable the plugin
		// $db->setQuery("UPDATE #__extensions SET enabled = '1' WHERE `element` = 'mod_pagebuilderck' AND `type` = 'module'");
		// $db->execute();

		// pagebuilderck plugin
		// $plugins = array('text', 'icon', 'icontext', 'image', 'separator', 'message', 'tabs', 'accordion', 'module', 'video', 'audio');
		$plugins = JFolder::folders($src_ext.'/addons');
		$ordering = 1;
		foreach ($plugins as $plugin) {
			$result = $installer->install($src_ext . '/addons/' . $plugin);
			$status[] = array('name'=>'Pagebuilder CK - ' . $plugin,'type'=>'plugin', 'result' => $result);
			if ($type !== 'update') {
				// if update, then do nothing. Else it will install and enable the plugins/module
				// auto enable the plugin
				$db->setQuery("UPDATE #__extensions SET enabled = '1', ordering = '" . $ordering . "' WHERE `element` = '" . $plugin . "' AND `type` = 'plugin' AND `folder` = 'pagebuilderck'");
				$db->execute();
				$ordering++;
			}
		}

		foreach ($status as $statu) {
			if ($statu['result'] == true) {
				$alert = 'success';
				$icon = 'icon-ok';
				$text = 'Successful';
			} else {
				$alert = 'warning';
				$icon = 'icon-cancel';
				$text = 'Failed';
			}
			echo '<div class="alert alert-' . $alert . '"><i class="icon ' . $icon . '"></i>Installation and activation of the <b>' . $statu['type'] . ' ' . $statu['name'] . '</b> : ' . $text . '</div>';
		}

		// disable the old update site
		$db->setQuery("UPDATE #__update_sites SET enabled = '0' WHERE `location` = 'https://update.joomlack.fr/com_pagebuilderck_update.xml'");
		$result5 = $db->execute();

		// remove obsolete file
		$menufile = JPATH_ROOT . '/components/com_pagebuilderck/views/page/tmpl/default.xml';
		if (file_exists($menufile)) {
			unlink($menufile);
			// echo 'MENU ITEM FILE REMOVED';
		}

		return true;
	}

	public function getXmlData($file) {
		if ( ! is_file($file))
		{
			return '';
		}

		$xml = simplexml_load_file($file);

		if ( ! $xml || ! isset($xml['version']))
		{
			return '';
		}

		return $xml;
	}
}
