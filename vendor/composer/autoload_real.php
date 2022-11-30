<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitedc94fb114a2d0b2dd31b1ac4530fee9
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInitedc94fb114a2d0b2dd31b1ac4530fee9', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitedc94fb114a2d0b2dd31b1ac4530fee9', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitedc94fb114a2d0b2dd31b1ac4530fee9::getInitializer($loader));

        $loader->register(true);

        $includeFiles = \Composer\Autoload\ComposerStaticInitedc94fb114a2d0b2dd31b1ac4530fee9::$files;
        foreach ($includeFiles as $fileIdentifier => $file) {
            composerRequireedc94fb114a2d0b2dd31b1ac4530fee9($fileIdentifier, $file);
        }

        return $loader;
    }
}

/**
 * @param string $fileIdentifier
 * @param string $file
 * @return void
 */
function composerRequireedc94fb114a2d0b2dd31b1ac4530fee9($fileIdentifier, $file)
{
    if (empty($GLOBALS['__composer_autoload_files'][$fileIdentifier])) {
        $GLOBALS['__composer_autoload_files'][$fileIdentifier] = true;

        require $file;
    }
}
