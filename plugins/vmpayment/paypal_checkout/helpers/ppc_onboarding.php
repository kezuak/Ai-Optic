<?php

/**
 *
 * Paypal checkout payment plugin
 *
 * @author Max Milbers
 * @version $Id: ppc_identity.php
 * @package VirtueMart
 * @subpackage payment
 * Copyright (C) 2023 Virtuemart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
 *
 * http://virtuemart.net
 */


class PayPalOnboarding {

	static function getAccessTokenFromAuthCode ($plugin, &$render) {
		$data = file_get_contents("php://input");
		$data = json_decode($data);

		$postData = 'grant_type=authorization_code&code=' . $data->authCode . '&code_verifier=' . $data->sellerNonce;

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => PayPalToken::getUrl($plugin->_currentMethod)."/v1/oauth2/token",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_SSL_VERIFYPEER => true,
			CURLOPT_USERPWD => $data->sharedId,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => $postData,
			CURLOPT_HTTPHEADER => array(
				"Content-Type: application/x-www-form-urlencoded"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);

		if ($err) {
			echo "cURL Error #:" . $err;
		} else {
			//$render = $response;
			$response = json_decode($response);
			if(!empty($response->access_token)){
				echo '{"access_token": "' . $response->access_token . '"}';
			} else {
				echo 0;
			}

			die;
		}
	}

	static function getCredentials ($plugin, &$render) {

		$data = file_get_contents("php://input");
		$data = json_decode($data);

		if($plugin->_currentMethod->sandbox=='' or !empty($plugin->_currentMethod->sandbox)){
			$partnerId = "J4B84GGFGKHTS";
		} else {
			$partnerId = "WBA3Y7FQXGVW4";
		}


		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => PayPalToken::getUrl($plugin->_currentMethod)."/v1/customer/partners/$partnerId/merchant-integrations/credentials/",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => true,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				"Content-Type: application/json",
				"Authorization: Bearer ". $data->access_token
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);
		print_r($response);
		print_r($err);

