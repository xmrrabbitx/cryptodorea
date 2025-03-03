<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit554b622bd28a5bfaa7453871a8f0ecd4
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

        spl_autoload_register(array('ComposerAutoloaderInit554b622bd28a5bfaa7453871a8f0ecd4', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit554b622bd28a5bfaa7453871a8f0ecd4', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit554b622bd28a5bfaa7453871a8f0ecd4::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
