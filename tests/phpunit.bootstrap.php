<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

$kernel = new App\Kernel('test', true);
$kernel->boot();

$application = new Symfony\Bundle\FrameworkBundle\Console\Application($kernel);
$application->setAutoExit(false);

$application->run(new Symfony\Component\Console\Input\ArrayInput([
    'command' => 'doctrine:database:drop',
    '--force' => '1',
]));

$application->run(new Symfony\Component\Console\Input\ArrayInput([
    'command' => 'doctrine:database:create',
]));

$application->run(new Symfony\Component\Console\Input\ArrayInput([
    'command' => 'doctrine:schema:create',
]));

$application->run(new Symfony\Component\Console\Input\ArrayInput([
    'command' => 'doctrine:fixtures:load',
    '--no-interaction' => '1',
]));

$kernel->shutdown();
