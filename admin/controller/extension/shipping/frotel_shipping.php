<?php
/**
 * User: ReZa ZaRe <Rz.ZaRe@Gmail.com>
 * Date: 5/29/15
 * Time: 12:04 AM
 */

class ControllerExtensionShippingFrotelShipping extends Controller
{
    private $error = array();

    public function index()
    {
        $this->language->load('extension/shipping/frotel_shipping');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('frotel_shipping', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/extension', 'type=shipping&token=' . $this->session->data['token'], 'SSL'));
        }
        /* entry ,text */
        $data['heading_title'] = $this->language->get('heading_title');

        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_sort'] = $this->language->get('entry_sort');
        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        /* button */
        $data['action'] = $this->url->link('extension/shipping/frotel_shipping','token='.$this->session->data['token'],'SSL');
        $data['cancel'] = $this->url->link('extension/shipping','type=shipping&token='.$this->session->data['token'],'SSL');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        /* breadcrumbs , error message*/
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_shipping'),
            'href'      => $this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('shipping/frotel_shipping', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        /* data */
        if (isset($this->request->post['frotel_shipping_status'])) {
            $data['frotel_shipping_status'] = $this->request->post['frotel_shipping_status'];
        } else {
            $data['frotel_shipping_status'] = $this->config->get('frotel_shipping_status');
        }

        if (isset($this->request->post['frotel_shipping_sort'])) {
            $data['frotel_shipping_sort'] = $this->request->post['frotel_shipping_sort'];
        } else {
            $data['frotel_shipping_sort'] = $this->config->get('frotel_shipping_sort');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/shipping/frotel.tpl', $data));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/shipping/frotel_shipping')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}