Feature: Request the user authorization token via OAuth2
    In order to get authorization of an user
    As a client app
    I need to be able to get the access token

    Scenario: Request access via resource owner password flow
        Given I use the resource owner password flow
        When I request the access token
        Then I should get an array with the access token
