<?php

namespace Authoritarian\Flow;

use Authoritarian\Credential\ClientCredential;
use Authoritarian\Exception\Flow\MissingTokenUrlException;

/**
 * Implementation of the Authorization Flow Interface to
 * the Resource Owner Password Flow of OAuth 2
 **/
class ResourceOwnerPasswordFlow extends AbstractFlow
{
    const GRANT_TYPE = 'password';

    protected $username;
    protected $password;

    /**
     * @param string $token_url The OAuth server endpoint to obtain the access tokens
     * @param string $username The user's username to login
     * @param string $password The user's password
     */
    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Get the authorization request
     *
     * @return Guzzle\Http\Message\Request
     */
    public function getRequest()
    {
        parent::getRequest();

        return $this->client->post(
            $this->tokenUrl,
            $this->getContentTypeFormUrlencodedHeader(),
            $this->removeNullItems(
                array(
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'username' => $this->username,
                    'password' => $this->password,
                    'scope' => $this->scope,
                    'grant_type' => self::GRANT_TYPE,
                )
            )
        );
    }

    private function getContentTypeFormUrlencodedHeader()
    {
        return array(
            'Content-Type' => 'application/x-www-form-urlencoded; charset=utf-8',
        );
    }
}

