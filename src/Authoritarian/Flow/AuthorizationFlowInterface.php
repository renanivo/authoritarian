<?php

namespace Authoritarian\Flow;

/**
 *  Authorization Flow interface to generate Access Token Requests
 */
interface AuthorizationFlowInterface
{
    /**
     * Set the Http Client
     *
     * @param \Guzzle\Http\Client $client   HTTP Client
     */
    public function setClient(\Guzzle\Http\Client $client);

    /**
     * Get the request to the Access Token
     *
     * @return \Guzzle\Http\Message\RequestInterface
     */
    public function getRequest();
}
