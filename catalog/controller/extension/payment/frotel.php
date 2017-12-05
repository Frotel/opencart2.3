<?php
/**
 * User: ReZa ZaRe <Rz.ZaRe@Gmail.com>
 * Date: 5/29/15
 * Time: 12:31 AM
 */

class ControllerExtensionPaymentFrotel extends Controller
{
    private $errors = '';

    public function index()
    {
        $data['text_loading'] = $this->language->get('text_loading');
        $this->language->load('extension/payment/frotel');
        $data['error_response'] = $this->language->get('error_response');
        $data['button_confirm'] = $this->language->get('button_confirm');

        $data['continue'] = $this->url->link('checkout/success');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/frotel.tpl')) {
            $template = DIR_TEMPLATE.$this->config->get('config_template') . '/template/extension/payment/frotel.tpl';
        } else {
            $template = 'extension/payment/frotel.tpl';
        }

        return $this->load->view($template,$data);
    }

    /**
     * ثبت سفارش
     */
    public function confirm()
    {
        if (!isset($this->session->data['order_id']))
            $this->response->redirect($this->url->link('checkout/cart'));
        
        $this->load->model('checkout/order');
        $this->language->load('extension/payment/frotel');

        $order = $this->model_checkout_order->getOrder($this->session->data['order_id']);

        if (!isset($order['shipping_code'])) {
            echo (json_encode(array(
                'error'=>1,
                'message'=>'<div class="alert alert-danger">'.$this->language->get('order_not_found').'</div>'
            )));
            exit;
        }

        $shipping = str_replace('frotel_shipping.','',$order['shipping_code']);
        $shipping = explode('_',$shipping);

        $send_type = 2;
        if ($shipping[0] == 'express')
            $send_type = 1;

        $buy_type = 1;

        if ($shipping[1] == 'online')
            $buy_type = 2;

        $url = $this->url->link('checkout/success');
        $frotel_factor = '';
        $desc = '';

        $bz = isset($this->session->data['frotel_bz']) && intval($this->session->data['frotel_bz'])>0?intval($this->session->data['frotel_bz']):0;

        if ($bz<0)
            $bz = 0;

        $porsant = intval($this->config->get('frotel_product_porsant'));
        if ($porsant<0)
            $porsant = 0;

        try {
            $name = $order['shipping_firstname'];
            $family = $order['shipping_lastname'];
            $address = $order['shipping_address_1'] . ' ' . ($order['shipping_address_2'] ? '('.$order['shipping_address_2'].')' : '');

            $postCode = $order['shipping_postcode'];
            $email = $order['email'];

            $mobile = $order['telephone'];
            $phone = '';
            $gender = 1;
            $province = $this->session->data['province_id'];
            $city = $this->session->data['city_id'];
            $pm = $order['comment'];
            $basket = array();
            $products = $this->cart->getProducts();

            $pro_code_key = $this->config->get('frotel_pro_code');

            $default_weight = ceil($this->config->get('frotel_default_weight'));
            foreach ($products as $item) {
                $product_name = array();

                foreach ($item['option'] as $option) {
                    $product_name[] = $option['name'] . ' : ' . $option['value'];
                }

                if (empty($product_name))
                    $product_name = '';
                else
                    $product_name = '(' . implode(',', $product_name) . ')';

                $weight = ceil($this->weight->convert($item['weight'], $this->config->get('config_weight_class_id'), 2));  # convert to gram
                if ($weight<=0)
                    $weight = $default_weight;

                $basket[] = array(
                    'pro_code' => $item[$pro_code_key],
                    'name' => $item['name'] . $product_name,
                    'price' => ceil($this->currency->convert($item['price'], $this->config->get('config_currency'), 'RLS')),    # convert to RLS
                    'count' => ceil($item['quantity']),
                    'weight' => $weight,
                    'porsant' => $porsant,
                    'bazaryab' => $bz,
                );
            }

            $this->load->library('frotel/helper');

            $helper = $this->registry->get('helper');

            $result = $helper->registerOrder($name, $family, $gender, $mobile, $phone, $email, $province, $city, $address, $postCode, $buy_type, $send_type, $pm, $basket);
            $frotel_factor = $result['factor']['id'];

            $sql = 'UPDATE `'.DB_PREFIX.'order`
                    SET `total` = '.$result['factor']['total'].'
                    WHERE `order_id` = '.$this->session->data['order_id'];

            $this->db->query($sql);

            if (isset($result['factor']['banks'])) {
                $url = $this->url->link('extension/payment/frotel/pay');
                // clear cart
                $this->cart->clear();
            }

            $result['pay'] = 0;
            $this->session->data['frotel_data'] = $result;

        }catch (frotel\FrotelWebserviceException $e){
            echo (json_encode(array(
                'error'=>1,
                'message'=>$e->getMessage()
            )));
            exit;
        }catch (frotel\FrotelResponseException $e) {
            if (isset($this->session->data['frotel_shipping_default'])) {
                $desc = "مبلغ ".number_format($this->session->data['frotel_shipping_default'])." ریال به عنوان علی الحساب هزینه ارسال دریافت شد.\n";
            }
        }

        $sql = "INSERT INTO `".DB_PREFIX."frotel_factor`(`oc_order_id`,`frotel_factor`,`last_change_status`,`province`,`city`,`pay_verify`,`frotel_bz`,`desc`)
                        VALUES ({$this->session->data['order_id']},'{$frotel_factor}',".time().",{$province},{$city},0,{$bz},'{$desc}')
                        ON DUPLICATE KEY UPDATE `frotel_factor` = '{$frotel_factor}',`last_change_status` = ".time().",`frotel_bz`={$bz},`desc` = CONCAT(`desc`,\"\n\",'{$desc}')";

        $this->db->query($sql);

        $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('frotel_order_status'), '', true);
        $this->session->data['frotel_shipping_default'] = null;
        $this->session->data['province_id'] = null;
        $this->session->data['city_id'] = null;
        echo (json_encode(array(
            'error'=>0,
            'url'=>$url
        )));
    }

    /**
     * فرم انتخاب درگاه برای پرداخت آنلاین
     */
    public function pay()
    {
        if (!isset($this->session->data['frotel_data']))
            $this->response->redirect($this->url->link('checkout/cart'));

        $this->language->load('extension/payment/frotel');
        $this->document->setTitle($this->language->get('text_title'));
        $data['text_title'] = $this->language->get('text_title');
        $data['text_error_response'] = $this->language->get('error_response');
        $data['text_start_transaction'] = $this->language->get('start_transaction');
        if (isset($this->session->data['pay_error'])) {
            $data['pay_error'] = $this->session->data['pay_error'];
            $this->session->data['pay_error'] = null;
        } else {
            $data['pay_error'] = '';
        }
        $data['choose_bank'] = $this->language->get('choose_bank');
        $data['banks'] = $this->session->data['frotel_data']['factor']['banks'];
        $data['url'] = $this->url->link('extension/payment/frotel/gateway');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/frotel_pay.tpl')) {
            $template = DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/frotel_pay.tpl';
        } else {
            $template = 'extension/payment/frotel_pay.tpl';
        }

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        echo $this->load->view($template,$data);
    }

    /**
     * دریافت فرم ارجاع به بانک از وب سرویس
     */
    public function gateway()
    {
        $this->language->load('extension/payment/frotel');

        if (!isset($this->request->get['bank'])) {
            echo json_encode(array(
                'error'=>1,
                'message'=>$this->language->get('choose_bank')
            ));
            exit;
        }

        $bank = intval($this->request->get['bank']);
        $orderId = $this->session->data['order_id'];
        $frotel_factor = $this->session->data['frotel_data']['factor']['id'];
        $callback = $this->url->link('extension/payment/frotel/callback','id='.$orderId.'&factor='.$frotel_factor);

        $this->load->library('frotel/helper');
        $helper = $this->registry->get('helper');

        try {
            $result = $helper->pay($frotel_factor,$bank,$callback);
            $output = array(
                'error' => 0,
                'message' => $result
            );
        }catch (frotel\FrotelWebserviceException $e){
            $output = array(
                'error' => 1,
                'message' => $e->getMessage()
            );
        }catch (frotel\FrotelResponseException $e){
            $output = array(
                'error' => 1,
                'message' => $this->language->get('error_webservice')
            );
        }

        echo json_encode($output);
        exit;
    }

    /**
     * برگشت خریدار از بانک
     * بررسی صحت پرداخت
     *
     */
    public function callback()
    {
        if (!isset($this->request->get['id'],$this->request->get['factor'],$this->request->get['_i'],$this->request->get['sb'])) {
            die('A');
            $this->response->redirect($this->url->link('checkout/cart'));
        }


        # اگر پرداخت قبلا تایید شده بود
        if ($this->session->data['frotel_data']['pay'] == 1)
            $this->response->redirect($this->url->link('checkout/success'));


        $orderId = intval($this->request->get['id']);
        $factor = $this->request->get['factor'];
        $_i = intval($this->request->get['_i']);
        $sb = $this->request->get['sb'];

        $this->load->model('checkout/order');
        $order = $this->model_checkout_order->getOrder($orderId);

        if (!isset($order['frotel_factor']) || $order['frotel_factor'] != $factor)
            $this->response->redirect($this->url->link('checkout/cart'));


        $this->load->library('frotel/helper');
        $helper = $this->registry->get('helper');

        $this->language->load('extension/payment/frotel');
        try {
            $result = $helper->checkPay($factor,$_i,$sb);
            if ($result['verify'] == 1) {
                $comment = "پرداخت نقدی سفارش تایید شده است.\nکد رهگیری پرداخت : {$result['code']}\n";
                $this->db->query('
                    UPDATE `'.DB_PREFIX.'order` SET `comment` = CONCAT(`comment`,"\n","'.$comment.'"),`order_status_id` = '.$this->config->get('frotel_verify_status').'
                    WHERE `order_id` = '.$orderId.'
                ');

                $this->db->query('
                    UPDATE `'.DB_PREFIX.'frotel_factor` SET `pay_verify` = 1,`desc` = CONCAT(`desc`,"\n","'.$comment.'")
                    WHERE `oc_order_id` = '.$orderId.'
                ');


                $this->session->data['frotel_data']['pay'] = 1;
                $this->session->data['pay_verify'] = sprintf($this->language->get('verify_success'),$result['code']);
                $this->response->redirect($this->url->link('checkout/success'));
            } else {
                $this->session->data['pay_error'] = $result['message'];
            }


        } catch (frotel\FrotelWebserviceException $e) {
            $this->session->data['pay_error'] = $e->getMessage();
        } catch (frotel\FrotelResponseException $e) {
            $this->session->data['pay_error'] = $this->language->get('error_webservice');
        }

        $this->response->redirect($this->url->link('extension/payment/frotel/pay'));
    }

    /**
     * رهگیری سفارش
     */
    public function tracking()
    {
        $url = $this->config->get('frotel_url');
        $api = $this->config->get('frotel_api');

        $this->load->library('frotel/helper');
        $factor = isset($this->request->get['factor'])?$this->request->get['factor']:false;

        $h = new helper($url,$api);

        try{
            $result = $h->tracking($factor);
            $output = array(
                'error'=>0,
                'message'=>array(
                    'barcode'=>$result['order']['barcode'],
                    'status'=>$result['order']['status'],
                )
            );
        } catch (FrotelWebserviceException $e) {
            $output = array(
                'error'=>1,
                'message'=>$e->getMessage()
            );
        } catch (FrotelResponseException $e) {
            $output = array(
                'error'=>1,
                'message'=>$e->getMessage()
            );
        }

        $this->response->setOutput(json_encode($output));
    }


    /**
     * فرم انتخاب شهر و استان
     */
    public function city()
    {
        $this->language->load('extension/payment/frotel');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if (empty($this->request->post['province_id']) || $this->request->post['province_id']<=0) {
                $this->errors['province_id'] = $this->language->get('error_province_empty');
            }
            if (empty($this->request->post['city_id']) || $this->request->post['province_id']<=0)  {
                $this->errors['city_id'] = $this->language->get('error_city_empty');
            }

            if (!$this->errors) {
                $this->session->data['province_id'] = $this->request->post['province_id'];
                $this->session->data['city_id']  = $this->request->post['city_id'];

                $this->response->redirect($this->url->link('checkout/checkout', '', 'SSL'));
            }
        }

        $this->document->setTitle($this->language->get('text_title_select_city'));
        $data['text_title_select_city'] = $this->language->get('text_title_select_city');
        $data['text_select_city_desc'] = $this->language->get('text_select_city_desc');
        $data['button_continue'] = $this->language->get('button_continue');
        $data['entry_province'] = $this->language->get('entry_province');
        $data['entry_city'] = $this->language->get('entry_city');
        $data['error'] = $this->errors?implode('<br />',$this->errors):'';

        $this->document->addScript('http://pc.fpanel.ir/ostan.js');
        $this->document->addScript('http://pc.fpanel.ir/city.js');

        $data['url'] = $this->url->link('extension/payment/frotel/city');

        if (isset($this->session->data['province_id']))
            $data['province_id'] = $this->session->data['province_id'];

        if (isset($this->session->data['city_id']))
            $data['city_id'] = $this->session->data['city_id'];

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/payment/frotel_city.tpl')) {
            $template = DIR_TEMPLATE.$this->config->get('config_template') . '/template/extension/payment/frotel_city.tpl';
        } else {
            $template = 'extension/payment/frotel_city.tpl';
        }


        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        echo $this->load->view($template,$data);
    }
}
