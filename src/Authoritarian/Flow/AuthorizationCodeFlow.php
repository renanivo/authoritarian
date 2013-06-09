<?php

namespace Authoritarian\Flow;

/**
 * Implementation of the Authorization Flow Interface to
 * the Authorization Code Flow of Oauth 2
 **/
class AuthorizationCodeFlow implements AuthorizationFlowInterface
{
    protected $tokenUrl;
    protected $clientId;
    protected $clientSecret;
    protected $scope;
    protected $authorizeUrl;

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
     * Get the authorization request
     *
     * @return Guzzle\Http\Message\Request
     */
    public function getRequest()
    {
        return $this->client->get();
    }

    /**
     * Get the URL to user's authorization
     *
     * @param string $callback_url The URL that will handle the authorization code after the user's login
     * @param string $state A CSRF token
     * @return string The URL to user's authorization
     */
    public function getAuthorizeUrl($callback_url, $state = null)
    {
        $query_parameters = array(
            'redirect_uri' => $callback_url,
            'client_id' => $this->clientId,
        );

        if (!is_null($state)) {
            $query_parameters['state'] = $state;
        }

        return $this->authorizeUrl . '?' . http_build_query($query_parameters);
    }
}

