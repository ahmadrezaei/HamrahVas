# Hamrah vas

**Install package by composer**

`composer require alirezadp10/hamrah-vas`

**create config.php file and enter yor data:**

```php
$username = '';
$password = '';
$shortCode = '';
$serviceKey = '';
$chargeCode = '';
```

**Import vendor and config.php in your files and create class instance:**

```php
require_once 'config.php';
require_once 'vendor/autoload.php';

$phoneNumber = ''; // user phone number like $_SESSION['phone']
$mobin = new \ahmadrezaei\mobinone\MobinOne($username, $password, $shortCode, $serviceKey, $phoneNumber);
```

**Now you can use the methods:**

```php
$mobin->sendSMS(); // send SMS to user
$mobin->inAppCharge(); // send register request
$mobin->inAppChargeConfirm(); // confirm register request by code
$mobin->randomString() // create rando string
```

Examples
===

**the are 3 example page in the package, you can use the package like this files:**

```php
sample.php // for register user
notificaion.php // for handle service notifications
mp.php // for handle MO messages
```