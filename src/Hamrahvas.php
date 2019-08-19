<?php

namespace Alirezadp10\Hamrahvas;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use function json_encode;
use SoapClient;

/**
 * Class HamrahVas
 * @package alirezadp10/hamrah-vas
 */
class Hamrahvas
{
    private $username;
    private $password;

    public function __construct($configs)
    {
        $this->username = $configs['username'];
        $this->password = $configs['password'];
    }

    /**
     * Client url to request
     *
     * @var string $url
     */
    protected $baseUrl = 'http://79.175.138.66:8080';

    /**
     * Send SMS to users
     *
     * @param $phoneNumber
     * @param $textMessage
     * @param int $serviceId
     * @param $ShortCode
     * @param int $MsgTypeCodeList
     * @param int $chargeCodeNumberList
     * @return array
     * @throws Exception
     */
    public function sendSMS(
        $phoneNumber, $textMessage, $serviceId, $ShortCode, $MsgTypeCodeList = 41,
        $chargeCodeNumberList = 0
    ){
        try {
            // Initialize WS with the WSDL
            $client = new SoapClient("http://79.175.138.70:8080/SMSBuffer.asmx?wsdl");

            // Set request params
            $params = [
                "username"             => $this->username,
                "password"             => $this->password,
                "numberList"           => ['0' . substr($phoneNumber, -10)],
                "contentList"          => [$textMessage],
                "origShortCodeList"    => [$ShortCode],
                "serviceIdList"        => [$serviceId],
                "MsgTypeCodeList"      => [$MsgTypeCodeList],
                "chargeCodeNumberList" => [$chargeCodeNumberList],
            ];

            // Invoke WS method (MessageListUploadWithServiceId) with the request params
            $response = $client->__soapCall(
                "MessageListUploadWithServiceId",
                [$params],
                [
                    'uri'        => 'http://tempuri.org/',
                    'soapaction' => '',
                ]
            );

            return $response;

        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Subscribe/Unsubscribe user to VAS service
     *
     * @param $phoneNumber
     * @param int $serviceId
     * @param string $chargeCodeNumber
     * @param string $price
     * @param string $description
     * @param string $content
     * @return array
     * @throws Exception
     */
    public function inAppCharge(
        $serviceId, $phoneNumber, $chargeCodeNumber = '0', $price = '5000',
        $description = 'Request Sub', $content = '1111'
    )
    {
        $baseUrl = $this->baseUrl;
        $username = $this->username;
        $password = $this->password;
        $url = "$baseUrl/OTP/Push?username=$username&password=$password";
        $fields = [
            'serviceId'        => $serviceId,
            'cellPhoneNumber'  => substr($phoneNumber, -10),
            'chargeCodeNumber' => $chargeCodeNumber,
            'price'            => $price,
            'cpUniqueToken'    => Str::random(),
            'description'      => $description,
            'content'          => $content,
        ];

        // build the urlencoded data
        $postvars = http_build_query($fields);

        try {
            // open connection
            $ch = curl_init();

            // set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, count($fields));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            // execute post

            $result = curl_exec($ch);

            $json = json_decode($result, TRUE);

            if (isset($json['status'])) {
                if ($json['status'] != 2) {
                    if (isset($json['destinationResult']['statusInfo']['errorInfo']['errorDescription'])) {
                        throw new \Exception($json['destinationResult']['statusInfo']['errorInfo']['errorDescription']);
                    }
                    throw new \Exception($result);
                }
            }

            return $json;

        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Confirm user subscribe
     *
     * @param int $serviceId
     * @param $phoneNumber
     * @param $otpTransactionId
     * @param $pin
     * @param $cpUniqueToken
     * @param string $content
     * @return array
     * @throws Exception
     */
    public function inAppChargeConfirm(
        $serviceId, $phoneNumber, $otpTransactionId, $pin, $cpUniqueToken, $content = '1111'
    )
    {
        $baseUrl = $this->baseUrl;
        $username = $this->username;
        $password = $this->password;
        $url = "$baseUrl/OTP/Charge?username=$username&password=$password";
        $fields = [
            'serviceId'        => $serviceId,
            'cellPhoneNumber'  => substr($phoneNumber, -10),
            'otpTransactionId' => $otpTransactionId,
            'transactionPIN'   => $pin,
            'cpUniqueToken'    => $cpUniqueToken,
            'content'          => $content,
        ];

        // build the urlencoded data
        $postvars = http_build_query($fields);

        try {
            // open connection
            $ch = curl_init();

            // set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, count($fields));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            // execute post
            $result = curl_exec($ch);

            $json = json_decode($result, TRUE);

            if (isset($json['status'])) {
                if ($json['status'] != 2 && $json['status'] != 3) {
                    if (isset($json['destinationResult']['statusInfo']['errorInfo']['errorDescription'])) {
                        throw new \Exception($json['destinationResult']['statusInfo']['errorInfo']['errorDescription']);
                    }
                    throw new \Exception($result);
                }
            }

            return $json;

        } catch (Exception $e) {
            throw $e;
        }
    }
}