<?php

namespace Authoritarian\Flow;

use Authoritarian\Exception\Flow\MissingAuthorizationCodeException;

/**
 * Implementation of Authorization Code Flow
 **/
class AuthorizationCodeFlow extends AbstractFlow
{
    const GRANT_TYPE = 'authorization_code';
    const RESPONSE_TYPE = 'code';

    protected $authorizationUrl;
    protected $code;
    protected $redirectUri;
    protected $state;
    protected $parameters;

    /**
     * @param string $authorization_url OAuth 2's Authorization endpoint url
     */
    public function __construct($authorization_url = null)
    {
        $this->setAuthorizationUrl($authorization_url);
    }

    /**
     * @param string $authorization_url OAuth 2's Authorization endpoint url
     */
    public function setAuthorizationUrl($authorization_url)
    {
        $this->authorizationUrl = $authorization_url;
    }

    /**
     * @param string $code The authorization code retrieved in the callback page
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @param string $uri the callback URI to retrieve the authorization code
     */
    public function setRedirectUri($uri)
    {
        $this->redirectUri = $uri;
    }

    /**
     * @param string $state The app's state to be resumed at the callback
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * Get the URL to user's authentication and authorization
     *
     * @return string
     */
    public function getAuthUrl()
    {
        return $this->authorizationUrl . '?' . $this->getAuthorizeQueryParameters();
    }

    /**
     * {@inheritDoc}
     * @throws MissingAuthorizationCodeException When the authorization code
     * wasn't set
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
}
