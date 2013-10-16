<?php

namespace Authoritarian\Flow;

use Authoritarian\Credential\ClientCredential;
use Authoritarian\Exception\Flow\MissingTokenUrlException;
use Authoritarian\Exception\Flow\MissingClientCredentialException;

/**
 *  Authorization Flow interface to generate Access Token Requests
 */
abstract class AbstractFlow
{
    protected $client;
    protected $tokenUrl;
    protected $clientId;
    protected $clientSecret;
    protected $scope;

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
    public function setClientCredential($client_id, $client_secret)
    {
        $this->clientId = $client_id;
        $this->clientSecret = $client_secret;
    }

    /**
     * @param string $scope The scope the app is requiring access
     */
    public function setScope($scope)
    {
        $this->scope = $scope;
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

        if (is_null($this->clientId) || is_null($this->clientSecret)) {
            throw new MissingClientCredentialException(
                'No Client Id or Client Secret given to generate a request'
            );
        }
    }

    protected function removeNullItems(array $parameters)
    {
        return array_filter(
            $parameters,
            function ($item) {
                return !is_null($item);
            }
        );
    }
}

