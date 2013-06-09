<?php

namespace Authoritarian\Flow;

/**
 * Implementation of the Authorization Flow Interface to
 * the Authorization Code Flow of Oauth 2
 **/
class AuthorizationCodeFlow
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
}

