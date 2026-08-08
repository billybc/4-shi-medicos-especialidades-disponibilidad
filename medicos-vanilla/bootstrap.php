<?php

declare(strict_types=1);

define('MEDICOS_BASE_PATH', __DIR__);
define('MEDICOS_STORAGE_PATH', MEDICOS_BASE_PATH . '/storage');
define('MEDICOS_DATABASE_PATH', MEDICOS_STORAGE_PATH . '/medicos.sqlite');
define('MEDICOS_SCHEMA_PATH', MEDICOS_BASE_PATH . '/database/schema.sql');
define('MEDICOS_SEED_PATH', MEDICOS_BASE_PATH . '/database/seed.php');

spl_autoload_register(static function (string $class): void {
    $prefix = 'Medicos\\';

    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $file = MEDICOS_BASE_PATH . '/src/' . str_replace('\\', '/', $relativeClass) . '.php';

    if (is_file($file)) {
        require $file;
    }
});
