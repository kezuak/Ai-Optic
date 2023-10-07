/**
 *
 * Paypal payment plugin
 *
 * @author Jeremy Magne
 * @author Valérie Isaksen
 * @version $Id: paypal.php 7217 2013-09-18 13:42:54Z alatak $
 * @package VirtueMart
 * @subpackage payment
 * Copyright (C) 2004 - 2014 Virtuemart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
 *
 * http://virtuemart.net
 */

function onboardedCallback(authCode, sharedId){

    /*console.log("am inside");
    console.log(authCode);
    console.log(sharedId);

    console.log('my first url ', Virtuemart.vmSiteurl+"plugins/vmpayments/paypal_checkout/fields/getAccessTokenFromAuthCode.php");*/
    fetch(Virtuemart.vmSiteurl+"administrator/index.php?option=com_virtuemart&view=plugin&vmtype=vmpayment&name=paypal_checkout&task=getAccessTokenFromAuthCode&pm="+vmPP.pm, {
        method: "post",
        body: JSON.stringify({"authCode": authCode, "sharedId" : sharedId, "sellerNonce": vmPP.sellerNonce })

    }).then( h => h.json())
        .then( (data) => {
            console.log(data);

            fetch(Virtuemart.vmSiteurl+"administrator/index.php?option=com_virtuemart&view=plugin&vmtype=vmpayment&name=paypal_checkout&task=getCredentials&pm="+vmPP.pm, {
                method: "post",
                body: JSON.stringify({"access_token": data.access_token})
            }).then( h => h.json() )
                .then( function(data) {
                    console.log(data);
                    fetch(Virtuemart.vmSiteurl+"administrator/index.php?option=com_virtuemart&view=plugin&vmtype=vmpayment&name=paypal_checkout&task=setCredentials&pm="+vmPP.pm, {
                        method: "post",
                        body: JSON.stringify({"client_id": data.client_id, "client_secret": data.client_secret, "payer_id": data.payer_id})
                    }).then( function(data) {
                        window.location.href = Virtuemart.vmSiteurl+"administrator/index.php?option=com_virtuemart&view=paymentmethod&task=edit&cid[]="+vmPP.pm;
                        //console.log(data);
                        return true;
                    });
                });

        });

}

