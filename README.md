# Hamrah vas

**Install package by composer**

`composer require ahmadrezaei/hamrah-vas`

## Setup

**publish the configuration file if you want to change any defaults:**

1. In `/config/app.php`, add the following to `providers`:
  
  ```
  Alirezadp10\Hamrahvas\ServiceProvider::class,
  ```
  and the following to `aliases`:
  ```
  'Hamrahvas' => Alirezadp10\Hamrahvas\HamrahvasFacade::class,
  ```
2. Run `php artisan vendor:publish --provider="Alirezadp10\Hamrahvas\ServiceProvider"`.

**Now you can use the methods:**

```php
use Alirezadp10\Hamrahvas\HamrahvasFacade;
.
.
.
HamrahvasFacade::inAppCharge($request, $service->serviceId); // send register request
HamrahvasFacade::inAppChargeConfirm($request, $service->serviceId); // confirm register request by code
```