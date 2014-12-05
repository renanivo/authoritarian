<?php

namespace spec\Authoritarian\Flow;

use PhpSpec\ObjectBehavior;
use Authoritarian\Flow\AuthorizationCodeFlow;

class AuthorizationCodeFlowSpec extends ObjectBehavior
{
    private $authorizeUrl;
    private $tokenUrl;
    private $clientId;
    private $clientSecret;
    private $code;
    private $scope;

    public function let()
    {
        $this->authorizeUrl = 'http://api.example.com/oauth/authorize';
        $this->tokenUrl = 'http://api.example.com/oauth/token';
        $this->clientId = 'client id';
        $this->clientSecret = 'client secret';
        $this->code = 'code';
        $this->scope = 'scope';

        $this->beConstructedWith(
            $this->authorizeUrl
        );

        $this->setHttpClient(new \Guzzle\Http\Client());
        $this->setClientCredential($this->clientId, $this->clientSecret);
        $this->setScope($this->scope);
        $this->setCode($this->code);
        $this->setTokenUrl($this->tokenUrl);
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('Authoritarian\Flow\AuthorizationCodeFlow');
    }

    public function it_should_get_the_authorize_url()
    {
        $this->getAuthUrl()
            ->shouldStartWith($this->authorizeUrl);
    }

    public function it_should_get_the_authorize_url_with_callback()
    {
        $callback = 'http://example.com/callback';
        $this->setRedirectUri($callback);
        $this->getAuthUrl()
            ->shouldHaveQueryParameter(
                'redirect_uri',
                $callback
            );
    }

    public function it_should_get_the_authorize_url_with_clent_id()
    {
        $this->getAuthUrl()
            ->shouldHaveQueryParameter(
                'client_id',
                $this->clientId
            );
    }

    public function it_should_get_the_authorize_url_with_the_correct_response_type()
    {
        $this->getAuthUrl()
            ->shouldHaveQueryParameter(
                'response_type',
                AuthorizationCodeFlow::RESPONSE_TYPE
            );
    }

    public function it_should_get_the_authorize_url_with_the_given_scope()
    {
        $this->getAuthUrl()
            ->shouldHaveQueryParameter('scope', $this->scope);
    }

    public function it_should_get_the_authorize_url_with_state_when_given()
    {
        $state = 'state';
        $this->setState($state);
        $this->getAuthUrl()
            ->shouldHaveQueryParameter('state', $state);
    }

    public function it_should_get_a_valid_authorize_url()
    {
        $this->setRedirectUri('http://example.com/callback');
        $this->setState('state');
        $this->getAuthUrl()
            ->shouldBeAValidUrl(
                FILTER_FLAG_PATH_REQUIRED | FILTER_FLAG_QUERY_REQUIRED
            );
    }

    public function it_should_throw_an_exception_to_get_a_request_without_code()
    {
        $this->setCode(null);
        $this->shouldThrow(
            '\Authoritarian\Exception\FlowException'
        )->duringGetRequest();
    }

    public function it_should_throw_an_exception_to_get_a_request_without_token_url()
    {
        $this->setTokenUrl(null);
        $this->shouldThrow(
            '\Authoritarian\Exception\FlowException'
        )->duringGetRequest();
    }

    public function it_should_get_post_a_request()
    {
        $this->getRequest()->getMethod()->shouldBe('POST');
    }

    public function it_should_get_a_request_to_the_token_url()
    {
        $this->getRequest()->getUrl()->shouldBeEqualTo($this->tokenUrl);
    }

    public function it_should_get_a_request_with_code()
    {
        $this->getRequest()->shouldHavePostParameter('code', $this->code);
    }

    public function it_should_get_a_request_with_client_id()
    {
        $this->getRequest()
            ->shouldHavePostParameter('client_id', $this->clientId);
    }

    public function it_should_get_a_request_with_client_secret()
    {
        $this->getRequest()
            ->shouldHavePostParameter('client_secret', $this->clientSecret);
    }

    public function it_should_get_a_request_with_redirect_uri()
    {
        $callback = 'http://example.com/callback';
        $this->setRedirectUri($callback);
        $this->getRequest()
            ->shouldHavePostParameter('redirect_uri', $callback);
    }

    public function it_should_get_a_request_with_scope()
    {
        $this->getRequest()
            ->shouldHavePostParameter('scope', $this->scope);
    }

    public function it_should_get_a_request_without_scope()
    {
        $this->setScope(null);
        $this->getRequest()
            ->shouldNotHavePostParameter('scope', '');
    }

    public function it_should_get_a_request_with_grant_type()
    {
        $this->getRequest()
            ->shouldHavePostParameter(
                'grant_type',
                AuthorizationCodeFlow::GRANT_TYPE
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
            'haveQueryParameter' => function ($subject, $key, $value) {
                $querystring = explode('?', $subject)[1];
                $parameters = array();
                parse_str($querystring, $parameters);

                return array_key_exists($key, $parameters) &&
                    $parameters[$key] == $value;
            },
            'havePostParameter' => function ($subject, $key, $value) {
                $body = preg_split('/\n\s*\n/', $subject)[1];
                $parameters = array();
                parse_str($body, $parameters);

                return array_key_exists($key, $parameters) &&
                    $parameters[$key] == $value;
            },
        );
    }
}
