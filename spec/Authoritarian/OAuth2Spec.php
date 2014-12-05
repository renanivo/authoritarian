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
    public $client;
    public $tokenUrl;

    public function let()
    {
        $this->client = new Client();
        $this->responses = new MockPlugin();
        $this->client->addSubscriber($this->responses);
        $this->tokenUrl = 'http://example.com/oauth/token';

        $this->beConstructedWith($this->tokenUrl);
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
        $this->setHttpClient($this->client);

        $flow = new ClientCredentialsFlow();
        $flow->setClientCredential('username', 'password');

        $this->requestAccessToken($flow)
            ->shouldHaveType('Guzzle\Http\Message\Response');
    }

    /**
     * @param Authoritarian\Flow\ClientCredentialsFlow $flow
     * @param Guzzle\Http\Message\Request              $request
     */
    public function it_should_request_access_token_when_client_was_not_set($flow, $request)
    {
        $flow->setHttpClient(Argument::type('Guzzle\Http\Client'))
            ->shouldBeCalled();
        $flow->setTokenUrl($this->tokenUrl)->shouldBeCalled();
        $flow->getRequest()->willReturn($request);

        $this->requestAccessToken($flow);
    }
}
