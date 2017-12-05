<?php
namespace frotel;

/**
 * User: ReZa ZaRe <Rz.ZaRe@Gmail.com>
 * Date: 7/13/15
 * Time: 12:57 PM
 */
class Helper
{

    private $url;
    private $api_key;
    private $registry;


    const METHOD_POST   = 'post';
    const METHOD_GET    = 'get';

    /**
     * list of errors
     *
     * @var array
     */
    private $errors = array();

    public function __construct($registry)
    {
        $this->registry = $registry;
        $this->url = $this->config->get('frotel_url');
        $this->api_key = $this->config->get('frotel_api');
    }


    public function __get($name) {
        return $this->registry->get($name);
    }

    /**
     * get frotel service price
     *
     * @return array|bool
     */
    public function frotelService()
    {
        return $this->call('order/frotelService.json',array());
    }

    /**
     * get post + tax price for send order
     *
     * @param array $params
     * @return array|bool
     */
    public function fPrice($params)
    {
        return $this->call('order/fPrice.json',$params);
    }

    /**
     * @param int $des_city
     * @param int $price
     * @param int $weight
     * @param array $buy_type
     * @param array $delivery_type
     * @return array|bool
     * @throws FrotelResponseException
     */
    public function getPrices($des_city,$price,$weight,$buy_type,$delivery_type)
    {
        $params = array(
            'des_city'  => $des_city,
            'price'     => $price,
            'weight'    => $weight,
            'buy_type'  => $buy_type,
            'send_type' => $delivery_type
        );
        return $this->call('order/getPrices.json',$params);
    }

    /**
     * separation cart
     *
     * @param array $params
     * @return array|bool
     */
    public function separationCart($params)
    {
        return $this->call('order/separationCart.json',$params);
    }

    /**
     * cost calculation
     *
     * @param array $params
     * @return array|bool
     */
    public function costCalculation($params)
    {
        return $this->call('order/costCalculation.json',$params);
    }

    /**
     * register order method for physical products
     *
     * @param string $name
     * @param string $family
     * @param int $gender
     * @param string $mobile
     * @param string $phone
     * @param string $email
     * @param int $province
     * @param int $city
     * @param string $address
     * @param string $postCode
     * @param int $buy_type
     * @param int $send_type
     * @param string $pm
     * @param array $basket
     * @param array $fields
     * @return array|bool
     * @throws FrotelResponseException
     */
    public function registerOrder($name,$family,$gender,$mobile,$phone,$email,$province,$city,$address,$postCode,$buy_type,$send_type,$pm,$basket,$fields=array())
    {
        $params = array(
            'name'      => $name,
            'family'    => $family,
            'gender'    => $gender,
            'mobile'    => $mobile,
            'phone'     => $phone,
            'email'     => $email,
            'province'  => $province,
            'city'      => $city,
            'address'   => $address,
            'postCode'  => $postCode,
            'buy_type'  => $buy_type,
            'send_type' => $send_type,
            'pm'        => $pm,
            'basket'    => $basket,
            'fields'    => $fields,
        );

        return $this->call('order/registerOrder.json',$params);
    }

    /**
     * register order method for virtual products
     *
     * @param string $name
     * @param string $family
     * @param int $gender
     * @param string $mobile
     * @param string $phone
     * @param string $email
     * @param string $pm
     * @param array $basket
     * @param array $fields
     * @return array|bool
     * @throws FrotelResponseException
     */
    public function registerOrderVirtual($name,$family,$gender,$mobile,$phone,$email,$pm,$basket,$fields=array())
    {
        $params = array(
            'name'      => $name,
            'family'    => $family,
            'gender'    => $gender,
            'mobile'    => $mobile,
            'phone'     => $phone,
            'email'     => $email,
            'pm'        => $pm,
            'basket'    => $basket,
            'fields'    => $fields,
        );

        return $this->call('order/registerOrderVirtual.json',$params);
    }

    /**
     * register order method for service products
     *
     * @param string $name
     * @param string $family
     * @param int $gender
     * @param string $mobile
     * @param string $phone
     * @param string $email
     * @param int $province
     * @param int $city
     * @param string $address
     * @param string $postCode
     * @param string $pm
     * @param array $basket
     * @param array $fields
     * @return array|bool
     * @throws FrotelResponseException
     */
    public function registerOrderService($name,$family,$gender,$mobile,$phone,$email,$province,$city,$address,$postCode,$pm,$basket,$fields=array())
    {
        $params = array(
            'name'      => $name,
            'family'    => $family,
            'gender'    => $gender,
            'mobile'    => $mobile,
            'phone'     => $phone,
            'email'     => $email,
            'province'  => $province,
            'city'      => $city,
            'address'   => $address,
            'postCode'  => $postCode,
            'pm'        => $pm,
            'basket'    => $basket,
            'fields'    => $fields,
        );

        return $this->call('order/registerOrderService.json',$params);
    }

    /**
     * رهگیری سفارش
     *
     * @param string $factor
     * @return array|bool
     * @throws FrotelResponseException
     */
    public function tracking($factor)
    {
        $params = array(
            'factor'   => $factor
        );

        return $this->call('order/tracking.json',$params);
    }

    /**
     * دریافت فرم ارجاع به بانک
     *
     * @param string $factor
     * @param int $bankId
     * @param string $callback
     * @return array|bool
     * @throws FrotelResponseException
     */
    public function pay($factor,$bankId,$callback)
    {
        $params = array(
            'factor'    => $factor,
            'bank'      => $bankId,
            'callback'  => $callback
        );

        return $this->call('payment/pay.json',$params);
    }

    /**
     * بررسی صحت پرداخت
     *
     * @param string $factor
     * @param int $paymentId
     * @param string $ref
     * @return array|bool
     * @throws FrotelResponseException
     */
    public function checkPay($factor,$paymentId,$ref)
    {
        $params = array(
            'factor'    => $factor,
            'paymentId' => $paymentId,
            'ref'       => $ref
        );

        return $this->call('payment/checkPay.json',$params);
    }

    /**
     * call method in webservice
     *
     * @param string $url
     * @param array $params
     * @param string $methodType
     * @return array|bool
     * @throws FrotelResponseException
     * @throws FrotelWebserviceException
     */
    private function call($url,$params,$methodType = helper::METHOD_POST)
    {
        // flush error list
        $this->errors = array();

        if (stripos($url, 'http://') === false)
            $url = $this->url . $url;

        $params['api'] = $this->api_key;
        $data = http_build_query($params);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, $methodType === helper::METHOD_POST);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //execute post
        $result = curl_exec($ch);

        //close connection
        curl_close($ch);

        $result = json_decode($result,true);

        if (json_last_error() == JSON_ERROR_NONE)
            return $this->parseResponse($result);


        throw new FrotelResponseException('Failed to Parse Response ('.json_last_error().')');
    }

    /**
     * parse webservice response
     *
     * @param array $response
     * @return bool
     * @throws FrotelResponseException
     * @throws FrotelWebserviceException
     */
    private function parseResponse($response)
    {
        if (!isset($response['code'],$response['message'],$response['result']))
            throw new FrotelResponseException('پاسخ دریافتی از سرور معتبر نیست.');



        if ($response['code'] == 0)
            return $response['result'];

        $this->errors[] = $response['message'];
        throw new FrotelWebserviceException($response['message']);
    }

    /**
     * get list of errors
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}

class FrotelResponseException extends \Exception{}

class FrotelWebserviceException extends \Exception{}