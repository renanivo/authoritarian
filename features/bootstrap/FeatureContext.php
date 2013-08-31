<?php

use Behat\Behat\Context\ClosuredContextInterface;
use Behat\Behat\Context\TranslatedContextInterface;
use Behat\Behat\Context\BehatContext;
use Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

use Behat\Mink\Driver\Selenium2Driver;

use Authoritarian\Flow\ResourceOwnerPasswordFlow;
use Authoritarian\Flow\AuthorizationCodeFlow;
use Authoritarian\Credential\ClientCredential;

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
        putenv('NODE_PATH=' . dirname(dirname(__DIR__)) . '/node_modules/');
    }

    /**
     * @Given /^I use the resource owner password flow$/
     */
    public function iUseTheResourceOwnerPasswordFlow()
    {
        $this->flow = new ResourceOwnerPasswordFlow(
            $this->parameters['token_url'],
            $this->parameters['username'],
            $this->parameters['password']
        );
    }

    /**
     * @When /^I request the access token$/
     */
    public function iRequestTheAccessToken()
    {
        $this->flow->setClientCredential(
            new ClientCredential(
                $this->parameters['client_id'],
                $this->parameters['client_secret']
            )
        );
        $this->flow->setScope($this->parameters['scope']);
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
            $this->parameters['authorize_url']
        );
    }

    /**
     * @Given /^I authorize the app at the web ui$/
     */
    public function iAuthorizeTheAppAtTheWebUi()
    {
        $session = $this->startWebdriverSession();

        $this->flow->setRedirectUri($this->parameters['redirect_url']);

        $session->visit($this->flow->getAuthorizeUrl());
        $this->fillLoginFormAndSubmit($session->getPage());

        if ($this->wasAuthorizationButtonPrompted($session->getCurrentUrl())) {
            $session->getPage()
                ->pressButton($this->parameters['authorize_button']);
        }

        $code = $this->getAuthorizationCode($session->getCurrentUrl());
        $this->flow->setCode($code);
    }

    private function startWebdriverSession()
    {
        $driver = new Selenium2Driver(
            $this->parameters['browser'],
            null,
            $this->parameters['selenium_webdriver_host']
        );

        $session = new Behat\Mink\Session($driver);
        $session->start();

        return $session;
    }

    private function fillLoginFormAndSubmit($page)
    {
        $page->fillField(
            $this->parameters['username_field'],
            $this->parameters['username']
        );
        $page->fillField(
            $this->parameters['password_field'],
            $this->parameters['password']
        );
        $page->pressButton(
            $this->parameters['submit_button']
        );
    }

    private function wasAuthorizationButtonPrompted($currentUrl)
    {
        return $currentUrl == $this->flow->getAuthorizeUrl();
    }

    private function getAuthorizationCode($currentUrl)
    {
        $querystring = explode('?', $currentUrl)[1];
        $parameters = array();
        parse_str($querystring, $parameters);
        return $parameters['code'];
    }
}

