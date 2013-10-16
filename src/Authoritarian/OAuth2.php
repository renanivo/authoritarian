<?php

namespace Authoritarian;

/**
 * Implementation of OAuthInterface to get the user's access token using OAuth2
 **/
class OAuth2 implements OAuth2Interface
{
    protected $client;
    protected $clientCredential;
    protected $tokenUrl;

    public function __construct(\Guzzle\Http\ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param Credential\ClientCredencial $credential The app's client id and
     * secret
     */
    public function setClientCredential(Credential\ClientCredencial $credential)
    {
        $this->clientCredential = $credential;
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

