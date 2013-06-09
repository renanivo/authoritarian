<?php

namespace spec\Authoritarian\Flow;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AuthorizationCodeFlowSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(
            'http://api.example.com/oauth/token',
            'client id',
            'client secret',
            'scope',
            'http://api.example.com/oauth/authorize'
        );
    }
    public function it_should_be_initializable()
    {
        $this->shouldHaveType('Authoritarian\Flow\AuthorizationCodeFlow');
    }
}
