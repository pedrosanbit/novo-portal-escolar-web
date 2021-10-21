<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit497a7b42abf5555f71da0a61e4de76c1
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit497a7b42abf5555f71da0a61e4de76c1::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit497a7b42abf5555f71da0a61e4de76c1::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit497a7b42abf5555f71da0a61e4de76c1::$classMap;

        }, null, ClassLoader::class);
    }
}