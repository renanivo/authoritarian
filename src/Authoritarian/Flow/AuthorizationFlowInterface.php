<?php

namespace Authoritarian\Flow;

use Authoritarian\Credential\ClientCredential;

/**
 *  Authorization Flow interface to generate Access Token Requests
 */
interface AuthorizationFlowInterface
{
    /**
     * @param \Guzzle\Http\ClientInterface $client The HTTP Client
     */
    public function setHttpClient(\Guzzle\Http\ClientInterface $client);

    /**
     * @param Authoritarian\Credential\ClientCredential $credential The App's
     * Client Credentials
     */
    public function setClientCredential(ClientCredential $credential);

    /**
     * @param string The scope the app is requiring access
     */
    public function setScope($scope);

    /**
     * Get the request to the Access Token
     *
     * @return \Guzzle\Http\Message\RequestInterface
     */
    public function getRequest();
}