		die;
	}

	static function setCredentials($plugin, &$render){

		$data = file_get_contents("php://input");
		$data = json_decode($data);

		self::storeMethodsClientData($plugin, $data);

		return true;
	}

	static function checkMerchant($plugin, &$render){

		if($plugin->_currentMethod->sandbox=='' or !empty($plugin->_currentMethod->sandbox)){
			$partnerId = "J4B84GGFGKHTS";
			$sandbox = 'sandbox_';
		} else {
			$partnerId = "WBA3Y7FQXGVW4";
			$sandbox = '';
		}

		$url = PayPalToken::getUrl($plugin->_currentMethod).'/v1/customer/partners/'.$partnerId.'/merchant-integrations/'.$plugin->_currentMethod->{$sandbox.'paypal_merchant_id'};
		PayPalToken::getPayPalAccessToken($plugin);
		$data = '';

		$options = PayPalToken::getMinimalHeaderInOptions($plugin->_currentMethod);
		$body = PayPalToken::sendCURL($options, $url, $data, 'get');

		$data = new stdClass();
		$data->msg = array();
		if(isset($body->payments_receivable) and empty($body->payments_receivable)){
			//$data->msg = array();
			$data->msg[]= vmText::sprintf('Attention: You currently cannot receive payments due to restriction on your PayPal account. Please reach out to PayPal Customer Support or connect to %1$s for more information.','https://www.paypal.com');
			$data->color = 'red';
		}
		if(isset($body->primary_email_confirmed) and empty($body->primary_email_confirmed)){
			//$data->msg = array();
			$data->msg[]= " ".vmText::sprintf('Attention: Please confirm your email address on %1$s in order to receive payments! You currently cannot receive payments.','https://www.paypal.com/businessprofile/settings');
			$data->color = 'red';
		}

		$currencyM = VmModel::getModel('currency');
		$currencycode = $currencyM->getCurrency($plugin->_currentMethod->currency_id)->currency_code_3;
		//supported currencies https://developer.paypal.com/docs/reports/reference/paypal-supported-currencies/
		$supportedCurrencies = array('AUD','BRL','CAD','CNY','CZK','DKK','EUR','HKD','HUF','ILS','JPY','MYR','MXN','TWD','NZD','NOK','PHP','PLN','GBP','SGD','SEK','CHF','THB','USD');
		if(!in_array($currencycode, $supportedCurrencies)){
			$data->msg[]= " ".vmText::sprintf('Attention: Your selected currency is not supported by PayPal. Use the link to see a list of accepted currencies %1$s','https://developer.paypal.com/docs/reports/reference/paypal-supported-currencies/');
			$data->color = 'red';
		}

		$vendorM = VmModel::getModel('vendor');
		$address = $vendorM->getVendorAdressBT($plugin->_currentMethod->virtuemart_vendor_id);

		$data->ACDC = false;
		$data->apm = false;
		$data->pui = false;
		if(!empty($body->capabilities)){
			foreach($body->capabilities as $capable){
				if($capable->name=='PAYPAL_CHECKOUT_ALTERNATIVE_PAYMENT_METHODS' and $capable->status=='ACTIVE'){
					$data->apm = true;
				} else if($capable->name=='CUSTOM_CARD_PROCESSING' and $capable->status=='ACTIVE'){
					$data->ACDC = true;
				} else if($capable->name=='PAY_UPON_INVOICE' and $capable->status=='ACTIVE'){
					$data->pui = true;
				}
			}
		}

		VmModel::getModel('country');
		$countryCode = VirtueMartModelCountry::getCountryFieldByID($address->virtuemart_country_id, 'country_3_code');
		$supportedCountries = array('AUS','CAN','FRA','DEU','ITA','ESP','GBR','USA','MEX','JPN');
		//Country check for Advanced Credit and Debitcard.

		if(!in_array($countryCode, $supportedCountries)){
			$data->ACDC = false;
		}

		if(empty($data->msg)){
			if(isset($body->products)){
				$products = array();
				foreach($body->products as $product){
					if($product->name=='PPCP_CUSTOM' and $product->vetting_status=='SUBSCRIBED') {
						//if(!empty($body->scopes)){
						$data->msg[] = vmText::_('Your account is setup and ready for advanced credit card.');
						$data->color = 'green';

						//}
					} else if($product->name=='PPCP_STANDARD' and $product->vetting_status=='SUBSCRIBED'){
						$data->msg[] = ' '.vmText::_('Your account is setup and ready for PayPal Standard');
						$data->color = 'green';
					} else if($product->name=='EXPRESS_CHECKOUT' and $product->status=='ACTIVE'){
						$data->msg[] = ' '.vmText::_('Your account is setup and ready for PayPal Express');
						$data->color = 'green';
					} else {
						$products[] = $product->name;
					}
				}
				if(empty($data->msg)){
					$data->msg = array();
					if(empty($products)){
						$cR = 'none';
					} else {
						$cR = implode(', ',$products);
					}
					$data->msg[]= vmText::_('Your account is not ready for transaction. Currently registered products: '.$cR);
					vmdebug('Your account is not ready for Transaction. Full response',$body);
				} else {
					if ($render === null) {
						echo vmJsApi::safe_json_encode($data);
						die;
					} else {
						return true;
					}
				}
			}
		}

		if(!empty($data->msg)){
			$paymM = VmModel::getModel('paymentmethod');
			$table = $paymM->getTable();
			$table->load($plugin->_currentMethod->virtuemart_paymentmethod_id);
			$table->toggle('published','0');
			if($render!==null and $render == false){
				return false;
			}
		}

		if($render===null){
			echo vmJsApi::safe_json_encode( $data );
			die;
		} else {
			return true;
		}

	}

	static function disconnectMerchant($plugin, &$render){
		$pmId = vRequest::getInt('pm');
		if($pmId){

			self::storeMethodsClientData($plugin, false);
			$app = JFactory::getApplication();
			$app->redirect('index.php?option=com_virtuemart&view=paymentmethod&task=edit&cid[]='.$pmId);
		}

		return true;
	}

	static function storeMethodsClientData($plugin, $data){

		$sandbox = 'sandbox_';
		if($plugin->_currentMethod->sandbox=='0'){
			$sandbox = '';
		}

		$pModel = VmModel::getModel('paymentmethod');
		$methods = $plugin->getPluginMethods($plugin->_currentMethod->virtuemart_vendor_id, false);
		foreach($plugin->methods as $method){
			$payment = $pModel->getPayment($method->virtuemart_paymentmethod_id);
			if($data===false){
				$payment->{$sandbox.'client_id'} = '';
				$payment->{$sandbox.'client_secret'} = '';
				$payment->{$sandbox.'paypal_merchant_id'} = '';
			} else {
				if(!empty($data->client_id)) $payment->{$sandbox.'client_id'} = $data->client_id;    //vRequest::getCmd('client_id',false);
				if(!empty($data->client_secret)) $payment->{$sandbox.'client_secret'} = $data->client_secret;  //vRequest::getCmd('client_secret',false);
				if(!empty($data->payer_id)) $payment->{$sandbox.'paypal_merchant_id'} = $data->payer_id;
			}

			unset($payment->payment_name);
			unset($payment->payment_desc);
			unset($payment->slug);
			$payment->_update = true;
			$payment->_xParams = 'payment_params';
			VmEcho::$echoDebug = 1;
			vmdebug('setCredentials, storing payment ',$payment);
			$payment->store();
		}

	}
}