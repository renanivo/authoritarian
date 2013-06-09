Feature: Resource Owner Password Flow
    In order to get authorization of an user
    As a Resource Owner app
    I need to be able to get the access token using username and password

    Scenario: Get the access token of a user
        Given I use the resource owner password flow
        When I request the Access Token
        Then I should get an array with the access token
