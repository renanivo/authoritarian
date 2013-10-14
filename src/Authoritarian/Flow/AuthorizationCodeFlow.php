<?php

namespace Authoritarian\Flow;

use Authoritarian\Exception\Flow\MissingAuthorizationCodeException;
use Authoritarian\Exception\Flow\MissingTokenUrlException;
use Authoritarian\Credential\ClientCredential;

/**
 * Implementation of the Authorization Flow Interface to
 * the Authorization Code Flow of Oauth 2
 **/
class AuthorizationCodeFlow implements FlowInterface
{
    const GRANT_TYPE = 'authorization_code';
    const RESPONSE_TYPE = 'code';

    protected $tokenUrl;
    protected $clientCredential;
    protected $authorizeUrl;
    protected $code;
    protected $redirectUri;
    protected $state;
    protected $parameters;

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
    public function setHttpClient(\Guzzle\Http\ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritDoc}
     */
    public function setClientCredential(ClientCredential $credential)
    {
        $this->clientCredential = $credential;
    }

    /**
     * {@inheritDoc}
     */
    public function setTokenUrl($token_url)
    {
        $this->tokenUrl = $token_url;
    }

    /**
     * {@inheritDoc}
     */
    public function setScope($scope)
    {
        $this->scope = $scope;
    }

    /**
     * Set the Code retrived from the querystring after the user's authorization
     *
     * @param string $code The Authorization Code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * Set the URI that will be used to retrieve the authorization code
     *
     * @param string $url the callback URI
     */
    public function setRedirectUri($url)
    {
        $this->redirectUri = $url;
    }

    /**
     * Set a CSRF token to validate the response code
     *
     * @param string $state the CSRF token
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
        if (is_null($this->code)) {
            throw new MissingAuthorizationCodeException(
                'No authorization code given to generate a request'
            );
        }

        if (is_null($this->tokenUrl)) {
            throw new MissingTokenUrlException(
                'No OAuth token URL given to generate a request'
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
            'client_id' => $this->clientCredential->getId(),
            'client_secret' => $this->clientCredential->getSecret(),
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
            'client_id' => $this->clientCredential->getId(),
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

