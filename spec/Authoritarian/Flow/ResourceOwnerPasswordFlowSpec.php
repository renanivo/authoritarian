<?php

namespace spec\Authoritarian\Flow;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Authoritarian\Flow\ResourceOwnerPasswordFlow;
use Authoritarian\Credential\ClientCredential;

class ResourceOwnerPasswordFlowSpec extends ObjectBehavior
{
    private $tokenUrl;
    private $username;
    private $password;
    private $clientId;
    private $clientSecret;

    public function let()
    {
        $this->tokenUrl = 'http://api.example.com/oauth/token';
        $this->username = 'username';
        $this->password = 'password';
        $this->clientId = 'client id';
        $this->clientSecret = 'client secret';
        $client = new \Guzzle\Http\Client();

        $this->beConstructedWith(
            $this->username,
            $this->password
        );

        $this->setTokenUrl($this->tokenUrl);
        $this->setHttpClient($client);
        $this->setClientCredential(
            new ClientCredential($this->clientId, $this->clientSecret)
        );
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType('Authoritarian\Flow\ResourceOwnerPasswordFlow');
    }

    public function it_should_create_a_request_to_the_token_url()
    {
        $this->getRequest()->getUrl()->shouldBeEqualTo($this->tokenUrl);
    }

    public function it_should_create_a_request_with_method_post()
    {
        $this->getRequest()
            ->getMethod()
            ->shouldBe('POST');
    }

    public function it_should_create_a_request_with_form_url_encode_header()
    {
        $this->getRequest()
            ->getHeader('Content-Type')
            ->hasValue('application/x-www-form-urlencoded; charset=utf-8')
            ->shouldBe(true);
    }

    public function it_should_create_a_request_with_client_id_in_the_body()
    {
        $this->getRequest()
            ->shouldHavePostParameter('client_id', $this->clientId);
    }

    public function it_should_create_a_request_with_client_secret_in_the_body()
    {
        $this->getRequest()
            ->shouldHavePostParameter('client_secret', $this->clientSecret);
    }

    public function it_should_create_a_request_with_grant_type_password_in_the_body()
    {
        $this->getRequest()
            ->shouldHavePostParameter(
                'grant_type',
                ResourceOwnerPasswordFlow::GRANT_TYPE
            );
    }

    public function it_should_create_a_request_with_the_given_scope_in_the_body()
    {
        $scope = 'scope';
        $this->setScope($scope);
        $this->getRequest()
            ->shouldHavePostParameter('scope', $scope);
    }

    public function it_should_create_a_request_with_username_in_the_body()
    {
        $this->getRequest()
            ->shouldHavePostParameter('username', $this->username);
    }

    public function it_should_create_a_request_with_password_in_the_body()
    {
        $this->getRequest()
            ->shouldHavePostParameter('password', $this->password);
    }

    public function getMatchers()
    {
        return array(
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

