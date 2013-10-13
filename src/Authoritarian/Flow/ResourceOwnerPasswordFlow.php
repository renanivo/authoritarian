<?php

namespace Authoritarian\Flow;

use Authoritarian\Credential\ClientCredential;

/**
 * Implementation of the Authorization Flow Interface to
 * the Resource Owner Password Flow of OAuth 2
 **/
class ResourceOwnerPasswordFlow implements FlowInterface
{
    const GRANT_TYPE = 'password';

    protected $client;
    protected $tokenUrl;
    protected $clientCredential;
    protected $parameters = array();

    /**
     * @param string $token_url The OAuth server endpoint to obtain the access tokens
     * @param string $username The user's username to login
     * @param string $password The user's password
     */
    public function __construct($username, $password)
    {
        $this->setParameter('username', $username);
        $this->setParameter('password', $password);
        $this->setParameter('grant_type', self::GRANT_TYPE);
    }

    /**
     * @param Guzzle\Http\ClientInterface $client The HTTP Client
     */
    public function setHttpClient(\Guzzle\Http\ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param Authoritarian\Credential\ClientCredential $credential The App's
     * Client Credential
     */
    public function setClientCredential(ClientCredential $credential)
    {
        $this->setParameter('client_id', $credential->getId());
        $this->setParameter('client_secret', $credential->getSecret());
    }

    /**
     * {@inheritDoc}
     */
    public function setTokenUrl($token_url)
    {
        $this->tokenUrl = $token_url;
    }

    /**
     * @param string The scope the app is requiring access
     */
    public function setScope($scope)
    {
        $this->setParameter('scope', $scope);
    }

    /**
     * Get the authorization request
     *
     * @return Guzzle\Http\Message\Request
     */
    public function getRequest()
    {
        return $this->client->post(
            $this->tokenUrl,
            $this->getContentTypeFormUrlencodedHeader(),
            $this->parameters
        );
    }

    private function setParameter($key, $value)
    {
        $this->parameters[$key] = $value;
    }

    private function getContentTypeFormUrlencodedHeader()
    {
        return array(
            'Content-Type' => 'application/x-www-form-urlencoded; charset=utf-8',
        );
    }
}

