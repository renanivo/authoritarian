Authoritarian
=============

An OAuth 2 client for PHP with multiple authorization flows support

Install
-------

Get [composer](http://getcomposer.org/) and execute:

```bash
php composer.phar require renanivo/authoritarian
```

Usage
-----

You just need two steps to request the access token to your OAuth2 provider:

### Step 1 - Setup your flow

#### Client Credentials Flow
```php
use Authoritarian\Flow\ClientCredentialsFlow;

$flow = new ClientCredentialsFlow();
$flow->setClientCredential('client id', 'client secret');
```

#### Authorization-Code Flow

in the login page:

```php
<?php

use Authoritarian\Flow\AuthorizationCodeFlow;

$flow = new AuthorizationCodeFlow(
    'http://example.com/oauth/authorize'
);

$flow->setClientCredential('client id', 'client secret');
$flow->setCallbackUri('http://example.com/callback');

header('Location: ' . $flow->getAuthUrl());
```

in the callback page:

```php
$code = $_GET['code'];
$flow->setCode($code);
```

#### Resource Owner Password
```php
<?php

use Authoritarian\Flow\ResourceOwnerPasswordFlow;

$flow = new ResourceOwnerPasswordFlow(
    'username',
    'password'
);

$flow->setClientCredential($credential);
$flow->setScope('scope');
```

### Step 2 - Request the access token
```php
<?php

use Authoritarian\OAuth2;

$oauth2 = new OAuth2('http://example.com/oauth/token');
$token = $oauth2->requestAccessToken($flow)->json();
```
