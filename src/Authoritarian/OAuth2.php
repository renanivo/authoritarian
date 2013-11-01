<?php

namespace Authoritarian;

/**
 * Implementation of AuthorizationInterface for OAuth2
 **/
class OAuth2 implements AuthorizationInterface
{
    protected $client;
    protected $tokenUrl;

    /**
     * @param string $token_url The URL to request the Access Token
     */
    public function __construct($token_url)
    {
        $this->tokenUrl = $token_url;
    }

    public function setHttpClient(\Guzzle\Http\ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritDoc}
     */
    public function requestAccessToken(Flow\AbstractFlow $flow)
    {
        if (is_null($this->client)) {
            $this->client = new \Guzzle\Http\Client();
        }

        $flow->setHttpClient($this->client);
        $flow->setTokenUrl($this->tokenUrl);

        return $flow->getRequest()->send();
    }
}

