<?php

namespace spec\Authoritarian\Flow;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use PhpSpec\Matcher\CallbackMatcher;

class AuthorizationCodeFlowSpec extends ObjectBehavior
{
    private $authorizeUrl;

    public function let()
    {
        $this->authorizeUrl = 'http://api.example.com/oauth/authorize';
        $this->beConstructedWith(
            'http://api.example.com/oauth/token',
            'client id',
            'client secret',
            'scope',
            $this->authorizeUrl
        );
        $this->setClient(new \Guzzle\Http\Client());
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('Authoritarian\Flow\AuthorizationCodeFlow');
    }

    public function it_should_get_the_authorize_with_the_given_url()
    {
        $this->getAuthorizeUrl('http://example.com/callback')
            ->shouldStartWith($this->authorizeUrl);
    }

    public function it_should_get_the_authorize_url_with_callback()
    {
        $this->getAuthorizeUrl('http://example.com/callback')
            ->shouldMatch('/redirect_uri=http%3A%2F%2Fexample.com%2Fcallback/');
    }

    public function it_should_get_the_authorize_url_with_clent_id()
    {
        $this->getAuthorizeUrl('http://example.com/callback')
            ->shouldMatch('/client_id=client\+id/');
    }

    public function it_should_get_the_authorize_url_with_state_when_given()
    {
        $this->getAuthorizeUrl('http://example.com/callback', 'state')
            ->shouldMatch('/state=state/');
    }

    public function it_should_get_a_valid_authorize_url()
    {
        $this->getAuthorizeUrl('http://example.com/callback')
            ->shouldBeAValidUrl();
    }

    public function getMatchers()
    {
        return array(
            'beAValidUrl' => function($subject) {
                return false !== filter_var(
                    $subject,
                    FILTER_VALIDATE_URL,
                    array(
                        'flags' => array(
                            FILTER_FLAG_PATH_REQUIRED,
                            FILTER_FLAG_QUERY_REQUIRED,
                        ),
                    )
                );
            }
        );
    }
}

