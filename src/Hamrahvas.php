<?php

namespace Alirezadp10\Hamrahvas;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
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
     * @return array
     * @throws Exception
     */
    public function sendSMS($phoneNumber,$textMessage, $serviceId, $ShortCode)
    {
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
                "MsgTypeCodeList"      => [41],
                "chargeCodeNumberList" => [0],
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
     * @param \Illuminate\Http\Request $request
     * @param int $serviceId
     * @return array
     * @throws Exception
     */
    public function inAppCharge(Request $request, $serviceId)
    {
        $baseUrl = $this->baseUrl;
        $username = $this->username;
        $password = $this->password;
        $url = "$baseUrl/OTP/Push?username=$username&password=$password";
        $fields = [
            'cellPhoneNumber'  => substr($request->phoneNumber, -10),
            'serviceId'        => $serviceId,
            'chargeCodeNumber' => '0',
            'price'            => '5000',
            'cpUniqueToken'    => Str::random(),
            'description'      => 'Request Sub',
            'content'          => '1111',
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
                    throw new \Exception('متاسفانه عملیات با شکست همراه شد!');
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
     * @param \Illuminate\Http\Request $request
     * @param int $serviceId
     * @return array
     */
    public function inAppChargeConfirm(Request $request, $serviceId)
    {
        $baseUrl = $this->baseUrl;
        $username = $this->username;
        $password = $this->password;
        $url = "$baseUrl/OTP/Charge?username=$username&password=$password";
        $fields = [
            'serviceId'        => $serviceId,
            'cellPhoneNumber'  => substr($request->phoneNumber, -10),
            'otpTransactionId' => $request->otpTransactionId,
            'transactionPIN'   => $request->pin,
            'cpUniqueToken'    => $request->cpUniqueToken,
            'content'          => '1111',
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
                    throw new \Exception('متاسفانه عملیات با شکست همراه شد!');
                }
            }

            return $json;

        } catch (Exception $e) {
            throw $e;
        }
    }
}