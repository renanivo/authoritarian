<?php

namespace Authoritarian;

use Guzzle\Http\ClientInterface;

/**
 * Implementation of OAuthInterface to get the user's access token using OAuth2
 **/
class OAuth2 implements OAuth2Interface
{
    protected $client;
    protected $tokenUrl;

    /**
     * @param string $token_url The URL to request the Access Token
     * @param Guzzle\Http\ClientInterface $client The HTTP Client
     */
    public function __construct($token_url, ClientInterface $client)
    {
        $this->client = $client;
        $this->tokenUrl = $token_url;
    }

    /**
     * {@inheritDoc}
     */
    public function requestAccessToken(Flow\AbstractFlow $flow)
    {
        $flow->setHttpClient($this->client);
        $flow->setTokenUrl($this->tokenUrl);

        return $flow->getRequest()->send();
    }
}

