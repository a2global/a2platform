<?php

namespace A2Global\A2Platform\Bundle\DevelopmentBundle\Behat;

use Behat\Mink\Driver\Selenium2Driver;
use Behat\MinkExtension\Context\MinkContext;

class OmniContext extends MinkContext
{
    const SCREENSHOTS_DIRECTORY = './tests/Behat/Screenshots';
    const PAGE_LOAD_TIME_SECONDS = 2;
    const slow = true;
    protected $response;

    /**
     * @AfterStep
     * Take screenshot when step fails. Works only with Selenium2Driver.
     * Screenshot is saved at [Date]/[Feature]/[Scenario]/[Step].jpg
     */
    public function after($scope)
    {
        if ($scope->getTestResult()->getResultCode() === 99) {
            $driver = $this->getSession()->getDriver();
            if ($driver instanceof Selenium2Driver) {
                $fileName = date('d-m-y') . '-' . uniqid() . '.png';
                $this->saveScreenshot($fileName, self::SCREENSHOTS_DIRECTORY);
                print 'Screenshot at: ' . self::SCREENSHOTS_DIRECTORY . '/' . $fileName;
            }
        }
    }

    /**
     * @When I accept confirmation dialogs
     */
    public function acceptConfirmation()
    {
        $this->getSession()->getDriver()->getWebDriverSession()->accept_alert();
    }

    public function clickLink($link)
    {
        $link = $this->fixStepArgument($link);
        $this->getSession()->getPage()->clickLink($link);
    }

    /**
     * @Then /^I should see "([^"]*)" in the code$/
     */
    public function inspectCode($code)
    {
        if (!$this->assertSession()->statusCodeEquals($code)) {
            throw new Exception("There is no such value in code.");
        }
        return $this->assertSession()->statusCodeEquals($code);
    }

    /**
     * Filling the field with parameter using jQuery . Some forms can't be filled using other functions.
     *
     * @When /^(?:|I )fill the field "(?P<field>(?:[^"]|\\")*)" with value "(?P<value>(?:[^"]|\\")*)" using jQuery$/
     */
    public function checkFieldValue($id, $value)
    {
        $response = $this->getSession()->getDriver()->evaluateScript(
            "return jQuery('#" . $id . "').val();"
        );
        if ($response != $value) {
            throw new Exception("Value doesn't match");
        }
    }

    /**
     * @Then /^I execute jQuery click on selector "([^"]*)"$/
     */
    public function executeJQueryForSelector($arg)
    {

        $jQ = "return jQuery('" . $arg . "').click();";
        #$this->getSession()->getDriver()->evaluateScript($jQ);

        try {
            $this->getSession()->getDriver()->evaluateScript($jQ);
        } catch (Exception $e) {
            throw new \Exception("Selector isn't valid");
        }

    }

    /**
     * Setting custom size of the screen using width and height parameters
     *
     * @Given /^the custom size is "([^"]*)" by "([^"]*)"$/
     */
    public function theCustomSizeIs($width, $height)
    {
        $this->getSession()->resizeWindow($width, $height, 'current');
    }

    /**
     * Setting screen size to 1400x900 (desktop)
     *
     * @Given /^the size is desktop/
     */
    public function theSizeIsDesktop()
    {
        $this->getSession()->resizeWindow(1400, 900, 'current');
    }

    /**
     * Setting screen size to 1024x900 (tablet landscape)
     *
     * @Given /^the size is tablet landscape/
     */
    public function theSizeIsTabletLandscape()
    {
        $this->getSession()->resizeWindow(1024, 900, 'current');
    }

    /**
     * Setting screen size to 768x900 (tablet portrait)
     *
     * @Given /^the size is tablet portrait/
     */
    public function theSizeIsTabletPortrait()
    {
        $this->getSession()->resizeWindow(768, 900, 'current');
    }

