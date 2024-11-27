Feature: Basic test

  Scenario: Simple compute test
    When I multiply 3 by 5 into return
    Then return should be equal to 15

  Scenario: Simple compute test two
    When I multiply 5 by 5 into return
    Then return should be equal to 25

  Scenario: Simple compute test negative
    When I multiply 5 by 5 into return
    Then return should not be equal to 15