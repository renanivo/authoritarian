<?php

namespace Authoritarian\Flow;

/**
 * Implementation of Client Credentials Flow
 */
class ClientCredentialsFlow extends AbstractFlow
{
    const GRANT_TYPE = 'client_credentials';

    /**
     * {@inheritDoc}
     */
    public function getRequest()
    {
        parent::getRequest();

        return $this->client->post(
            $this->tokenUrl,
            null,
            $this->removeNullItems(
                array(
                    'grant_type' => self::GRANT_TYPE,
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                )
            )
        );
    }
}