    /**
     * Setting screen size to 640x900 (mobile landscape)
     *
     * @Given /^the size is mobile landscape/
     */
    public function theSizeIsMobileLandscape()
    {
        $this->getSession()->resizeWindow(640, 900, 'current');
    }

    /**
     * Setting screen size to 320x900 (mobile portrait)
     *
     * @Given /^the size is mobile portrait/
     */
    public function theSizeIsMobilePortrait()
    {
        $this->getSession()->resizeWindow(320, 900, 'current');
    }

    /**
     * Check if the port is 443(https) or 80(http) / secure or not.
     *
     * @Then /^the page is secure$/
     */
    public function thePageIsSecure()
    {
        $current_url = $this->getSession()->getCurrentUrl();
        if (strpos($current_url, 'https') === false) {
            throw new Exception('Page is not using SSL and is not Secure');
        }
    }

    /**
     * This will cause a 3 second delay
     *
     * @Given /^I wait$/
     */
    public function iWait()
    {
        sleep(3);
    }

    /**
     * Hover over an item using id|name|class
     *
     * @Given /^I hover over the item "([^"]*)"$/
     */
    public function iHoverOverTheItem($arg1)
    {
        if ($this->getSession()->getPage()->find('css', $arg1)) {
            $this->getSession()->getPage()->find('css', $arg1)->mouseOver();
        } else {
            throw new Exception('Element not found');
        }
    }

    /**
     * See if Element has style eg p.padL8 has style font-size= 12px
     *
     * @Then /^the element "([^"]*)" should have style "([^"]*)"$/
     */
    public function theElementShouldHaveStyle($arg1, $arg2)
    {
        $element = $this->getSession()->getPage()->find('css', $arg1);
        if ($element) {
            if (strpos($element->getAttribute('style'), $arg2) === FALSE) {
                throw new Exception('Style not found');
            }
        } else {
            throw new Exception('Element not found');
        }
    }

    /**
     * Look for a cookie
     *
     * @Then /^I should see cookie "([^"]*)"$/
     */
    public function iShouldSeeCookie($cookie_name)
    {
        if ($this->getSession()->getCookie('welcome_info_name') == $cookie_name) {
            return TRUE;
        } else {
            throw new Exception('Cookie not found');
        }
    }

    /**
     * Setting the cookie with particular value
     *
     * @Then /^I set cookie "([^"]*)" with value "([^"]*)"$/
     */
    public function iSetCookieWithValue($cookie_name, $value)
    {
        $this->getSession()->setCookie($cookie_name, $value);
    }

    /**
     * Check if the cookie isn't presented
     *
     * @Then /^I should not see cookie "([^"]*)"$/
     */
    public function iShouldNotSeeCookie($cookie_name)
    {
        if ($this->getSession()->getCookie('welcome_info_name') == $cookie_name) {
            throw new Exception('Cookie not found');
        }
    }

    /**
     * Destroy cookies. Resetting the session
     *
     * @Then /^I reset the session$/
     */
    public function iDestroyMyCookies()
    {
        $this->getSession()->reset();
    }


    /**
     * See if element is visible
     *
     * @Then /^Element "([^"]*)" is visible$/
     */
    public function elementIsVisible($arg)
    {
        $el = $this->getSession()->getPage()->find('css', $arg);
        if ($el) {
            if (!$el->isVisible()) {
                throw new Exception('Element is not visible');
            }
        } else {
            throw new Exception('Element not found');
        }
    }

    /**
     * See if element is not visible
     *
     * @Then /^Element "([^"]*)" is not visible$/
     */
    public function elementIsNotVisible($arg)
    {
        $el = $this->getSession()->getPage()->find('css', $arg);
        if ($el) {
            if ($el->isVisible()) {
                throw new Exception('Element is visible');
            }
        } else {
            throw new Exception('Element not found');
        }
    }

    /**
     * @Then /^Element "([^"]*)" exists/
     */
    public function elementExists($arg)
    {
        $el = $this->getSession()->getPage()->find('css', $arg);
        if (!$el) {
            throw new Exception('Element doesnt exists, but should: ' . $arg);
        }
    }

