<?php
/**
 * User: ReZa ZaRe <Rz.ZaRe@Gmail.com>
 * Date: 5/28/15
 * Time: 10:50 PM
 */

class ControllerExtensionPaymentFrotel extends Controller
{
    private $error = array();

    public function index()
    {
        $this->language->load('extension/payment/frotel');
        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

            if (!isset($this->request->post['frotel_pro_code']) || ($this->request->post['frotel_pro_code'] != 'model' && $this->request->post['frotel_pro_code'] == 'product_id'))
                $this->request->post['frotel_pro_code'] = 'product_id';

            $this->model_setting_setting->editSetting('frotel', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/extension', 'type=payment&token=' . $this->session->data['token'], 'SSL'));
        }


        /* label */
        $data['entry_api'] = $this->language->get('entry_api');
        $data['entry_api_desc'] = $this->language->get('entry_api_desc');
        $data['entry_url'] = $this->language->get('entry_url');
        $data['entry_url_desc'] = $this->language->get('entry_url_desc');
        $data['entry_method_delivery'] = $this->language->get('entry_method_delivery');
        $data['entry_express'] = $this->language->get('entry_express');
        $data['entry_registered'] = $this->language->get('entry_registered');
        $data['entry_method_payments'] = $this->language->get('entry_method_payments');
        $data['entry_online'] = $this->language->get('entry_online');
        $data['entry_cod'] = $this->language->get('entry_cod');
        $data['entry_order_status'] = $this->language->get('entry_order_status');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_verify_status'] = $this->language->get('entry_verify_status');
        $data['entry_verify_status_desc'] = $this->language->get('entry_verify_status_desc');
        $data['entry_sort'] = $this->language->get('entry_sort');
        $data['entry_product_porsant'] = $this->language->get('entry_product_porsant');
        $data['entry_product_porsant_desc'] = $this->language->get('entry_product_porsant_desc');
        $data['entry_default_online_express'] = $this->language->get('entry_default_online_express');
        $data['entry_default_online_registered'] = $this->language->get('entry_default_online_registered');
        $data['entry_default_cod_express'] = $this->language->get('entry_default_cod_express');
        $data['entry_default_cod_registered'] = $this->language->get('entry_default_cod_registered');
        $data['entry_default_weight'] = $this->language->get('entry_default_weight');
        $data['entry_pro_code'] = $this->language->get('entry_pro_code');
        $data['entry_pro_code_desc'] = $this->language->get('entry_pro_code_desc');
        $data['entry_geo_zone'] = $this->language->get('entry_geo_zone');

        /* text and button and form */
        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['action'] = $this->url->link('extension/payment/frotel','token='.$this->session->data['token'],'SSL');
        $data['cancel'] = $this->url->link('extension/extension','type=payment&token='.$this->session->data['token'],'SSL');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_failed_get_price'] = $this->language->get('text_failed_get_price');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_rial'] = $this->language->get('text_rial');
        $data['text_weight_unit'] = $this->language->get('text_weight_unit');
        $data['text_weight_unit_desc'] = $this->language->get('text_weight_unit_desc');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_all_zones'] = $this->language->get('text_all_zones');
        $data['text_product_id'] = $this->language->get('text_product_id');
        $data['text_product_model'] = $this->language->get('text_product_model');
        $data['heading_title'] = $this->language->get('heading_title');

