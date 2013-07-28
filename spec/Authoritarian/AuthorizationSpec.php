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
    public function it_should_get_the_response_of_the_flow_request($client, $flow, $request, $response)
    {
        $flow->setHttpClient($client)->shouldBeCalled();

        $response->getBody()->willReturn('response');
        $response->getHeader('Content-Type')->willReturn(null);
        $request->send()->willReturn($response);
        $flow->getRequest()->willReturn($request);

        $this->requestAccessToken($flow)->shouldBeEqualTo('response');
    }

    /**
     * @param Authoritarian\Flow\ResourceOwnerPasswordFlow $flow
     * @param Guzzle\Http\Message\Request $request
     * @param Guzzle\Http\Message\Response $response
     */
    public function it_should_get_an_array_when_the_response_content_type_is_json($client, $flow, $request, $response)
    {
        $flow->setHttpClient($client)->shouldBeCalled();

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

        $this->requestAccessToken($flow)
            ->shouldBeEqualTo($access_token);
    }
}

