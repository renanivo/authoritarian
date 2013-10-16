<?php

namespace Authoritarian\Flow;

use Authoritarian\Exception\Flow\MissingAuthorizationCodeException;
use Authoritarian\Credential\ClientCredential;

/**
 * Implementation of the Authorization Flow Interface to
 * the Authorization Code Flow of Oauth 2
 **/
class AuthorizationCodeFlow extends AbstractFlow
{
    const GRANT_TYPE = 'authorization_code';
    const RESPONSE_TYPE = 'code';

    protected $authorizeUrl;
    protected $code;
    protected $redirectUri;
    protected $state;
    protected $parameters;
    protected $clientId;
    protected $clientSecret;

    /**
     * @param string $authorize_url The OAuth Authorize endpoint url
     */
    public function __construct($authorize_url)
    {
        $this->authorizeUrl = $authorize_url;
    }

    /**
     * {@inheritDoc}
     */
    public function setClientCredential($client_id, $client_secret)
    {
        $this->clientId = $client_id;
        $this->clientSecret = $client_secret;
    }

    /**
     * {@inheritDoc}
     */
    public function setScope($scope)
    {
        $this->scope = $scope;
    }

    /**
     * @param string $code The Authorization Code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @param string $url the callback URI to retrieve the authorization code
     */
    public function setRedirectUri($url)
    {
        $this->redirectUri = $url;
    }

    /**
     * @param string $state the CSRF token to validate the response code
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * Get the URL to user's authorization
     *
     * @return string The URL to user's authorization
     */
    public function getAuthorizeUrl()
    {
        return $this->authorizeUrl . '?' . $this->getAuthorizeQueryParameters();
    }

    /**
     * {@inheritDoc}
     * @throws Authoritarian\Exception\Flow\MissingAuthorizationCodeException When the authorization code wasn't set
     */
    public function getRequest()
    {
        parent::getRequest();

        if (is_null($this->code)) {
            throw new MissingAuthorizationCodeException(
                'No authorization code given to generate a request'
            );
        }

        return $this->client->post(
            $this->tokenUrl,
            null,
            $this->getRequestPostParameters()
        );
    }

    private function getRequestPostParameters()
    {
        $parameters = array(
            'code' => $this->code,
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => self::GRANT_TYPE,
            'redirect_uri' => $this->redirectUri,
            'scope' => $this->scope,
        );

        return $this->removeNullItems($parameters);
    }

    private function getAuthorizeQueryParameters()
    {
        $parameters = array(
            'redirect_uri' => $this->redirectUri,
            'client_id' => $this->clientId,
            'response_type' => self::RESPONSE_TYPE,
            'scope' => $this->scope,
            'state' => $this->state,
        );

        return http_build_query(
            $this->removeNullItems($parameters)
        );
    }

    private function removeNullItems(array $parameters)
    {
        return array_filter(
            $parameters,
            function ($item) {
                return !is_null($item);
            }
        );
    }
}

