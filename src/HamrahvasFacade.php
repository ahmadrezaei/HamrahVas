<?php

namespace Alirezadp10\Hamrahvas;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array inAppCharge(\Illuminate\Http\Request $request, int $serviceId)
 * @method static array inAppChargeConfirm(\Illuminate\Http\Request $request, int $serviceId)
 */
class HamrahvasFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Hamrahvas::class;
    }
}