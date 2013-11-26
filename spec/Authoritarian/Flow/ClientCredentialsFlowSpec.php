<?php

namespace spec\Authoritarian\Flow;

use PhpSpec\ObjectBehavior;

class ClientCredentialsFlowSpec extends ObjectBehavior
{
    private $clientId;
    private $clientSecret;

    public function let()
    {
        $this->clientId = 'client-id';
        $this->clientSecret = 'client-secret';

        $this->setHttpClient(new \Guzzle\Http\Client());
        $this->setTokenUrl('http://example.com/oauth/token');
        $this->setClientCredential($this->clientId, $this->clientSecret);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('Authoritarian\Flow\ClientCredentialsFlow');
    }

    public function it_should_get_a_post_request()
    {
        $this->getRequest()->getMethod()->shouldBeEqualTo('POST');
    }

    public function it_should_get_request_with_grant_type()
    {
        $this->getRequest()->shouldHavePostParameter(
            'grant_type',
            'client_credentials'
        );
    }

    public function it_should_get_a_request_with_client_id()
    {
        $this->getRequest()->shouldHavePostParameter(
            'client_id',
            $this->clientId
        );
    }

    public function it_should_get_a_request_with_client_secret()
    {
        $this->getRequest()->shouldHavePostParameter(
            'client_secret',
            $this->clientSecret
        );
    }

    public function getMatchers()
    {
        return array(
            'beAValidUrl' => function ($subject, $flags) {
                return false !== filter_var(
                    $subject,
                    FILTER_VALIDATE_URL,
                    array(
                        'flags' => $flags,
                    )
                );
            },
            'havePostParameter' => function ($subject, $key, $value) {
                $body = preg_split('/\n\s*\n/', $subject)[1];
                $parameters = array();
                parse_str($body, $parameters);

                return array_key_exists($key, $parameters) &&
                    $parameters[$key] == $value;
            }
        );
    }
}
