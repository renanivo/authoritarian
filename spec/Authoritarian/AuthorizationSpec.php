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
        $response->getHeader('Content-Type')->willReturn(null);
        $request->send()->willReturn($response);
        $flow->getRequest()->willReturn($request);

        $this->getAccessToken($flow)->shouldBeEqualTo('response');
    }

    /**
     * @param Authoritarian\Flow\ResourceOwnerPasswordFlow $flow
     * @param Guzzle\Http\Message\Request $request
     * @param Guzzle\Http\Message\Response $response
     */
    public function it_should_return_array_for_json_responses($client, $flow, $request, $response)
    {
        $flow->setClient($client)->shouldBeCalled();

        $access_token = array(
            'access_token' => 'access-token-value'
        );
        $response->json()->willReturn($access_token);
        $response->getHeader('Content-Type')->willReturn(
            new \Guzzle\Http\Message\Header(
                'Content-Type',
                array('application/json; charset=utf-8')
            )
        );
        $request->send()->willReturn($response);
        $flow->getRequest()->willReturn($request);

        $this->getAccessToken($flow)
            ->shouldBeEqualTo($access_token);
    }
}
