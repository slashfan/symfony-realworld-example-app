<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Symfony\Set\SymfonySetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::CACHE_DIR, 'var/rector');
    $parameters->set(Option::PATHS, [__DIR__ . '/src', __DIR__ . '/tests']);
    $parameters->set(Option::SYMFONY_CONTAINER_XML_PATH_PARAMETER, __DIR__ . '/var/cache/dev/App_KernelDevDebugContainer.xml');
    $containerConfigurator->import(SymfonySetList::SYMFONY_CODE_QUALITY);
};
