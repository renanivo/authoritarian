<?php

namespace Authoritarian\Flow;

use Authoritarian\Exception\FlowException;

/**
 * Implementation of the Authorization Flow Interface to
 * the Authorization Code Flow of Oauth 2
 **/
class AuthorizationCodeFlow implements AuthorizationFlowInterface
{
    const GRANT_TYPE = 'authorization_code';
    const RESPONSE_TYPE = 'code';

    protected $tokenUrl;
    protected $clientId;
    protected $clientSecret;
    protected $scope;
    protected $authorizeUrl;
    protected $code;
    protected $redirectUri;
    protected $state;

    /**
     * Constructor
     *
     * @param string $token_url The OAuth Token endpoint url
     * @param string $client_id The app's client id
     * @param string $client_secret The app's client secret
     * @param string $scope The data your application is requesting access to
     * @param string $authorize_url The OAuth Authorize endpoint url
     */
    public function __construct(
        $token_url,
        $client_id,
        $client_secret,
        $scope,
        $authorize_url
    ) {
        $this->tokenUrl = $token_url;
        $this->clientId = $client_id;
        $this->clientSecret = $client_secret;
        $this->scope = $scope;
        $this->authorizeUrl = $authorize_url;
    }

    /**
     * Set HTTP Client
     *
     * @param Guzzle\Http\ClientInterface $client An instance of Guzzle Client
     */
    public function setClient(\Guzzle\Http\ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Set the Authorization Code
     *
     * @param string $code The Authorization Code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * Set the URI the user will be redirected after
     * authentication and authorization
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
     * Get the authorization request
     *
     * @return Guzzle\Http\Message\Request
     */
    public function getRequest()
    {
        if (is_null($this->code)) {
            throw new FlowException(
                'No code set. Impossible to create an '
                . 'Authorization Code Flow Request'
            );
        }

        return $this->client->post(
            $this->tokenUrl,
            null,
            array(
                'code' => $this->code,
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'grant_type' => self::GRANT_TYPE,
                'redirect_uri' => $this->redirectUri,
                'scope' => $this->scope,
            )
        );
    }

    /**
     * Get the URL to user's authorization
     *
     * @return string The URL to user's authorization
     */
    public function getAuthorizeUrl()
    {
        $query_parameters = array(
            'redirect_uri' => $this->redirectUri,
            'client_id' => $this->clientId,
            'response_type' => self::RESPONSE_TYPE,
            'scope' => $this->scope,
        );

        if (!is_null($this->state)) {
            $query_parameters['state'] = $this->state;
        }

        return $this->authorizeUrl . '?' . http_build_query($query_parameters);
    }
}

