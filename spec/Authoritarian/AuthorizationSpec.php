<?php

namespace spec\Authoritarian;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AuthorizationSpec extends ObjectBehavior
{
    public function let($client)
    {
        $client->beADoubleOf('Guzzle\Http\Client');
        $this->beConstructedWith($client);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('Authoritarian\Authorization');
    }

    /**
     * @param Authoritarian\Flow\ResourceOwnerPasswordFlow $flow
     * @param Guzzle\Http\Message\Request $request
     * @param Guzzle\Http\Message\Response $response
     */
    public function it_should_return_the_request_response($client, $flow, $request, $response)
    {
        $flow->setClient($client)->shouldBeCalled();

        $response->getBody()->willReturn('response');
        $request->send()->willReturn($response);
        $flow->getRequest()->willReturn($request);

        $this->getAccessToken($flow)->shouldBeEqualTo('response');
    }
}
