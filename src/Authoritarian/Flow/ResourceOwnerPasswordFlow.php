<?php

namespace Authoritarian\Flow;

use Authoritarian\Credential\ClientCredential;
use Authoritarian\Exception\Flow\MissingTokenUrlException;

/**
 * Implementation of the Authorization Flow Interface to
 * the Resource Owner Password Flow of OAuth 2
 **/
class ResourceOwnerPasswordFlow implements FlowInterface
{
    const GRANT_TYPE = 'password';

    protected $client;
    protected $tokenUrl;
    protected $clientId;
    protected $clientSecret;
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
     * {@inheritDoc}
     */
    public function setClientCredential($client_id, $client_secret)
    {
        $this->setParameter('client_id', $client_id);
        $this->setParameter('client_secret', $client_secret);
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
        if (is_null($this->tokenUrl)) {
            throw new MissingTokenUrlException(
                'No OAuth token URL given to generate a request'
            );
        }

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

