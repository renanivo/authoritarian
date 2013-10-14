<?php

namespace spec\Authoritarian;

use Guzzle\Http\Client;
use Guzzle\Plugin\Mock\MockPlugin;
use Guzzle\Http\Message\Response;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Authoritarian\Flow\ResourceOwnerPasswordFlow;

class AuthorizationSpec extends ObjectBehavior
{
    public $responses;

    public function let()
    {
        $client = new Client();
        $this->responses = new MockPlugin();
        $client->addSubscriber($this->responses);

        $this->beConstructedWith($client);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('Authoritarian\Authorization');
    }

    public function it_should_get_the_response_of_the_flow_request()
    {
        $this->responses->addResponse(
            new Response(
                200,
                null,
                'response'
            )
        );

        $this->requestAccessToken(new ResourceOwnerPasswordFlow('', ''))
            ->shouldBeEqualTo('response');
    }

    public function it_should_get_an_array_when_the_response_content_type_is_json()
    {
        $access_token = array('access_token' => 'access-token-value');

        $this->responses->addResponse(
            new Response(
                200,
                array('Content-Type' => 'application/json; charset=utf-8'),
                json_encode($access_token)
            )
        );

        $this->requestAccessToken(new ResourceOwnerPasswordFlow('', ''))
            ->shouldBeEqualTo($access_token);
    }
}

