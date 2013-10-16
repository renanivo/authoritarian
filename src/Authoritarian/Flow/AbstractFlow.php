<?php

namespace Authoritarian\Flow;

use Authoritarian\Credential\ClientCredential;
use Authoritarian\Exception\Flow\MissingTokenUrlException;

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
     * Get the request to the Access Token
     *
     * @throws Authoritarian\Exception\Flow\MissingTokenUrlException When the OAuth token URL wasn't set
     *
     * @return \Guzzle\Http\Message\RequestInterface
     */
    public function getRequest() {
        if (is_null($this->tokenUrl)) {
            throw new MissingTokenUrlException(
                'No OAuth token URL given to generate a request'
            );
        }
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
}

