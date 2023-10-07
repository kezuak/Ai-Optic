<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2020. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */
defined('_JEXEC') or die;
?>
<nav id="context-menu-ck" class="context-menu-ck">
	<ul class="context-menu-ck__items">
		<li class="context-menu-ck__item context-menu-ck_row parent" data-target="row">
			<a href="#" class="context-menu-ck__link" data-action="parentItem"><i class="fa fa-ellipsis-h"></i> <?php echo JText::_('CK_ROW') ?></a>
			<ul class="context-menu-ck__items">
				<li class="context-menu-ck__item">
					<a href="#" class="context-menu-ck__link" data-action="row.edit"><i class="fa fa-pencil-square-o"></i> <?php echo JText::_('CK_EDIT_STYLES') ?></a>
					<a href="#" class="context-menu-ck__link context-menu-ck__item-styles-copy" data-action="row.stylecopy"><i class="fa fa-copy"></i> </a>
					<a href="#" class="context-menu-ck__link context-menu-ck__item-styles-paste" data-action="row.stylepaste"><i class="fa fa-paste"></i> </a>
				</li>
				<li class="context-menu-ck__item">
					<a href="#" class="context-menu-ck__link" data-action="row.columns"><i class="fa fa-align-justify"></i> <?php echo JText::_('CK_EDIT_COLUMNS') ?></a>
				</li>
				<li class="context-menu-ck__item">
					<a href="#" class="context-menu-ck__link" data-action="row.fullwidth"><i class="fa fa-expand"></i> <?php echo JText::_('CK_FULLWIDTH') ?></a>
				</li>
				<li class="context-menu-ck__item">
					<a href="#" class="context-menu-ck__link" data-action="row.duplicate"><i class="fa fa-clone"></i> <?php echo JText::_('CK_DUPLICATE_ROW') ?></a>
				</li>
				<li class="context-menu-ck__item">
					<a href="#" class="context-menu-ck__link" data-action="row.favorite"><i class="fa fa-magic"></i> <?php echo JText::_('CK_DESIGN_SUGGESTIONS') ?></a>
				</li>
				<li class="context-menu-ck__item">
					<a href="#" class="context-menu-ck__link" data-action="row.save"><i class="fa fa-floppy-o"></i> <?php echo JText::_('CK_SAVE') ?></a>
				</li>
				<li class="context-menu-ck__item">
					<a href="#" class="context-menu-ck__link" data-action="row.remove"><i class="fa fa-times"></i> <?php echo JText::_('CK_REMOVE_ROW') ?></a>
				</li>
			</ul>
		</li>
		<li class="context-menu-ck__item context-menu-ck_column parent" data-target="column">
			<a href="#" class="context-menu-ck__link" data-action="parentItem"><i class="fa fa-columns"></i> <?php echo JText::_('CK_COLUMN') ?></a>
			<ul class="context-menu-ck__items">
				<li class="context-menu-ck__item">
					<a href="#" class="context-menu-ck__link" data-action="column.edit"><i class="fa fa-pencil-square-o"></i> <?php echo JText::_('CK_EDIT_STYLES') ?></a>
					<a href="#" class="context-menu-ck__link context-menu-ck__item-styles-copy" data-action="column.stylecopy"><i class="fa fa-copy"></i> </a>
					<a href="#" class="context-menu-ck__link context-menu-ck__item-styles-paste" data-action="column.stylepaste"><i class="fa fa-paste"></i> </a>
				</li>
				<li class="context-menu-ck__item">
					<a href="#" class="context-menu-ck__link" data-action="column.duplicate"><i class="fa fa-clone"></i> <?php echo JText::_('CK_DUPLICATE_COLUMN') ?></a>
				</li>
				<li class="context-menu-ck__item">
					<a href="#" class="context-menu-ck__link" data-action="column.favorite"><i class="fa fa-magic"></i> <?php echo JText::_('CK_DESIGN_SUGGESTIONS') ?></a>
				</li>
				<li class="context-menu-ck__item">
					<a href="#" class="context-menu-ck__link" data-action="column.remove"><i class="fa fa-times"></i> <?php echo JText::_('CK_REMOVE_BLOCK') ?></a>
				</li>
			</ul>
		</li>
		<li class="context-menu-ck__item context-menu-ck_item parent" data-target="item">
			<a href="#" class="context-menu-ck__link" data-action="parentItem"><i class="fa fa-window-maximize"></i> <?php echo JText::_('CK_ITEM') ?></a>
			<ul class="context-menu-ck__items">
				<li class="context-menu-ck__item">
					<a href="#" class="context-menu-ck__link" data-action="item.edit"><i class="fa fa-pencil-square-o"></i> <?php echo JText::_('CK_EDIT_ITEM') ?></a>
					<a href="#" class="context-menu-ck__link context-menu-ck__item-styles-copy" data-action="item.stylecopy"><i class="fa fa-copy"></i> </a>
					<a href="#" class="context-menu-ck__link context-menu-ck__item-styles-paste" data-action="item.stylepaste"><i class="fa fa-paste"></i> </a>
				</li>
				<li class="context-menu-ck__item">
					<a href="#" class="context-menu-ck__link" data-action="item.duplicate"><i class="fa fa-clone"></i> <?php echo JText::_('CK_DUPLICATE_ITEM') ?></a>
				</li>
				<li class="context-menu-ck__item">
					<a href="#" class="context-menu-ck__link" data-action="item.favorite"><i class="fa fa-magic"></i> <?php echo JText::_('CK_DESIGN_SUGGESTIONS') ?></a>
				</li>
				<li class="context-menu-ck__item">
					<a href="#" class="context-menu-ck__link" data-action="item.save"><i class="fa fa-floppy-o"></i> <?php echo JText::_('CK_SAVE') ?></a>
				</li>
				<li class="context-menu-ck__item">
					<a href="#" class="context-menu-ck__link" data-action="item.clearstyles"><i class="fa fa-paint-brush"></i> <?php echo JText::_('CK_CLEAR_STYLES') ?></a>
				</li>
				<li class="context-menu-ck__item">
					<a href="#" class="context-menu-ck__link" data-action="item.remove"><i class="fa fa-times"></i> <?php echo JText::_('CK_REMOVE_ITEM') ?></a>
				</li>
			</ul>
		</li>
	</ul>
</nav>
<link rel="stylesheet" href="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/ckcontextmenu.css?ver=<?php echo PAGEBUILDERCK_VERSION ?>" type="text/css" />
<script src="<?php echo PAGEBUILDERCK_MEDIA_URI ?>/assets/ckcontextmenu.js?ver=<?php echo PAGEBUILDERCK_VERSION ?>" type="text/javascript"></script>