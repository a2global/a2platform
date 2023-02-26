How to create new project, using A2Platform:

composer create-project symfony/skeleton:"5.4.*" project_name
cd project_name
composer require webapp
composer require symfony/apache-pack
(if recipe was not installed — no file public/.htaccess — run: $ composer recipes:install symfony/apache-pack)
composer require a2global/a2platform

Local dev:

update composer.json with:

"minimum-stability": "dev",
"repositories": [
    {
        "type": "path",
        "url": "../A2Platform"
    }
]

-- 

copy recipes/packages/security.yml to project
copy recipes/routing/security.yml to project
make sure files are exists and extends base classes:
    Entity/User.php
    Repository/UserRepository.php

register bundles in the kernel.php:
    A2Global\A2Platform\Bundle\CoreBundle\CoreBundle::class => ['all' => true],
    A2Global\A2Platform\Bundle\AdminBundle\AdminBundle::class => ['all' => true],
    A2Global\A2Platform\Bundle\AuthBundle\AuthBundle::class => ['all' => true],
    A2Global\A2Platform\Bundle\DataBundle\DataBundle::class => ['all' => true],
    A2Global\A2Platform\Bundle\DatasheetBundle\DatasheetBundle::class => ['all' => true],

create .env.local
make:migration
doctrine:migrations:migrate
