<?php

namespace Authoritarian;

/**
 * Implementation of OAuthInterface to get the user's access token using OAuth2
 **/
class OAuth2 implements OAuth2Interface
{
    protected $client;
    protected $tokenUrl;
    protected $clientId;
    protected $clientSecret;

    public function __construct(\Guzzle\Http\ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $token_url Then endpoint to request the Access Token
     */
    public function setTokenUrl($token_url)
    {
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

