<?php
/**
 * User: ReZa ZaRe <Rz.ZaRe@Gmail.com>
 * Date: 5/29/15
 * Time: 12:34 AM
 */

class ModelExtensionShippingFrotelShipping extends Model
{

    public function getQuote($address)
    {
        $this->load->library('frotel/helper');

        $totalWeight = $this->weight->convert($this->cart->getWeight(),$this->config->get('config_weight_class_id'),2);
        $totalPrice = $this->currency->convert($this->cart->getTotal(),$this->config->get('config_currency'),'RLS');

        $online = $this->config->get('frotel_online');
        $cod = $this->config->get('frotel_cod');
        $buy_type = array();
        if ($online)
            $buy_type['online'] = 2;
        if ($cod)
            $buy_type['cod'] = 1;


        $express = $this->config->get('frotel_express');
        $registered = $this->config->get('frotel_registered');

        $delivery_type = array();
        if ($express)
            $delivery_type[1] = 'express';

        if ($registered)
            $delivery_type[2] = 'registered';


        $totalWeight = $totalWeight>0?$totalWeight:intval($this->config->get('frotel_default_weight'));

        $h = $this->registry->get('helper');

        $this->language->load('extension/shipping/frotel_shipping');
        $quote_data = array();
        if (!isset($this->session->data['province_id'])) {
            $error = '<div class="alert alert-danger">'.$this->language->get('choose_city').'</div>';
        } else {
            try {
                $result = $h->getPrices(intval($this->session->data['city_id']), $totalPrice, $totalWeight, array_values($buy_type), array_keys($delivery_type));
                $error = false;

                foreach ($result as $buy => $cost) {
                    if ($buy == 'naghdi')
                        $buy = 'online';
                    else
                        $buy = 'cod';


                    foreach ($cost as $index => $item) {
                        $post_price = $item['post'] + $item['tax'] + $item['frotel_service'];
                        $quote_data[$delivery_type[$index] . '_' . $buy] = array(
                            'code'          => 'frotel_shipping.' . $delivery_type[$index] . '_' . $buy,
                            'title'         => $this->language->get($delivery_type[$index] . '_' . $buy),
                            'tax_class_id'  => 0,
                            'cost'          => $post_price,
                            'text'          => $this->currency->format($post_price, 'RLS'),
                            'default'       => 0
                        );
                    }
                }
            } catch (frotel\FrotelWebserviceException $ex) {
                $error = '<div class="alert alert-danger">' . $ex->getMessage() . '</div>';
            } catch (frotel\FrotelResponseException $ex) {
                $error = false;
                $result = array();
                if ($online) {
                    if ($express) {
                        $result['naghdi'][1] = array(
                            'post'=>intval($this->config->get('frotel_default_online_express')),
                            'tax'=>0
                        );
                    }
                    if ($registered) {
                        $result['naghdi'][2] = array(
                            'post'=>intval($this->config->get('frotel_default_online_registered')),
                            'tax'=>0
                        );
                    }
                }
                if ($cod) {
                    if ($express) {
                        $result['posti'][1] = array(
                            'post'=>intval($this->config->get('frotel_default_cod_express')),
                            'tax'=>0
                        );
                    }
                    if ($registered) {
                        $result['posti'][2] = array(
                            'post'=>intval($this->config->get('frotel_default_cod_registered')),
                            'tax'=>0
                        );
                    }
                }

                foreach ($result as $buy => $cost) {
                    if ($buy == 'naghdi')
                        $buy = 'online';
                    else
                        $buy = 'cod';

                    foreach ($cost as $index => $item) {
                        $post_price = $item['post'] + $item['tax'];
                        $quote_data[$delivery_type[$index] . '_' . $buy] = array(
                            'code'          => 'frotel_shipping.' . $delivery_type[$index] . '_' . $buy,
                            'title'         => $this->language->get($delivery_type[$index] . '_' . $buy),
                            'tax_class_id'  => 0,
                            'cost'          => $post_price,
                            'text'          => $this->currency->format($post_price, 'RLS'),
                            'default'       => 1
                        );
                    }
                }
            }
        }

        return array(
            'code'       => 'frotel_shipping',
            'title'      => $this->language->get('text_title'),
            'quote'      => $quote_data,
            'sort_order' => $this->config->get('frotel_shipping_sort'),
            'error'      => $error
        );
    }
}