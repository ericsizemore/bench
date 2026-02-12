<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Concat\JoinStringConcatRector;
use Rector\Config\RectorConfig;
use Rector\PHPUnit\CodeQuality\Rector\Class_\PreferPHPUnitSelfCallRector;
use Rector\PHPUnit\CodeQuality\Rector\Class_\PreferPHPUnitThisCallRector;

return RectorConfig::configure()
    ->withParallel()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withCache(
        __DIR__ . '/build/rector'
    )
    ->withRules([
        PreferPHPUnitSelfCallRector::class,
    ])
    ->withSkip([
        JoinStringConcatRector::class,
        PreferPHPUnitThisCallRector::class,
    ])
    ->withPhpSets(
        php82: true
    )
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        codingStyle: true,
        typeDeclarations: true,
        privatization: true,
        naming: true,
        earlyReturn: true,
        //strictBooleans: true,
        rectorPreset: true,
        phpunitCodeQuality: true,
    )
    ;
