<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */
// no direct access
defined('_JEXEC') or die;


// check the joomla! version
if (version_compare(JVERSION, '3.0.0') > 0) {
	$jversion = '3';
} else {
	$jversion = '2';
}

$user = JFactory::getUser();
$userId = $user->get('id');
// for ordering
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$saveOrder = $listOrder == 'a.ordering';
?>
<div class="ckadminsidebar"><?php echo JHtmlSidebar::render() ?></div>
<div class="ckadminarea">
	<form action="<?php echo JRoute::_('index.php?option=com_pagebuilderck&view=contenttypes'); ?>" method="post" name="adminForm" id="adminForm">
		<table class="table table-striped" id="ckcontenttypeslist">
			<thead>
				<tr>
					<th class='left'>
						<?php echo JText::_('COM_PAGEBUILDERCK_CONTENTTYPES'); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="10">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
				<?php
				foreach ($this->items as $i => $item) :
					$canCreate = $user->authorise('core.create', 'com_pagebuilderck');
					$canEdit = $user->authorise('core.edit', 'com_pagebuilderck');
					$canCheckin = $user->authorise('core.manage', 'com_pagebuilderck');
					$canChange = $user->authorise('core.edit.state', 'com_pagebuilderck');
					$link = 'index.php?option=com_pagebuilderck&view=contenttype&layout=edit&type=' . $item;
					?>
					<tr class="row<?php echo $i % 2; ?>" data-type="<?php echo $item; ?>">
						<td>
							<a href="<?php echo $link; ?>"><?php echo $item; ?></a>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<div>
			<input type="hidden" name="task" value="" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</form>
</div>