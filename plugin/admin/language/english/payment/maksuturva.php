<?php
/**
 * Maksuturva Payment Module for osCommerce 2.3.x
 * Module developed by
 * 	RunWeb Desenvolvimento de Sistemas LTDA and
 *  Movutec Oy
 *
 * www.runweb.com.br
 * www.movutec.com
 */
// Heading
$_['heading_title']       		= 'Maksuturva/eMaksut';
$_['heading_maksuturva']   		= 'Maksuturva/eMaksut Verify';
$_['text_maksuturva']			= '<a onclick="window.open(\'https://www.maksuturva.fi\');"><img src="view/image/payment/maksuturva.png" alt="Maksuturva" title="Maksuturva/eMaksut" style="border: 1px solid #EEEEEE;" /></a>';
$_['text_verify']				= 'Verify all pending payments';
$_['text_kauppias']				= 'Open KauppiasExtranet to view payments';
$_['text_verifylist']			= 'List for verified pending payments';
$_['text_order']				= 'Order ID';
$_['text_message']				= 'Message from Maksuturva';
$_['text_payment']				= 'Payment';
$_['text_success']				= 'Configuration saved!';

// Entries configuration (on install)
$_['entry_sandbox']         	= 'Sandbox Mode:<br /><span class="help">Do you want to enable the test mode? All the payments will not be real.</span>';
$_['entry_secretkey']         	= 'Secret Key:<br /><span class="help">Your unique secret key provided by Maksuturva/eMaksut.</span>';
$_['entry_sellerid']         	= 'Seller id:<br /><span class="help">The seller identification provided by Maksuturva/eMaksut upon your registration.</span>';
$_['entry_keyversion']         	= 'Secret Key Version:<br /><span class="help">The version of the secret key provided by Maksuturva</span>';
$_['entry_url']         		= 'Communication URL:<br /><span class="help">The URL used to communicate with maksuturva. Do not change this configuration unless you know what you are doing.</span>';
$_['entry_encoding']			= 'Communication encoding:<br /><span class="help">Maksuturva accepts both ISO-8859-1 and UTF-8 encodings to receive the transactions.</span>';
$_['entry_emaksut']				= 'eMaksut:<br /><span class="help">Use eMaksut payment service instead of Maksuturva.</span>';
$_['entry_status']       		= 'Module Status:';
$_['entry_sort_order']   		= 'Sort Order:';
$_['entry_created']       		= 'Status for a created order:';
$_['entry_completed']      		= 'Status for a complete (successfully) order:';
$_['entry_cancelled']      		= 'Status for a cancelled order:';
$_['entry_error']	      		= 'Status for a failed order:';
$_['entry_delayed']      		= 'Status for a delayed order (giropay):';

$_['error_sellerid']			= 'Seller ID is mandatory';
$_['error_secretkey']			= 'Secret key is mandatory';

// Order status update
$_['ERROR_QUERY']			= 'Error on status posting (check your CURL PHP configuration)';
$_['ERROR_RESPONSE_FALSE']	= 'Error: order not found on Maksuturva or connection failed';
$_['PAYMENT_IDENTIFIED']	= 'Payment confirmed by Maksuturva/eMaksut.';
$_['PAYMENT_CANCELLED']		= 'Payment cancelled by customer.';
$_['NO_CHANGE']				= 'Waiting for payment confirmation by Maksuturva/eMaksut';
?>
