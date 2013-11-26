<?php

namespace Authoritarian;

/**
 * Interface for request user authorizations via access tokens
 */
interface AuthorizationInterface
{
    /**
     * Requests the token endpoint and get the user's Access Token
     *
     * @param Flow\AbstractFlow $flow The Oauth2 flow
     *
     * @return \Guzzle\Http\Message\Response
     */
    public function requestAccessToken(Flow\AbstractFlow $flow);
}