jQuery().ready(function ($) {

    //lets do an ajax here to get merchant credibility, account problems and similar
    checkMerchantCredit = function(){

        $.ajax({
            type: "GET",
            cache: false,
            dataType: "json",
            /*data: config,*/
            url: Virtuemart.vmSiteurl + "administrator/index.php?option=com_virtuemart&view=plugin&vmtype=vmpayment&name=paypal_checkout&task=checkMerchant&pm=" + vmPP.pm,
        }).done( function(data) {
            console.log('checkMerchantCredit my data',data);
            if (data != false && data !='') {

                $("#pp-messages").html(data.msg);
                $("#pp-messages").css('visibility','visible');
                $("#pp-messages").css("border-color", data.color);
                $("#pp-messages").css("border-style", "solid");
                $("#pp-messages").css("border-width", "1px");
                $("#pp-messages").css("padding", "5px");

                if(!data.ACDC){
                    console.log('Disable ACDC');
                    $("#params_paypal_products").find("option[value='hosted-fields']").remove();
                    $("#params_paypal_products_chzn_o_1").remove();
                }

                if(!data.pui){
                    console.log('Disable PUI');
                    $("#params_paypal_products").find("option[value='pui']").remove();
                    $("#params_paypal_products_chzn_o_2").remove();
                }

                if(!data.apm){
                    console.log('Disable APMs');
                    $("#params_paypal_products").find("option[value='sofort']").remove();
                    $("#params_paypal_products_chzn_o_3").remove();

                    $("#params_paypal_products").find("option[value='bancontact']").remove();
                    $("#params_paypal_products_chzn_o_4").remove();

                    $("#params_paypal_products").find("option[value='blik']").remove();
                    $("#params_paypal_products_chzn_o_5").remove();

                    $("#params_paypal_products").find("option[value='eps']").remove();
                    $("#params_paypal_products_chzn_o_6").remove();

                    $("#params_paypal_products").find("option[value='giropay']").remove();
                    $("#params_paypal_products_chzn_o_7").remove();

                    $("#params_paypal_products").find("option[value='ideal']").remove();
                    $("#params_paypal_products_chzn_o_8").remove();

                    $("#params_paypal_products").find("option[value='mybank']").remove();
                    $("#params_paypal_products_chzn_o_9").remove();

                    $("#params_paypal_products").find("option[value='p24']").remove();
                    $("#params_paypal_products_chzn_o_10").remove();

                }

                //window.location.href = Virtuemart.vmSiteurl + "index.php?option=com_virtuemart&view=plugin&vmtype=vmpayment&name=paypal_checkout&task=ordercompleted";
               // window.location.href = Virtuemart.vmSiteurl + "index.php?option=com_virtuemart&view=cart&layout=orderdone";
                if(vmPP.debug == '1') {console.log('checkMerchantCredit ',data);}
                //alert('Thank your your order');
            } else {
                if(vmPP.debug == '1') {console.log('checkMerchantCredit ',data);}
                //Virtuemart.emptyOrderId(true);
                alert('Something went wrong capturing the payment');
            }
            //window.location.href = Virtuemart.vmSiteurl + "index.php?option=com_virtuemart&view=cart";

        })
    }

    /************/
    /* Handlers */
    /************/
    handleSandbox = function () {

        var sandBoxCheck = $("input[name='params[sandbox]']");

        sandBoxCheck
            .off("change",sendCurrentPPForm)
            .on("change",sendCurrentPPForm);
        // jQuery(document).off("load updateVirtueMartProductDetail updateVirtueMartCartModule", Virtuemart.renderPayPalButtons);
       // sandbox.off('click', )

        var sandbox = $("input[name='params[sandbox]']:checked").val();
        if (sandbox == 1) {
            var sandboxmode = 'sandbox';
        } else {
            var sandboxmode = 'production';
        }

        //$("input[name='params[sandbox]']:checked")
        /*$('.std,.api,.live,.sandbox,.sandbox_warning, .accelerated_onboarding').parents('.control-group').hide();
        $('.get_sandbox_credentials').hide();
        $('.get_paypal_credentials').hide();
        // $('.authentication').hide();
        $('.authentication').parents('.control-group').hide();*/

        if (sandboxmode == 'production') {
            $('.live').parents('.control-group').show();
            $('.sandbox').parents('.control-group').hide();
            $('.sandbox_warning').parents('.control-group').hide();
        } else {
            $('.live').parents('.control-group').hide();
            $('.sandbox').parents('.control-group').show();
            $('.sandbox_warning').parents('.control-group').show();
        }
    }

    sendCurrentPPForm = function (e) {

        //Joomla.submitbutton('apply');
        $('#adminForm').find("input[name='task']").val('apply') ;
        $('#adminForm').submit();
    }

    handlePUIButton = function () {

        var product = $("[name='params[paypal_products]']").val();
        console.log('handlePUIButton',product);

        if (product == 'pui') {
            var paypalproduct = $('#params_pui_instructions').parent();
            var sandbox = $("input[name='params[sandbox]']:checked").val();
            if (sandbox == 1) {
                var sandboxDot = 'sandbox.';
            } else {
                var sandboxDot = '';
            }
            var url = 'https://www.'+sandboxDot+'paypal.com/bizsignup/entry?country.x='+vmPP.country+'&product=payment_methods&capabilities=PAY_UPON_INVOICE';
            console.log('handlePUIButton',vmPP.reqApprovalPP,sandboxDot,paypalproduct);
            paypalproduct.append('<div id="reqApprovalPP"><a target="_blank" href="'+url+'" data-paypal-button="PPLtBlue">'+vmPP.reqApprovalPP+'</a></div>' );

            console.log('Pui case');
            $('.showPUI').parents('.control-group').show();
            $('.hidePUI').parents('.control-group').hide();
        } else if (product != 'buttons' /*&& product!= 'hosted-fields'*/) {
            console.log('APM case');
            $('#reqApprovalPP').remove('#reqApprovalPP');
            $('.hidePUI').parents('.control-group').show();
            $('.showPUI').parents('.control-group').hide();
            $('.hideAPM').parents('.control-group').hide();

            if (product == 'hosted-fields') {
                console.log('Hosted case');
                $('.showHosted').parents('.control-group').show();
            } else {
                $('.showHosted').parents('.control-group').hide();
            }

        }
        else {
            console.log('Buttons hosted');
            $('#reqApprovalPP').remove('#reqApprovalPP');

            $('.hidePUI').parents('.control-group').show();
            $('.hideAPM').parents('.control-group').show();
            if (product == 'hosted-fields') {
                $('.showHosted').parents('.control-group').show();
            } else {
                $('.showHosted').parents('.control-group').hide();
            }
            $('.showPUI').parents('.control-group').hide();
        }
    }

    $("input[name='params[sandbox]']").change(function () {
        handleSandbox();
    });

    $("#params_paypal_products").change(function () {
        handlePUIButton();
    });

    handleSandbox();
    handlePUIButton();
    checkMerchantCredit();
});
