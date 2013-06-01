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

        if ($this->hasJsonHeader($response)) {
            return $response->json();
        } else {
            return $response->getBody();
        }
    }

    protected function hasJsonHeader($response)
    {
        $content_type = (string)$response->getHeader('Content-Type');
        return preg_match('/application\/json/', $content_type);
    }
}

