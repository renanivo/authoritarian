<?php

namespace Authoritarian\Credential;

class ClientCredential
{
    private $id;
    private $secret;

    public function __construct($client_id, $client_secret)
    {
        $this->id = $client_id;
        $this->secret = $client_secret;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getSecret()
    {
        return $this->secret;
    }
}

