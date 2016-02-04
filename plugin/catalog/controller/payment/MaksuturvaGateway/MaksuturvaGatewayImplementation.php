<?php
/**
 * Maksuturva Payment Module for osCommerce 2.3.x
 * Module developed by
 * 	RunWeb Desenvolvimento de Sistemas LTDA and
 *  Movutec Oy
 *
 * www.runweb.com.br
 * www.movutec.com
 * Creation date: 01/12/2011
 * Last update: 31/01/2012
 */
require_once dirname(__FILE__) . '/MaksuturvaGatewayAbstract.php';

/**
 * Main class for gateway payments
 * @author RunWeb
 */
class MaksuturvaGatewayImplementation extends MaksuturvaGatewayAbstract
{
	var $sandbox = false;
	var $specialChars = array("\n", "\t");
	
	function __construct($order_info, $controller, $encoding)
	{
	    if ($controller->config->get('maksuturva_sandbox')) {
	    	$this->sandbox = true;
	        $secretKey = '11223344556677889900';
	        $sellerId = 'testikauppias';
	    } else {
	        $secretKey = $controller->config->get('maksuturva_secretkey');
	        $sellerId = $controller->config->get('maksuturva_sellerid');;
		}
		$dueDate = date("d.m.Y");
		$id = self::getMaksuturvaId($order_info['order_id']);
		//Adding each product from order
		$products_rows = array();
		if ($controller->cart){
			$products = $controller->cart->getProducts();
		} else {
			$products = array();
		}
		$products_total = 0;
		
		foreach ($products as $product) {
			// get current product tax
			$tax = 0;
			if ($controller->config->get('config_tax') && method_exists($controller->tax, 'getTax')){
				$tax = $controller->tax->getTax(100, $product["tax_class_id"]);
			}
			
			$name = strip_tags( htmlspecialchars_decode( $product["name"] ) );
			$model = strip_tags( htmlspecialchars_decode( $product["model"] ) );
			$price = $product["price"];
			
			if ($controller->currency && $order_info['currency_value']){
				$price = $controller->currency->format($price, $order_info['currency_code'], $order_info['currency_value'], false);
			}

			$products_total += $price * $product["quantity"] * (1.0 + ($tax / 100));
		    $row = array(
		        'pmt_row_name' => $name,                                                      //alphanumeric        max lenght 40             -
            	'pmt_row_desc' => $name . ": " . $model,                                                       //alphanumeric        max lenght 1000      min lenght 1
            	'pmt_row_quantity' => $product["quantity"],                                                     //numeric             max lenght 8         min lenght 1
            	'pmt_row_deliverydate' => date("d.m.Y"),                                                   //alphanumeric        max lenght 10        min lenght 10        dd.MM.yyyy
            	'pmt_row_price_net' => str_replace('.', ',', sprintf("%.2f", $price )),          //alphanumeric        max lenght 17        min lenght 4         n,nn
            	'pmt_row_vat' => str_replace('.', ',', sprintf("%.2f", $tax)),                  //alphanumeric        max lenght 5         min lenght 4         n,nn
            	'pmt_row_discountpercentage' => "0,00",                                                    //alphanumeric        max lenght 5         min lenght 4         n,nn
            	'pmt_row_type' => 1,
		    );
		    array_push($products_rows, $row);
		}


		$total_data = array();
		$discount = 0;
		$shipping = 0;
		$taxes = $controller->cart->getTaxes();
		$controller->load->model('setting/extension');
		$results = $controller->model_setting_extension->getExtensions('total');
		
		foreach ($results as $result) {
			if ($controller->config->get($result['code'] . '_status')){
				if ($result['code'] == 'coupon') {
					$controller->load->model('total/coupon');
					$controller->model_total_coupon->getTotal($total_data, $discount, $taxes);
				} else if  ($result['code'] == 'shipping'){
					$controller->load->model('total/shipping');
					$controller->model_total_shipping->getTotal($total_data, $shipping, $taxes);
				}
			}
		}
		$total = $order_info['total'];
		if ($controller->currency && $order_info['currency_value']){
			$shipping = $controller->currency->format($shipping, $order_info['currency_code'], $order_info['currency_value'], false);
			$total = $controller->currency->format($total, $order_info['currency_code'], $order_info['currency_value'], false);
		}
		
		// forcing discount to be the diference (maybe some uncovered opencart's feature)
		$discount = round($total, 2) - ($shipping + $products_total);
		// Adding the shipping cost as a row
		if ($controller->cart){
			$shipping_cost = $shipping;

		} else {
			$shipping_cost = 0;
		}
		if ($discount < 0){
			$row = array(
			    'pmt_row_name' => 'Coupon Discount',
	        	'pmt_row_desc' => 'Coupon Discount',
	        	'pmt_row_quantity' => 1,
	        	'pmt_row_deliverydate' => date("d.m.Y"),
	        	'pmt_row_price_net' => str_replace('.', ',', sprintf("%.2f", $discount)),
	        	'pmt_row_vat' => "0,00",
	        	'pmt_row_discountpercentage' => "0,00",
	        	'pmt_row_type' => 6,
			);
			array_push($products_rows, $row);
			$products_total += $discount;
		} else {
			$shipping_cost += $discount;
		}

		$row = array(
		    'pmt_row_name' => 'Shipping cost',
        	'pmt_row_desc' =>  ( (trim($order_info["shipping_method"]) != '') ? $order_info["shipping_method"] : 'No Shipping method'),
        	'pmt_row_quantity' => 1,
        	'pmt_row_deliverydate' => date("d.m.Y"),
        	'pmt_row_price_net' => str_replace('.', ',', sprintf("%.2f", $shipping_cost)),
        	'pmt_row_vat' => "0,00",
        	'pmt_row_discountpercentage' => "0,00",
        	'pmt_row_type' => 2,
		);
		array_push($products_rows, $row);
		
		$options = array(
			"pmt_keygeneration" => $controller->config->get('maksuturva_keyversion') ,
			"pmt_id" 		=> $id,
			"pmt_orderid"	=> $id,
			"pmt_reference" => $id,
			"pmt_sellerid" 	=> $sellerId,
			"pmt_duedate" 	=> $dueDate,

			"pmt_okreturn"	=> $this->getLink('payment/maksuturva/callback', '', 'SSL'),
			"pmt_errorreturn"	=> $this->getLink('payment/maksuturva/error', '', 'SSL'),
			"pmt_cancelreturn"	=> $this->getLink('payment/maksuturva/cancel', '', 'SSL'),
			"pmt_delayedpayreturn"	=> $this->getLink('payment/maksuturva/delay', '', 'SSL'),
			"pmt_amount" 		=> str_replace('.', ',', sprintf("%.2f", $products_total)),

			// Customer Information
			"pmt_buyername" 	=> trim($order_info["payment_firstname"] . " " . $order_info["payment_lastname"]),
		    "pmt_buyeraddress" => $order_info["payment_address_1"] . " " . $order_info["payment_address_2"],
			"pmt_buyerpostalcode" => $order_info['payment_postcode'],
			"pmt_buyercity" => $order_info['payment_city'],
			"pmt_buyercountry" => $order_info['payment_iso_code_2'],
		    "pmt_buyeremail" => $order_info['email'],

			// emaksut
			"pmt_escrow" => ($controller->config->get('maksuturva_emaksut') == "0" ? "Y" : "N"),

		    // Delivery information
			"pmt_deliveryname" 	=> trim($order_info["shipping_firstname"] . " " . $order_info["shipping_lastname"]),
		    "pmt_deliveryaddress" => $order_info["shipping_address_1"] . " " . $order_info["shipping_address_2"],
			"pmt_deliverypostalcode" => $order_info['shipping_postcode'],
			"pmt_deliverycity" => $order_info['shipping_city'],
			"pmt_deliverycountry" => $order_info['shipping_iso_code_2'],

			
			"pmt_sellercosts" => str_replace('.', ',', sprintf("%.2f", $shipping_cost)),

		    "pmt_rows" => count($products_rows),
		    "pmt_rows_data" => $products_rows

		);
		
		#var_dump($options);exit;
		parent::__construct($secretKey, $options, $encoding, $controller->config->get('maksuturva_url'));		
	}
	
	
	static public function getMaksuturvaId($order_id){
		return intval($order_id) + 100;
	}
	static public function getOrderId($maksuturva_id){
		return intval($maksuturva_id) - 100;
	}
	
    public function calcPmtReferenceCheckNumber()
    {
        return $this->getPmtReferenceNumber($this->_formData['pmt_reference']);
    }

    public function calcHash()
    {
        return $this->generateHash();
    }

    public function getHashAlgo()
    {
        return $this->_hashAlgoDefined;
    }
    
    /*private function convert_encoding($value, $encoding){
    	
		return str_replace($this->specialChars, '', html_entity_decode($value, ENT_QUOTES, $encoding));
    	
    }*/
    private function getLink($route, $args, $ssl){
		if ($this->url){
			$url = $this->url->link($route, $args, $ssl);
		} else {
			$url = HTTPS_SERVER . "index.php?route=".$route."&".$args; 
		}
		return $url;
	} 

}