    /**
     * @Then /^Element "([^"]*)" doesnt exists$/
     */
    public function elementDoesntExists($arg)
    {
        $el = $this->getSession()->getPage()->find('css', $arg);
        if ($el) {
            throw new Exception('Element exists, but should not: ' . $arg);
        }
    }

    /**
     * Set a waiting time in seconds
     *
     * @Given /^I wait for "([^"]*)" seconds$/
     */
    public function iWaitForSeconds($arg1)
    {
        sleep((int)$arg1);
    }

    /**
     * Switching to iFrame with Name(don't use id, title etc. ONLY NAME)
     *
     * @Given /I switch to iFrame named "([^"]*)"$/
     */
    public function iSwitchToIframeNamed($arg1)
    {
        $this->getSession()->switchToIFrame($arg1);
    }

    /**
     * Switching to Window with Name(don't use id, title etc. ONLY NAME)
     *
     * @Given /^I switch to window named "([^"]*)"$/
     */
    public function iSwitchPreviousToWindow($arg1)
    {
        $this->getSession()->switchToWindow($arg1);
    }

    /**
     * Switching to second window
     *
     * @Given /^I switch to the second window$/
     */
    public function iSwitchToSecondWindow()
    {
        $windowNames = $this->getSession()->getWindowNames();
        if (count($windowNames) > 1) {
            $this->getSession()->switchToWindow($windowNames[1]);
        }
    }

    /**
     * Click an element with an onclick handler
     *
     * @Given /^I click on element which has onclick handler located at "([^"]*)"$/
     */
    public function iClickOnElementWhichHasOnclickHandlerLocatedAt($item)
    {
        $node = $this->getSession()->getPage()->find('css', $item);
        if ($node) {
            $this->getSession()->wait(3000,
                "jQuery('{$item}').trigger('click')"
            );
        } else {
            throw new Exception('Element not found');
        }
    }

