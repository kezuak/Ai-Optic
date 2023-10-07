<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */

defined('_JEXEC') or die;

use Pagebuilderck\CKModel;

class PagebuilderckModelContenttypes extends CKModel {

	protected $context = 'pagebuilderck.types';

	public function __construct() {

		parent::__construct();
	}

	private $types;

	public function getItems() {
		if (empty($this->types)) {
			// load the custom plugins
			JPluginHelper::importPlugin( 'pagebuilderck' );
			$items = Pagebuilderck\CKFof::triggerEvent( 'onPagebuilderckAddContentType' );
			// $items = $otheritems;
			$this->types = array();
			if (count($items)) {
				foreach ($items as $item) {
					$this->types[] = $item;
				}
			}
		}
		// sort by alphabetical, by values
		asort($this->types);

		return $this->types;
	}
}
