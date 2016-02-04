<?php
class ControllerPaymentMaksuturva extends Controller {
	// variables from GET on payment return
    var $_compulsoryReturnData = array(
    	"pmt_action",
    	"pmt_version",
    	"pmt_id",
    	"pmt_reference",
    	"pmt_amount",
    	"pmt_currency",
    	"pmt_sellercosts",
    	"pmt_paymentmethod",
    	"pmt_escrow",
    	"pmt_hash"
    );
	
 	protected function index() {
		$this->language->load('payment/maksuturva');
		$this->id = 'payment';
		$this->code = 'maksuturva';
	    $this->data['button_confirm'] = $this->language->get('button_confirm');
	    $this->data['text_information'] =  $this->language->get('text_information');
		$this->data['error_information'] = "";		
		$sandbox = $this->config->get('maksuturva_sandbox');

		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		if ($order_info) {
			
			require_once dirname(__FILE__) . '/MaksuturvaGateway/MaksuturvaGatewayImplementation.php';
			$this->data['action'] = MaksuturvaGatewayImplementation::getPaymentUrl($this->config->get('maksuturva_url'));
			
			#$products = $this->cart->getProducts();
			#$total = $this->cart->getTotal();
			$gateway = new MaksuturvaGatewayImplementation($order_info, $this, $this->config->get("maksuturva_encoding"));
			
	    	$returnString = '';
	    	foreach($gateway->getFieldArray() as $key => $value) {
	            $returnString .= '<input type="hidden" name="' . $key . '" value="' . $value . '"/>';
	    	}
			$this->data['maksuturvaForm'] =  $returnString;
			$this->data['sandbox'] = $this->config->get('maksuturva_sandbox');
			if ( $this->currency->getCode() != "EUR") {
				$this->data['error_information'] = $this->language->get('maksuturva_not_euro');
			}

			$this->load->model('checkout/order');
			//$this->model_checkout_order->confirm($order_info['order_id'], $this->config->get('maksuturva_created'), $notify=FALSE);
			
		    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/maksuturva.tpl')) {
		    	$this->template = $this->config->get('config_template') . '/template/payment/maksuturva.tpl';
		    } else{
		      	$this->template = 'default/template/payment/maksuturva.tpl';
		    }	
			
		    $this->render();			
		}
	}

	public function message($message, $link=null){
		$this->data['text_title'] = $this->language->get('text_title');
		$this->data['text_message'] = $this->language->get($message);

		
	    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/maksuturva_callback.tpl')) {
	    	$this->template = $this->config->get('config_template') . '/template/payment/maksuturva_callback.tpl';
	    } else{
	      	$this->template = 'default/template/payment/maksuturva_callback.tpl';
	    }	
	    $this->response->setOutput($this->render());	
	}

	
	public function error() {
		$this->callback('maksuturva_error', 'checkout/checkout');
	}

	public function cancel() {
		$this->callback('maksuturva_cancelled', 'checkout/checkout');
	}
	
	public function delay() {
		$this->callback('maksuturva_delayed', 'checkout/success');
	}
	
	public function callback($status = false, $link = null) {
		
		$this->load->model('checkout/order');
		$this->language->load('payment/maksuturva');
		
		require_once dirname(__FILE__) . '/MaksuturvaGateway/MaksuturvaGatewayImplementation.php';
		$order_id = MaksuturvaGatewayImplementation::getOrderId($this->request->get['pmt_id']);
		$order_info = $this->model_checkout_order->getOrder($order_id);
		
		$this->load->library('user');
		$this->user = new User($this->registry);

		// check if order belongs to logged user - if not, stop callback. Also, let's ignore it if it's a guest payment.
		if (($this->customer->getId() != $order_info['customer_id']) && ($order_info['customer_id'] != 0)) {
			exit;
		}
	
	
		
		if ($status == false){
			$values = array();
	        foreach ($this->_compulsoryReturnData as $field) {
	        	if (isset($this->request->get[$field])) {
	        	    $values[$field] = $this->request->get[$field];
	            } else {
	  	        	return $this->message('EMPTY_ANSWER');
	            }
	        }
    		// now, validate the hash
            
            // instantiate the gateway with the original order
			$gateway = new MaksuturvaGatewayImplementation($order_info, $this, $this->config->get("maksuturva_encoding"));
    		// calculate the hash for order
        	$calculatedHash = $gateway->generateReturnHash($values);
        	// test the hash
        	if (!($calculatedHash == $values['pmt_hash'])) {
        	    return $this->message('RETURN_HASH');
        	}			

			// Then we have a confirmed payment
			$this->model_checkout_order->confirm($order_info['order_id'], $this->config->get('maksuturva_completed'));
			$this->redirect( $this->getLink('checkout/success', '', 'SSL') );
		}
		// Check if order is still not answered
		if ($order_info['order_status_id'] != $this->config->get('maksuturva_completed')) {
			if ($status == 'maksuturva_delayed'){
				$this->model_checkout_order->confirm($order_info['order_id'], $this->config->get($status));
			} else {
				$this->model_checkout_order->update($order_info['order_id'], $this->config->get($status), $notify = false);
			}
			$this->redirect( $this->getLink($link, '', 'SSL') );
		}
		else{
			return $this->message('ORDER_IN_PROGRESS');
		}
			
	}
	private function getLink($route, $args, $ssl){
		if ($this->url){
			$url = $this->url->link($route, $args, $ssl);
		} else {
			$url = HTTPS_SERVER . "index.php?route=".$route."&".$args; 
		}
		return $url;
	} 

}
?>
