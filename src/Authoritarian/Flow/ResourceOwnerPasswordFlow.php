<?php

namespace Authoritarian\Flow;

use Authoritarian\Credential\ClientCredential;
use Authoritarian\Exception\Flow\MissingTokenUrlException;

/**
 * Implementation of Resource Owner Password Flow
 **/
class ResourceOwnerPasswordFlow extends AbstractFlow
{
    const GRANT_TYPE = 'password';

    protected $username;
    protected $password;

    /**
     * @param string $username
     * @param string $password
     */
    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * {@inheritDoc}
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

