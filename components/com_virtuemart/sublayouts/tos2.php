<?php
/**
 * field tos
 *
 * @package	VirtueMart
 * @subpackage Cart
 * @author Max Milbers
 * @link https://virtuemart.net
 * @copyright Copyright (c) 2014 - 2017 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL2, see LICENSE.php
 * @version $Id: cart.php 7682 2014-02-26 17:07:20Z Milbo $
 */

defined('_JEXEC') or die('Restricted access');

/** @var TYPE_NAME $viewData */
$_prefix = $viewData['prefix'];
$field = $viewData['field'];
$tos = $field['value'];

if(VmConfig::isSiteByApp()){
	vmJsApi::popup('#full-tos2','#terms-of-service2');
	$cart = VirtuemartCart::getCart();
	$cart->prepareVendor();
	if(empty($tos) and !VmConfig::get ('agree_to_tos_onorder', true)){
		if(is_array($cart->BT) and !empty($cart->BT['tos2'])){
			$tos = $cart->BT['tos2'];
		}
	}
}

$class = 'terms-of-service';
if(!empty($field['required'])){
	$class .= ' required';
}
echo VmHtml::checkbox ($_prefix.$field['name'], $tos, 1, 0, 'class="'.$class.'"', 'tos2');

if ( VmConfig::isSiteByApp() ) {
?>
<div class="terms-of-service">
	<label for="tos">
		<a href="<?php echo JRoute::_ ('index.php?option=com_virtuemart&view=vendor&layout=tos&virtuemart_vendor_id=1', FALSE) ?>" class="terms-of-service" id="terms-of-service2" rel="facebox"
		   target="_blank">
			<span class="vmicon vm2-termsofservice-icon"></span>
			<?php echo vmText::_ ('COM_VIRTUEMART_CART_TOSTWO_READ_AND_ACCEPTED') ?>
		</a>
	</label>

	<div id="full-tos">
		<h2><?php echo vmText::_ ('COM_VIRTUEMART_CART_TOSTWO') ?></h2>
		<?php echo $cart->vendor->vendor_terms_of_service ?>
		</div>
</div>
<?php
}



?>