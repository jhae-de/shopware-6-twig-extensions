<?php declare(strict_types=1);

namespace Jhae\TwigExtensions\Test;

use Composer\Autoload\ClassLoader;

$loader = require __DIR__ . '/../vendor/autoload.php';

if ($loader instanceof ClassLoader) {
    $loader->unregister();
    $loader->register();
}
