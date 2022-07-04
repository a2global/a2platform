How to create new project, using A2Platform:

$ composer create-project symfony/skeleton:"5.4.*" project_name
$ cd project_name
$ composer require webapp
$ composer require symfony/apache-pack
(if recipe was not installed — no file public/.htaccess — run: $ composer recipes:install symfony/apache-pack)
$ composer require a2platform

Local dev:

update composer.json with:

"minimum-stability": "dev",
"repositories": [
    {
        "type": "path",
        "url": "../A2Platform"
    }
]

$ composer require "a2global/a2platform": "*"