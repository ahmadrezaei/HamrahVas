<?php

namespace Alirezadp10\Hamrahvas;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array inAppCharge(\Illuminate\Http\Request $request, int $serviceId)
 * @method static array inAppChargeConfirm(\Illuminate\Http\Request $request, int $serviceId)
 * @method static array sendSMS(\Illuminate\Http\Request $request, int $phoneNumber, string $textMessage, int $serviceId,int $ShortCode)
 */
class HamrahvasFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Hamrahvas::class;
    }
}
