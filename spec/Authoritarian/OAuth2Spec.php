<?php

namespace spec\Authoritarian;

use Guzzle\Http\Client;
use Guzzle\Plugin\Mock\MockPlugin;
use Guzzle\Http\Message\Response;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Authoritarian\Flow\ClientCredentialsFlow;

class OAuth2Spec extends ObjectBehavior
{
    public $responses;

    public function let()
    {
        $client = new Client();
        $this->responses = new MockPlugin();
        $client->addSubscriber($this->responses);

        $this->beConstructedWith('http://example.com/oauth/token', $client);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('Authoritarian\OAuth2');
    }

    public function it_should_get_the_response_of_the_flow_request()
    {
        $response = new Response(
            200,
            null,
            'response'
        );
        $this->responses->addResponse($response);
        $flow = new ClientCredentialsFlow();
        $flow->setClientCredential('username', 'password');

        $this->requestAccessToken($flow)
            ->shouldHaveType('Guzzle\Http\Message\Response');
    }
}

