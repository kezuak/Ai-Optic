<?php
/**
 * @name		Page Builder CK
 * @package		com_pagebuilderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Pagebuilderck\CKFof;

$objid = $this->input->get('ckobjid', '');
$acl = $this->input->get('acl', '', 'string');

$user   = JFactory::getUser();
$groups = JAccess::getGroupsByUser($user->id);

?>
<div class="menuck clearfix fixedck">
	<div class="inner clearfix">
		<div class="headerck">
			<span class="headerckicon cktip" data-placement="bottom" title="<?php echo JText::_('CK_SAVE_CLOSE'); ?>" onclick="ckSetAcl();ckCloseEdition();">×</span>
			<span class="headerckicon cksave cktip" data-placement="bottom" title="<?php echo JText::_('CK_APPLY'); ?>" onclick="ckSetAcl();"><span class="fa fa-check"></span></span>
			<span class="headercktext"><?php echo JText::_('CK_ACL_EDIT'); ?></span>
		</div>
		<div id="elementscontainer" style="padding:5px;box-sizing: border-box;">
			<?php if (! CKFof::userCan('core.itemacl')) {
				echo JText::_('CK_NO_RIGHTS');
			} else {
			?>
			<table class="cktable cktable-bordered">
				<thead>
					<tr>
						<th><?php echo JText::_('CK_GROUP') ?></th>
						<th><?php echo JText::_('CK_VIEW_CONTENT') ?></th>
						<th><?php echo JText::_('CK_EDIT') ?></th>
					</tr>
				</thead>
				<tbody>
				<?php
				$groups = CKFof::dbLoadObjectList("SELECT * FROM #__usergroups ORDER BY lft ASC");
				$indent = 0;
				foreach ($groups as $i => $group) {
					if ($group->id == 8) continue;
					?>
					<tr class="ckaclrow" data-group="<?php echo $group->id ?>">
						<td>
							<?php
							if (isset($groups[$i-1])) {
								if (($groups[$i-1]->rgt - $group->lft) > 1) $indent++;
								if (($groups[$i-1]->rgt - $group->lft) < -1) $indent--;
								echo str_repeat('-', $indent);
							}
							?>
							<?php echo $group->title ?>
						</td>
						<td>
							<input id="aclgroup<?php echo $group->id ?>_view" name="aclgroup<?php echo $group->id ?>_view" value="2" type="checkbox" checked class="ckaclfieldview"/>
						</td>
						<td>
							<input id="aclgroup<?php echo $group->id ?>_edit" name="aclgroup<?php echo $group->id ?>_edit" value="1" type="checkbox" checked class="ckaclfieldedit" />
						</td>
					</tr>
					<?php
				}
				?>
				</tbody>
			</table>
			<?php } ?>
		</div>
	</div>
	<div class="clr"></div>
</div>

<?php
exit();