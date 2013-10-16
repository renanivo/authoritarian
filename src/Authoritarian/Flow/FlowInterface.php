<?php

namespace Authoritarian\Flow;

use Authoritarian\Credential\ClientCredential;

/**
 *  Authorization Flow interface to generate Access Token Requests
 */
interface FlowInterface
{
    /**
     * @param Guzzle\Http\ClientInterface $client The HTTP Client
     */
    public function setHttpClient(\Guzzle\Http\ClientInterface $client);

    /**
     * @param string $client_id     The app's client id
     * @param string $client_secret The app's client secret
     */
    public function setClientCredential($client_id, $client_secret);

    /**
     * @param string $scope The scope the app is requiring access
     */
    public function setScope($scope);


    /**
     * @param string $token_url The URL to request the Access Token
     */
    public function setTokenUrl($token_url);

    /**
     * Get the request to the Access Token
     *
     * @throws Authoritarian\Exception\Flow\MissingTokenUrlException When the OAuth token URL wasn't set
     *
     * @return \Guzzle\Http\Message\RequestInterface
     */
    public function getRequest();
}

