<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit77208a27fc9e6cfcd77d78ecc9b89b23
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Symfony\\Component\\Dotenv\\' => 25,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Symfony\\Component\\Dotenv\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/dotenv',
        ),
    );

    public static $prefixesPsr0 = array (
        'o' => 
        array (
            'org\\bovigo\\vfs' => 
            array (
                0 => __DIR__ . '/..' . '/mikey179/vfsstream/src/main/php',
            ),
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Shuchkin\\SimpleXLSXGen' => __DIR__ . '/..' . '/shuchkin/simplexlsxgen/src/SimpleXLSXGen.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit77208a27fc9e6cfcd77d78ecc9b89b23::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit77208a27fc9e6cfcd77d78ecc9b89b23::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit77208a27fc9e6cfcd77d78ecc9b89b23::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit77208a27fc9e6cfcd77d78ecc9b89b23::$classMap;

        }, null, ClassLoader::class);
    }
}