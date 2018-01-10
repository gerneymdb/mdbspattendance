<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb63891d3bba0578317ed4c5761ca5743
{
    public static $prefixLengthsPsr4 = array (
        'p' => 
        array (
            'phpseclib\\' => 10,
        ),
        'P' => 
        array (
            'Psr\\Log\\' => 8,
        ),
        'F' => 
        array (
            'Fuel\\Upload\\' => 12,
        ),
        'C' => 
        array (
            'Composer\\Installers\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'phpseclib\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpseclib/phpseclib/phpseclib',
        ),
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/Psr/Log',
        ),
        'Fuel\\Upload\\' => 
        array (
            0 => __DIR__ . '/..' . '/fuelphp/upload/src',
        ),
        'Composer\\Installers\\' => 
        array (
            0 => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers',
        ),
    );

    public static $prefixesPsr0 = array (
        'M' => 
        array (
            'Monolog' => 
            array (
                0 => __DIR__ . '/..' . '/monolog/monolog/src',
            ),
            'Michelf' => 
            array (
                0 => __DIR__ . '/..' . '/michelf/php-markdown',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitb63891d3bba0578317ed4c5761ca5743::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb63891d3bba0578317ed4c5761ca5743::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInitb63891d3bba0578317ed4c5761ca5743::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
