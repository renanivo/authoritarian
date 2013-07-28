<?php

namespace spec\Authoritarian\Flow;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Authoritarian\Exception\FlowException;

class AuthorizationCodeFlowSpec extends ObjectBehavior
{
    private $authorizeUrl;
    private $tokenUrl;

    public function let()
    {
        $this->authorizeUrl = 'http://api.example.com/oauth/authorize';
        $this->tokenUrl = 'http://api.example.com/oauth/token';

        $this->beConstructedWith(
            $this->tokenUrl,
            'client id',
            'client secret',
            'scope',
            $this->authorizeUrl
        );
        $this->setHttpClient(new \Guzzle\Http\Client());
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('Authoritarian\Flow\AuthorizationCodeFlow');
    }

    public function it_should_get_the_authorize_url()
    {
        $this->getAuthorizeUrl()
            ->shouldStartWith($this->authorizeUrl);
    }

    public function it_should_get_the_authorize_url_with_callback()
    {
        $this->setRedirectUri('http://example.com/callback');
        $this->getAuthorizeUrl()
            ->shouldMatch('/redirect_uri=http%3A%2F%2Fexample.com%2Fcallback/');
    }

    public function it_should_get_the_authorize_url_with_clent_id()
    {
        $this->setRedirectUri('http://example.com/callback');
        $this->getAuthorizeUrl()
            ->shouldMatch('/client_id=client\+id/');
    }

    public function it_should_get_the_authorize_url_with_the_correct_response_type()
    {
        $this->setRedirectUri('http://example.com/callback');
        $this->getAuthorizeUrl()
            ->shouldMatch('/response_type=code/');
    }

    public function it_should_get_the_authorize_url_with_the_given_scope()
    {
        $this->setRedirectUri('http://example.com/callback');
        $this->getAuthorizeUrl()
            ->shouldMatch('/scope=scope/');
    }

    public function it_should_get_the_authorize_url_with_state_when_given()
    {
        $this->setRedirectUri('http://example.com/callback');
        $this->setState('state');
        $this->getAuthorizeUrl()
            ->shouldMatch('/state=state/');
    }

    public function it_should_get_a_valid_authorize_url()
    {
        $this->getAuthorizeUrl('http://example.com/callback')
            ->shouldBeAValidUrl(
                FILTER_FLAG_PATH_REQUIRED | FILTER_FLAG_QUERY_REQUIRED
            );
    }

    public function it_should_throw_an_exception_to_get_a_request_without_code()
    {
        $this->shouldThrow(
            new FlowException(
                'No code set. Impossible to create an '
                . 'Authorization Code Flow Request'
            )
        )->duringGetRequest();
    }

    public function it_should_get_post_a_request()
    {
        $this->setCode('code');
        $this->getRequest()->getMethod()->shouldBe('POST');
    }

    public function it_should_get_a_request_to_the_token_url()
    {
        $this->setCode('code');
        $this->getRequest()->getUrl()->shouldBeEqualTo($this->tokenUrl);
    }

    public function it_should_get_a_request_with_code()
    {
        $this->setCode('my-code');
        $this->getRequest()->__toString()->shouldMatch('/code=my-code/');
    }

    public function it_should_get_a_request_with_client_id()
    {
        $this->setCode('code');
        $this->getRequest()
            ->__toString()
            ->shouldMatch('/client_id=client%20id/');
    }

    public function it_should_get_a_request_with_client_secret()
    {
        $this->setCode('code');
        $this->getRequest()
            ->__toString()
            ->shouldMatch('/client_secret=client%20secret/');
    }

    public function it_should_get_a_request_with_redirect_uri()
    {
        $this->setCode('code');
        $this->setRedirectUri('http://example.com/callback');
        $this->getRequest()
            ->__toString()
            ->shouldMatch('/redirect_uri=http%3A%2F%2Fexample.com%2Fcallback/');
    }

    public function it_should_get_a_request_with_scope()
    {
        $this->setCode('code');
        $this->getRequest()
            ->__toString()
            ->shouldMatch('/scope=scope/');
    }

    public function it_should_get_a_request_with_grant_type()
    {
        $this->setCode('code');
        $this->getRequest()
            ->__toString()
            ->shouldMatch('/grant_type=authorization_code/');
    }

    public function it_should_get_a_request_with_state()
    {
        $this->setCode('code');
        $this->getRequest()
            ->__toString()
            ->shouldMatch('/grant_type=authorization_code/');
    }

    public function getMatchers()
    {
        return array(
            'beAValidUrl' => function($subject, $flags) {
                return false !== filter_var(
                    $subject,
                    FILTER_VALIDATE_URL,
                    array(
                        'flags' => $flags,
                    )
                );
            }
        );
    }
}

