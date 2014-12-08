Authoritarian
=============
[![Build Status](https://travis-ci.org/renanivo/authoritarian.png?branch=master)](https://travis-ci.org/renanivo/authoritarian)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/renanivo/authoritarian/badges/quality-score.png?s=63b8e247fadff1a31f463332f7c3aa8f5a08a9da)](https://scrutinizer-ci.com/g/renanivo/authoritarian/)
[![Code Coverage](https://scrutinizer-ci.com/g/renanivo/authoritarian/badges/coverage.png?s=49435dc730c693973c7cce2790a09a9a774ba76f)](https://scrutinizer-ci.com/g/renanivo/authoritarian/)

An OAuth 2 client for PHP with multiple authorization flows support

Install
-------

Get [composer](http://getcomposer.org/) and execute:

```bash
php composer.phar require renanivo/authoritarian
```

Usage
-----

Just setup your flow and request an access token:

#### Client Credentials Flow
```php
<?php
use Authoritarian\OAuth2;
use Authoritarian\Flow\ClientCredentialsFlow;

$flow = new ClientCredentialsFlow();
$flow->setClientCredential('client id', 'client secret');

$oauth2 = new OAuth2('http://example.com/oauth/token');
$token = $oauth2->requestAccessToken($flow)->json();
```

#### Authorization-Code Flow

in the login page:

```php
<?php
use Authoritarian\OAuth2;
use Authoritarian\Flow\AuthorizationCodeFlow;

$flow = new AuthorizationCodeFlow();
$flow->setAuthorizationUrl('http://example.com/oauth/authorize');
$flow->setClientCredential('client id', 'client secret');
$flow->setRedirectUri('http://example.com/callback');

header('Location: ' . $flow->getAuthUrl());
```

in the callback page:

```php
<?php
use Authoritarian\OAuth2;
use Authoritarian\Flow\AuthorizationCodeFlow;

$flow = new AuthorizationCodeFlow();
$flow->setClientCredential('client id', 'client secret');
$flow->setCode($_GET['code']);

$oauth2 = new OAuth2('http://example.com/oauth/token');
$token = $oauth2->requestAccessToken($flow)->json();
```

#### Resource Owner Password
```php
<?php
use Authoritarian\OAuth2;
use Authoritarian\Flow\ResourceOwnerPasswordFlow;

$flow = new ResourceOwnerPasswordFlow(
    'username',
    'password'
);
$flow->setClientCredential('client id', 'client secret');

$oauth2 = new OAuth2('http://example.com/oauth/token');
$token = $oauth2->requestAccessToken($flow)->json();
```

Generate Docs
-------------

- Download apigen.phar:

    ```bash
    curl -sS http://apigen.org/installer | php
    ```

- Run ApiGen:

    ```bash
    php apigen.phar generate
    ```