    /**
     * Y would be the way to up and down the page. A good default for X is 0
     *
     * @Given /^I scroll to x "([^"]*)" y "([^"]*)" coordinates of page$/
     */
    public function iScrollToXYCoordinatesOfPage($arg1, $arg2)
    {
        $function = "(function(){
              window.scrollTo($arg1, $arg2);
            })()";
        try {
            $this->getSession()->executeScript($function);
        } catch (Exception $e) {
            throw new \Exception("ScrollIntoView failed");
        }
    }

    /**
     * Check existence of JavaScript variable on loaded page.
     *
     * @Then /^I should see "([^"]*)" Js variable$/
     */
    public function iShouldSeeJsVariable($variable_name)
    {

        $javascript = <<<EOT
return (typeof $variable_name === "undefined") ? 0 : 1;
EOT;

        // Execute javascript and return variable value or undefined
        // if javascript variable not exists or equals to undefined.
        $variable_value_exist = $this->getSession()->evaluateScript($javascript);

        if (empty($variable_value_exist)) {
            throw new Exception('JavaScript variable doesn\'t exists or undefined.');
        }
    }

    /**
     * Check NON existence of JavaScript variable on loaded page.
     *
     * @Then /^I should not see "([^"]*)" Js variable$/
     */
    public function iShouldNotSeeJsVariable($variable_name)
    {

        $javascript = 'return (typeof $variable_name != $variable_value_exist) ? 0 : 1;';

        // Execute javascript and return variable value or undefined
        // if javascript variable not exists or equals to undefined.
        $variable_value_exist = $this->getSession()->evaluateScript($javascript);

        if (empty($variable_value_exist)) {
            throw new Exception('JavaScript variable match.');
        }
    }

    /**
     * @Then /^I should see "([^"]*)" in the "([^"]*)" Js variable$/
     */
    public function iShouldSeeInTheJsVariable($variable_value, $variable_name)
    {

        $javascript = <<<EOT
return (typeof $variable_name === "undefined") ? "" : $variable_name;
EOT;

        // Execute javascript and return variable value or undefined
        // if javascript variable not exists or equals to undefined.
        $variable_value_exist = $this->getSession()->evaluateScript($javascript);

        if ($variable_value_exist === "undefined") {
            throw new Exception('JavaScript variable doesn\'t exists or undefined.');
        }

        if ($variable_value != $variable_value_exist) {
            throw new Exception('JavaScript variable value doesn\'t match.');
        }
    }

    /**
     * Scrolling to the particular element(arg1 - Nav menu selector, arg2 - element's selector to scroll to)
     *
     * @Given /^I scroll to element "([^"]*)" "([^"]*)"$/
     */
    public function iScrollToElement($arg1, $arg2)
    {
        $function = <<<JS
     var headerHeight = jQuery('$arg2').outerHeight(true),
          scrollBlock = jQuery('$arg1').offset().top;
 jQuery('body, html').scrollTo(scrollBlock - headerHeight);

JS;
        try {
            $this->getSession()->executeScript($function);
        } catch (Exception $e) {
            throw new \Exception("ScrollIntoElement failed");
        }
    }

    /**
     * Verifying that element has particular class
     *
     * @When /^element "(?P<field>(?:[^"]|\\")*)" should have class "(?P<value>(?:[^"]|\\")*)"$/
     */
    public function checkElementClass($arg, $class)
    {
        $response = $this->getSession()->getDriver()->evaluateScript(
            "           
            return (function () {
            var element = jQuery('" . $arg . "');
            if (element.length > 0) {
              if (element.hasClass('" . $class . "')){
                return 'Ok';
              }
              
              else {
                return 'Class doesn\'t match';
              }
            }
            else {
              return 'Selector wasn\'t found';
            }
            })();
            "
        );
        if ($response != 'Ok') {
            throw new Exception($response);
        }
    }

    /**
     * Checks, that page does not contain specified texts
     *
     * @Then /^I dont see "([^"]*)"(?: "([^"]*)")?(?: "([^"]*)")?(?: "([^"]*)")?(?: "([^"]*)")?$/
     */
    public function assertPageDoesntContainsTexts($text1, $text2 = null, $text3 = null, $text4 = null, $text5 = null)
    {
        $texts = [$text1, $text2, $text3, $text4, $text5];

        foreach ($texts as $text) {
            if (is_null($text)) {
                continue;
            }
            $this->assertSession()->pageTextNotContains($this->fixStepArgument($text));
        }
    }

    /**
     * Checks, that page contains specified texts
     *
     * @Then /^I see "([^"]*)"(?: "([^"]*)")?(?: "([^"]*)")?(?: "([^"]*)")?(?: "([^"]*)")?$/
     */
    public function assertPageContainsTexts($text1, $text2 = null, $text3 = null, $text4 = null, $text5 = null)
    {
        $texts = [$text1, $text2, $text3, $text4, $text5];

        foreach ($texts as $text) {
            if (is_null($text)) {
                continue;
            }
            $this->assertSession()->pageTextContains($this->fixStepArgument($text));
        }
    }

    /**
     * @Then /^Current URL is "([^"]*)"$/
     */
    public function assertCurrentUrlIs($url)
    {
        $urlParsed = parse_url($this->getSession()->getCurrentUrl());

        if ($this->getSession()->getCurrentUrl() !== $url) {
            throw new \InvalidArgumentException(sprintf('Failing asserting current url "%s" is "%s"', $this->getSession()->getCurrentUrl(), $url));
        }
    }

    /**
     * @Then /^Current URL path is "([^"]*)"$/
     */
    public function assertCurrentUrlPathIs($path)
    {
        $urlParsed = parse_url($this->getSession()->getCurrentUrl());
        $actual = trim($urlParsed['path'], '/');
        $expected = trim($path, '/');

        if ($actual !== $expected) {
            throw new \InvalidArgumentException(sprintf('Failing asserting current url "%s" is "%s"', $actual, $expected));
        }
    }

    /**
     * @Given /^I wait a lot$/
     */
    public function iWaitALot()
    {
        sleep(60);
    }

    /**
     * @Given /^I wait for page load$/
     */
    public function iWaitForPageLoad()
    {
        sleep(self::PAGE_LOAD_TIME_SECONDS);
    }

    /**
     * Setting screen size to 320x480 (mobile portrait)
     * iPhone 4.7-inch
     * iPhone 6, iPhone 6S, iPhone 7, iPhone 8
     * @Given /^The size is mobile$/
     */
    public function theSizeIsMobile()
    {
        $this->getSession()->resizeWindow(375, 667, 'current');
    }

    /**
     * @When /^I checking checkbox with name "([^"]*)"$/
     * Param = NAME ONLY
     */
    public function iCheckingCheckboxByName($name)
    {
        $this->iWaitForSeconds(1);
        $selector = sprintf("[name='%s']", $name);
        $element = $this->getSession()->getPage()->find('css', $selector);

        if (!$element) {
            throw new Exception('Checkbox not found by name: ' . $name);
        }
        $element->check();
    }

    /**
     * @When /^I unchecking checkbox with name "([^"]*)"$/
     * Param = NAME ONLY
     */
    public function iUncheckingCheckboxByName($name)
    {
        $this->iWaitForSeconds(1);
        $selector = sprintf("[name='%s']", $name);
        $element = $this->getSession()->getPage()->find('css', $selector);

        if (!$element) {
            throw new Exception('Checkbox not found by name: ' . $name);
        }
        $element->uncheck();
    }

    /**
     * @When /^I click element with selector "([^"]*)"$/
     */
    public function iClickElementWithSelector($selector)
    {
        $this->getSession()->getPage()->find('css', $selector)->click();
    }

    /**
     * @When /^I fill element with selector "([^"]*)" with "([^"]*)"$/
     */
    public function iFillElementWithSelector($selector, $value)
    {
        $this->getSession()->getPage()->find('css', $selector)->setValue($value);
    }

    /**
     * @When /^I click "([^"]*)"( "([^"]*)")?$/
     */
    public function iClick($text, $null = null, $additional = null)
    {
        $session = $this->getSession();
        $xpath = sprintf('//div[text()="%s" and contains(@class, \'tap\')%s]', $text, ($additional ? ' and @' . trim($additional) : ''));
        $selector = $session->getSelectorsHandler()->selectorToXpath('xpath', $xpath);
        $element = $session->getPage()->find('xpath', $selector);

        if (null === $element) {
            throw new \InvalidArgumentException(sprintf('Cannot find text: "%s"', $text));
        }
//        var_dump($element->getXpath());
//        var_dump($element->getOuterHtml());
        $element->click();

        if (static::slow) {
            $this->iWaitForSeconds(2);
        }
    }

    /**
     * Fills in specified field with date
     * Example: When I fill in "field_ID" with date "now"
     * Example: When I fill in "field_ID" with date "-7 days"
     * Example: When I fill in "field_ID" with date "+7 days"
     * Example: When I fill in "field_ID" with date "-/+0 weeks"
     * Example: When I fill in "field_ID" with date "-/+0 years"
     *
     * @When /^(?:|I )fill in "(?P<field>(?:[^"]|\\")*)" with date "(?P<value>(?:[^"]|\\")*)"$/
     */
    public function fillDateField($field, $value)
    {
        $newDate = strtotime("$value");

        $dateToSet = date("d/m/Y", $newDate);
        $this->getSession()->getPage()->fillField($field, $dateToSet);
    }

    /**
     * @Given /^I am in the office$/
     */
    public function iAmInTheOffice()
    {
        $this->iAmOnHomepage();
        $this->clickLink('Sign in');
        $this->fillField('phoneNumber', 1);
        $this->fillField('password', 1);
        $this->pressButton('Log in');
        $this->clickLink('Office');
        $this->assertPageContainsText('Staging');

        if (static::slow) {
            $this->iWaitForSeconds(2);
        }
    }

    /**
     * @Then /^I should see time "([^"]*)"$/
     */
    public function iShouldSeeTime($format)
    {
        $date = new \DateTime("now", new \DateTimeZone("Europe/Kiev"));
        $this->assertSession()->pageTextContains($this->fixStepArgument($date->format($format)));
    }

    /**
     * @Then /^I should see rounded time "([^"]*)" with format "([^"]*)"$/
     */
    public function iShouldSeeRoundedTimeWithFormat($time, $format)
    {
        $date = new \DateTime($time, new \DateTimeZone("Europe/Kiev"));
        $left = $date->format('i') % 5;
        $minutesShift = $left === 4 ? 6 : 5 - $left;
        $date->modify(sprintf('+%s minutes', $minutesShift));
        echo 'Asserting page has next time text: `' . $date->format($format) . '`';
        $this->assertSession()->pageTextContains($this->fixStepArgument($date->format($format)));
    }

    /**
     * @When /^I click on element contains "([^"]*)"$/
     */
    public function iClickOnElementContains($text)
    {
        $page = $this->getSession()->getPage();
        $tags = ['div', 'span', 'a', 'button'];

        foreach ($tags as $tag) {
            $element = $page->find("css", sprintf('%s.tap:contains(%s)', $tag, $text));

            if ($element) {
//                var_dump($element->getXpath());
//                var_dump($element->getOuterHtml());
                $element->click();

                if (static::slow) {
                    $this->iWaitForSeconds(2);
                }

                return;
            }
        }

        throw new Exception(sprintf('Element %s containing text `%s` couldn`t be found', implode(', ', $tags), $text));
    }

    /**
     * @When /^Element not exists "([^"]*)"$/
     */
    public function elementNotExists($selector)
    {
        $element = $this->getSession()->getPage()->find('css', $selector);

        if (!is_null($element)) {
            throw new Exception('Element doesnt exists: ' . $selector);
        }
    }

    /**
     * @When /^I send a "([^"]*)" request to "([^"]*)"$/
     *
     * @param $method
     * @param $uri
     */
    public function iSendARequestTo($method, $uri)
    {
        $client = $this->getSession()->getDriver()->getClient();
        $this->response = $client->request($method, $this->baseUrl . $uri);
    }

    /**
     * @Then /^the response type should be "([^"]*)"$/
     *
     * @param $type
     * @throws Exception
     */
    public function theResponseTypeShouldBeJson($type)
    {
        $headers = $this->getSession()->getResponseHeaders();

        if ($headers['Content-Type'][0] != $type) {
            throw new Exception('Invalid response type');
        }
    }

    /**
     * @When /^I click the radio button with "([^"]*)" label name$/
     */
    public function iSelectTheRadioButton($labelText)
    {
        // Find the label by its text, then use that to get the radio item's ID
        $radioId = null;
        /** #var $label NodeElement */
        foreach ($this->getSession()->getPage()->findAll('css', 'label') as $label) {
            if ($labelText === $label->getText()) {
                $label->click();
            }
        }
    }

    /**
     * @Then /^Element "([^"]*)" should have value "([^"]*)"$/
     */
    public function elementShouldHaveValue($selector, $expected)
    {
        $selector = '[name="' . $selector . '"]';
        $element = $this->getSession()->getPage()->find('css', $selector);

        if (!$element) {
            throw new Exception(sprintf('Element `%s` not found', $selector));
        }
        $actual = $this->getSession()->getPage()->find('css', $selector)->getValue();

        if ($expected != $actual) {
            throw new Exception(
                sprintf('Expected value `%s`, got `%s` in the `%s`', $expected, $actual, $selector)
            );
        }
    }
}