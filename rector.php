<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Php80\Rector\Class_\AnnotationToAttributeRector;
use Rector\Symfony\Set\SymfonySetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->parallel();
    $rectorConfig->phpVersion(PhpVersion::PHP_81);
    $rectorConfig->cacheDirectory('var/rector');
    $rectorConfig->symfonyContainerXml(__DIR__ . '/var/cache/dev/AppKernelDevDebugContainer.xml');
    $rectorConfig->phpstanConfig(__DIR__ . '/phpstan.neon.dist');
    $rectorConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/migrations',
        __DIR__ . '/tests',
    ]);
    $rectorConfig->skip([
        AnnotationToAttributeRector::class,
        // __DIR__ . '/src',
        // __DIR__ . '/src/*/Tests/*',
        // __DIR__ . '/src/ComplicatedFile.php',
        // SimplifyIfReturnBoolRector::class,
        // SimplifyIfReturnBoolRector::class => [
        //    __DIR__ . '/src/ComplicatedFile.php',
        //    __DIR__ . '/src',
        //    __DIR__ . '/src/*/Tests/*',
        // ],
    ]);
    $rectorConfig->sets([
        SymfonySetList::SYMFONY_CODE_QUALITY,
    ]);
};
