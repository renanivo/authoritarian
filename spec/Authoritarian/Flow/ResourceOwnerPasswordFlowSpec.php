<?php

namespace spec\Authoritarian\Flow;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Authoritarian\Flow\ResourceOwnerPasswordFlow;

class ResourceOwnerPasswordFlowSpec extends ObjectBehavior
{
    protected $tokenUrl;
    public function let()
    {
        $this->tokenUrl = 'http://api.example.com/oauth/token';
        $this->beConstructedWith(
            $this->tokenUrl,
            'client id',
            'client secret',
            'scope',
            'username',
            'password'
        );

        $client = new \Guzzle\Http\Client();
        $this->setClient($client);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('Authoritarian\Flow\ResourceOwnerPasswordFlow');
    }

    public function it_should_create_a_request_to_the_token_url()
    {
        $this->getRequest()->getUrl()->shouldBeEqualTo($this->tokenUrl);
    }
}