        /* breadcrumbs */
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_payment'),
            'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('payment/frotel', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        if (isset($this->error['warning'])) {
            $data['warning'] = $this->error['warning'];
        } else {
            $data['warning'] = '';
        }

        if (isset($this->error['error'])) {
            $data['error'] = implode('<br />',$this->error['error']);
        } else {
            $data['error'] = '';
        }


        /* data */
        if (isset($this->request->post['frotel_api'])) {
            $data['frotel_api'] = $this->request->post['frotel_api'];
        } else {
            $data['frotel_api'] = $this->config->get('frotel_api');
        }

        if (isset($this->request->post['frotel_url'])) {
            $data['frotel_url'] = $this->request->post['frotel_url'];
        } else {
            $data['frotel_url'] = $this->config->get('frotel_url');
        }

        if (isset($this->request->post['frotel_express'])) {
            $data['frotel_express'] = $this->request->post['frotel_express'];
        } else {
            $data['frotel_express'] = $this->config->get('frotel_express');
        }

        if (isset($this->request->post['frotel_registered'])) {
            $data['frotel_registered'] = $this->request->post['frotel_registered'];
        } else {
            $data['frotel_registered'] = $this->config->get('frotel_registered');
        }

        if (isset($this->request->post['frotel_online'])) {
            $data['frotel_online'] = $this->request->post['frotel_online'];
        } else {
            $data['frotel_online'] = $this->config->get('frotel_online');
        }

        if (isset($this->request->post['frotel_cod'])) {
            $data['frotel_cod'] = $this->request->post['frotel_cod'];
        } else {
            $data['frotel_cod'] = $this->config->get('frotel_cod');
        }

        if (isset($this->request->post['frotel_status'])) {
            $data['frotel_status'] = $this->request->post['frotel_status'];
        } else {
            $data['frotel_status'] = $this->config->get('frotel_status');
        }

        if (isset($this->request->post['frotel_verify_status'])) {
            $data['frotel_verify_status'] = $this->request->post['frotel_verify_status'];
        } else {
            $data['frotel_verify_status'] = $this->config->get('frotel_verify_status');
        }

        if (isset($this->request->post['frotel_order_status'])) {
            $data['frotel_order_status'] = $this->request->post['frotel_order_status'];
        } else {
            $data['frotel_order_status'] = $this->config->get('frotel_order_status');
        }

        if (isset($this->request->post['frotel_product_porsant'])) {
            $data['frotel_product_porsant'] = $this->request->post['frotel_product_porsant'];
        } else {
            $data['frotel_product_porsant'] = $this->config->get('frotel_product_porsant');
        }

        if (isset($this->request->post['frotel_sort'])) {
            $data['frotel_sort'] = $this->request->post['frotel_sort'];
        } else {
            $data['frotel_sort'] = $this->config->get('frotel_sort');
        }

        if (isset($this->request->post['frotel_default_online_express'])) {
            $data['frotel_default_online_express'] = $this->request->post['frotel_default_online_express'];
        } else {
            $data['frotel_default_online_express'] = $this->config->get('frotel_default_online_express');
        }

        if (isset($this->request->post['frotel_default_online_registered'])) {
            $data['frotel_default_online_registered'] = $this->request->post['frotel_default_online_registered'];
        } else {
            $data['frotel_default_online_registered'] = $this->config->get('frotel_default_online_registered');
        }

        if (isset($this->request->post['frotel_default_cod_express'])) {
            $data['frotel_default_cod_express'] = $this->request->post['frotel_default_cod_express'];
        } else {
            $data['frotel_default_cod_express'] = $this->config->get('frotel_default_cod_express');
        }

        if (isset($this->request->post['frotel_default_cod_registered'])) {
            $data['frotel_default_cod_registered'] = $this->request->post['frotel_default_cod_registered'];
        } else {
            $data['frotel_default_cod_registered'] = $this->config->get('frotel_default_cod_registered');
        }

        if (isset($this->request->post['frotel_default_weight'])) {
            $data['frotel_default_weight'] = $this->request->post['frotel_default_weight'];
        } else {
            $data['frotel_default_weight'] = $this->config->get('frotel_default_weight');
        }

        if (isset($this->request->post['frotel_default_weight'])) {
            $data['frotel_default_weight'] = $this->request->post['frotel_default_weight'];
        } else {
            $data['frotel_default_weight'] = $this->config->get('frotel_default_weight');
        }

        if (isset($this->request->post['frotel_pro_code'])) {
            $data['frotel_pro_code'] = $this->request->post['frotel_pro_code'];
        } else {
            $data['frotel_pro_code'] = $this->config->get('frotel_pro_code');
        }

        $this->load->model('localisation/order_status');
        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        $this->load->model('localisation/geo_zone');
        $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/payment/frotel.tpl', $data));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/payment/frotel')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (isset($this->request->post['frotel_status']) && $this->request->post['frotel_status'] == 1) {
            if (!isset($this->request->post['frotel_express']) && !isset($this->request->post['frotel_express'])) {
                $this->error['error'][] = $this->language->get('delivery_not_selected');
            }

            if (!isset($this->request->post['frotel_online']) && !isset($this->request->post['frotel_cod'])) {
                $this->error['error'][] = $this->language->get('payment_not_selected');
            }
        }

        if (strlen($this->request->post['frotel_api'])==0) {
            $this->error['error'][] = $this->language->get('error_empty_api');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * create table for store frotel factor
     */
    public function install()
    {
        $this->db->query('CREATE TABLE IF NOT EXISTS `'.DB_PREFIX.'frotel_factor` (
  `oc_order_id` int(11) NOT NULL,
  `frotel_factor` varchar(20) COLLATE utf8_persian_ci DEFAULT NULL,
  `last_change_status` int(11) DEFAULT NULL,
  `province` int(11) NOT NULL,
  `city` int(11) NOT NULL,
  `pay_verify` tinyint(1) NOT NULL DEFAULT "0",
  `frotel_bz` int(11) DEFAULT 0,
  `desc` text COLLATE utf8_persian_ci,
  PRIMARY KEY (`oc_order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;
                            ');
    }

    /**
     * ثبت سفارش در فروتل
     * توسط مدیر برای سفارشاتی که در هنگام ثبت سفارش در فروتل خطا داده است
     */
    public function register()
    {
        $this->language->load('extension/payment/frotel');

        $orderId = isset($this->request->get['id'])?$this->request->get['id']:0;
        if (!$orderId) {
            echo json_encode(array(
                'error'   => 1,
                'message' => $this->language->get('invalid_order')
            ));
            exit;
        }

        $this->load->model('sale/order');

        $order = $this->model_sale_order->getOrder($orderId);

        if (!isset($order['order_id'])) {
            echo json_encode(array(
                'error'   => 1,
                'message' => $this->language->get('not_found_order')
            ));
            exit;
        }

        if ($order['frotel_factor']) {
            echo json_encode(array(
                'error'   => 1,
                'message' => $this->language->get('already_register')
            ));
            exit;
        }
        if ($order['province']<=0 || $order['city']<=0) {
            echo json_encode(array(
                'error'   => 1,
                'message' => $this->language->get('error_province')
            ));
            exit;
        }

        $this->load->library('frotel/helper');

        try {
            $helper = $this->registry->get('helper');
            if (!$helper) {
                die('aAA');
            }
            $products = $this->model_sale_order->getOrderProducts($orderId);

            $name = $order['shipping_firstname'];
            $family = $order['shipping_lastname'];
            $gender = 1;
            $mobile = $order['telephone'];
            $phone = '';
            $email = $order['email'];
            $province = intval($order['province']);
            $city = intval($order['city']);
            $address = $order['shipping_address_1'].($order['shipping_address_2']?'('.$order['shipping_address_2'].')':'');
            $postCode = $order['shipping_postcode'];

            $shipping = str_replace('frotel_shipping.','',$order['shipping_code']);
            $shipping = explode('_',$shipping);

            $send_type = 2;
            if ($shipping[0] == 'express')
                $send_type = 1;

            $buy_type = 1;

            if ($shipping[1] == 'online')
                $buy_type = 2;

            $pm = $order['comment'];

            $pro_code_key = $this->config->get('frotel_pro_code');
            $weight = $this->config->get('frotel_default_weight');

            $bz = $order['frotel_bz'];
            $porsant = intval($this->config->get('frotel_product_porsant'));
            if ($porsant<0)
                $porsant = 0;

            $basket = array();
            foreach ($products as $item) {
                $product_name = array();

                $item['option'] = $this->model_sale_order->getOrderOptions($orderId,$item['order_product_id']);
                foreach ($item['option'] as $option) {
                    $product_name[] = $option['name'] . ' : ' . $option['value'];
                }
                if (empty($product_name))
                    $product_name = '';
                else
                    $product_name = '(' . implode(',', $product_name) . ')';

                $basket[] = array(
                    'pro_code' => $item[$pro_code_key],
                    'name' => $item['name'] . $product_name,
                    'price' => ceil($item['price']),    # convert to RLS
                    'count' => ceil($item['quantity']),
                    'weight' => ceil($weight),
                    'porsant' => $porsant,
                    'bazaryab' => $bz
                );
            }

            $result = $helper->registerOrder($name,$family,$gender,$mobile,$phone,$email,$province,$city,$address,$postCode,$buy_type,$send_type,$pm,$basket);
            $frotel_factor = $result['factor']['id'];
            $sql = "INSERT INTO `".DB_PREFIX."frotel_factor`(`oc_order_id`,`frotel_factor`,`last_change_status`,`province`,`city`,`pay_verify`,`frotel_bz`,`desc`)
                        VALUES ({$orderId},'{$frotel_factor}',".time().",{$province},{$city},0,{$bz},'')
                        ON DUPLICATE KEY UPDATE `frotel_factor` = '{$frotel_factor}',`last_change_status` = ".time();

            $this->db->query($sql);

            $result['factor']['total'] = $this->currency->convert($result['factor']['total'],'RLS',$this->currency->getCode());

            $sql = 'UPDATE `'.DB_PREFIX.'order`
                    SET `total` = '.$result['factor']['total'].'
                    WHERE `order_id` = '.$orderId;

            $this->db->query($sql);
            $output = array(
                'error'=>0,
                'message'=>'',
                'factor'=>$frotel_factor
            );
        }catch (frotel\FrotelWebserviceException $e) {
            $output = array(
                'error'=>1,
                'message'=>$e->getMessage(),
            );
        }catch (frotel\FrotelResponseException $e){
            $output = array(
                'error'=>1,
                'message'=>$e->getMessage(),
            );
        }catch(\Exception $e){
            $output = array(
                'error'=>1,
                'message'=>'خطایی رخ داده است.',
            );
        }

        echo json_encode($output);
        exit;
    }
}