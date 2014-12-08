<?php

use Behat\Behat\Context\BehatContext;
use Behat\Mink\Driver\Selenium2Driver;
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
            $this->parameters['username'],
            $this->parameters['password']
        );
        $this->flow->setClientCredential(
            $this->parameters['client_id'],
            $this->parameters['client_secret']
        );
    }

    /**
     * @When /^I request the access token$/
     */
    public function iRequestTheAccessToken()
    {
        $oauth2 = new Authoritarian\OAuth2($this->parameters['token_url']);
        $this->accessToken = $oauth2->requestAccessToken($this->flow);
    }

    /**
     * @Then /^I should get a response with the access token$/
     */
    public function iShouldGetAnArray()
    {
        if (!is_array($this->accessToken->json()) ||
            !array_key_exists('access_token', $this->accessToken->json())
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
            $this->parameters['authorize_url']
        );
        $this->flow->setClientCredential(
            $this->parameters['client_id'],
            $this->parameters['client_secret']
        );
    }

    /**
     * @Given /^I authorize the app at the web ui$/
     */
    public function iAuthorizeTheAppAtTheWebUi()
    {
        $session = $this->startWebdriverSession();

        $this->flow->setRedirectUri($this->parameters['redirect_url']);

        $session->visit($this->flow->getAuthUrl());
        $this->fillLoginFormAndSubmit($session->getPage());

        if ($this->wasAuthorizationButtonPrompted($session->getCurrentUrl())) {
            $page = $session->getPage();
            $page->pressButton($this->parameters['authorize_button']);
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
        return $currentUrl == $this->flow->getAuthUrl();
    }

    private function getAuthorizationCode($currentUrl)
    {
        $querystring = explode('?', $currentUrl)[1];
        $parameters = array();
        parse_str($querystring, $parameters);

        return $parameters['code'];
    }
}
