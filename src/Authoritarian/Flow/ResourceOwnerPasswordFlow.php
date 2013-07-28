<?php

namespace Authoritarian\Flow;

use Authoritarian\Credential\ClientCredential;

/**
 * Implementation of the Authorization Flow Interface to
 * the Resource Owner Password Flow of OAuth 2
 **/
class ResourceOwnerPasswordFlow implements AuthorizationFlowInterface
{
    const GRANT_TYPE = 'password';

    protected $client;
    protected $tokenUrl;
    protected $clientCredential;
    protected $username;
    protected $password;

    /**
     * @param string $token_url The OAuth server endpoint to obtain the access tokens
     * @param string $username The user's username to login
     * @param string $password The user's password
     */
    public function __construct($token_url, $username, $password)
    {
        $this->tokenUrl = $token_url;
        $this->username = $username;
        $this->password = $password;
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
        $this->clientCredential = $credential;
    }

    /**
     * @param string The scope the app is requiring access
     */
    public function setScope($scope)
    {
        $this->scope = $scope;
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
            null,
            array(
                'client_id' => $this->clientCredential->getId(),
                'client_secret' => $this->clientCredential->getSecret(),
                'grant_type' => self::GRANT_TYPE,
                'scope' => $this->scope,
                'username' => $this->username,
                'password' => $this->password,
            )
        );
    }
}

