<?php
/**
 *
 * loads the add to cart button
 *
 * @package     VirtueMart
 * @subpackage
 * @author      Max Milbers, Valerie Isaksen
 * @link        https://virtuemart.net
 * @copyright   Copyright (c) 2015 VirtueMart Team. All rights reserved.
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @version     $Id: addtocartbtn.php 8024 2014-06-12 15:08:59Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

/** @var TYPE_NAME $viewData */
if ($viewData['orderable'])
{
    ?>
    <input type="submit" name="addtocart" class="btn btn-success btn-block"
           value="<?php echo vmText::_('COM_VIRTUEMART_CART_ADD_TO') ?>"/>
    <?php
} else
{
    ?>
    <input type="submit" name="addtocart" class="btn btn-outline-success btn-block"
           value="<?php echo vmText::_('COM_VIRTUEMART_ADDTOCART_CHOOSE_VARIANT') ?>" disabled/>
    <?php
}