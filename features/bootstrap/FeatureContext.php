<?php

use Behat\Behat\Context\ClosuredContextInterface;
use Behat\Behat\Context\TranslatedContextInterface;
use Behat\Behat\Context\BehatContext;
use Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

use Authoritarian\Flow\ResourceOwnerPasswordFlow;
use Authoritarian\Flow\AuthorizationCodeFlow;

/**
 * Features context.
 */
class FeatureContext extends BehatContext
{
    private $parameters;
    private $flow;
    private $accessToken;

    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @Given /^I use the resource owner password flow$/
     */
    public function iUseTheResourceOwnerPasswordFlow()
    {
        $this->flow = new ResourceOwnerPasswordFlow(
            $this->parameters['token_url'],
            $this->parameters['client_id'],
            $this->parameters['client_secret'],
            $this->parameters['scope'],
            $this->parameters['username'],
            $this->parameters['password']
        );
    }

    /**
     * @When /^I request the access token$/
     */
    public function iRequestTheAccessToken()
    {
        $client = new Guzzle\Http\Client();
        $authorization = new Authoritarian\Authorization($client);
        $this->accessToken = $authorization->requestAccessToken($this->flow);
    }

    /**
     * @Then /^I should get an array with the access token$/
     */
    public function iShouldGetAnArray()
    {
        if (!is_array($this->accessToken) ||
            !array_key_exists('access_token', $this->accessToken)
        ) {
            throw new Exception('Access token should be an array');
        }
    }

    /**
     * @Given /^I use the authorization code flow$/
     */
    public function iUseTheAuthorizationCodeFlow()
    {
        $this->flow = new AuthorizationCodeFlow(
            $this->parameters['token_url'],
            $this->parameters['client_id'],
            $this->parameters['client_secret'],
            $this->parameters['scope'],
            $this->parameters['authorize_url']
        );
    }

    /**
     * @Given /^I authorize the app at the web ui$/
     */
    public function iAuthorizeTheAppAtTheWebUi()
    {
        $host = '127.0.0.1';
        $port = 8124;
        $node_bin = '/usr/local/bin/node';
        $script = null;

        $driver = new \Behat\Mink\Driver\ZombieDriver(
            new \Behat\Mink\Driver\NodeJS\Server\ZombieServer($host, $port, $node_bin, $script)
        );
        $session = new \Behat\Mink\Session($driver);
        $session->start();

        $session->visit($this->flow->getAuthorizeUrl('callback://'));

        $page = $session->getPage();
    }

    /**
     * @When /^I request the acess token$/
     */
    public function iRequestTheAcessToken()
    {
        throw new PendingException();
    }
}

