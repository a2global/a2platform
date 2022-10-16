https://symfony.com/doc/current/bundles/prepend_extension.html
https://symfonycasts.com/blog/behat-symfony
https://symfonycasts.com/screencast/symfony-bundle/packagist
https://www.thinktocode.com/2018/03/26/symfony-4-rest-api-part-1-fosrestbundle/

behat
    https://symfonycasts.com/blog/behat-symfony
    pecl install pcov
    brew install selenium-server
    

pathes:
    phpunit/php-code-coverage
    (vendor/a2global/a2platform/patches/phpunit/php-code-coverage/coverage_html_report.patch)

composer
    https://stackoverflow.com/questions/59214928/composer-repository-type-depends-on-environment
    COMPOSER=composer.dev.json composer update a2global/a2platform


"repositories": [
    {
        "type": "path",
        "url": "../A2Platform"
    }
]

https://symfony.com/doc/current/session/locale_sticky_session.html