<?php

namespace Alirezadp10\Hamrahvas;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array inAppCharge(int $serviceId, int $phoneNumber, string $chargeCodeNumber, string $price, string $description, string $content)
 * @method static array inAppChargeConfirm(int $serviceId, int $phoneNumber, int $otpTransactionId, int $pin, string $cpUniqueToken, string $content)
 * @method static array sendSMS(string $phoneNumber, string $textMessage, int $serviceId, string $ShortCode, int $MsgTypeCodeList, int $chargeCodeNumberList)
 */
class HamrahvasFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Hamrahvas::class;
    }
}
