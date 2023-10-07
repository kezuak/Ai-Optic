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

require_once PAGEBUILDERCK_PATH . '/helpers/defines.js.php';

// check the joomla! version
if (version_compare(JVERSION, '3.0.0') > 0) {
	$jversion = '3';
} else {
	$jversion = '2';
}

$user = JFactory::getUser();

// for ordering
$listOrder = $this->state->get('filter_order', 'a.id');
$listDirn = $this->state->get('filter_order_Dir', 'ASC');
$filter_search = $this->state->get('filter_search', '');
$limitstart = $this->state->get('limitstart', 0);
$limit = $this->state->get('limit', 20);
CKFof::addStyleSheet(PAGEBUILDERCK_MEDIA_URI . '/assets/adminlist.css');
$saveOrder = $listOrder == 'a.ordering';
?>
<script src="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/jqueryck.js" type="text/javascript"></script>
<script src="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/jquery-uick-custom.js" type="text/javascript"></script>

<div class="ckadminsidebar"><?php echo JHtmlSidebar::render() ?></div>
<div class="ckadminarea">
	<form action="<?php echo JRoute::_('index.php?option=com_pagebuilderck&view=elements'); ?>" method="post" name="adminForm" id="adminForm">
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
			<?php if ($jversion === '3') { ?>
				<div class="btn-group pull-right hidden-phone ordering-select">
					<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>
					<?php echo $this->pagination->getLimitBox(); ?>
				</div>
			<?php } ?>
		</div>
		<div class="clearfix"> </div>
		<table class="table table-striped" id="ckelementslist">
			<thead>
				<tr>
					<th width="1%" class="nowrap center hidden-phone">
						<?php echo JHtml::_('grid.sort', 'CK_ORDERING', 'a.ordering', $listDirn, $listOrder); ?>
					</th>
					<th width="1%">
						<input type="checkbox" name="checkall-toggle" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" value="" onclick="Joomla.checkAll(this)" />
					</th>

					<th class='left'>
						<?php echo JHtml::_('grid.sort', 'COM_PAGEBUILDERCK_PAGES_NAME', 'a.title', $listDirn, $listOrder); ?>
					</th>
					<th class='left'>
						<?php echo JHtml::_('grid.sort', 'COM_PAGEBUILDERCK_DESCRIPTION', 'a.description', $listDirn, $listOrder); ?>
					</th>
					<th class='left'>
						<?php echo JHtml::_('grid.sort', 'COM_PAGEBUILDERCK_TYPE', 'a.type', $listDirn, $listOrder); ?>
					</th>
					<?php if (isset($this->items[0]->state)) { ?>
					<?php } ?>
					<?php if (isset($this->items[0]->id)) {
						?>
						<th width="1%" class="nowrap">
							<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
						</th>
					<?php } ?>
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
				// load all the pagebuilderck plugins
				$pluginsType = PagebuilderckHelper::getPluginsMenuItemType();

				foreach ($this->items as $i => $item) :
					$canCreate = $user->authorise('core.create', 'com_pagebuilderck');
					$canEdit = $user->authorise('core.edit', 'com_pagebuilderck');
					$canCheckin = $user->authorise('core.manage', 'com_pagebuilderck');
					$canChange = $user->authorise('core.edit.state', 'com_pagebuilderck');
					$link = 'index.php?option=com_pagebuilderck&view=element&task=element.edit&id=' . $item->id;
					?>
					<tr class="row<?php echo $i % 2; ?>" data-id="<?php echo (int) $item->id; ?>">
						<td class="order nowrap center hidden-phone">
							<?php
							$iconClass = '';
							if (!$canChange) {
								$iconClass = ' inactive';
							} elseif (!$saveOrder) {
								$iconClass = ' inactive tip-top cktip" title="' . JHtml::_('tooltipText', 'JORDERINGDISABLED');
							}
							?>
							<span class="sortable-handler<?php echo $iconClass; ?>">
								<span class="icon-menu" aria-hidden="true"></span>
							</span>
							<?php if ($canChange && $saveOrder) : ?>
								<input type="text" style="display:none" name="order[]" size="5"
									   value="<?php echo $item->ordering; ?>" class="width-20 text-area-order" />
							<?php endif; ?>
						</td>
						<td class="center">
								   <?php echo JHtml::_('grid.id', $i, $item->id); ?>
						</td>

						<td>
							<a href="<?php echo $link; ?>"><?php echo $item->title; ?></a>
						</td>
						<td>
							<?php echo $item->description; ?>
						</td>
						<td>
							<?php
							if ($item->type == 'row') {
								echo JText::_('COM_PAGEBUILDERCK_CONTENT_ROW');
							} else {
								$typeName = $pluginsType[$item->type]->title;
								echo $typeName;
							}
							?>
						</td>

							<?php if (isset($this->items[0]->id)) {
								?>
							<td class="center">
							<?php echo (int) $item->id; ?>
							</td>
						<?php } ?>
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
	<script>
		$ck('#ckelementslist tbody').sortable({
			items: "> tr",
			// helper: "clone",
			handle: ".sortable-handler:not(.inactive)",
			forcePlaceholderSize: true,
			tolerance: "pointer",
			placeholder: "placeholderck",
			stop: function (e, ui) {
				ckSaveElementsListSorting();
			}
		});

		function ckSaveElementsListSorting() {
			var ordering = new Object();
			var i = 0;
			$ck('#ckelementslist tbody tr').each(function () {
				ordering[$ck(this).attr('data-id')] = i;
				i++;
			});
			var myurl = PAGEBUILDERCK.URIPBCK + "&task=elements.order&" + PAGEBUILDERCK.TOKEN;
			$ck.ajax({
				type: "POST",
				url: myurl,
				data: {
					ordering: ordering
				}
			}).done(function (code) {

			}).fail(function () {
				alert(Joomla.JText._('CK_FAILED', 'Failed'));
			});
		}
	</script>
</div>