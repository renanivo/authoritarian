Authoritarian
=============

A PHP library for OAuth 2 with multiple flows support

Install
-------

Get [composer](http://getcomposer.org/) and execute:

```bash
php composer.phar require renanivo/authoritarian
```

Enter the version or `dev-master` to get the repository latest.

Usage
-----

### Setup your client credentials
```php
<?php

use Authoritarian\Credential\ClientCredential;

$credential = new ClientCredential(
    'client id',
    'client secret'
);
```

### Choose your authorization flow

#### Authorization-Code Flow
```php
<?php

use Authoritarian\Flow\AuthorizationCodeFlow;

$flow = new AuthorizationCodeFlow(
    'http://example.com/oauth/token',
    'http://example.com/oauth/authorize'
);

$flow->setClientCredentials($credential);
$flow->setScope('scope');
$flow->setCallbackUri('http://example.com/callback');

$url = $flow->getAuthorizeUrl();

// redirect the user to the $url

// in the callback page
$code = $_GET['code'];
$flow->setCode($code);
```

#### Resource Owner Password
```php
<?php

use Authoritarian\Flow\ResourceOwnerPasswordFlow;

$flow = new ResourceOwnerPasswordFlow(
    'http://example.com/oauth/token',
    'username',
    'password'
);

$flow->setClientCredentials($credential);
$flow->setScope('scope');
```

### Request the access token
```php
<?php

use Authoritarian\Authorization;

$authorization = new Authorization();
$token = $authorization->requestAccessToken($flow);

// you can use the token to make requests authorized by the user
```
