<?php

namespace spec\Authoritarian\Credential;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ClientCredentialSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('client id', 'client secret');
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Authoritarian\Credential\ClientCredential');
    }
}

