<?php

namespace Authoritarian;

/**
 * Authorization class to get the user's access token
 **/
class Authorization implements AuthorizationInterface
{
    protected $client;
    protected $clientCredential;
    protected $tokenUrl;

    public function __construct(\Guzzle\Http\ClientInterface $client)
    {
        $this->client = $client;
    }

    public function setClientCredential(Credential\ClientCredencial $credential)
    {
        $this->clientCredential = $credential;
    }

    public function setTokenUrl($token_url)
    {
        $this->tokenUrl = $token_url;
    }

    public function requestAccessToken(Flow\FlowInterface $flow)
    {
        $flow->setHttpClient($this->client);
        $flow->setTokenUrl($this->tokenUrl);

        $response = $flow->getRequest()->send();

        if ($this->hasJsonHeader($response)) {
            return $response->json();
        } else {
            return (string) $response->getBody();
        }
    }

    protected function hasJsonHeader($response)
    {
        $content_type = (string)$response->getHeader('Content-Type');
        return preg_match('/application\/json/', $content_type);
    }
}

