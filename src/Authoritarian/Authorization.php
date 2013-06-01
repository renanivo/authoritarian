<?php

namespace Authoritarian;

/**
 * Authorization class to get the user's access token
 **/
class Authorization implements AuthorizationInterface
{
    protected $client;

    public function __construct(\Guzzle\Http\ClientInterface $client)
    {
        $this->client = $client;
    }

    public function getAccessToken(Flow\AuthorizationFlowInterface $flow)
    {
        $flow->setClient($this->client);
        $response = $flow->getRequest()->send();
        return $response->getBody();
    }
}

