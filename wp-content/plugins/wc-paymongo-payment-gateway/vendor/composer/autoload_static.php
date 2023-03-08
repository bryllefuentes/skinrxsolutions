<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit0504ed8b584569c0377de9e4ad4826eb
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PostHog\\' => 8,
        ),
        'C' => 
        array (
            'Cynder\\PayMongo\\' => 16,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PostHog\\' => 
        array (
            0 => __DIR__ . '/..' . '/posthog/posthog-php/lib',
        ),
        'Cynder\\PayMongo\\' => 
        array (
            0 => __DIR__ . '/../..' . '/classes',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit0504ed8b584569c0377de9e4ad4826eb::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit0504ed8b584569c0377de9e4ad4826eb::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit0504ed8b584569c0377de9e4ad4826eb::$classMap;

        }, null, ClassLoader::class);
    }
}