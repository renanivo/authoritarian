<?php

namespace Authoritarian\Flow;

use Authoritarian\Credential\ClientCredential;

/**
 *  Authorization Flow interface to generate Access Token Requests
 */
abstract class AbstractFlow
{
    protected $client;
    protected $tokenUrl;

    /**
     * @param Guzzle\Http\ClientInterface $client The HTTP Client
     */
    public function setHttpClient(\Guzzle\Http\ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $token_url The URL to request the Access Token
     */
    public function setTokenUrl($token_url)
    {
        $this->tokenUrl = $token_url;
    }

    /**
     * @param string $client_id     The app's client id
     * @param string $client_secret The app's client secret
     */
    abstract public function setClientCredential($client_id, $client_secret);

    /**
     * @param string $scope The scope the app is requiring access
     */
    abstract public function setScope($scope);

    /**
     * Get the request to the Access Token
     *
     * @throws Authoritarian\Exception\Flow\MissingTokenUrlException When the OAuth token URL wasn't set
     *
     * @return \Guzzle\Http\Message\RequestInterface
     */
    abstract public function getRequest();
}

