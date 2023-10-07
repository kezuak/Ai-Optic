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
$function = $this->input->get('function', '', 'cmd');
$target = $this->input->get('target', '', 'cmd');
CKFof::addStyleSheet(PAGEBUILDERCK_MEDIA_URI . '/assets/adminlist.css');
if ($target == 'leftpanel') {
	?>
	<style>
		body.contentpane {
			background: #f5f5f5;
			padding: 5px;
		}
	</style>
	<?php
}
?>
<link rel="stylesheet" href="<?php echo JUri::root(true) ?>/components/com_pagebuilderck/assets/font-awesome.min.css" type="text/css" />
<div class="pagebuilderckchecking"></div>
<form action="<?php echo JRoute::_('index.php?option=com_pagebuilderck&view=pages&layout=modal&tmpl=component&function=' . $function . '&target=' . $target); ?>" method="post" name="adminForm" id="adminForm" class="ckinterface">
	<?php if ($target == 'leftpanel') { ?>
		<div class="ckleftpanelsearch">
			<input type="text" name="filter_search" id="filter_search" class="cksearchleftpanel" placeholder="<?php echo JText::_('CK_SEARCH') ?>" value="<?php echo addslashes($this->state->get('filter_search')); ?>" />
			<i class="fa fa-search cktip" title="<?php echo JText::_('CK_SEARCH') ?>" onclick="ckSearchAddon()"></i>
			<i onclick="document.getElementById('filter_search').value = '';document.getElementById('adminForm').submit();" class="fa fa-times cktip" title="<?php echo JText::_('CK_CLEAN') ?>" ></i>
		</div>
		<?php
	} else {
		?>
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
		<?php
	}
	?>
	<div class="clearfix"> </div>
	<div style="margin: 5px 0;">
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

				<th class='left'>
					<?php //echo JText::_('COM_PAGEBUILDERCK_TEMPLATES_NAME');  ?>
					<?php echo JHtml::_('grid.sort', 'COM_PAGEBUILDERCK_PAGES_NAME', 'a.title', $listDirn, $listOrder); ?>
				</th>
				<?php if (isset($this->items[0]->state)) { ?>
				<?php } ?>
				<?php if (isset($this->items[0]->id)) {
					?>
					<th width="1%" class="nowrap">
						<?php //echo JText::_('JGRID_HEADING_ID');  ?>
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
			foreach ($this->items as $i => $item) :
				$canCreate = $user->authorise('core.create', 'com_pagebuilderck');
				$canEdit = $user->authorise('core.edit', 'com_pagebuilderck');
				$canCheckin = $user->authorise('core.manage', 'com_pagebuilderck');
				$canChange = $user->authorise('core.edit.state', 'com_pagebuilderck');
				// $link = 'index.php?option=com_pagebuilderck&view=page&task=page.edit&id=' . $item->id;
				$link = 'javascript:void(0)';
				$onclick = 'window.parent.' . $function . '(' . $item->id . ', \'page\', \'' . addslashes($item->title) . '\');';
				?>
				<tr class="row<?php echo $i % 2; ?> ckrowpage">
					<td>
						<a href="<?php echo $link; ?>" onclick="<?php echo $onclick ?>"><?php echo $item->title; ?></a>
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
