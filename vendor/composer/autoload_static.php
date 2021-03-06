<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit51ff88e5013c78154ddb7c72e26e73d9
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Psr\\SimpleCache\\' => 16,
            'Psr\\Cache\\' => 10,
        ),
        'M' => 
        array (
            'MatthiasMullie\\Scrapbook\\' => 25,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Psr\\SimpleCache\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/simple-cache/src',
        ),
        'Psr\\Cache\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/cache/src',
        ),
        'MatthiasMullie\\Scrapbook\\' => 
        array (
            0 => __DIR__ . '/..' . '/matthiasmullie/scrapbook/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit51ff88e5013c78154ddb7c72e26e73d9::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit51ff88e5013c78154ddb7c72e26e73d9::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
