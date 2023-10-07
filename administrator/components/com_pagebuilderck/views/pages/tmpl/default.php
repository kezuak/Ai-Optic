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

use Pagebuilderck\CKFof;

require_once(PAGEBUILDERCK_PATH . '/helpers/defines.js.php');

// check the joomla! version
if (version_compare(JVERSION, '3.0.0') > 0) {
	$jversion = '3';
} else {
	$jversion = '2';
}

$user = CKFof::getUser();
$userId = $user->get('id');
// for ordering
$listOrder = $this->state->get('filter_order', 'a.id');
$listDirn = $this->state->get('filter_order_Dir', 'ASC');
$filter_search = $this->state->get('filter_search', '');
$limitstart = $this->state->get('limitstart', 0);
$limit = $this->state->get('limit', 20);
CKFof::addStyleSheet(PAGEBUILDERCK_MEDIA_URI . '/assets/adminlist.css');
?>
<div class="ckadminsidebar"><?php echo JHtmlSidebar::render() ?></div>
<div class="ckadminarea">
<div class="pagebuilderckchecking"></div>
<div class="alert"><?php echo JText::_('COM_PAGEBUILDERCK_VOTE_JED'); ?>&nbsp;<a href="https://extensions.joomla.org/extensions/extension/authoring-a-content/content-construction/page-builder-ck" target="_blank" class="btn btn-small btn-warning"><?php echo JText::_('COM_PAGEBUILDERCK_VOTE_JED_BUTTON'); ?></a></div>
<form action="<?php echo JRoute::_('index.php?option=com_pagebuilderck&view=pages'); ?>" method="post" name="adminForm" id="adminForm">
	<div id="filter-bar" class="btn-toolbar input-group">
		<div class="filter-search btn-group pull-left">
			<label for="filter_search" class="element-invisible"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>" value="<?php echo addslashes($this->state->get('filter_search')); ?>" class="cktip form-control" title="" />
		</div>
		<div class="input-group-append btn-group pull-left hidden-phone">
			<button type="submit" class="btn btn-primary cktip" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i><?php echo ($jversion === '2' ? JText::_('JSEARCH_FILTER_SUBMIT') : ''); ?></button>
			<button type="button" class="btn btn-secondary cktip" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.getElementById('filter_search').value = '';
					this.form.submit();"><i class="icon-remove"></i><?php echo ($jversion === '2' ? JText::_('JSEARCH_FILTER_CLEAR') : ''); ?></button>
		</div>
			&nbsp;
		<?php if ($jversion === '3') { ?>
		<div class="btn-group pull-right hidden-phone ordering-select">
			<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>
			<?php echo $this->pagination->getLimitBox(); ?>
		</div>
		<?php } ?>
			&nbsp;
			
	</div>
<div class="clearfix"> </div>
		<div style="margin: 5px 0;">
			<label for="categories" ><?php echo JText::_('COM_PAGEBUILDERCK_CATEGORIES'); ?></label>
			<select id="categories" name="categories" class="form-select" onchange="this.form.submit();">
				<option value="0"><?php echo JText::_('COM_PAGEBUILDERCK_SELECT_CATEGORY'); ?></option>
				<?php
				$categories = PagebuilderckHelper::getCategories();
				foreach ($categories as $category) {
					?>
					<option value="<?php echo $category->id ?>" <?php echo ($category->id == addslashes($this->state->get('categories')) ?'selected="true"':'') ?>><?php echo $category->name ?></option>
					<?php
				}
				?>
			</select>
		</div>
    <table class="table table-striped" id="templateckList">
        <thead>
            <tr>
                <th width="1%">
                    <input type="checkbox" name="checkall-toggle" class="form-check-input" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" value="" onclick="Joomla.checkAll(this)" />
                </th>

                <th class='left'>
						<?php //echo JText::_('COM_PAGEBUILDERCK_TEMPLATES_NAME');  ?>
					<?php echo JHtml::_('grid.sort', 'COM_PAGEBUILDERCK_PAGES_NAME', 'a.title', $listDirn, $listOrder); ?>
                </th>
				<?php if (isset($this->items[0]->state)) { ?>
				<?php } ?>
					<th class="nowrap">
						<?php echo JHtml::_('grid.sort', 'COM_PAGEBUILDERCK_CATEGORIES', 'a.categories', $listDirn, $listOrder); ?>
					</th>
				<?php if (isset($this->items[0]->id)) {
					?>
					<th width="1%" class="nowrap">
							<?php //echo JText::_('JGRID_HEADING_ID');  ?>
						<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
					</th>
				<?php } ?>
				<th width="1%" class="nowrap center">
					<?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
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
				$link = 'index.php?option=com_pagebuilderck&view=page&task=page.edit&id=' . $item->id;
				?>
				<tr class="row<?php echo $i % 2; ?>">
					<td class="center">
						<?php echo JHtml::_('grid.id', $i, $item->id); ?>
	                </td>

	                <td>
						<?php if ($item->checked_out) { ?>
						<a class="tbody-icon" href="<?php echo JRoute::_('index.php?option=com_pagebuilderck&task=pages.checkin&id=' . $item->id); ?>"><span class="icon-checkedout" aria-hidden="true"></span></a>
						<?php /*<div role="tooltip" id="cbcheckin0-desc"><strong>Check-in</strong><br>test<br>Saturday, 05 February 2022<br>08:57</div> */ ?>
						<?php } ?>
	                    <a href="<?php echo $link; ?>"><?php echo $item->title; ?></a>
	                </td>

						<td>
							<?php
							if (! empty($item->categoriesByName)) {
								// echo '<span class="badge bg-secondary">' . implode('</span><span class="badge bg-secondary">', $item->categoriesByName) . '</span>';
								echo implode("<br/>", $item->categoriesByName);
							} ?>
						</td>
					<?php if (isset($this->items[0]->id)) {
						?>
						<td class="center">
							<?php echo (int) $item->id; ?>
						</td>
					<?php } ?>
					<td class="center">
						<?php
						$buttonstate = $item->state == '1' ? '' : 'un';
						?>
						<span class="icon-<?php echo $buttonstate ?>publish"></span>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<input type="hidden" name="state_request" value="1" />
		<?php echo JHtml::_('form.token'); ?>
    </div>
</form>
</div>