<?php

namespace Alirezadp10\Hamrahvas;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

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
    protected $url = 'http://10.20.9.6:8080/server.php?wsdl';

    /**
     * Send SMS to one user or group of users
     *
     * @param array $phoneNumbers
     * @param array $messages
     * @param string $chargeCode
     * @param string $amount
     * @param string $type
     * @param string $requestId
     * @return mixed
     */
    public function sendSMS()
    {
        //TODO
        return NULL;
    }

    /**
     * Subscribe/Unsubscribe user to VAS service
     *
     * @param string $chargeCode
     * @param string $amount
     * @param string $requestId
     * @return array
     */
    public function inAppCharge(Request $request, $serviceId)
    {
        $username = $this->username;
        $password = $this->password;
        $url = "http://79.175.138.66:8080/OTP/Push?username=$username&password=$password";
        $fields = [
            'cellPhoneNumber'  => $request->phoneNumber,
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

            if ($json['status'] != 2) {
                throw new \Exception('متاسفانه عملیات با شکست همراه شد!');
            }

            return $json;

        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Confirm user subscribe
     *
     * @param string $OTPTransactionId
     * @param string $TXCode
     * @param string $transactionPin
     * @return array
     */
    public function inAppChargeConfirm(Request $request, $serviceId)
    {
        $username = $this->username;
        $password = $this->password;
        $url = "http://79.175.138.66:8080/OTP/Charge?username=$username&password=$password";
        $fields = [
            'serviceId'        => $serviceId,
            'cellPhoneNumber'  => $request->phoneNumber,
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

            if ($json['status'] != 2 && $json['status'] != 3) {
                throw new \Exception('متاسفانه عملیات با شکست همراه شد!');
            }

            return $json;

        } catch (Exception $e) {
            throw $e;
        }
    }
}