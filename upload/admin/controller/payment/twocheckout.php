<?php 
class ControllerPaymentTwoCheckout extends Controller {
	private $error = array(); 

	public function index() {
		$this->load->language('payment/twocheckout');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->load->model('setting/setting');
			
			$this->model_setting_setting->editSetting('twocheckout', $this->request->post);				
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->https('extension/payment'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		
		$this->data['entry_account'] = $this->language->get('entry_account');
		$this->data['entry_secret'] = $this->language->get('entry_secret');
		$this->data['entry_test'] = $this->language->get('entry_test');
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');		
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');
		 
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->error['account'])) {
			$this->data['error_account'] = $this->error['account'];
		} else {
			$this->data['error_account'] = '';
		}	
		
		if (isset($this->error['secret'])) {
			$this->data['error_secret'] = $this->error['secret'];
		} else {
			$this->data['error_secret'] = '';
		}	
		
  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('extension/payment'),
       		'text'      => $this->language->get('text_payment'),
      		'separator' => ' :: '
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('payment/twocheckout'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
				
		$this->data['action'] = $this->url->https('payment/twocheckout');
		
		$this->data['cancel'] = $this->url->https('extension/payment');
		
		if (isset($this->request->post['twocheckout_account'])) {
			$this->data['twocheckout_account'] = $this->request->post['twocheckout_account'];
		} else {
			$this->data['twocheckout_account'] = $this->config->get('twocheckout_account');
		}

		if (isset($this->request->post['twocheckout_secret'])) {
			$this->data['twocheckout_secret'] = $this->request->post['twocheckout_secret'];
		} else {
			$this->data['twocheckout_secret'] = $this->config->get('twocheckout_secret');
		}
		
		if (isset($this->request->post['twocheckout_test'])) {
			$this->data['twocheckout_test'] = $this->request->post['paypal_test'];
		} else {
			$this->data['twocheckout_test'] = $this->config->get('twocheckout_test');
		}
		
		if (isset($this->request->post['twocheckout_order_status_id'])) {
			$this->data['twocheckout_order_status_id'] = $this->request->post['twocheckout_order_status_id'];
		} else {
			$this->data['twocheckout_order_status_id'] = $this->config->get('twocheckout_order_status_id'); 
		}
		
		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['twocheckout_geo_zone_id'])) {
			$this->data['twocheckout_geo_zone_id'] = $this->request->post['twocheckout_geo_zone_id'];
		} else {
			$this->data['twocheckout_geo_zone_id'] = $this->config->get('twocheckout_geo_zone_id'); 
		}
		
		$this->load->model('localisation/geo_zone');
										
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['twocheckout_status'])) {
			$this->data['twocheckout_status'] = $this->request->post['twocheckout_status'];
		} else {
			$this->data['twocheckout_status'] = $this->config->get('twocheckout_status');
		}
		
		if (isset($this->request->post['twocheckout_sort_order'])) {
			$this->data['twocheckout_sort_order'] = $this->request->post['twocheckout_sort_order'];
		} else {
			$this->data['twocheckout_sort_order'] = $this->config->get('twocheckout_sort_order');
		}

		$this->template = 'payment/twocheckout.tpl';
		$this->children = array(
			'common/header',	
			'common/footer',	
			'common/menu'	
		);
		
		$this->response->setOutput($this->render(TRUE));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/twocheckout')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['twocheckout_account']) {
			$this->error['account'] = $this->language->get('error_account');
		}

		if (!$this->request->post['twocheckout_secret']) {
			$this->error['secret'] = $this->language->get('error_secret');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
}
?>