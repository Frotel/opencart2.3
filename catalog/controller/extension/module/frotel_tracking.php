<?php  
class ControllerExtensionModuleFrotelTracking extends Controller {

	public function index($setting)
	{
		$this->load->language('extension/module/frotel_tracking');
		$data['heading_title'] = $this->language->get('heading_title');
		$data['frotel_tracking_id'] = uniqid();

		$data['button_tracking'] = $this->language->get('button_tracking');

		$data['text_tracking_factor'] = $this->language->get('text_tracking_factor');
		$data['text_order_code'] = $this->language->get('text_order_code');
		$data['text_order_status'] = $this->language->get('text_order_status');

      	$data['heading_title'] = $this->language->get('heading_title');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/frotel_tracking.tpl')) {
			$template = $this->config->get('config_template') . '/template/module/frotel_tracking.tpl';
		} else {
			$template = 'default/template/module/frotel_tracking.tpl';
		}

        $this->document->addScript('catalog/view/javascript/frotel_tracking/tracking.js');
		return $this->load->view($template,$data);
	}
}