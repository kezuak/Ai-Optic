<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */

defined('_JEXEC') or die;

use Pagebuilderck\CKView;
use Pagebuilderck\CKFof;

class PagebuilderckViewPage extends CKView
{

	public function display($tpl = null)
	{
		$user = CKFof::getUser();

		$model = $this->getModel();

		if (substr( $this->input->get('layout', '', 'cmd'), 0, 4 ) !== 'ajax') {
			// check if the page is available for modification
			$id = $this->input->get('id', 0, 'int');
			$this->item = $model->getItem($id);

			// check that we got a page
			if (empty($this->item))
			{
				throw new Exception(JText::_('COM_PAGEBUILDERCK_ERROR_PAGE_NOT_FOUND'), 404);
			}
		}

		// check if we are viewing the frontend layout
		if ( ($this->input->get('layout', null, 'cmd') === null || $this->input->get('layout', null, 'cmd') === 'default')
			 && $tpl === null) {

			// check the rights to access the page
			$groups	= $user->getAuthorisedViewLevels();
			// if ((!in_array($this->item->access, $groups)) || (!in_array($this->item->category_access, $groups)))
			if (!in_array($this->item->access, $groups))
			{
				throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 403);
				return;
			}
		} else {
			// check that the user has the rights to edit
			$authorised = ($user->authorise('core.create', 'com_pagebuilderck') || (count($user->getAuthorisedCategories('com_pagebuilderck', 'core.create'))));
			if ($authorised !== true)
			{
				throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 403);
				return false;
			}

			if (! $model->checkout($id)) {
				CKFof::redirect(
					JRoute::_(
							'index.php?option=com_pagebuilderck&view=page&id=' . $id, false
						)
				);
				return false;
			}

			include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/menustyles.php';
			include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/stylescss.php';
			include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/ckeditor.php';
			include_once JPATH_ADMINISTRATOR . '/components/com_pagebuilderck/helpers/pagebuilderck.php';

			// get instance of the editor to load the css / js in the page
			$this->ckeditor = PagebuilderckHelper::loadEditor();
		}

		// Check if access is not public
		// $groups	= $user->getAuthorisedViewLevels();

		// if ($this->_layout == 'edit' || !$this->item->id) {
			// $authorised = $user->authorise('core.edit.own', 'com_pagebuilderck');

			// if ($authorised !== true) {
				// Redirect to the edit screen.
				// $app->redirect(JURI::root() . 'index.php?option=com_templateck&view=login&template=templatecreatorck&tmpl=login&id=' . $this->item->id);
				// return false;
			// }
		// }
// TODO : ajouter permissions et acl
		// if ((!in_array($item->access, $groups)) || (!in_array($item->category_access, $groups)))
		// {
			// JError::raiseWarning(403, JText::_('JERROR_ALERTNOAUTHOR'));
			// return;
		// }

		// $model = $this->getModel();
		// $model->hit();

		return parent::display($tpl);
	}
}
